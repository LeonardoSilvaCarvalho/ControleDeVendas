<?= $this->extend('layout/principal'); ?>
<?= $this->section('titulo'); ?><?= $titulo; ?><?= $this->endSection(); ?>
<?= $this->section('conteudo'); ?>
<div class="content container my-4">
<h1><?= esc($titulo) ?></h1>

<a href="<?= site_url('cupons/criar') ?>" class="btn btn-success mb-3">+ Novo Cupom</a>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php elseif (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Código</th>
        <th>Desconto</th>
        <th>Tipo</th>
        <th>Validade</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($cupons as $cupom): ?>
        <tr>
            <td><?= esc($cupom['id']) ?></td>
            <td><?= esc($cupom['codigo']) ?></td>
            <td>
                <?= $cupom['tipo'] === 'porcentagem'
                    ? esc($cupom['desconto']) . '%'
                    : 'R$ ' . number_format($cupom['desconto'], 2, ',', '.') ?>
            </td>
            <td><?= esc($cupom['tipo']) ?></td>
            <td><?= date('d/m/Y', strtotime($cupom['validade'])) ?></td>
            <td>
                <a href="<?= site_url('cupons/editar/' . $cupom['id']) ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="<?= site_url('cupons/excluir/' . $cupom['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir este cupom?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?= $this->endSection() ?>
