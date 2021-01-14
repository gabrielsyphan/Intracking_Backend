<?php $v->layout("_theme.php") ?>

<div class="container pt-5">
    <div class="row mt-5 p-4 justify-content-center">
        <div class="col-xl-5 mb-5">
            <div class="web-div-box">
                <div class="box-div-info pl-5 pr-5 pb-5 pt-3">
                    <div class="text-center">
                        <h3 class="h2-title-header-black">Bem-vindo ao Orditi!</h3>
                        <p class="subtitle-section-p">Comece informando sua nova senha</p>
                        <div class="mt-5">
                            <img style="width: 40%" src="<?= url('themes/assets/img/picture.png') ?>">
                            <p class="mt-3 subtitle-section-p"><?= explode(' ', $userName)[0] ?> <?= explode(' ', $userName)[1] ?></p>
                        </div>
                    </div>
                    <hr>
                    <form>
                        <div class="form-group">
                            <label>Senha:</label>
                            <input class="form-input" type="password" title="Sua senha" placeholder="Sua senha">
                        </div>
                        <div class="form-group">
                            <label>Confirmar senha:</label>
                            <input class="form-input" type="password" title="Sua senha" placeholder="Repita sua senha">
                        </div>
                        <button type="button" class="btn-3 primary-color w-100 mt-3">Confirmar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>