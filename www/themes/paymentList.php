<?php $v->layout("_theme.php") ?>
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Filtrar Dados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-5 text-center">
                    <form id="form-create-account" method="POST" action="<?= $router->route("web.validateAccount"); ?>">
                        <fieldset class="row">
                            <div class="col-12">
                                <input id="marketInput" list="market" placeholder="mercado">
                                <datalist id="market">
                                    <option value="Internet Explorer">
                                    <option value="Firefox">
                                    <option value="Chrome">
                                    <option value="Opera">
                                    <option value="Safari">
                                </datalist>
                                <input id="sectorInput" list="sector" onkeyup="dashFilter()">
                                <datalist id="sector">
                                    <option value="Internet Explorer">
                                    <option value="Firefox">
                                    <option value="Chrome">
                                    <option value="Opera">
                                    <option value="Safari">
                                </datalist>
                                <input id="boxInput" list="box">
                                <datalist id="box" onkeyup="dashFilter()">
                                    <option value="Internet Explorer">
                                    <option value="Firefox">
                                    <option value="Chrome">
                                    <option value="Opera">
                                    <option value="Safari">
                                </datalist>
                            </div>
                            <div class="col-xl-12 text-right mb-5 mt-5">
                                <button type="button" class="btn-3 primary" onclick="dashFilter()">Filtrar</button>
                            </div>
                            <hr class="">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-5">
    <a data-toggle="modal" data-target="#filterModal">filtrar</a>
    <div class="row">
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Pagamentos cadastrados
                            </h4>
                            <hr>
                            <h2 class="title-section" id="paymentCount"><?= $amount ?></h2>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-credit-card card-icon registered-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Pagamentos confirmados
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $paid ?></h2>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-verified_user card-icon paid-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Pagamentos pendentes
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $pendent ?></h2>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-warning card-icon pending-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Pagamentos <br> vencidos
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $expired ?></h2>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-block card-icon expired-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-xl-12 mb-5">
            <div class="web-div-box">
                <div class="box-div-info">
                    <h3 class="ml-3 title-section">Lista de pagamentos</h3>
                    <p class="ml-3 subtitle-section-p">Todos os pagamentos cadastrados no Orditi</p>

                    <div class="div-box-span-icon mt-4">
                        <div class="div-table-search">
                            <input id="text" onkeyup="tableFilter()" class="input-table-search" type="text"
                                   placeholder="Filtrar por proprietário...">
                            <div class="circle-button primary search">
                                <span class="icon-search"></span>
                            </div>
                        </div>

                        <div class="dropleft">
                            <div class="ml-3 circle-button secondary" id="dropdownMenuButton"
                                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                 title="Exportar tabela">
                                <span class="icon-download"></span>
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?= url('exportData/2') ?>">
                                    Exportar tabela em xls
                                </a>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="box-div-info-overflow-x">
                        <?php if (!$payments): ?>
                            <div class="p-5 mt-5 text-center">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                                <p class="mt-5 subtitle-section-p">Ops! Não encontramos nenhum pagamento para exibir
                                    aqui. 😥</p>
                            </div>
                        <?php else: ?>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" class="table-col-2" onclick="changeFilter(0)">
                                        <div class="d-flex">
                                            <div class="marker" id="0"></div>
                                            Valor
                                        </div>
                                    </th>
                                    <th scope="col" class="table-col-2" onclick="changeFilter(1)">
                                        <div class="d-flex">
                                            <div class="marker" id="1"></div>
                                            Vencimento
                                        </div>
                                    </th>
                                    <?php if ($_SESSION['user']['team'] == 2): ?>
                                        <th scope="col" class="table-col-2" onclick="changeFilter(2)">
                                            <div class="d-flex">
                                                <div class="marker" id="2"></div>
                                                Mercado
                                            </div>
                                        </th>
                                        <th scope="col" class="table-col-1" onclick="changeFilter(3)">
                                            <div class="d-flex">
                                                <div class="marker" id="3"></div>
                                                Box
                                            </div>
                                        </th>
                                    <?php else: ?>
                                        <th scope="col" class="table-col-1" onclick="changeFilter(2)">
                                            <div class="marker " id="2"></div>
                                            Cod Referência
                                        </th>
                                        <th scope="col" class="table-col-2" onclick="changeFilter(3)">
                                            <div class="marker" id="3"></div>
                                            Tipo
                                        </th>
                                    <?php endif; ?>
                                    <th scope="col" class="table-col-2" onclick="changeFilter(4)">
                                        <div class="marker" id="4"></div>
                                        Status
                                    </th>
                                    <th scope="col" onclick="changeFilter(5)">
                                        <div class="marker active" id="5"></div>
                                        Proprietário
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="table-data">
                                <?php
                                if ($payments !== NULL):
                                    foreach ($payments as $payment):
                                        switch ($payment->status):
                                            case 1:
                                                $divStatus = 'primary';
                                                $textStatus = 'Pago';
                                                $trClass = 'border-left-green';
                                                break;
                                            case 2:
                                                $divStatus = 'secondary';
                                                $textStatus = 'Vencido';
                                                $trClass = 'border-left-red';
                                                break;
                                            default:
                                                $divStatus = 'tertiary';
                                                $textStatus = 'Pendente';
                                                $trClass = 'border-left-yellow';
                                                break;
                                        endswitch; ?>
                                        <tr class="<?= $trClass ?>"
                                            onclick="openPage('<?= $payment->cod_referencia ?>')">
                                            <td>R$ <?= $payment->valor ?>,00</td>
                                            <td><?= date('d/m/Y', strtotime($payment->pagar_em)); ?></td>
                                            <?php if ($_SESSION['user']['team'] == 2): ?>
                                                <td><?= $payment->name_zone; ?></td>
                                                <td><?= $payment->name_box ?></td>
                                            <?php else: ?>
                                                <td>
                                                    <?php switch ($payment->tipo):
                                                        case 1: ?>
                                                            Recorrente
                                                            <?php break;
                                                        default: ?>
                                                            Vencido
                                                            <?php break; endswitch; ?>
                                                </td>
                                                <td><?= $payment->cod_referencia ?></td>
                                            <?php endif; ?>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="status-circle <?= $divStatus; ?> t-5"></div>
                                                    <?= $textStatus; ?>
                                                </div>
                                            </td>
                                            <td><?= $payment->name ?></td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                            <div class="text-center p-4 empty-table">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty.svg') ?>">
                                <h4 class="black-title-section">Ops.......!</h4>
                                <p class="subtitle-section-p">Nenhum dado foi encontrado</p>
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
        let selectedOption = 5;
        <?php if($_SESSION['user']['team'] == 2): ?>
        let options = ['valor', 'vencimento', 'mercado', 'box', 'status', 'proprietário'];
        <?php else: ?>
        let options = ['valor', 'vencimento', 'Referência', 'tipo', 'status', 'proprietário'];
        <?php endif; ?>
        let market = ""
        let sector = ""

        function openPage(data) {
            window.open("http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?" + data, '_blank');
        }

        function changeFilter(num) {
            selectedOption = num;
            input = document.getElementById("text");
            input.placeholder = "Filtrar por " + options[num] + "...";
            mark = document.getElementById(num);
            markers = document.getElementsByClassName("marker");
            for (var i = 0; i < markers.length; i++) {
                markers[i].className = "marker"
            }
            mark.className = "marker active";
        }

        function dashFilter() {

            let market, sector, filterSector, filterMarket, table, tr, td, i, txtValue, paymentCount, countField;

            market = document.getElementById("marketInput");
            sector = document.getElementById("sectorInput");
            countField = document.getElementById("paymentCount");
            filterMarket = market.value.toUpperCase();
            filterSector = sector.value.toUpperCase();
            table = document.getElementById("table-data");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[2];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filterMarket) > -1) {
                        td = tr[i].getElementsByTagName("td")[3];
                        if (td) {
                            txtValue = td.textContent || td.innerText;
                            if (txtValue.toUpperCase().indexOf(filterSector) > -1) {
                                tr[i].style.display = "";
                            } else {
                                tr[i].style.display = "none";
                            }
                            console.log(txtValue);
                        }
                    } else {
                        tr[i].style.display = "none";
                    }
                    console.log(txtValue);
                }
            }
            if ($('tr:visible').length === 1) {
                $('.empty-table').show();
                paymentCount = table.getElementsByTagName("tr");
                countField.innerText = paymentCount;
            } else {
                if ($('.empty-table').show()) {
                    $('.empty-table').hide()
                }
            }
        }

        function tableFilter() {

            dashFilter();

            let input, filter, table, tr, td, i, txtValue;

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
