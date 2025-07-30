<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('titulo'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?= $this->renderSection('estilo'); ?>
    <style>
        body {
            min-height: 100vh;
            display: flex;
        }
        .sidebar {
            width: 190px;
            background: #343a40;
            color: white;
            padding: 1rem;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
        .content {
            flex-grow: 1;
            padding: 2rem;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Meu Painel</h4>
    <a href="<?= site_url('/')?>">📦 Produtos</a>
    <a href="<?= site_url('cupons/index')?>">📦 Cupons</a>
    <a href="<?= site_url('pedidos')?>">📦 Pedidos</a>
</div>

<!-- Conteúdo -->
<?= $this->renderSection('conteudo'); ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts'); ?>

</body>
</html>
