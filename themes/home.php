<?php $v->layout("_theme.php") ?>

<div class="container-fluid mt-5" style="background-color: #fff;">
    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-xl-6">
                <h2 class="black-title-section">Bem-vindo ao Orditi!</h2>
                <p class="sibtitle-section-p">Este é um sistema de ordenamento intinerante que busca solucionar os problemas de organização e
                    distribuição dos ambulantes de sua cidade.</p>

                <p class="sibtitle-section-p">Esperamos que aproveite ao máximo o nosso sistema e não hesite em nos comunicar caso se depare
                    com algum problema.</p>

                <p class="sibtitle-section-p" style="font-size: 12px">
                    Feito com <span style="color:red;">❤</span>  por Orditi ©
                </p>
            </div>
            <div class="col-xl-6 text-center">
                <img style="width: 60%" src="<?= url('themes/assets/img/locale.svg') ?>">
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-5 mb-5" style="background-color: #fff;">
    <div class="container pt-5 pb-5 mb-5">
        <div class="row">
            <div class="col-xl-6 pt-5">
                <img class="mt-5 align-middle" style="width: 90%" src="<?= url('themes/assets/img/contact-us.svg') ?>">
            </div>
            <div class="col-xl-6">
                <h3 class="black-title-section">Contate-nos</h3>
                <p class="sibtitle-section-p">Nos envie um email para tirar dúvidas, sugestões ou resolver
                    algum problema encontrado no sistema.</p>
                <hr>
                <form id="form-contact">
                    <div class="form-group">
                        <label>Fone:</label>
                        <input type="text" id="phone" class="form-input" name="phone"
                               placeholder="Whatsapp com DDD:" required>
                    </div>
                    <div class="form-group">
                        <label>Mensagem:</label>
                        <textarea class="form-input" name="description"
                                  placeholder="Descreva seu problema:" required></textarea>
                    </div>
                    <button type="submit" class="btn-2 btn-primary">
                        Enviar mensagem
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
    <script>
        $(document).ready(function () {
            $("#phone").mask('00 00000-0000', {reverse: true});
        });
        $("#form-contact").on("submit", function (e) {
            $("#loader-div").show();
            e.preventDefault();
            let data = new FormData(this);

            $.ajax({
                type:'POST',
                url: "<?= $router->route("web.formContact"); ?>",
                data:data,
                cache:false,
                contentType: false,
                processData: false,
                success:function(returnData){
                    $("#loader-div").hide();
                    if(returnData == 1){
                        swal({
                            icon: "success",
                            title: "Sucesso!",
                            text: "Um email de contato foi enviado para nosso suporte.",
                        });
                    }else{
                        swal({
                            icon: "error",
                            title: "Erro!",
                            text: "Não foi possível enviar o email. Por favor, ente novamente mais tarde",
                        });
                    }
                },
                error: function(returnData){
                    $("#loader-div").hide();
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Não foi possível enviar o email. Por favor, tente novamente mais tarde",
                        button: "Entendi",
                    });
                    console.log("error");
                    console.log(returnData);
                }
            });
        })
    </script>
<?php $v->end(); ?>
