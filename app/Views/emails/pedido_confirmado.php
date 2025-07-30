<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px;">
    <h2 style="color: #27ae60;">✔ Pedido Confirmado</h2>
    <p>Olá! Seu pedido <strong>#<?= esc($pedido['pedido_id']) ?></strong> foi confirmado com sucesso. Abaixo estão os detalhes:</p>

    <h3 style="color: #2980b9;">📦 Itens do Pedido</h3>
    <ul style="padding-left: 20px;">
        <?php foreach ($pedido['itens'] as $item): ?>
            <li>
                <strong><?= esc($item['nome']) ?></strong> - <?= esc($item['quantidade']) ?> x R$ <?= number_format($item['preco'], 2, ',', '.') ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3 style="color: #2980b9;">📍 Endereço de Entrega</h3>
    <p>
        <strong>Logradouro:</strong> <?= esc($pedido['logradouro']) ?><br>
        <strong>Número:</strong> <?= esc($pedido['numero']) ?><br>
        <strong>Bairro:</strong> <?= esc($pedido['bairro']) ?><br>
        <strong>Cidade:</strong> <?= esc($pedido['cidade']) ?>/<?= esc($pedido['estado']) ?><br>
        <strong>CEP:</strong> <?= esc($pedido['cep']) ?>
    </p>

    <h3 style="color: #2980b9;">💰 Resumo</h3>
    <p>
        <strong>Frete:</strong> R$ <?= number_format($pedido['frete'], 2, ',', '.') ?><br>
        <strong>Subtotal:</strong> R$ <?= number_format($pedido['subtotal'], 2, ',', '.') ?><br>

        <?php if (!empty($pedido['cupom'])): ?>
            <strong>Cupom aplicado:</strong> <?= esc($pedido['cupom']) ?><br>
            <strong>Desconto:</strong> - R$ <?= number_format($pedido['desconto'], 2, ',', '.') ?><br>
        <?php endif; ?>

        <strong>Total:</strong> <span style="color: green;">R$ <?= number_format($pedido['total'], 2, ',', '.') ?></span>
    </p>

    <hr style="margin: 30px 0;">
    <p style="font-size: 0.9em; color: #888;">Este e-mail foi gerado automaticamente por nossa loja. Em caso de dúvidas, entre em contato conosco.</p>
</div>
