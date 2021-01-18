<?php $v->layout("_theme.php") ?>

<div class="container-fluid">
    <div class="row mt-5">
        <div class="col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-xl-8">
                            <h4 class="title-section">
                                Pagamentos cadastrados
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $amount ?></h2>
                        </div>
                        <div class="col-xl-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-credit-card card-icon registered-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-xl-8">
                            <h4 class="title-section">
                                Pagamentos confirmados
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $paid ?></h2>
                        </div>
                        <div class="col-xl-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-verified_user card-icon paid-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-xl-8">
                            <h4 class="title-section">
                                Pagamentos pendentes
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $pendent ?></h2>
                        </div>
                        <div class="col-xl-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-warning card-icon pending-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-xl-8">
                            <h4 class="title-section">
                                Pagamentos <br> vencidos
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $expired ?></h2>
                        </div>
                        <div class="col-xl-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-block card-icon expired-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mb-5">
            <div class="web-div-box">
                <div class="box-div-info" style="max-height: 60vh;">
                    <div>
                        <h4 class="ml-3 title-section">Lista de pagamentos</h4>
                        <p class="ml-3 subtitle-section-p">Todos os pagamentos cadastrados no Orditi</p>

                        <div class="div-box-span-icon mt-3">
                            <div class="div-table-search">
                                <input id="text" onkeyup="tableFilter()" class="input-table-search" type="text" placeholder="Filtrar pelo ambulante...">
                                <div class="circle-button primary search">
                                    <span class="icon-search"></span>
                                </div>
                            </div>

                            <div class="dropleft">
                                <div class="ml-3 circle-button secondary" id="dropdownMenuButton"
                                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Exportar tabela">
                                    <span class="icon-download"></span>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="<?= url('exportData/1') ?>">
                                        Exportar pagamentos
                                    </a>
                                </div>
                            </div>

                    </div>
                    <hr style="margin-bottom: 0">
                    <div class="box-div-info-overflow-x">
                        <?php if($payments == NULL): ?>
                            <div class="p-5 mt-5 text-center">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                                <p class="mt-5">Ops! Não encontramos nenhum pagamento para exibir aqui. 😥</p>
                            </div>
                        <?php else: ?>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" class="table-col-2">Status</th>
                                    <th scope="col" class="table-col-2">Valor</th>
                                    <th scope="col" class="table-col-2">Vencimento</th>
                                    <th scope="col" class="table-col-1">Cod Referência</th>
                                    <th scope="col" class="table-col-2">Tipo</th>
                                    <th scope="col">Ambulante</th>
                                </tr>
                                </thead>
                                <tbody id="table-data">
                                <?php
                                if($payments !== NULL):
                                    foreach ($payments as $payment): ?>
                                        <tr onclick="openPage('<?= $payment->cod_referencia ?>')">
                                            <td>
                                                <?php switch ($payment->status):
                                                case 0: ?>
                                                    <div class="status-button tertiary">Pendente</div>
                                                <?php break; case 1: ?>
                                                    <div class="status-button primary">Pago</div>
                                                <?php break; case 2: ?>
                                                    <div class="status-button secondary">Vencido</div>
                                                <?php break; case 3: ?>
                                                    <div class="status-button tertiary">Pendente</div>
                                                <?php break; endswitch; ?>
                                            </td>
                                            <td>R$ <?= $payment->valor ?>,00</td>
                                            <td><?= date('d-m-Y', strtotime($payment->pagar_em)); ?></td>
                                            <td><?= $payment->cod_referencia ?></td>
                                            <td>                                      <?php switch ($payment->tipo):
                                                case 1: ?>
                                                    <div class="status-button primary">Pagamento</div>
                                                <?php break; default: ?>
                                                    <div class="status-button secondary">Vencido</div>
                                                <?php break; endswitch; ?></td>
                                                <td><?= $payment->name ?></td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                            <div class="text-center p-4 empty-table">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty.svg') ?>">
                                <h4>Ops.......!</h4>
                                <p>Nenhum dado foi encontrado</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $v->start("scripts"); ?>
<script src="<?= url("themes/assets/vendor/bootstrap/js/popper.js"); ?>"></script>
<script src="<?= url("themes/assets/vendor/bootstrap/js/bootstrap.min.js"); ?>"></script>
<script>
    function openPage(data) {
        window.open("http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?"+ data, '_blank');
    }

    function tableFilter() {
        let input, filter, table, tr, td, i, txtValue;
        let selectedOption = 3;

        input = document.getElementById("text");
        filter = input.value.toUpperCase();
        table = document.getElementById("table-data");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[selectedOption];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
                console.log(txtValue);
            }
        }
        if ($('tr:visible').length === 1) {
            $('.empty-table').show();
        } else {
            if ($('.empty-table').show()) {
                $('.empty-table').hide()
            }
        }
    }
</script>
<?php $v->end(); ?>
