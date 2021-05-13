<?php $v->layout("_theme.php"); ?>

<div id="modal-1" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-xl-10 p-5 container-white modal-overflow mh-80">
                <div class="row">
                    <div class="col-8">
                        <h3 class="black-title-section">Meus anexos</h3>
                    </div>
                    <div class="col-4 text-right mt-3">
                        <span class="icon-close" onclick="closeModal(1)"></span>
                    </div>
                </div>
                <p class="subtitle-section-p">Arquivos enviados durante o cadastro da licença.</p>
                <hr>
                <div class="row m-0 p-4">
                    <div class="col-xl-12 mb-3 pl-5 pr-5">
                        <?php if ($uploads && count($uploads) > 0): $aux = 1;
                            foreach ($uploads as $upload): ?>
                                <div class="row div-gray-bg mb-5 p-5">
                                    <div class="col-xl-3 p-0 text-center">
                                        <img style="width: 150px;"
                                             src="<?= url('/themes/assets/uploads/') ?><?= $upload['groupName'] . '/' .
                                             $upload['userId'] . '/' . $upload['fileName'] ?>">
                                    </div>
                                    <div class="col-xl-9 text-sm-center text-md-left">
                                        <h5 class="mt-5 mt-md-3"><?= explode(".", $upload['fileName'])[0] ?></h5>
                                        <p class="subtitle-section-p">Para editar ou visualizar a imagem, acione os
                                            botões abaixo.</p>
                                        <div class="text-right mt-5 pt-3 d-flex">
                                            <form class="mr-2"
                                                  action="<?= url('downloadFile/' . $upload['groupName'] . '/' . $upload['userId']
                                                      . '/' . $upload['fileName']) ?>">
                                                <button class="btn-3 primary">Baixar</button>
                                            </form>
                                            <button class="btn-3 secondary-color"
                                                    onclick="openFile('<?= $upload['groupName'] . '/' .
                                                    $upload['userId'] . '/' . $upload['fileName'] ?>')">Visualizar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php $aux++; endforeach; endif; ?>
                    </div>
                </div>
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
            <p class="subtitle-section-p">Informações da licença de uso de solo</p>
            <hr>
            <div class="div-gray-bg border-top-green p-5">
                <h4 class="black-title-section">Informações da licença</h4>
                <hr>

                <div class="row">
                    <div class="col-3 subtitle-section-p">
                        Cpf:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $user->cpf ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Proprietário:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $user->nome ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Nome de fantasia
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->nome_empresa ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Cnpj:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->cnpj ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Cmc:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->cmc ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Descrição do equipamento:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->tipo_equipamento ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Endereço:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->endereco ?>
                    </div>
                </div>
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
                <div class="col-sm-6">
                    <div class="row m-0 mt-3 p-4 border-left-green-light div-request-license mb-5" onclick="openOrder()">
                        <div class="col-2 text-center mt-4">
                            <img src="<?= url('themes/assets/img/order.png') ?>">
                        </div>
                        <div class="col-10">
                            <h4 class="black-title-section">Alvará</h4>
                            <p class="subtitle-section-p">Acessar alvará.</p>
                        </div>
                    </div>
<!--                    <div class="row m-0 mt-3 pt-4 pl-4 pr-4 pb-0 border-left-green div-request-license">-->
<!--                        <div class="col-2 text-center mt-4">-->
<!--                            <img src="--><?//= url('themes/assets/img/salesman.png') ?><!--">-->
<!--                        </div>-->
<!--                        <div class="col-10">-->
<!--                            <h4 class="black-title-section">Gerar link de adesão</h4>-->
<!--                            <div class="h-50">-->
<!--                                <input name="generator" id="generator" class="form-input w-50 h-25">-->
<!--                                <button class="btn-3 primary h-75" type="button" onclick="generator()">Gerar</button>-->
<!--                                <button class="btn-3 secondary-color h-75" type="button" onclick="" id="copy"-->
<!--                                        data-clipboard-target="#generator">Copiar-->
<!--                                </button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php $v->start("scripts"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
<script>
    function generator() {
        let date = new Date()


    const clipboard = new ClipboardJS('#copy')

    clipboard.on('success', function (e) {
    });

    clipboard.on('error', function (e) {
        swal({
            icon: "error",
            title: "Erro!",
            text: "Não foi possível copiar o link.",
        });
    });

    function openOrder() {
        window.open('<?= url('order') ?>/2/<?= md5($license->id) ?>', '_blank')
    }
</script>
<?php $v->end(); ?>
