<?php $v->layout("_theme.php"); ?>

<div class="container-fluid container-white border-bottom-gray mt-5">
    <div class="p-5 row">
        <div class="col-xl-12 mb-5">
            <div style="max-height: 60vh;">
                <div>
                    <h3 class="ml-3 black-title-section">Minhas licenças</h3>
                    <p class="ml-3 subtitle-section-p">Todos as suas licenças aparecerão aqui.</p>

                    <div class="div-box-span-icon mt-3">
                        <div class="div-table-search">
                            <input id="text" onkeyup="tableFilter()" class="input-table-search" type="text"
                                   placeholder="Filtrar tipo...">
                            <div class="circle-button primary search">
                                <span class="icon-search"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="mb-0">
                <div class="box-div-info-overflow-x">
                    <?php if (!$licenses): ?>
                        <div class="p-5 mt-5 text-center">
                            <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                            <p class="mt-5">Ops! Não encontramos nenhuma licença. 😥</p>
                        </div>
                    <?php else: ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col" class="table-col-4"># <span class="icon-arrow_downward"></span></th>
                                <th scope="col" class="table-col-2 ">Tipo <span class="icon-arrow_upward"></span></th>
                                <th scope="col" class="table-col-1">Início <span class="icon-arrow_downward"></span>
                                </th>
                                <th scope="col" class="table-col-1">Fim <span class="icon-arrow_downward"></span></th>
                                <th scope="col" class="table-col-4">Status <span class="icon-arrow_downward"></span>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="table-data">
                            <?php
                            $aux = 0;
                            foreach ($licenses as $license):
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
                                    <td><strong><?= $aux ?></strong></td>
                                    <td><?= $types[$license->tipo-1]->nome ?></td>
                                    <td><?= $license->data_inicio ?></td>
                                    <td><?= $license->data_fim ?></td>
                                    <td>
                                        <div class="status-button <?= $divStatus ?>"><?= $textStatus ?></div>
                                    </td>
                                </tr>
                                <?php $aux++; endforeach; ?>
                            </tbody>
                        </table>
                        <div class="text-center p-4 empty-table">
                            <img style="width: 20%" src="<?= url('themes/assets/img/empty.svg') ?>">
                            <h4>Ops.......!</h4>
                            <p>Nenhum dado foi encontrado</p>
                        </div>
                    <?php endif; ?>
                </div>
                <hr>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script src="<?= url("themes/assets/vendor/bootstrap/js/popper.js"); ?>"></script>
<script src="<?= url("themes/assets/vendor/bootstrap/js/bootstrap.min.js"); ?>"></script>
<script>
    function tableFilter() {
        let input, filter, table, tr, td, i, txtValue;
        let selectedOption = 1;

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

    function licenseInfo(licenseType, licenseId) {
        window.location.href = '<?= url('licenseInfo') ?>/' + licenseType + '/' + licenseId;
    }
</script>
<?php $v->end(); ?>
