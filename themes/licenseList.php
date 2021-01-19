<?php $v->layout("_theme.php"); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-xl-12 mb-5">
            <div class="web-div-box">
                <div class="box-div-info" style="max-height: 60vh;">
                    <div>
                        <h3 class="ml-3 black-title-section">Minhas licenças</h3>
                        <p class="ml-3 subtitle-section-p">Todos as suas licenças aparecerão aqui.</p>

                        <div class="div-box-span-icon mt-3">
                            <div class="div-table-search">
                                <input id="text" onkeyup="tableFilter()" class="input-table-search" type="text" placeholder="Filtrar pelo nome...">
                                <div class="circle-button primary search">
                                    <span class="icon-search"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="box-div-info-overflow-x">
                        <?php if(!$licenses): ?>
                            <div class="p-5 mt-5 text-center">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                                <p class="mt-5">Ops! Não encontramos nenhuma licença. 😥</p>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
