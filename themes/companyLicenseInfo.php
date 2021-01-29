<?php $v->layout("_theme.php"); ?>

<div id="modal-1" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-10 p-5 container-white">
                <h3 class="black-title-section">Anexos</h3>
                <p class="subtitle-section-p">Arquivos enviados durante o cadastro da licença.</p>
                <hr>
                <div class="div-box-span-icon mt-5">
                    <span class="icon-close" onclick="closeModal(1)"></span>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>
                    <tbody id="table-data">
                    <?php if ($uploads && count($uploads) > 0):
                        $aux = 1;
                        foreach ($uploads as $upload): ?>
                            <tr>
                                <th scope="row"><?= $aux ?></th>
                                <td><?= $upload['fileName'] ?></td>
                                <td style="display: flex">
                                    <form action="<?= url('downloadFile/' . $upload['groupName'] . '/' . $upload['userId']
                                        . '/' . $upload['fileName']) ?>">
                                        <button class="btn" type="submit">
                                            <span class="icon-download"></span>
                                        </button>
                                    </form>
                                    <button class="btn" type="submit"
                                            onclick="openFile('<?= $upload['groupName'] . '/' .
                                            $upload['userId'] . '/' . $upload['fileName'] ?>')">
                                        <span class="icon-image"></span>
                                    </button>
                                </td>
                            </tr>
                            <?php $aux++; endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal-2" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-10 p-5 container-white">
                <h3 class="black-title-section">Meus pagamentos</h3>
                <p class="subtitle-section-p">Todos os pagamentos referente às suas licenças.</p>
                <hr>
                <div class="div-box-span-icon mt-5">
                    <span class="icon-close" onclick="closeModal(2)"></span>
                </div>
                <div class="box-div-info-overflow-x background-body">
                    <?php if ($payments): ?>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Status</th>
                            <th>Valor</th>
                            <th>Tipo</th>
                            <th>Validade</th>
                            <th>Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment):
                                if ($payment->id_empresa == null):
                                    if ($payment->status == 3 || $payment->status == 0):
                                        $divStatus = 'statusPendent';
                                        $textStatus = 'Pendente';
                                        $trClass = 'border-left-yellow';
                                    elseif ($payment->status == 1):
                                        $divStatus = 'statusPaid';
                                        $textStatus = 'Pago';
                                        $trClass = 'border-left-green';
                                    else:
                                        $divStatus = 'statusExpired';
                                        $textStatus = 'Vencido';
                                        $trClass = 'border-left-red';
                                    endif;
                                    if ($payment->tipo == 0):
                                        $type = "Multa";
                                    else:
                                        $type = "Pagamento";
                                    endif; ?>
                                    <tr class="<?= $trClass ?>">
                                        <td class="<?= $divStatus ?>"><?= $textStatus ?></td>
                                        <td>R$ <?= $payment->valor ?>,00</td>
                                        <td><?= $type ?></td>
                                        <td><?= date('d-m-Y', strtotime($payment->pagar_em)); ?></td>
                                        <td>
                                            <?php if ($payment->status == 2): ?>
                                                <a class="btn-3 secondary"
                                                   href="http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?<?= $payment->cod_referencia ?>"
                                                   target="_blank">Pagar</a>
                                            <?php elseif ($payment->status == 0 || $payment->status == 3): ?>
                                                <a class="btn-3 tertiary"
                                                   href="http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?<?= $payment->cod_referencia ?>"
                                                   target="_blank">Pagar</a>
                                            <?php else: ?>
                                                Não há ações
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <div class="p-5 mt-5 text-center">
                            <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                            <p class="mt-5 subtitle-section-p">Você ainda não possui boletos cadastrados.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid container-white mt-5 p-5">
    <div class="row">
        <div class="col-12">
            <h3 class="black-title-section">Minha licença</h3>
            <p class="subtitle-section-p">Informações da licença de empresa</p>
            <hr>
            <div class="div-gray-bg border-top-green p-5">
                <h4 class="black-title-section">Informações da licença</h4>
                <hr>

                <div class="row">
                    <div class="col-3 subtitle-section-p">
                        Proprietário:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $user ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Nome de fantasia
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->nome_fantasia ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        CNPJ:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->cnpj ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        CMC:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->cmc ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Quantitativo equipamento:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->quantidade_equipamentos ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Endereço:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
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
                <div class="col-sm-6" onclick="openModal(1)">
                    <div class="row m-0 mt-3 p-4 border-left-yellow div-request-license">
                        <div class="col-2 text-center mt-4">
                            <img src="<?= url('themes/assets/img/files.png') ?>">
                        </div>
                        <div class="col-10">
                            <h4 class="black-title-section">Anexos</h4>
                            <p class="subtitle-section-p">Arquivos enviados.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6" onclick="openModal(2)">
                    <div class="row m-0 mt-3 p-4 border-left-green div-request-license">
                        <div class="col-2 text-center mt-4">
                            <img src="<?= url('themes/assets/img/cash-payment.png') ?>">
                        </div>
                        <div class="col-10">
                            <h4 class="black-title-section">Boleto</h4>
                            <p class="subtitle-section-p">Acessar boleto.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
