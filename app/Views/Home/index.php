<?= $this->extend('layout/principal'); ?>

<?= $this->section('titulo'); ?><?= $titulo; ?><?= $this->endSection(); ?>

<?= $this->section('conteudo'); ?>
<div class="content">
        <h2>Produtos Cadastrados</h2>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="<?= site_url('carrinho') ?>" class="btn btn-outline-dark me-2">🛒 Visualizar Carrinho</a>
        <a href="<?= site_url('produtos/novo') ?>" class="btn btn-primary">➕ Novo Produto</a>
    </div>

    <?php if (session('msg')): ?>
        <div class="alert alert-info"><?= session('msg') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Qtd. Variações</th>
            <th>Estoque Total</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= esc($p['nome']) ?></td>
                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                <td><?= $p['total_variacoes'] ?></td>
                <td><?= $p['total_estoque'] ?? 0 ?></td>
                <td class="d-flex gap-2">
                    <a href="<?= site_url('produtos/editar/' . $p['id']) ?>" class="btn btn-sm btn-warning">✏️ Editar</a>

                    <form action="<?= site_url('produtos/excluir/' . $p['id']) ?>" method="post" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-danger">🗑 Excluir</button>
                    </form>

                    <a href="<?= site_url('carrinho/adicionar/' . $p['id']) ?>" class="btn btn-sm btn-success">🛒 Vender</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection(); ?>
