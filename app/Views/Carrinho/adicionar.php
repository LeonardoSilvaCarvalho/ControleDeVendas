<?= $this->extend('layout/principal'); ?>

<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection(); ?>

<?= $this->section('conteudo'); ?>
<div class="content">
<h3>Adicionar <?= $produto['nome']; ?> ao Carrinho</h3>

<form action="<?= site_url('carrinho/salvar') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">

    <div class="mb-3">
        <label>Variação</label>
        <select name="variacao_id" class="form-control form-select">
            <?php foreach ($variacoes as $v): ?>
                <option value="<?= $v['id'] ?>"><?= $v['nome'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Quantidade</label>
        <input type="number" name="quantidade" class="form-control" min="1" required>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-success">Adicionar</button>
        <a href="<?= base_url('/') ?>" class="btn btn-secondary">← Voltar para Lista</a>
    </div>
</form>
</div>
<?= $this->endSection(); ?>
