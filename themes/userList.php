<?php $v->layout("_theme.php") ?>

<div class="container-fluid mt-5 container-white border-bottom-gray">
    <div class="row mt-5">
        <div class="col-xl-12 mb-5">
            <div class="web-div-box">
                <div class="box-div-info">
                    <div>
                        <h3 class="ml-3 title-section">Lista de usuários</h3>
                        <p class="ml-3 subtitle-section-p">Todos os usuários cadastrados no Orditi</p>

                        <div class="div-box-span-icon mt-3">
                            <div class="div-table-search">
                                <input id="text" onkeyup="tableFilter()" class="input-table-search" type="text"
                                       placeholder="Filtrar pelo nome...">
                                <div class="circle-button primary search">
                                    <span class="icon-search"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="box-div-info-overflow-x">
                        <?php if (!$users): ?>
                            <div class="p-5 mt-5 text-center">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                                <p class="mt-5 subtitle-section-p">Ops! Não encontramos nenhum ambulante ou empresa.
                                    😥</p>
                            </div>
                        <?php else: ?>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" class="table-col-2">CPF <span class="icon-arrow_downward"></span>
                                    </th>
                                    <th scope="col" class="table-col-1">Nome <span class="icon-arrow_upward"></span>
                                    </th>
                                    <th scope="col" class="table-col-1">Email <span class="icon-arrow_downward"></span>
                                    </th>
                                    <th scope="col" class="table-col-1">Telefone <span class="icon-arrow_downward"></span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="table-data">
                                <?php foreach ($users as $user): ?>
                                    <tr class="border-left-green" onclick="profile('<?= md5($user->id) ;?>')">
                                        <td><?= $user->cpf ?></td>
                                        <td><?= $user->nome ?></td>
                                        <td><?= $user->email ?></td>
                                        <td><?= $user->telefone ?></td>
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

    function profile(userId) {
        window.location.href = '<?= url('profileUser') ?>' + '/' + userId;
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
