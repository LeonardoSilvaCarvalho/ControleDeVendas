<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EstoqueModel;
use App\Models\ProdutoModel;
use App\Models\VariacaoModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProdutoController extends BaseController
{
    private $produtoModel;
    private $estoqueModel;
    private $variacaoModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
        $this->estoqueModel = new EstoqueModel();
        $this->variacaoModel = new VariacaoModel();
    }

    public function index()
    {
        $produtos = $this->produtoModel
            ->select('produtos.*, COUNT(variacoes.id) AS total_variacoes, SUM(estoque.quantidade) AS total_estoque')
            ->join('variacoes', 'variacoes.produto_id = produtos.id', 'left')
            ->join('estoque', 'estoque.variacao_id = variacoes.id', 'left')
            ->groupBy('produtos.id')
            ->findAll();

        return view('Home/index', [
            'titulo' => 'Produtos Cadastrados',
            'produtos' => $produtos
        ]);
    }

    public function novo()
    {
        return view('Home/novo', ['titulo' => 'Novo Produto']);
    }

    public function editar($id)
    {
        $produto = $this->produtoModel->find($id);
        $variacoes = $this->variacaoModel->where('produto_id', $id)->findAll();

        // pega estoque de cada variação
        foreach ($variacoes as &$v) {
            $v['quantidade'] = $this->estoqueModel
                ->where('variacao_id', $v['id'])
                ->get()->getRow('quantidade') ?? 0;
        }

        return view('Home/editar', [
            'titulo' => 'Editar Produto',
            'produto' => $produto,
            'variacoes' => $variacoes
        ]);
    }

    public function atualizar($id)
    {
        $nome = $this->request->getPost('nome');
        $preco = $this->request->getPost('preco');
        $variacoes = $this->request->getPost('variacoes');
        $quantidades = $this->request->getPost('quantidades');

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $this->produtoModel->update($id, ['nome' => $nome, 'preco' => $preco]);

            // Remove todas variações/estoques antigos
            $oldV = $this->variacaoModel->where('produto_id', $id)->findAll();
            foreach ($oldV as $v) {
                $this->estoqueModel->where('variacao_id', $v['id'])->delete();
            }
            $this->variacaoModel->where('produto_id', $id)->delete();

            // Adiciona novamente as novas variações
            foreach ($variacoes as $i => $nomeV) {
                $variacaoId = $this->variacaoModel->insert([
                    'produto_id' => $id,
                    'nome' => $nomeV
                ]);
                $this->estoqueModel->insert([
                    'variacao_id' => $variacaoId,
                    'quantidade' => $quantidades[$i] ?? 0
                ]);
            }

            $db->transComplete();

            return redirect()->to(site_url('/'))->with('msg', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('msg', 'Erro: ' . $e->getMessage());
        }
    }

    public function salvar()
    {
        $nome = $this->request->getPost('nome');
        $preco = $this->request->getPost('preco');
        $variacoes = $this->request->getPost('variacoes');
        $quantidades = $this->request->getPost('quantidades');

        // Validação básica
        if (!$nome || !$preco || empty($variacoes) || empty($quantidades)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Preencha todos os campos.']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Cadastra o produto
            $produtoId = $this->produtoModel->insert([
                'nome' => $nome,
                'preco' => $preco,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // 2. Cadastra as variações com estoque
            foreach ($variacoes as $index => $nomeVariacao) {
                $nomeVariacao = trim($nomeVariacao);
                $quantidade = isset($quantidades[$index]) ? (int)$quantidades[$index] : 0;

                if ($nomeVariacao === '') continue;

                $variacaoId = $this->variacaoModel->insert([
                    'produto_id' => $produtoId,
                    'nome' => $nomeVariacao
                ]);

                $this->estoqueModel->insert([
                    'produto_id' => $produtoId,
                    'variacao_id' => $variacaoId,
                    'quantidade' => $quantidade
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Erro ao salvar dados no banco.');
            }

            return redirect()->back()->with('msg', '✅ Produto cadastrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('msg', 'Erro: ' . $e->getMessage());
        }
    }

    public function excluir($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Deleta estoque vinculado
            $variacoes = $this->variacaoModel->where('produto_id', $id)->findAll();
            foreach ($variacoes as $v) {
                $this->estoqueModel->where('variacao_id', $v['id'])->delete();
            }

            // Deleta variações
            $this->variacaoModel->where('produto_id', $id)->delete();

            // Deleta o produto
            $this->produtoModel->delete($id);

            $db->transComplete();

            return redirect()->to(site_url('/'))->with('msg', '✅ Produto excluído com sucesso!');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to(site_url('/'))->with('msg', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }

}
