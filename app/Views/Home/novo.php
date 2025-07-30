<?= $this->extend('layout/principal'); ?>

<?= $this->section('titulo'); ?>
Cadastro de Produtos
<?= $this->endSection(); ?>

<?= $this->section('estilo'); ?>
<!-- Pode adicionar estilos extras aqui -->
<?= $this->endSection(); ?>

<?= $this->section('conteudo'); ?>
<div class="content">
    <?php if (session()->getFlashdata('msg')): ?>
        <div class="alert alert-info">
            <?= session()->getFlashdata('msg'); ?>
        </div>
    <?php endif; ?>
    <h2>Cadastro de Produto</h2>
    <form id="formProduto" method="POST" action="<?= site_url('produto/salvar') ?>">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex: Camiseta Premium">
        </div>
        <div class="mb-3">
            <label for="preco" class="form-label">Preço</label>
            <input type="number" class="form-control" id="preco" name="preco" placeholder="Ex: 99.90">
        </div>

        <div class="mb-3">
            <div id="variacoes-container">
                <div class="row mb-2 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">Variação</label>
                        <input type="text" class="form-control" name="variacoes[]" placeholder="Ex: Tamanho M">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estoque</label>
                        <input type="number" class="form-control" name="quantidades[]" placeholder="Qtd">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="button" class="btn btn-danger remove-variacao w-100">🗑</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" id="addVariacao">➕ Adicionar Variação</button>
        </div>



        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success">💾 Salvar Produto</button>
            <a href="<?= base_url('/') ?>" class="btn btn-secondary">← Voltar para Lista</a>
        </div>
    </form>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // Adicionar nova variação
    document.getElementById('addVariacao').addEventListener('click', function () {
        const container = document.getElementById('variacoes-container');
        const div = document.createElement('div');
        div.className = 'row mb-2';
        div.innerHTML = `
        <div class="col-md-6">
            <input type="text" class="form-control" name="variacoes[]" placeholder="Ex: Cor Azul">
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control" name="quantidades[]" placeholder="Qtd">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-variacao w-100">🗑</button>
        </div>
    `;
        container.appendChild(div);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-variacao')) {
            e.target.closest('.row').remove();
        }
    });
</script>
<?= $this->endSection(); ?>
