<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public $fromEmail = 'loja@teste.com';
    public $fromName  = 'Loja Dev';
    public $protocol  = 'smtp';
    public $SMTPHost  = 'sandbox.smtp.mailtrap.io';
    public $SMTPUser  = 'ca2be3cd1bb8c2';
    public $SMTPPass  = 'ec2603e89642cc';
    public $SMTPPort  = 587;
    public $mailType  = 'html';
    public $charset   = 'UTF-8';
}
