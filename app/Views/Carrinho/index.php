<?= $this->extend('layout/principal'); ?>

<?= $this->section('titulo'); ?><?= $titulo; ?><?= $this->endSection(); ?>

<?= $this->section('conteudo'); ?>
<div class="content container my-4">

    <div class="mb-4 d-flex gap-2">
        <h3>Carrinho</h3>
        <a href="<?= base_url('/') ?>" class="btn btn-secondary">← Adicionar mais itens</a>
    </div>

    <?php if (session('msg')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= session('msg') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabela de produtos -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                <tr>
                    <th>Produto</th>
                    <th>Qtd</th>
                    <th>Preço</th>
                    <th>Subtotal</th>
                    <th>Ações</th> <!-- Nova coluna -->
                </tr>
                </thead>
                <tbody>
                <?php foreach ($carrinho as $indice => $item): ?>
                    <tr>
                        <td><?= esc($item['nome']) ?></td>
                        <td><?= esc($item['quantidade']) ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                        <td>
                            <form method="post" action="<?= site_url('carrinho/remover/' . $indice) ?>" onsubmit="return confirm('Remover este item do carrinho?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-danger">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="post" action="<?= site_url('carrinho/aplicarCupom') ?>" class="row g-3 align-items-center">
                <?= csrf_field() ?>
                <div class="col-md-6">
                    <input type="text" name="cupom" class="form-control" placeholder="Digite seu cupom de desconto" required>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary">Aplicar Cupom</button>
                </div>
                <?php if (session()->get('cupom')): ?>
                    <div class="col-md-12 mt-2">
                        <div class="alert alert-success mb-0">
                            Cupom "<strong><?= esc(session()->get('cupom')['codigo']) ?></strong>" aplicado!
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Resumo do pedido -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <p class="mb-2"><strong>Subtotal:</strong> R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
            <?php if(!empty($cupom)): ?>
                <p class="mb-2 text-success"><strong>Cupom: (<?= esc($cupom['codigo'])?>):</strong> - R$ <?= number_format($desconto,2, ',', '.') ?></p>
            <?php endif;?>
            <p class="mb-2"><strong>Frete:</strong> R$ <?= number_format($frete, 2, ',', '.') ?></p>
            <hr>
            <p class="mb-0 fs-5"><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>
            <div class="d-flex justify-content-end">
                <a href="<?= site_url('carrinho/limpar') ?>" class="btn btn-danger">🗑 Limpar Carrinho</a>
            </div>
        </div>
    </div>

    <!-- Formulário para calcular frete -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="post" action="<?= site_url('carrinho/calcularFrete') ?>" class="row g-3 align-items-center">
                <?= csrf_field() ?>
                <div class="col-auto flex-grow-1">
                    <input type="text" name="cep" placeholder="Digite o CEP" class="form-control" required>
                </div>
                <div class="col-auto">
                    <button class="btn btn-info">Pesquisar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Formulário para endereço de entrega -->
    <?php if (!empty($endereco)): ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Endereço de Entrega</h4>
                <form method="post" action="<?= site_url('carrinho/finalizar') ?>">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" id="cep" name="cep" value="<?= esc($endereco['cep']) ?>" readonly class="form-control">
                        </div>

                        <div class="col-md-8">
                            <label for="logradouro" class="form-label">Logradouro</label>
                            <input type="text" id="logradouro" name="logradouro" value="<?= esc($endereco['logradouro']) ?>" readonly class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" id="numero" name="numero" placeholder="Digite o número" required class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" id="bairro" name="bairro" value="<?= esc($endereco['bairro']) ?>" readonly class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" id="cidade" name="cidade" value="<?= esc($endereco['cidade']) ?>" readonly class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado</label>
                            <input type="text" id="estado" name="estado" value="<?= esc($endereco['estado']) ?>" readonly class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="email_cliente" class="form-label">E-mail para confirmação</label>
                            <input type="email" id="email_cliente" name="email_cliente" class="form-control" required placeholder="exemplo@dominio.com">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">Confirmar Endereço e Finalizar Pedido</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection(); ?>
