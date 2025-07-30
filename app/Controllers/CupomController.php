<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CuponsModel;
use CodeIgniter\HTTP\ResponseInterface;

class CupomController extends BaseController
{
    protected $cupomModel;

    public function __construct()
    {
        $this->cupomModel = new CuponsModel();
    }

    public function index()
    {
        $cupons = $this->cupomModel->orderBy('id', 'DESC')->findAll();

        return view('cupons/index', [
           'cupons' => $cupons,
           'titulo' => 'Gerenciar Cupons',
        ]);
    }

    public function criar(){
        return view('cupons/criar', ['titulo' => 'Criar Cupom']);
    }

    public function salvar(){
        $dados = $this->request->getPost();

        $validacao = $this->validate([
           'codigo' => 'required|is_unique[cupons.codigo]',
           'desconto' => 'required|decimal',
           'tipo' => 'required|in_list[fixo,porcentagem]',
        ]);

        if (!$validacao) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getError());
        }

        $dados['validade'] = date('Y-m-d', strtotime('+7 days'));

        $this->cupomModel->save($dados);

        return redirect()->to(site_url('cupons/index'))->with('success', 'Cupom criado com sucesso!');

    }

    public function editar($id){
        $cupom = $this->cupomModel->find($id);

        if (!$cupom){
            return redirect()->to(site_url('cupons'))->with('error', 'Cupom não encontrado');
        }

        return view('cupons/editar',[
           'cupom' => $cupom,
           'titulo' => 'Editar Cupom',
        ]);
    }

    public function atualizar($id) {
        $dados = $this->request->getPost();

        $validacao = $this->validate([
            'codigo' => "required|is_unique[cupons.codigo,id,{$id}]",
            'desconto' => 'required|decimal',
            'tipo' => 'required|in_list[fixo,porcentagem]',
            'validade' => 'required|valid_date[Y-m-d]',
        ]);

        if (!$validacao) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->cupomModel->update($id, $dados);

        return redirect()->to(site_url('cupons/index'))->with('success', 'Cupom atualizado com sucesso!');

    }

    public function excluir($id){
        $this->cupomModel->delete($id);
        return redirect()->to(site_url('cupons/index'))->with('success', 'Cupom excluído com sucesso!');
    }


}
