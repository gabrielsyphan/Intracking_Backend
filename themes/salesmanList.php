<?php $v->layout("_theme.php") ?>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-xl-3 mb-4">
            <div class="web-div-box border-bottom-gray">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Licenças cadastradas
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $registered ?></h2>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-drivers-license card-icon registered-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 mb-4">
            <div class="web-div-box border-bottom-gray">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Licenças aprovadas
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
        <div class="col-xl-3 mb-4">
            <div class="web-div-box border-bottom-gray">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Licenças pendentes
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $pending ?></h2>
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
        <div class="col-xl-3 mb-4">
            <div class="web-div-box border-bottom-gray">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Licenças bloqueados
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $blocked ?></h2>
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

<div class="container-fluid container-white mt-3 border-bottom-gray">
    <div class="row mt-5">
        <div class="col-xl-12 mb-5">
            <div class="web-div-box">
                <div class="box-div-info" style="max-height: 60vh;">
                    <div>
                        <h3 class="ml-3 title-section">Lista de licenças</h3>
                        <p class="ml-3 subtitle-section-p">Todas licenças cadastradas no Orditi</p>

                        <div class="div-box-span-icon mt-3">
                            <div class="div-table-search">
                                <input id="text" onkeyup="tableFilter()" class="input-table-search" type="text"
                                       placeholder="Filtrar pelo nome...">
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
                                    <a class="dropdown-item" href="<?= url('exportData/1') ?>">
                                        Exportar tabela em xls
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="margin-bottom: 0">
                    <div class="box-div-info-overflow-x">
                        <?php if (!$licenses): ?>
                            <div class="p-5 mt-5 text-center">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                                <p class="mt-5 subtitle-section-p">Ops! Não encontramos nenhum ambulante ou empresa.
                                    😥</p>
                            </div>
                        <?php else: ?>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Cpf</th>
                                    <th>Proprietário</th>
                                    <th>Início</th>
                                    <th>Fim</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody id="table-data">
                                <?php foreach ($licenses as $license):
                                    switch ($license->status):
                                        case 0:
                                            $divStatus = 'tertiary';
                                            $textStatus = 'Pendente';
                                            $trClass = 'border-left-yellow';
                                            break;
                                        case 1:
                                            $divStatus = 'primary';
                                            $textStatus = 'Ativo';
                                            $trClass = 'border-left-green';
                                            break;
                                        default:
                                            $divStatus = 'secondary';
                                            $textStatus = 'Bloqueado';
                                            $trClass = 'border-left-red';
                                            break;
                                    endswitch; ?>
                                    <tr class="<?= $trClass ?>"
                                        onclick="licenseInfo(<?= $license->tipo ?>, '<?= md5($license->id) ?>')">
                                        <td><?= $types[$license->tipo-1]->nome ?></td>
                                        <td><?= $users[$license->id_usuario]->cpf ?></td>
                                        <td><?= $users[$license->id_usuario]->nome ?></td>
                                        <td><?= $license->data_inicio ?></td>
                                        <td><?= $license->data_fim ?></td>
                                        <td>
                                            <div class="status-button <?= $divStatus ?>"><?= $textStatus ?></div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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
    $('.js-pscroll').each(function () {
        var ps = new PerfectScrollbar(this);

        $(window).on('resize', function () {
            ps.update();
        })
    });

    function licenseInfo(licenseType, licenseId) {
        window.location.href = '<?= url('licenseInfo') ?>/' + licenseType + '/' + licenseId;
    }

    function tableFilter() {
        let input, filter, table, tr, td, i, txtValue;
        let selectedOption = 2;

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
