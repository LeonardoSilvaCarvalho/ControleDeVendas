<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PedidoModel;
use App\Models\ProdutoModel;
use CodeIgniter\HTTP\ResponseInterface;

class PedidoController extends BaseController
{
    public $pedidoModel;
    public $produtoModel;

    public function __construct(){
        $this->pedidoModel = new PedidoModel();
        $this->produtoModel = new ProdutoModel();
    }

    public function lista()
    {
        $pedidos = $this->pedidoModel->listarComProdutoPaginado(10); // 10 por página

        $dados = [
            'pedidos' => $pedidos,
            'pager'   => $this->pedidoModel->pager,
            'titulo'  => "Lista de Pedidos",
        ];

        return view('pedidos/listar', $dados);
    }

    public function atualizarStatus($id)
    {
        $status = $this->request->getPost('status');

        // Carrega pedido com nome do produto
        $pedido = $this->pedidoModel->buscarPedidoComProduto($id);

        if (!$pedido) {
            return redirect()->back()->with('error', 'Pedido não encontrado.');
        }

        if ($status === 'cancelado') {
            $this->pedidoModel->delete($id);
        } else {
            if ($pedido['status'] !== $status) {
                $this->pedidoModel->update($id, ['status' => $status]);
            }
        }

        $emailService = new \App\Services\EmailService();
        $emailService->enviarStatusPedido(
            $pedido['email_cliente'],
            $pedido['nome_produto'], // agora vem do JOIN
            $pedido,
            $status
        );

        return redirect()->to('pedidos')->with('success', 'Status atualizado.');
    }


}
