<?php

namespace App\Services;

use CodeIgniter\Email\Email;

class EmailService
{
    protected $email;

    public function __construct()
    {
        $this->email = \Config\Services::email();
    }

    public function enviarPedidoConfirmado(string $para, string $clienteNome, array $dadosPedido): bool
    {
        $this->email->setFrom('leonardomajornior@gmail.com', 'Minha Loja');
        $this->email->setTo($para);
        $this->email->setSubject('Pedido Recebido');

        $mensagem = view('emails/pedido_confirmado', [
            'nomeCliente' => $clienteNome,
            'pedido' => $dadosPedido,
        ]);

        $this->email->setMessage($mensagem);
        $this->email->setMailType('html');

        return $this->email->send();
    }

    public function enviarStatusPedido(string $para, string $nomeProduto, array $pedido, string $status): bool
    {
        $this->email->setFrom('leonardomajornior@gmail.com', 'Minha Loja');
        $this->email->setTo($para);
        $this->email->setSubject("Status do seu pedido: " . ucfirst($status));

        $mensagem = view('emails/status_pedido', [
            'pedido' => $pedido,
            'produto' => $nomeProduto,
            'status' => $status,
        ]);

        $this->email->setMessage($mensagem);
        $this->email->setMailType('html');

        return $this->email->send();
    }


}
