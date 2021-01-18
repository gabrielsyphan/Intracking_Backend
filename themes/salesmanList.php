<?php $v->layout("_theme.php") ?>

<div class="container-fluid">
    <div class="row mt-5">
        <div class="col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-xl-8">
                            <h4 class="title-section">
                                Ambulantes cadastrados
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $registered ?></h2>
                        </div>
                        <div class="col-xl-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-users card-icon registered-icon"></span>
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
                                Ambulantes autorizados
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
                                Ambulantes pendentes
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $pending ?></h2>
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
                                Ambulantes bloqueados
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $blocked ?></h2>
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
                        <h4 class="ml-3 title-section">Lista de usuários</h4>
                        <p class="ml-3 subtitle-section-p">Todos os ambulantes e empresas cadastrados no Orditi</p>

                        <div class="div-box-span-icon mt-3">
                            <div class="div-table-search">
                                <input id="text" onkeyup="tableFilter()" class="input-table-search" type="text" placeholder="Filtrar pelo nome...">
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
                                    <a class="dropdown-item" href="<?= url('exportData/2') ?>">
                                        <span class="icon-users mr-3"></span>
                                        Planilha de ambulantes
                                    </a>
                                    <a class="dropdown-item" href="<?= url('exportData/3') ?>">
                                        <span class="icon-building mr-3"></span>
                                        Planilha de empresas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="margin-bottom: 0">
                    <div class="box-div-info-overflow-x">
                        <?php if($users == NULL): ?>
                            <div class="p-5 mt-5 text-center">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                                <p class="mt-5">Ops! Não encontramos nenhum ambulante ou empresa. 😥</p>
                            </div>
                        <?php else: ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col" class="table-col-1">CPF/CNPJ</th>
                                <th scope="col" class="table-col-2">Nome</th>
                                <th scope="col" class="table-col-3">Email</th>
                                <th scope="col" class="table-col-4">Situação</th>
                                <th scope="col" class="table-col-5">Localização</th>
                            </tr>
                            </thead>
                            <tbody id="table-data">
                            <?php if($users !== NULL):
                            foreach ($users as $user): ?>
                                <tr onclick="openPage('<?= $user->id ?>')">
                                    <td><?= $user->cpf ?></td>
                                    <td><?= $user->nome ?></td>
                                    <td><?= $user->email ?></td>
                                    <td>
                                        <?php switch ($user->situacao):
                                        case 0: ?>
                                            <div class="status-button tertiary">Pendente</div>
                                        <?php break; case 1: ?>
                                            <div class="status-button primary">Ativo</div>
                                        <?php break; case 2: ?>
                                            <div class="status-button secondary">Inadimplente</div>
                                        <?php break; case 3: ?>
                                            <div class="status-button tertiary">Pendente</div>
                                        <?php break; endswitch; ?>
                                    </td>
                                    <td><?= $user->endereco ?></td>
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
        $('.js-pscroll').each(function(){
            var ps = new PerfectScrollbar(this);

            $(window).on('resize', function(){
                ps.update();
            })
        });

        function openPage(data) {
            window.open("<?= url('salesman'); ?>/"+ data, '_blank');
        }
        
        function openCompanyPage(data) {
            window.open("<?= url('company'); ?>/"+ data, '_blank');
        }

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
    </script>
<?php $v->end(); ?>
