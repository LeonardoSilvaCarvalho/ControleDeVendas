<?= $this->extend('layout/principal'); ?>
<?= $this->section('titulo'); ?><?= $titulo; ?><?= $this->endSection(); ?>
<?= $this->section('conteudo'); ?>
<div class="content container my-4">
    <h1><?= esc($titulo) ?></h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('cupons/atualizar/' . $cupom['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="codigo" class="form-label">Código do Cupom</label>
            <input type="text" name="codigo" id="codigo" class="form-control" required value="<?= old('codigo', $cupom['codigo']) ?>">
        </div>

        <div class="mb-3">
            <label for="desconto" class="form-label">Desconto</label>
            <input type="number" step="0.01" name="desconto" id="desconto" class="form-control" required value="<?= old('desconto', $cupom['desconto']) ?>">
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de Desconto</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="">Selecione</option>
                <option value="fixo" <?= old('tipo', $cupom['tipo']) === 'fixo' ? 'selected' : '' ?>>Valor fixo</option>
                <option value="porcentagem" <?= old('tipo', $cupom['tipo']) === 'porcentagem' ? 'selected' : '' ?>>Porcentagem</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="validade">Validade</label>
            <input type="date" name="validade" id="validade" class="form-control" value="<?= old('validade', $cupom['validade']) ?>">
        </div>


        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="<?= site_url('cupons/index') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?= $this->endSection() ?>