<?php $v->layout("_theme.php") ?>

<div class="container pt-5">
    <div class="row mt-5 p-4 justify-content-center">
        <div class="col-xl-5 mb-5">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="text-center mb-5">
                        <img src="<?= url('themes/assets/img/nav-logo.png') ?>" style="width: 50%;">
                    </div>
                    <hr>
                    <form id="form" class="form-login" method="POST">
                        <div class="form-group">
                            <label>Matrícula:</label>
                            <input type="text" class="form-input login-input" id="registration"
                                   name="registration" title="CPF" placeholder="Sua matrícula" required>
                        </div>
                        <div class="form-group">
                            <label>Senha:</label>
                            <input type="password" class="form-input login-input" id="psw" name="password"
                                   title="Senha" placeholder="Sua senha" required>
                        </div>

                        <div class="row text-center">
                            <div class="col-xl-6">
                                <p class="login-recovery" onclick="pswRecovery()">
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

                        <button type="submit" class="btn-2 btn-primary mt-4">Acessar</button>
                        <hr>
                        <div class="col-xl-12 text-center mb-2">
                            <p class="login-recovery">
                                <?= utf8_encode(strftime('%A, %d de %b de %Y', strtotime('today'))) ?>
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
    $(document).ready(function () {
        let registration = $("#registration");
        registration.mask('000000-0', {reverse: true});


        $('#form').on('submit',(function(e) {
            e.preventDefault();
            $("#loader-div").show();
            let data = new FormData(this);

            $.ajax({
                type:'POST',
                url: "<?= $router->route("web.validateAgent"); ?>",
                data:data,
                cache:false,
                contentType: false,
                processData: false,
                success:function(returnData){
                    $("#loader-div").hide();
                    if(returnData == 1){
                        window.location.href = "<?= url(); ?>";
                    }else{
                        swal({
                            icon: "error",
                            title: "Erro!",
                            text: "Esses dados não pertencem a um usuário.",
                        });
                    }
                },
                error: function(returnData){
                    $("#loader-div").hide();
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Não foi possível conectar ao servidor de login.",
                    });
                    console.log("error");
                    console.log(returnData);
                }
            });
        }));
    });

    function pswRecovery() {
        swal({
            icon: "warning",
            text: "Este recurso ainda não está disponível!",
        });
    }

    function createAccount() {
        window.location.href = "<?= url('createAccount'); ?>";
    }
</script>
<?php $v->end(); ?>
