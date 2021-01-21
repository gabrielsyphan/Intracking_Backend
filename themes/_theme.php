<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <link rel="shortcut icon" href="<?= url("themes/assets/img/icon.png"); ?>" type="image/x-icon">
        <link rel="stylesheet" href="<?= url("themes/assets/fonts/icomoon/style.css"); ?>">
        <link rel="stylesheet" href="<?= url("vendor/bootstrap/css/bootstrap.min.css"); ?>">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= url("themes/assets/css/style.css"); ?>">
        <link rel="stylesheet" href="<?= url("themes/assets/css/chat.css"); ?>">

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
              integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
              crossorigin="" />
        <title><?= $title; ?></title>

        <?= $v->section("css") ?>
    </head>
    <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
        <div class="wrapper">
            <?php $v->insert('_navbar'); ?>

            <div id="loader-div" class="loader-div" style="display: none">
                <div class="loader-spin"></div>
            </div>

            <?php if ($router->isCurrentRoute("web.profile")) {
                require 'profileModals.php';
            }?>

            <?php if(isset($_SESSION['user']['login'])): ?>
                <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLongTitle"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Sobre</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="p-5 text-center">
                                    <div id="myCarousel" class="carousel slide help" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="item active">
                                                <div class="p-5">
                                                    <img style="width: 80%" src="<?= url('themes/assets/img/organization.svg') ?>">
                                                </div>
                                                <h4 class="mt-5">Organização</h4>
                                                <p>Aqui você encontrará todos os dados dos ambulantes
                                                    registrados de forma rápida e precisa. Tudo isso para agilizar o seu
                                                    processo de trabalho e o tornando mais simples.</p>
                                            </div>

                                            <div class="item">
                                                <div class="p-5">
                                                    <img style="width: 80%" src="<?= url('themes/assets/img/locale.svg') ?>">
                                                </div>
                                                <h4 class="mt-5">Mapeamento</h4>
                                                <p>Através de nossos mapas, você facilmente encontrará
                                                    qualquer ambulante e saberá todas as suas informações sem nem mesmo
                                                    precisar abordá-lo.</p>
                                            </div>

                                            <div class="item">
                                                <div class="p-5">
                                                    <img style="width: 80%" src="<?= url('themes/assets/img/dashboard.svg') ?>">
                                                </div>
                                                <h4 class="mt-5">Dashboard</h4>
                                                <p>Através da nossa área de Dashboard, você terá um resumo de
                                                    todas as informações do sistema. Tais como a listagem de ambulantes,
                                                    pagamentos, empresas e até mesmo poderá limitar a quantidade de
                                                    ambulantes dentro de uma zona.</p>
                                            </div>
                                        </div>

                                        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid" style="padding-left: 0; padding-right: 0;">
                    <div class="dash-main-header">
                        <button id="sidebarCollapse" class="btn btn-style-5" type="button">
                            <span class="icon-list"></span>
                        </button>

                        <span class="dash-main-header-view">
                            <?= explode(' | ', $title)[0]; ?>
                        </span>

                        <a id="sidebarCollapse" class="signOut" href=""
                                data-toggle="modal" data-target="#helpModal">
                            <span class="icon-help"></span>
                            Sobre
                        </a>

                        <a class="signOut" href="<?= url("logout"); ?>">
                            <span class="icon-sign-out"></span>
                            Sair
                        </a>
                    </div>
                    <?= $v->section("content"); ?>
                    <?= $v->insert("_chat.php"); ?>
                </div>
            <?php else: ?>
                <?= $v->section("content"); ?>
            <?php endif; ?>
        </div>

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <?= $v->section("scripts") ?>
        <?= $v->insert('_websocket.php'); ?>
        <script src="<?= url("themes/assets/js/main.js"); ?>"></script>
        <?= $v->section("script-side-bar") ?>
        <script>
            $("#loader-div").hide();
            $(document).ready(function () {
                $('#sidebarCollapse').on('click', function () {
                    $('#sidebar').toggleClass('active');
                });

                if($('.chat-messages').children().length == 0) {
                    $(".chat-messages").append("<div class='text-center mt-5 pt-5 div-image'><img src='<?= url("/themes/assets/img/empty.svg") ?>' style='width: 80%;'> <h5 class='mt-3'>Nenhuma mensagem recebida.</h5></div>");
                } else {
                    $(".chat-messages").before("<div class='chat-start'>Mensagens recebidas</div>");
                }

                <?php if(isset($_SESSION['user']['login']) && $_SESSION['user']['login'] == 3): ?>
                let chatDiv = document.getElementsByClassName('chat-body')[0];
                chatDiv.scrollTop = chatDiv.scrollHeight;
                $(".chat-bot-icon").click(function (e) {
                    $(this).children('img').toggleClass('hide');
                    $(this).children('svg').toggleClass('animate');
                    $('.chat-screen').toggleClass('show-chat');
                });
                <?php endif; ?>
            });
        </script>
    </body>
</html>
