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
                            <img class="image-card-bubble" src="<?= $userImage ?>">
                            <p class="mt-3 subtitle-section-p"><?= explode(' ', $userName)[0] ?> <?= explode(' ', $userName)[1] ?></p>
                        </div>
                    </div>
                    <hr>
                    <form class="form-validate-password">
                        <input type="hidden" name="userId" value="<?= $userId ?>">
                        <div class="form-group">
                            <label>Senha:</label>
                            <input class="form-input" type="password" name="password" title="Sua senha" placeholder="Sua senha">
                        </div>
                        <div class="form-group">
                            <label>Confirmar senha:</label>
                            <input class="form-input" type="password" name="rePassword" title="Sua senha" placeholder="Repita sua senha">
                        </div>
                        <button type="submit" class="btn-3 primary-color w-100 mt-3">Confirmar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>
<script>
    $('.form-validate-password').on('submit', function (e) {
        e.preventDefault();

        $("#loader-div").show();
        let data = new FormData(this);

        $.ajax({
            type:'POST',
            url: "<?= $router->route("web.confirmAccountPassword"); ?>",
            data:data,
            cache:false,
            contentType: false,
            processData: false,
            success:function(returnData){
                $("#loader-div").hide();
                if(returnData == 0){
                    swal({
                        icon: "warning",
                        title: "Alerta!",
                        text: "As senhas precisam ser idênticas.",
                    });
                } else if(returnData == 1){
                    swal({
                        icon: "success",
                        title: "Parabéns!",
                        text: "Você acaba de completar seu cadastro, seja bem-vindo!",
                    }).then((result) => {
                        window.location.href = "<?= $router->route('web.home') ?>";
                    });
                } else {
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Não foi possível realizar o cadastro das senhas. Por favor, tente novamente mais tarde.",
                    });
                }
                console.log(returnData);
            },
            error: function(returnData){
                $("#loader-div").hide();
                console.log("error");
                console.log(returnData);
            }
        });
    });
</script>
<?php $v->end(); ?>
