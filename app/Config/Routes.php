<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ProdutoController::index');
$routes->get('produtos/novo', 'ProdutoController::novo');
$routes->post('produto/salvar', 'ProdutoController::salvar');
$routes->get('produtos/editar/(:num)', 'ProdutoController::editar/$1');
$routes->post('produtos/atualizar/(:num)', 'ProdutoController::atualizar/$1');
$routes->post('produtos/excluir/(:num)', 'ProdutoController::excluir/$1');

$routes->get('carrinho', 'CarrinhoController::index');
$routes->get('carrinho/adicionar/(:num)', 'CarrinhoController::adicionar/$1');
$routes->post('carrinho/salvar', 'CarrinhoController::salvar');
$routes->get('carrinho/limpar', 'CarrinhoController::limpar');
$routes->post('carrinho/remover/(:num)', 'CarrinhoController::remover/$1');
$routes->post('carrinho/calcularFrete', 'CarrinhoController::calcularFrete');
$routes->post('carrinho/finalizar', 'CarrinhoController::finalizar');
$routes->post('carrinho/aplicarCupom', 'CarrinhoController::aplicarCupom');

$routes->get('pedidos', 'PedidoController::lista');
$routes->post('pedidos/atualizar-status/(:num)', 'PedidoController::atualizarStatus/$1');

$routes->get('cupons/index', 'CupomController::index');
$routes->get('cupons/criar', 'CupomController::criar');
$routes->get('cupons/editar/(:num)', 'CupomController::editar/$1');
$routes->post('cupons/salvar', 'CupomController::salvar');
$routes->post('cupons/atualizar/(:num)', 'CupomController::atualizar/$1');
$routes->get('cupons/excluir/(:num)', 'CupomController::excluir/$1');
