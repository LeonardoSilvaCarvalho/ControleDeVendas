<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table            = 'pedidos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['cep', 'email_cliente', 'cupom_codigo', 'desconto', 'frete', 'subtotal', 'total', 'status'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function listarComProdutoPaginado($perPage = 10)
    {
        return $this->select('pedidos.*, produtos.nome as nome_produto, pedido_itens.quantidade')
            ->join('pedido_itens', 'pedido_itens.pedido_id = pedidos.id')
            ->join('produtos', 'produtos.id = pedido_itens.produto_id')
            ->orderBy('pedidos.created_at', 'DESC')
            ->paginate($perPage, 'pedidos');
    }

    public function buscarPedidoComProduto($pedidoId)
    {
        return $this->select('pedidos.*, produtos.nome AS nome_produto')
            ->join('pedido_itens', 'pedido_itens.pedido_id = pedidos.id')
            ->join('produtos', 'produtos.id = pedido_itens.produto_id')
            ->where('pedidos.id', $pedidoId)
            ->get()
            ->getRowArray();
    }


}
