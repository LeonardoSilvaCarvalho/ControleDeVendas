<!DOCTYPE html>
<html>
<head>
    <title>Status do Pedido</title>
</head>
<body>
<h2>Olá!</h2>
<p>O status do seu pedido foi atualizado para <strong><?= esc($status) ?></strong>.</p>
<p>Detalhes do pedido:</p>
<ul>
    <li>ID: <?= esc($pedido['id']) ?></li>
    <li>Produto: <?= esc($produto ?? 'N/A') ?></li> <!-- Corrigido -->
    <li>Valor: R$ <?= number_format($pedido['total'], 2, ',', '.') ?></li>
</ul>
<p>Agradecemos pela preferência!</p>
</body>
</html>
