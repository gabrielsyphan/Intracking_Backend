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
            <form id="form-psw-recovery" method="POST" action="<?= $router->route('web.pswRecovery'); ?>">
                <fieldset>
                    <div class="modal-body">
                        <div class="p-5">
                            <div class="form-group">
                                <h5>Insira os dados da conta que deseja recuperar:</h5>
                                <input type="text" id="identity" name="identity" class="form-input"
                                       placeholder="Inserir CPF">
                                <div class="invalidate-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-3 primary-color">
                            Confirmar
                        </button>
                    </div>
                </fieldset>
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
                    <form id="form-login" method="POST" action="<?= $router->route("web.validateLogin"); ?>">
                        <fieldset>
                            <div class="form-group">
                                <label>Nome:</label>
                                <input type="text" class="form-input login-input" id="identity" name="identity"
                                       title="CPF" placeholder="Seu CPF">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label>Senha:</label>
                                <input type="password" class="form-input login-input" id="psw" name="psw" title="Senha"
                                       placeholder="Sua senha">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="row text-left">
                                <div class="col-xl-6">
                                    <p class="login-recovery" data-toggle="modal" data-target="#recoveryPsw">
                                        Esqueceu a senha?
                                    </p>
                                </div>
                            </div>

                            <button type="submit" class="btn-3 w-100 primary-color mt-4">Acessar</button>

                            <hr>

                            <div class="col-xl-12 text-center mb-2">
                                <p class="login-recovery" onclick="createAccount()">
                                    Novo? <span>Cadastre-se!</span>
                                </p>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script>
    $("#identity").mask("999.999.999-99");

    $('form').on('submit', function (e) {
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
                if (returnData == 0) {
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Essa combinação de login e senha não pertencem a um usuário.",
                    });
                } else if (returnData == 1) {
                    window.location.href = "<?= $router->route('web.home'); ?>";
                } else if (returnData == 2) {
                    $("#newPassword").modal('show');
                } else if (returnData == 'pswSuccess'){
                    swal({
                        icon: "success",
                        title: "Tudo certo!",
                        text: "Acesse seu e-mail para definir uma nova senha.",
                    });
                }else {
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Erro ao processar requisição",
                    });
                }
                console.log(returnData);
            }).fail(function () {
                swal({
                    icon: "error",
                    title: "Erro!",
                    text: "Erro ao processar requisição",
                });
                console.log(returnData);
            }).always(function () {
                $("#loader-div").hide();
                fieldsetDisable.removeAttr("disabled");
            });
        }
    });

    function createAccount() {
        window.location.href = "<?= $router->route('web.createAccount'); ?>";
    }

    function loginAgent() {
        window.location.href = "<?= $router->route('web.agent'); ?>";
    }
</script>
<?php $v->end(); ?>
