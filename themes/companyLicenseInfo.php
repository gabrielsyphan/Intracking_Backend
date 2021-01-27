<?php $v->layout("_theme.php"); ?>

<div class="container-fluid container-white mt-5 p-5">
    <div class="row">
        <div class="col-xl-12">
            <h3 class="black-title-section">Minha licença</h3>
            <p class="subtitle-section-p">Informações da licença de empresa</p>
            <hr>
            <div class="div-gray-bg border-top-green p-5">
                <h4 class="black-title-section">Informações da licença</h4>
                <hr>

                <div class="row">
                    <div class="col-xl-3 subtitle-section-p">
                        Responsável:
                    </div>
                    <div class="col-xl-9 subtitle-section-p text-right">
                        <?= $user ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xl-3 subtitle-section-p">
                        Nome de fantasia
                    </div>
                    <div class="col-xl-9 subtitle-section-p text-right">
                        <?= $license->nome_fantasia ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xl-3 subtitle-section-p">
                        CNPJ:
                    </div>
                    <div class="col-xl-9 subtitle-section-p text-right">
                        <?= $license->cnpj ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xl-3 subtitle-section-p">
                        CMC:
                    </div>
                    <div class="col-xl-9 subtitle-section-p text-right">
                        <?= $license->cmc ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xl-3 subtitle-section-p">
                        Quantitativo equipamento:
                    </div>
                    <div class="col-xl-9 subtitle-section-p text-right">
                        <?= $license->quantidade_equipamentos ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xl-3 subtitle-section-p">
                        Endereço:
                    </div>
                    <div class="col-xl-9 subtitle-section-p text-right">
                        <?= $license->endereco ?>, <?= $license->numero ?>, <?= $license->bairro ?>,
                        <?= $license->cidade ?>, <?= $license->cep ?>
                    </div>
                </div>

                <h4 class="black-title-section mt-5">Produtos ofertados:</h4>
                <hr>
                <?php foreach (str_split($license->produto) as $product):
                    if ($product == 0): ?>
                        <p class="subtitle-section-p">Gêneros e produtos alimentícios em geral</p>
                    <?php elseif ($product == 1): ?>
                        <p class="subtitle-section-p">Bebidas alcoólicas</p>
                    <?php elseif ($product == 2): ?>
                        <p class="subtitle-section-p">Bebidas não alcoólicas<p>
                    <?php elseif ($product == 3): ?>
                        <p class="subtitle-section-p">Brinquedos e artigos ornamentais</p>
                    <?php elseif ($product == 4): ?>
                        <p class="subtitle-section-p">Confecções, calçados e artigos de uso pessoal</p>
                    <?php elseif ($product == 5): ?>
                        <p class="subtitle-section-p">Louças, ferragens, artefatos de plástico,borracha,
                            couro e utensílios domésticos</p>
                    <?php elseif ($product == 6): ?>
                        <p class="subtitle-section-p">Artesanato, antiguidades e artigos de arte em geral</p>
                    <?php elseif ($product == 7): ?>
                        <p class="subtitle-section-p"><?= $license->relato_atividade ?></p>
                <?php endif; endforeach; ?>
            </div>
            <div class="row mt-3">
                <div class="col-xl-6">
                    <div class="row m-0 mt-3 p-4 border-left-green div-request-license">
                        <div class="col-xl-2 text-center mt-4">
                            <img src="<?= url('themes/assets/img/cash-payment.png') ?>">
                        </div>
                        <div class="col-xl-10">
                            <h4 class="black-title-section">Boleto</h4>
                            <p class="subtitle-section-p">Acessar boleto.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="row m-0 mt-3 p-4 border-left-yellow div-request-license">
                        <div class="col-xl-2 text-center mt-4">
                            <img src="<?= url('themes/assets/img/files.png') ?>">
                        </div>
                        <div class="col-xl-10">
                            <h4 class="black-title-section">Anexos</h4>
                            <p class="subtitle-section-p">Arquivos enviados.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
