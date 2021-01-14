<?php $v->layout("_theme.php") ?>

<div class="modal fade" id="recoveryPsw" tabindex="-1" role="dialog" aria-labelledby="recoveryPsw"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Recuperar senha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-psw-recovery" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="p-5">
                        <div class="form-group">
                            <h5>Insira os dados da conta que deseja recuperar:</h5>
                            <input type="text" id="identityRecovery" name="identityRecovery" class="form-input"
                                   placeholder="Inserir CPF ou CNPJ">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="pswRecovery()">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="newPassword" tabindex="-1" role="dialog" aria-labelledby="newPassword"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Recuperar senha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-new-password" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="p-5">
                        <div class="form-group">
                            <h5>Insira sua nova senha:</h5>
                            <input type="password" id="newPasswordInput" name="newPasswordInput"
                                   class="form-input" placeholder="Senha">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-style-1 mt-3" onclick="newPassword()">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container pt-5">
    <div class="row mt-5 p-4 justify-content-center">
        <div class="col-xl-5 mb-5">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="text-center mb-5">
                        <img src="<?= url('themes/assets/img/nav-logo.png') ?>" style="width: 50%;">
                    </div>
                    <hr>
                    <form id="login-form" class="form-login" method="POST">
                        <div class="form-group">
                            <label>Nome:</label>
                            <input type="text" class="form-input login-input" id="identity" name="identity"
                                   title="CPF" placeholder="Seu CPF ou CNPJ" required>
                        </div>

                        <div class="form-group">
                            <label>Senha:</label>
                            <input type="password" class="form-input login-input" id="psw" name="psw" title="Senha"
                                   placeholder="Sua senha" required>
                        </div>

                        <div class="row text-center">
                            <div class="col-xl-6">
                                <p class="login-recovery" data-toggle="modal" data-target="#recoveryPsw">
                                    <span class="icon-globe mr-2 subtitle-section-p"></span>
                                    Recuperar senha
                                </p>
                            </div>
                            <div class="col-xl-6">
                                <p class="login-recovery" onclick="createAccount()">
                                    <span class="icon-user-plus mr-2 subtitle-section-p"></span>
                                    Cadastrar usuário
                                </p>
                            </div>
                        </div>

                        <button type="button" class="btn-2 btn-primary mt-4" onclick="submitForm()">Acessar</button>

                        <hr>

                        <div class="col-xl-12 text-center mb-2">
                            <p class="login-recovery" onclick="loginAgent()">
                                Sou um agente !
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
    <script>
        $("#identity").keydown(function(){
            try {
                $("#identity").unmask();
            } catch (e) {}

            var tamanho = $("#identity").val().length;

            if(tamanho < 11){
                $("#identity").mask("999.999.999-99");
            } else {
                $("#identity").mask("99.999.999/9999-99");
            }

            var elem = this;
            setTimeout(function(){
                elem.selectionStart = elem.selectionEnd = 10000;
            }, 0);
            var currentValue = $(this).val();
            $(this).val('');
            $(this).val(currentValue);
        });

        $("#identityRecovery").keydown(function(){
            try {
                $("#identityRecovery").unmask();
            } catch (e) {}

            var tamanho = $("#identityRecovery").val().length;

            if(tamanho < 11){
                $("#identityRecovery").mask("999.999.999-99");
            } else {
                $("#identityRecovery").mask("99.999.999/9999-99");
            }

            var elem = this;
            setTimeout(function(){
                elem.selectionStart = elem.selectionEnd = 10000;
            }, 0);
            var currentValue = $(this).val();
            $(this).val('');
            $(this).val(currentValue);
        });

        function submitForm() {
            let identity = $("#identity").val();
            let psw = $("#psw").val();
            let data = {'identity': identity, 'psw': psw};

            $("#loader-div").show();

            $.post("<?= $router->route("web.validateLogin"); ?>", data, function (e) {
                $("#loader-div").hide();
                if(e == 0){
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Essa combinação de login e senha não pertencem a um usuário.",
                    });
                }else if(e == 1){
                    $("#newPassword").modal('show');
                }else{
                    window.location.href = "<?= url('profile'); ?>";
                }
            }, "html").fail(function () {
                swal({
                    icon: "error",
                    title: "Erro!",
                    text: "Erro ao processar requisição",
                });
            });
        }

        function newPassword() {
            let newPsw = $("#newPasswordInput").val();
            let identity = $("#identity").val();

            let data = {'identity': identity, 'psw': newPsw};
            $.post("<?= $router->route("web.newPsw"); ?>", data, function (element) {
                if(element == 1){
                    $("#loader-div").hide();
                    swal({
                        icon: "success",
                        title: "Sucesso!",
                        text: "Sua senha foi alterada.",
                    }).then((value) => {
                        window.location.href = "<?= url('profile'); ?>";
                    });
                }
            }, "html").fail(function () {
                $("#loader-div").hide();
                swal({
                    icon: "error",
                    title: "Erro!",
                    text: "Erro ao processar requisição",
                });
            });
        }

        function pswRecovery() {
            let identity = $("#identityRecovery").val();
            if (identity) {
                let data = {'identity': identity};
                $.post("<?= $router->route("web.pswRecovery"); ?>", data, "html")
                    .then(function () {
                        swal({
                            icon: "success",
                            title: "Sucesso!",
                            text: "Caso esse usuário exista, dentro de alguns minutos uma senha de recuperação Orditi " +
                                "será enviada para seu email.",
                        });
                    })
                    .fail(function () {
                        swal({
                            icon: "error",
                            title: "Erro!",
                            text: "Erro ao processar requisição",
                        });
                    });
            } else {
                swal({
                    icon: "warning",
                    title: "Alerta",
                    text: "Prencha o campo com o CPF ou CNPJ da conta que deseja recuperar a senha.",
                });
            }
        }

        function createAccount() {
            window.location.href = "<?= url('createAccount'); ?>";
        }

        function loginAgent() {
            window.location.href = "<?= url('agent'); ?>";
        }
    </script>
<?php $v->end(); ?>
