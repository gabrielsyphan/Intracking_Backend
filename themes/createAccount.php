<?php $v->layout("_theme.php"); ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/util.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>" />
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<link rel="stylesheet" href="<?= url("themes/assets/css/multiples.css"); ?>" type="text/css">
<?php $v->end(); ?>

<div class="container pt-5 mt-5">
    <div class="web-div-box">
        <div class="box-div-info-no-padding justify-content-center">
            <div class="row m-0">
                <div class="col-xl-5 pt-3 pl-5 pr-5 pb-5 div-create-account-green">
                    <div class="ml-3">
                        <h2 class="h2-header">Cadastro</h2>
                        <hr>
                        <p class="h2-header">Novo usuário</p>
                        <div class="p-5 mt-5 text-center">
                            <img id="img-page-1" class="img-page" src="<?= url('themes/assets/img/create-account-step-1.svg') ?>">
                            <img id="img-page-2" class="img-page" src="<?= url('themes/assets/img/create-account-step-2.svg') ?>">
                        </div>
                    </div>
                </div>
                <div class="col-xl-7">
                    <div class="pr-5 box-card">
                        <div class="text-center pl-5 pr-5">
                            <div class="box-list box-1 active">
                                <h3>1</h3>
                            </div>
                        </div>
                        <div class="text-center pl-5 pr-5">
                            <div class="box-list box-2">
                                <h3>2</h3>
                            </div>
                        </div>
                    </div>
                    <hr class="ml-5 mr-5 hr-white">
                    <form id="form">
                        <div id="inputHidden"></div>
                        <div id="page-1">
                            <h2 class="pl-5 pr-5 h2-title-header-black">Dados Pessoais</h2>
                            <div class="row pl-5 pr-5 pt-3">
                                <div class="col-xl-6 form-group">
                                    <label>Nome:</label>
                                    <input type="text" class="form-input" id="name" name="name" placeholder="Seu Nome" required>
                                </div>
                                <div class="col-xl-6 form-group">
                                    <label>Mãe:</label>
                                    <input type="text" class="form-input" id="maternalName" name="maternalName" placeholder="Nome de sua mãe" required>
                                </div>
                                <div class="col-xl-6 form-group">
                                    <label>CPF:</label>
                                    <input type="text" class="form-input" id="identity" name="identity" onfocusout="validateCpf(this)" placeholder="Seu CPF" required>
                                </div>
                                <div class="col-xl-6 form-group">
                                    <label>RG:</label>
                                    <input type="text" class="form-input" id="rg" name="rg" placeholder="Seu RG" required>
                                </div>
                                <div class="col-xl-6 form-group">
                                    <label>E-mail:</label>
                                    <input type="email" class="form-input" id="email" name="email" placeholder="Seu E-mail" required>
                                </div>
                                <div class="col-xl-6 form-group">
                                    <label>Telefone:</label>
                                    <input type="text" class="form-input" id="phone" name="phone" placeholder="Seu Telefone" required>
                                </div>
                            </div>
                            <hr class="ml-5 mr-5 hr-gray">
                            <div class="pl-5 pr-5 text-right">
                                <button type="button" class="btn-3 tertiary-color mb-5" onclick="nextPage(1, 2)">Próximo</button>
                            </div>
                        </div>
                        <div id="page-2">
                            <h2 class="pl-5 pr-5 h2-title-header-black">Residência</h2>
                            <div class="row pl-5 pr-5 pt-3">
                                <div class="col-xl-12 form-group">
                                    <label>Endereço Residencial:</label>
                                    <input type="text" class="form-input" id="street" name="street" placeholder="Endereço da sua casa" required>
                                </div>
                                <div class="col-xl-6 form-group">
                                    <label>Nº:</label>
                                    <input type="text" class="form-input" id="number" name="number" placeholder="Número da sua casa" required>
                                </div>
                                <div class="col-xl-6 form-group">
                                    <label>Bairro:</label>
                                    <input type="text" class="form-input" id="neighborhood" name="neighborhood" placeholder="Seu Bairro" required>
                                </div>
                                <div class="col-xl-6 form-group">
                                    <label>Cidade:</label>
                                    <input type="text" class="form-input" id="city" name="city" placeholder="Sua Cidade" required>
                                </div>
                                <div class="col-xl-6 form-group">
                                    <label>CEP:</label>
                                    <input type="text" class="form-input" id="postcode" name="postcode" placeholder="Seu CEP" required>
                                </div>
                            </div>
                            <hr class="ml-5 mr-5 hr-gray">
                            <div class="pl-5 pr-5 text-right">
                                <button type="button" class="btn-3 quartenary-color mb-5" onclick="prevPage(2, 1)">Voltar</button>
                                <button type="submit" class="btn-3 tertiary-color mb-5">Cadastrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>
<script>
    $(function(){
        $('#form').on('submit', (function(e) {
            e.preventDefault();

            $("#loader-div").show();
            let data = new FormData(this);

            $.ajax({
                type:'POST',
                url: "<?= $router->route("web.validateAccount"); ?>",
                data:data,
                cache:false,
                contentType: false,
                processData: false,
                success:function(returnData){
                    $("#loader-div").hide();
                    if(returnData == 0){
                        swal({
                            icon: "success",
                            title: "Tudo certo!",
                            text: "Acesse seu email para confirmar seu cadastro e criar sua senha.",
                        }).then((result) => {
                            window.location.href = "<?= $router->route('web.home') ?>";
                        });
                    } else if(returnData == 1){
                        swal({
                            icon: "error",
                            title: "Erro",
                            text: "Já existe alguém cadastrado com esses dados.",
                        });
                    } else if(returnData == 2){
                        swal({
                            icon: "error",
                            title: "Erro",
                            text: "Não será possível realizar o cadastro. Por favor, dirija-se a secretaria de economia e realize seu cadastro mercantil de pessoa física ou jurídica para então dar prosseguimento ao seu cadastro no Orditi.",
                        });
                    } else if(returnData == 3) {
                        swal({
                            icon: "error",
                            title: "Erro",
                            text: "CPF inválido. Por favor, insira um CPF válido.",
                        });
                    } else {
                        swal({
                            icon: "error",
                            title: "Erro!",
                            text: "Não foi possível realizar o cadastro. Por favor, tente novamente mais tarde.",
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
        }));

        let identity = $("#identity");
        let rg = $("#rg");
        let phone = $("#phone");

        identity.mask('000.000.000-00', {reverse: true});
        rg.mask('0000000-0', {reverse: true});
        phone.mask('00 00000-0000', {reverse: true});

        let postcode = $("#postcode");
        postcode.mask('00000-000', {reverse: true});

    });

    function validateCpf(e){
        $("#loader-div").show();
        let cpf = formatedCPF(e);

        if(checkCpf(cpf) == false){
            $("#loader-div").hide();
            swal({
                icon: "error",
                title: "Erro",
                text: "O CPF digitado não é válido. Por favor, insira um CPF válido e tente novamente.",
            });
        } else{
            let data = {'cpf': cpf};
            $.post("<?= $router->route("web.checkAccount"); ?>", data, function (e) {
                $("#loader-div").hide();
                if(e == 0){
                    swal({
                        icon: "warning",
                        title: "Atenção",
                        text: "Não será possível realizar o cadastro. Por favor, dirija-se a secretaria de economia e realize seu cadastro mercantil de pessoa física ou jurídica para então dar prosseguimento com o do Orditi.",
                    });
                }
            }, "html").fail(function () {
                $("#loader-div").hide();
                    swal({
                        icon: "~warning",
                        title: "Atenção",
                        text: "Erro ao processar requisição.",
                    });
            });
        }
    }
</script>
<script type="text/javascript" src="<?= url("themes/assets/js/multiples.js"); ?>"></script>
<?php $v->end(); ?>
