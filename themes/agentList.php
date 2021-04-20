<?php $v->layout("_theme.php") ?>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="web-div-box border-bottom-gray">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Fiscais cadastrados
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $agentCount ?></h2>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-users card-icon registered-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="web-div-box border-bottom-gray">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Fiscais autorizados
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $approved ?></h2>
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
            <div class="web-div-box border-bottom-gray">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Fiscais pendentes
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $pendding ?></h2>
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
            <div class="web-div-box border-bottom-gray">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Fiscais bloqueados
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

<div class="container-fluid mt-3 order-bottom-gray">
    <div class="row">
        <div class="col-xl-12 mb-5">
            <div class="web-div-box">
                <div class="box-div-info">
                    <div>
                        <h3 class="ml-3 title-section">Lista de agentes</h3>
                        <p class="ml-3 subtitle-section-p">Todos os agentes cadastrados no Orditi</p>

                        <div class="div-box-span-icon mt-4">
                            <div class="div-table-search">
                                <input id="text" onkeyup="tableFilter()" class="input-table-search" type="text"
                                       placeholder="Filtrar pela matrícula...">
                                <div class="circle-button primary search">
                                    <span class="icon-search"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="box-div-info-overflow-x">
                        <?php if (!$agents): ?>
                            <div class="p-5 mt-5 text-center">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                                <p class="mt-5 subtitle-section-p">Ops! Não encontramos nenhum ambulante ou empresa.
                                    😥</p>
                            </div>
                        <?php else: ?>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" class="table-col-6">Agente</th>
                                    <th scope="col">Matrícula</th>
                                    <th scope="col">Cargo</th>
                                    <th scope="col">Secretaria</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Ações</th>
                                </tr>
                                </thead>
                                <tbody id="table-data">
                                <?php foreach ($agents as $agent):
                                    switch ($agent->situacao):
                                        case 0:
                                            $trClass = 'border-left-yellow';
                                            break;
                                        case 1:
                                            $trClass = 'border-left-green';
                                            break;
                                        default:
                                            $trClass = 'border-left-red';
                                            break;
                                    endswitch; ?>
                                    <tr class="<?= $trClass ?> custom-table">
                                        <td>
                                            <div class="d-flex">
                                                <img class="image-table" src="<?= $agent->image ?>">
                                                <div class="ml-4">
                                                    <h5><?= $agent->nome ?></h5>
                                                    <h5 class="table-contact"><?= $agent->email ?></h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= $agent->matricula ?></td>
                                        <td><?= $agent->role ?></td>
                                        <td><?= $agent->team ?></td>
                                        <td>
                                            <?php switch ($agent->situacao):
                                            case 0: ?>
                                            <div class="d-flex">
                                                <div class="status-circle tertiary"></div>
                                                Pendente
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn secondary-color status-circle-change"
                                               href="<?= $router->route('web.changeAgentStatus/' . $agent->id) ?>">
                                                <span class="icon-check"></span>
                                                Permitir
                                            </a>
                                        </td>
                                        <?php break;
                                        case 1: ?>
                                                <div class="d-flex">
                                                    <div class="status-circle primary"></div>
                                                    Ativo
                                                </div>
                                            </td>
                                            <td>
                                                <a class="status-circle-change"
                                                   href="<?= ($agent->id == $_SESSION['user']['id'] ? '#' : url('changeAgentStatus/' . $agent->id)) ?>" <?= ($agent->id == $_SESSION['user']['id'] ? 'disabled onclick="sameUser()"' : ''); ?>>
                                                    <span class="icon-block"></span>
                                                </a>
                                            </td>
                                            <?php break;
                                        default: ?>
                                                <div class="d-flex">
                                                    <div class="status-circle secondary"></div>
                                                    Bloqueado
                                                </div>
                                            </td>
                                            <td>
                                                <a class="status-circle-change"
                                                   href="<?= url('changeAgentStatus/' . $agent->id) ?>">
                                                    <span class="icon-check"></span>
                                                </a>
                                            </td>
                                            <?php break; endswitch; ?>
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
    let filterValue = 1;

    function alterFilter(e) {
        filterValue = e;
    }

    function sameUser() {
        swal({
            icon: "warning",
            title: "Ops..!",
            text: "Você não pode bloquear a sí mesmo."
        });
    }

    function tableFilter() {
        let input, filter, table, tr, td, i, txtValue;
        let selectedOption = filterValue;

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
