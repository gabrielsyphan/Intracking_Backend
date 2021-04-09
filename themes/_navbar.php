<?php if (isset($_SESSION['user']['login'])): ?>
    <nav id="sidebar">
        <div class="sidebar-header text-center">
            <img class="sidebar-image" src="<?= $_SESSION['user']['image'] ?>" data-toggle="modal"
                 data-target="#changeImageModal" title="Clique para alterar sua foto" alt="Foto do usuário">
            <p>
                <?= explode(' ', $_SESSION['user']['name'])[0] ?>
                <?= explode(' ', $_SESSION['user']['name'])[1] ?>
            </p>
        </div>

        <ul class="list-unstyled components mt-5 mb-5 pb-5">
            <li class="<?= ($router->isCurrentRoute("web.home") ? 'active' : ''); ?>">
                <a href="<?= url('') ?>"><span class="icon-home"></span>Início</a>
            </li>
            <?php if ($_SESSION['user']['login'] === 0): ?>
                <li class="<?= ($router->isCurrentRoute("web.licenseList") ? 'active' : ''); ?>">
                    <a href="<?= url("licenseList"); ?>">
                        <span class="icon-drivers-license"></span>
                        Minhas licenças
                    </a>
                </li>
                <li class="<?= ($router->isCurrentRoute("web.requestLicense") ? 'active' : ''); ?>">
                    <a href="<?= url("requestLicense"); ?>">
                        <span class="icon-drivers-license-o"></span>
                        Solicitar licença
                    </a>
                </li>
            <?php else: ?>
                <li class="
        <?= ($router->isCurrentRoute("web.licenseList") ? 'active' : ''); ?>
        <?= ($router->isCurrentRoute("web.salesmanProfile") ? 'active' : ''); ?>
        <?= ($router->isCurrentRoute("web.companyInfo") ? 'active' : ''); ?>
        ">
                    <a href="<?= url("licenseList"); ?>">
                        <span class="icon-drivers-license"></span>
                        Licenças
                    </a>
                </li>
                <?php if ($_SESSION['user']['role'] == 3 ||  $_SESSION['user']['role'] == 4): ?>
                    <li class="<?= ($router->isCurrentRoute("web.paymentList") ? 'active' : ''); ?>">
                        <a href="<?= url("paymentList"); ?>">
                            <span class="icon-money"></span>
                            Pagamentos
                        </a>
                    </li>
                <?php endif; ?>

                <li class="<?= ($router->isCurrentRoute("web.neighborhoodList") ? 'active' : ''); ?>">
                    <a href="<?= url("neighborhoodList"); ?>">
                        <span class="icon-map-marker"></span>
                        Bairros
                    </a>
                </li>

                <?php if ($_SESSION['user']['role'] == 4): ?>
                    <li class="<?= ($router->isCurrentRoute("web.agentList") ? 'active' : ''); ?>">
                        <a href="<?= url("agentList"); ?>">
                            <span class="icon-users"></span>
                            Fiscais
                        </a>
                    </li>
                <?php endif; ?>

                <li class="<?= ($router->isCurrentRoute("web.userList") ? 'active' : ''); ?>">
                    <a href="<?= url("userList"); ?>">
                        <span class="icon-user-circle"></span>
                        Usuários
                    </a>
                </li>

                <?php if ($_SESSION['user']['role'] == 4 || $_SESSION['user']['role'] == 2): ?>
                    <li class="<?= ($router->isCurrentRoute("web.createZone") ? 'active' : ''); ?>">
                        <a href="<?= url("createZone"); ?>">
                            <span class="icon-map-signs"></span>
                            Nova área
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($_SESSION['user']['role'] == 4): ?>
                    <li class="<?= ($router->isCurrentRoute("web.createAgent") ? 'active' : ''); ?>">
                        <a href="<?= url("createAgent"); ?>">
                            <span class="icon-user-secret"></span>
                            Novo fiscal
                        </a>
                    </li>
                    <li class="<?= ($router->isCurrentRoute("web.createUser") ? 'active' : ''); ?>">
                        <a href="<?= url("createUser"); ?>">
                            <span class="icon-user-plus"></span>
                            Novo usuário
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            <li class="<?= ($router->isCurrentRoute("web.salesmanMap") ? 'active' : ''); ?>">
                <a href="<?= url("salesmanMap"); ?>">
                    <span class="icon-map"></span>
                    Mapa
                </a>
            </li>
            <li class="<?= ($router->isCurrentRoute("web.profile") ? 'active' : ''); ?>">
                <a href="<?= url("profile"); ?>">
                    <span class="icon-user"></span>
                    Perfil
                </a>
            </li>
            <li class="<?= ($router->isCurrentRoute("web.videos") ? 'active' : ''); ?>">
                <a href="<?= url("videos"); ?>">
                    <span class="icon-video_library"></span>
                    Vídeos
                </a>
            </li>
            <li>
                <a href="<?= url("logout"); ?>">
                    <span class="icon-sign-out"></span>
                    Sair
                </a>
            </li>
        </ul>
    </nav>

    <div class="modal fade" id="changeImageModal" tabindex="-1" role="dialog" aria-labelledby="changeImageModal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Alterar foto do perfil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-new-user-image" enctype="multipart/form-data" method="POST"
                      action="<?= $router->route("web.updateUserImg"); ?>">
                    <fieldset>
                        <div class="modal-body">
                            <div class="p-5">
                                <div class="form-group">
                                    <h4>Selecione sua nova foto:</h4>
                                    <input type="file" class="" id="localImage"
                                           name="localImage"
                                           accept="image/png, image/jpg, image/jpeg"
                                           onchange="uploadImage(this)">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn-3 primary-color">Enviar</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

    <?= $v->start("script-side-bar"); ?>
    <script>
        $('#form-new-user-image').on('submit', function (e) {
            e.preventDefault();
            $("#loader-div").show();

            const _thisForm = $(this);
            const data = new FormData(this);
            const fieldsetDisable = _thisForm.find('fieldset');
            fieldsetDisable.attr('disabled', true);

            if (formSubmit(this) === true) {
                $.ajax({
                    type: _thisForm.attr('method'),
                    url: _thisForm.attr('action'),
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function (returnData) {
                    if (returnData == 'success') {
                        $('#loader-div').hide();
                        swal({
                            icon: "success",
                            title: "Tudo certo!",
                            text: "Sua foto de perfil foi alterada.",
                        }).then((result) => {
                            window.location.href = "<?= $router->route('web.home') ?>";
                        });
                    } else{
                        swal({
                            icon: "error",
                            title: "Erro",
                            text: "Não foi possível trocar sua foto.",
                        });
                    }
                    console.log(returnData);
                }).fail(function () {
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Erro ao processar requisição",
                    });
                }).always(function () {
                    $("#loader-div").hide();
                    fieldsetDisable.removeAttr("disabled");
                });
            }
        });
    </script>
    <?= $v->end(); ?>
<?php endif; ?>
