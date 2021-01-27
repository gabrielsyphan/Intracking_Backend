<?php $v->layout("_theme.php") ?>

<div class="container-fluid container-white mt-5 border-bottom-gray">
    <div class="p-5">
        <div class="row">
            <div class="col-xl-7">
                <h2 class="black-title-section">Precisa de ajuda?</h2>
                <p class="subtitle-section-p">Preparamos alguns vídeos para te ajudar a se localizar no sistema.</p>
            </div>
            <hr class="col-xl-12">
            <div class="col-xl-6 mb-5">
                <div class="row m-0 mt-5 p-4 div-gray-bg">
                    <div class="col-xl-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/user.png') ?>">
                    </div>
                    <div class="col-xl-10">
                        <h4>Como cadastrar um novo usuário</h4>
                        <p class="subtitle-section-p">Aprenda como adastrar um novo usuário.</p>
                    </div>
                    <div class="col-xl-12 text-center">
                        <hr>
                        <a href="https://www.youtube.com/embed/8B3gm7WL3CY" target="_blank">Acesse o vídeo clicando aqui!</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mb-5">
                <div class="row m-0 mt-5 p-4 div-gray-bg">
                    <div class="col-xl-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/salesman.png') ?>">
                    </div>
                    <div class="col-xl-10">
                        <h4>Como solicitar uma licença de ambulante</h4>
                        <p class="subtitle-section-p">Um tour por todos os mapas e como funcionam.</p>
                    </div>
                    <div class="col-xl-12 text-center">
                        <hr>
                        <a href="https://www.youtube.com/embed/s3pkLVJtueA" target="_blank">Acesse o vídeo clicando aqui!</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mb-5">
                <div class="row m-0 mt-5 p-4 div-gray-bg">
                    <div class="col-xl-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/building.png') ?>">
                    </div>
                    <div class="col-xl-10">
                        <h4>Como solicitar uma licença de empresa</h4>
                        <p class="subtitle-section-p">Um tour por todos os mapas e como funcionam.</p>
                    </div>
                    <div class="col-xl-12 text-center">
                        <hr>
                        <a href="https://www.youtube.com/embed/s3pkLVJtueA" target="_blank">Acesse o vídeo clicando aqui!</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mb-5">
                <div class="row m-0 mt-5 p-4 div-gray-bg">
                    <div class="col-xl-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/password.png') ?>">
                    </div>
                    <div class="col-xl-10">
                        <h4>Como alterar uma senha</h4>
                        <p class="subtitle-section-p">Aprenda como alterar sua senha do sistema.</p>
                    </div>
                    <div class="col-xl-12 text-center">
                        <hr>
                        <a href="https://www.youtube.com/embed/wSDvbEm6nng" target="_blank">Acesse o vídeo clicando aqui!</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mb-5">
                <div class="row m-0 mt-5 p-4 div-gray-bg">
                    <div class="col-xl-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/map.png') ?>">
                    </div>
                    <div class="col-xl-10">
                        <h4>Como funcionam os mapas</h4>
                        <p class="subtitle-section-p">Um tour por todos os mapas e como funcionam.</p>
                    </div>
                    <div class="col-xl-12 text-center">
                        <hr>
                        <a href="https://www.youtube.com/embed/s3pkLVJtueA" target="_blank">Acesse o vídeo clicando aqui!</a>
                    </div>
                </div>
            </div>

            <?php if ($_SESSION['user']['login'] === 3): ?>
            <div class="col-xl-6 mb-5">
                <div class="row m-0 mt-5 p-4 div-gray-bg">
                    <div class="col-xl-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/compass.png') ?>">
                    </div>
                    <div class="col-xl-10">
                        <h4>Como cadastrar uma nova zona</h4>
                        <p class="subtitle-section-p">Um tour por todos os mapas e como funcionam.</p>
                    </div>
                    <div class="col-xl-12 text-center">
                        <hr>
                        <a href="https://www.youtube.com/embed/s3pkLVJtueA" target="_blank">Acesse o vídeo clicando aqui!</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mb-5">
                <div class="row m-0 mt-5 p-4 div-gray-bg">
                    <div class="col-xl-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/megaphone.png') ?>">
                    </div>
                    <div class="col-xl-10">
                        <h4>Como notificar / multar um ambulante</h4>
                        <p class="subtitle-section-p">Um tour por todos os mapas e como funcionam.</p>
                    </div>
                    <div class="col-xl-12 text-center">
                        <hr>
                        <a href="https://www.youtube.com/embed/s3pkLVJtueA" target="_blank">Acesse o vídeo clicando aqui!</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mb-5">
                <div class="row m-0 mt-5 p-4 div-gray-bg">
                    <div class="col-xl-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/block.png') ?>">
                    </div>
                    <div class="col-xl-10">
                        <h4>Como suspender um ambulante</h4>
                        <p class="subtitle-section-p">Um tour por todos os mapas e como funcionam.</p>
                    </div>
                    <div class="col-xl-12 text-center">
                        <hr>
                        <a href="https://www.youtube.com/embed/s3pkLVJtueA" target="_blank">Acesse o vídeo clicando aqui!</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mb-5">
                <div class="row m-0 mt-5 p-4 div-gray-bg">
                    <div class="col-xl-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/download.png') ?>">
                    </div>
                    <div class="col-xl-10">
                        <h4>Como baixar o aplicativo</h4>
                        <p class="subtitle-section-p">Um tour por todos os mapas e como funcionam.</p>
                    </div>
                    <div class="col-xl-12 text-center">
                        <hr>
                        <a href="https://www.youtube.com/embed/s3pkLVJtueA" target="_blank">Acesse o vídeo clicando aqui!</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
