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
<<<<<<< Updated upstream
                        <p class="subtitle-section-p">Um tour por todos os mapas e como funcionam.</p>
=======
                        <p class="subtitle-section-p">Aprenda como cadastrar uma licença de ambulante.</p>
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
                        <p class="subtitle-section-p">Um tour por todos os mapas e como funcionam.</p>
=======
                        <p class="subtitle-section-p">Aprenda como cadastrar uma licença de empresa.</p>
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
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
=======
>>>>>>> Stashed changes
                        <p class="subtitle-section-p">Um tour por todos os mapas e como funcionam.</p>
                    </div>
                    <div class="col-xl-12 text-center">
                        <hr>
                        <a href="https://www.youtube.com/embed/s3pkLVJtueA" target="_blank">Acesse o vídeo clicando aqui!</a>
                    </div>
                </div>
            </div>
<<<<<<< Updated upstream
=======

            <?php if ($_SESSION['user']['login'] === 3): ?>
            <div class="col-xl-6 mb-5">
                <div class="row m-0 mt-5 p-4 div-gray-bg">
                    <div class="col-xl-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/compass.png') ?>">
                    </div>
                    <div class="col-xl-10">
                        <h4>Como cadastrar uma nova zona</h4>
                        <p class="subtitle-section-p">Aprenda como cadastrar ou bloquear uma nova zona.</p>
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
                        <h4>Como notificar um ambulante</h4>
                        <p class="subtitle-section-p">Aprenda como criar/acessar as notificações de um ambulante e como multa-lo.</p>
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
                        <h4>Como multar e/ou suspender um ambulante</h4>
                        <p class="subtitle-section-p">Aprenda como suspender a licença de um ambulante ou multa-lo por algo.</p>
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
                        <p class="subtitle-section-p">Aprenda como baixar o app dos fiscais.</p>
                    </div>
                    <div class="col-xl-12 text-center">
                        <hr>
                        <a href="https://www.youtube.com/embed/s3pkLVJtueA" target="_blank">Acesse o vídeo clicando aqui!</a>
                    </div>
                </div>
            </div>
>>>>>>> Stashed changes
            <?php endif; ?>
        </div>
    </div>
</div>
