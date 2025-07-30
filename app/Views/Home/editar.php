<?= $this->extend('layout/principal'); ?>

<?= $this->section('titulo'); ?><?= $titulo; ?><?= $this->endSection(); ?>

<?= $this->section('conteudo'); ?>
<div class="content">
    <h2><?= $titulo; ?></h2>
    <form method="post" action="<?= isset($produto) ? site_url('produtos/atualizar/' . $produto['id']) : site_url('produtos/salvar') ?>">
        <div class="mb-3">
            <label class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" name="nome" value="<?= $produto['nome'] ?? '' ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Preço</label>
            <input type="number" step="0.01" class="form-control" name="preco" value="<?= $produto['preco'] ?? '' ?>">
        </div>

        <div class="mb-3">
            <div id="variacoes-container">
                    <?php if (!empty($variacoes)): ?>
                        <?php foreach ($variacoes as $v): ?>
                            <div class="row mb-2 alingn-items-end">
                                <div class="col-md-6">
                                    <label class="form-label">Variação</label>
                                    <input type="text" class="form-control" name="variacoes[]" value="<?= esc($v['nome']) ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Estoque</label>
                                    <input type="number" class="form-control" name="quantidades[]" value="<?= $v['quantidade'] ?? 0 ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <button type="button" class="btn btn-danger remove-variacao w-100">🗑</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Variação</label>
                                <input type="text" class="form-control" name="variacoes[]" placeholder="Ex: Tamanho P">
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
                    <?php endif; ?>
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
