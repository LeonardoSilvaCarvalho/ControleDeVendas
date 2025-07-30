<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EstoqueModel;
use App\Models\PedidoModel;
use App\Models\ProdutoModel;
use App\Models\VariacaoModel;
use App\Models\CuponsModel;
use App\Services\EmailService;
use CodeIgniter\HTTP\ResponseInterface;

class CarrinhoController extends BaseController
{
    private $produtoModel;
    private $estoqueModel;
    private $variacaoModel;
    private $pedidosModel;
    private $cuponsModel;

    public function __construct(){
        $this->produtoModel = new ProdutoModel();
        $this->estoqueModel = new EstoqueModel();
        $this->variacaoModel = new VariacaoModel();
        $this->pedidosModel = new PedidoModel();
        $this->cuponsModel = new CuponsModel();
    }

    public function adicionar($produtoId)
    {
        $produto = $this->produtoModel->find($produtoId);

        if(!$produto){
            return redirect()->back()->with('msg', 'Produto não encontrado');
        }

        $variacoes = $this->variacaoModel
            ->where('produto_id', $produtoId)
            ->findAll();

        return view('Carrinho/adicionar', [
            'titulo' => "Carrinho",
            'produto' => $produto,
            'variacoes' => $variacoes,
        ]);
    }

    public function salvar(){
        $produtoId = $this->request->getPost('produto_id');
        $variacaoId = $this->request->getPost('variacao_id');
        $quantidade = $this->request->getPost('quantidade');

        $produto = $this->produtoModel->find($produtoId);
        $variacao = $this->variacaoModel->find($variacaoId);
        $estoque = $this->estoqueModel->where('variacao_id', $variacaoId)->get()->getRow('quantidade');

        if ($estoque < $quantidade) {
            return redirect()->back()->with('msg', 'Estoque insuficiente!');
        }

        $carrinho = session()->get('carrinho') ?? [];

        $carrinho[] = [
            'produto_id' => $produtoId,
            'variacao_id' => $variacaoId,
            'nome' => $produto['nome'] . ' - ' . $variacao['nome'],
            'quantidade' => $quantidade,
            'preco' => $produto['preco'],
            'subtotal' => $produto['preco'] * $quantidade,
        ];

        session()->set('carrinho', $carrinho);

        return redirect()->to(site_url('carrinho'));

    }

    public function index()
    {
        $carrinho = session()->get('carrinho') ?? [];
        $subtotal = array_sum(array_column($carrinho, 'subtotal'));
        $titulo = 'Carrinho';

        $frete = 0;
        if ($subtotal >= 52 && $subtotal <= 166.59) $frete = 15;
        elseif ($subtotal > 200) $frete = 0;

        $cupom = session()->get('cupom');
        $desconto = 0;

        if ($cupom) {
            if ($cupom['tipo'] === 'fixo') {
                $desconto = $cupom['desconto'];
            } elseif ($cupom['tipo'] === 'porcentagem') {
                $desconto = $subtotal * ($cupom['desconto'] / 100);
            }
        }

        $total = max(0, $subtotal - $desconto + $frete);

        $endereco = session()->get('endereco');
        session()->set('frete', $frete);

        return view('carrinho/index', compact(
            'carrinho', 'subtotal', 'frete', 'total', 'titulo', 'endereco', 'cupom', 'desconto'
        ));
    }

    public function calcularFrete()
    {
        $cep = preg_replace('/\D/', '', $this->request->getPost('cep'));

        $client = \Config\Services::curlrequest(['verify' => false]);
        $res = $client->get("https://viacep.com.br/ws/{$cep}/json/");
        $data = json_decode($res->getBody(), true);

        if (isset($data['erro'])) {
            return redirect()->back()->with('msg', 'CEP inválido');
        }

        session()->set('endereco', [
            'cep' => $cep,
            'logradouro' => $data['logradouro'],
            'bairro' => $data['bairro'],
            'cidade' => $data['localidade'],
            'estado' => $data['uf'],
        ]);

        return redirect()->back();
    }

    public function remover($indice)
    {
        $carrinho = session()->get('carrinho') ?? [];

        if (isset($carrinho[$indice])) {
            unset($carrinho[$indice]);
            $carrinho = array_values($carrinho);
            session()->set('carrinho', $carrinho);
            return redirect()->to(site_url('carrinho'))->with('msg', 'Item removido do carrinho.');
        }

        return redirect()->to(site_url('carrinho'))->with('msg', 'Item não encontrado no carrinho.');
    }

    public function limpar()
    {
        session()->remove('carrinho');
        session()->remove('cupom');
        return redirect()->to(site_url('carrinho'))->with('msg', 'Carrinho limpo');
    }

    public function finalizar()
    {
        helper('session');

        $carrinho = session('carrinho');
        if (!$carrinho || count($carrinho) == 0) {
            return redirect()->back()->with('msg', 'Carrinho vazio!');
        }

        $cep = $this->request->getPost('cep');
        $logradouro = $this->request->getPost('logradouro');
        $numero = $this->request->getPost('numero');
        $bairro = $this->request->getPost('bairro');
        $cidade = $this->request->getPost('cidade');
        $estado = $this->request->getPost('estado');
        $emailCliente = $this->request->getPost('email_cliente');

        if (!filter_var($emailCliente, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('msg', 'E-mail inválido!');
        }

        $frete = floatval(session()->get('frete') ?? 0);
        $subtotal = 0;

        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        $cupom = session()->get('cupom');
        $desconto = 0;

        if ($cupom) {
            if ($cupom['tipo'] === 'fixo') {
                $desconto = $cupom['desconto'];
            }elseif ($cupom['tipo'] === 'porcentagem') {
                $desconto = ($subtotal * $cupom['desconto']) / 100;
            }
        }

        $total = max(0, $subtotal - $desconto + $frete);

        // Inserir o pedido
        $pedidoModel = new \App\Models\PedidoModel();
        $pedidoId = $pedidoModel->insert([
            'cep' => $cep,
            'frete' => $frete,
            'subtotal' => $subtotal,
            'total' => $total,
            'email_cliente' => $emailCliente,
            'cupom_codigo'  => $cupom['codigo'] ?? null,
            'desconto'      => $desconto,
            'status'        => 'pendente'
        ]);

        $pedidoId = $pedidoModel->getInsertID();

        // Inserir os itens do pedido
        $pedidoItemModel = new \App\Models\PedidoItemModel();

        foreach ($carrinho as $item) {
            $pedidoItemModel->insert([
                'pedido_id' => $pedidoId,
                'produto_id' => $item['produto_id'],
                'variacao_id' => $item['variacao_id'],
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco'],
            ]);

            $estoque = $this->estoqueModel
                ->where('variacao_id', $item['variacao_id'])
                ->get()
                ->getRowArray();
            if ($estoque && isset($estoque['quantidade'])){
                $novoEstoque = max(0, $estoque['quantidade'] - $item['quantidade']);

                $this->estoqueModel
                    ->where('variacao_id', $item['variacao_id'])
                    ->set(['quantidade' => $novoEstoque])
                    ->update();
            }
        }

        // Preparar dados para o e-mail
        $dadosPedido = [
            'pedido_id' => $pedidoId,
            'cep' => $cep,
            'logradouro' => $logradouro,
            'numero' => $numero,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'frete' => $frete,
            'subtotal' => $subtotal,
            'total' => $total,
            'itens' => $carrinho,
            'cupom' => $cupom['codigo'] ?? null,
            'desconto' => $desconto
        ];

        $emailService = service('emailService');;
        $emailEnviado = $emailService->enviarPedidoConfirmado(
            $emailCliente,
            'Cliente',
            $dadosPedido
        );

        if (!$emailEnviado) {
            log_message('error', 'Erro ao enviar e-mail de pedido.');
        }

        if (!$emailEnviado) {
            log_message('error', 'Erro ao enviar e-mail de pedido.');
        }

        // Limpar o carrinho
        session()->remove('carrinho');
        session()->remove('cupom');

        return redirect()->to('/')->with('msg', 'Pedido finalizado com sucesso! E-mail enviado.');
    }

    public function aplicarCupom()
    {
        $codigo = $this->request->getPost('cupom');
        $cupomModel = $this->cuponsModel;
        $cupom = $cupomModel->where('codigo', $codigo)->first();

        if (!$cupom) {
            return redirect()->back()->with('msg', 'Cupom inválido!');
        }

        session()->set('cupom', $cupom);
        return redirect()->back()->with('msg', 'Cupom aplicado com sucesso!');
    }

}
