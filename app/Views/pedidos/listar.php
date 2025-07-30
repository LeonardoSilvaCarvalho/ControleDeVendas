<?= $this->extend('layout/principal'); ?>

<?= $this->section('titulo'); ?><?= $titulo; ?><?= $this->endSection(); ?>

<?= $this->section('conteudo'); ?>
<div class="content">
    <h2>📦 Lista de Pedidos</h2>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Email do Cliente</th>
            <th>Produto</th>
            <th>Quantidade</th> <!-- NOVO -->
            <th>CEP</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td>#<?= esc($pedido['id']) ?></td>
                <td><?= esc($pedido['email_cliente'] ?? 'N/D') ?></td>
                <td><?= esc($pedido['nome_produto'] ?? 'N/D') ?></td>
                <td><?= esc($pedido['quantidade'] ?? '1') ?></td> <!-- NOVO -->
                <td><?= esc($pedido['cep'] ?? 'N/D') ?></td>
                <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                <td>
                    <form action="<?= site_url('pedidos/atualizar-status/' . $pedido['id']) ?>" method="post" class="d-flex align-items-center gap-2">
                        <?= csrf_field() ?>
                        <select name="status" class="form-select form-select-sm w-auto" required>
                            <option value="pendente" <?= $pedido['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                            <option value="confirmado" <?= $pedido['status'] === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                            <option value="entregue" <?= $pedido['status'] === 'entregue' ? 'selected' : '' ?>>Entregue</option>
                            <option value="cancelado" <?= $pedido['status'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-success">Atualizar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <?= $pager->links('pedidos', 'default_full') ?>
    </div>

</div>
<?= $this->endSection(); ?>
