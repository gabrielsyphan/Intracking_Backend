<?php $v->layout("_theme.php"); ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/util.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<link rel="stylesheet" href="<?= url("themes/assets/css/multiples.css"); ?>" type="text/css">
<?php $v->end(); ?>

<div class="container pt-5 mt-5">
    <div class="web-div-box mb-5">
        <div class="box-div-info-no-padding justify-content-center">
            <div class="row m-0">
                <div class="col-xl-5 pt-3 pl-5 pr-5 pb-5 div-create-account-green">
                    <div class="ml-3">
                        <h2 class="h2-header">Cadastro</h2>
                        <hr>
                        <p class="h2-header">Novo usuário</p>
                        <div class="p-5 mt-5 text-center">
                            <img id="img-page-1" class="img-page"
                                 src="<?= url('themes/assets/img/create-account-step-1.svg') ?>">
                            <img id="img-page-2" class="img-page"
                                 src="<?= url('themes/assets/img/create-account-step-2.svg') ?>">
                            <img id="img-page-3" class="img-page"
                                 src="<?= url('themes/assets/img/create-account-step-6.svg') ?>">
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
                        <div class="text-center pl-5 pr-5">
                            <div class="box-list box-3">
                                <h3>3</h3>
                            </div>
                        </div>
                    </div>
                    <hr class="ml-5 mr-5 hr-white">
                    <form id="form-create-account" method="POST" action="<?= $router->route("web.validateAccount"); ?>">
                        <fieldset>
                            <div id="inputHidden"></div>
                            <div id="page-1">
                                <h2 class="pl-5 pr-5 h2-title-header-black">Dados Pessoais</h2>
                                <div class="row pl-5 pr-5 pt-3">
                                    <div class="col-xl-6 form-group">
                                        <label>Nome:</label>
                                        <input type="text" class="form-input" id="name" name="name" placeholder="Seu Nome">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 form-group">
                                        <label>Mãe:</label>
                                        <input type="text" class="form-input" id="maternalName" name="maternalName"
                                               placeholder="Nome de sua mãe">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 form-group">
                                        <label>CPF:</label>
                                        <input type="text" class="form-input" id="identity" name="identity"
                                               onfocusout="validateCpf(this)" placeholder="Seu CPF">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 form-group">
                                        <label>RG:</label>
                                        <input type="text" class="form-input" id="rg" name="rg" placeholder="Seu RG">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 form-group">
                                        <label>E-mail:</label>
                                        <input type="email" class="form-input" id="email" name="email"
                                               placeholder="Seu E-mail">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 form-group">
                                        <label>Telefone:</label>
                                        <input type="text" class="form-input" id="phone" name="phone"
                                               placeholder="Seu Telefone">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <hr class="ml-5 mr-5 hr-gray">
                                <div class="pl-5 pr-5 text-right">
                                    <button type="button" class="btn-3 tertiary-color mb-5" onclick="nextPage(1, 2)">
                                        Próximo
                                    </button>
                                </div>
                            </div>
                            <div id="page-2">
                                <h2 class="pl-5 pr-5 h2-title-header-black">Residência</h2>
                                <div class="row pl-5 pr-5 pt-3">
                                    <div class="col-xl-12 form-group">
                                        <label>Endereço Residencial:</label>
                                        <input type="text" class="form-input" id="street" name="street"
                                               placeholder="Endereço da sua casa">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 form-group">
                                        <label>Nº:</label>
                                        <input type="text" class="form-input" id="number" name="number"
                                               placeholder="Número da sua casa">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 form-group">
                                        <label>Bairro:</label>
                                        <input type="text" class="form-input" id="neighborhood" name="neighborhood"
                                               placeholder="Seu Bairro">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 form-group">
                                        <label>Cidade:</label>
                                        <input type="text" class="form-input" id="city" name="city"
                                               placeholder="Sua Cidade">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 form-group">
                                        <label>CEP:</label>
                                        <input type="text" class="form-input" id="postcode" name="postcode"
                                               placeholder="Seu CEP">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <hr class="ml-5 mr-5 hr-gray">
                                <div class="pl-5 pr-5 text-right">
                                    <button type="button" class="btn-3 quartenary-color mb-5" onclick="prevPage(2, 1)">
                                        Voltar
                                    </button>
                                    <button type="button" class="btn-3 tertiary-color mb-5" onclick="nextPage(2, 3)">
                                        Próximo
                                    </button>
                                </div>
                            </div>
                            <div id="page-3">
                                <h2 class="pl-5 pr-5 h2-title-header-black">Anexos</h2>
                                <div class="row pl-5 pr-5 pt-3" style="height: 322px;">
                                    <div class="col-xl-6">
                                        <p class="label-left">Foto do rosto: </p>
                                    </div>
                                    <div class="col-xl-6 text-center">
                                        <label class="label-file userImage-file" for="userImage"><span
                                                    class="icon-plus mr-2"></span> Selecionar Arquivo</label>
                                        <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                               id="userImage" name="userImage" accept="image/png, image/jpg, image/jpeg">
                                        <div class="invalid-feedback"></div>
                                        <div class="userImage-file-uploaded file-uploaded-container">
                                            <div class="card-content-upload text-center p-3">
                                                <div class="card-content-type-upload">
                                                    <span class="userImage-type"></span>
                                                </div>
                                            </div>
                                            <div class="ml-3 text-left">
                                                <div class="d-flex">
                                                    <p class="userImage-name"></p>
                                                    <span id="userImage-span-close"
                                                          class="icon-close ml-3 card-close-file userImage"
                                                          onclick="changeFile(this)"></span>
                                                </div>
                                                <div class="card-content-progress"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 second-attach">
                                        <p class="label-left">Foto da identidade (CPF + RG): </p>
                                    </div>
                                    <div class="col-xl-6 text-center second-attach">
                                        <label class="label-file identityImage-file" for="identityImage"><span
                                                    class="icon-plus mr-2"></span> Selecionar Arquivo</label>
                                        <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                               id="identityImage" name="identityImage"
                                               accept="image/png, image/jpg, image/jpeg">
                                        <div class="invalid-feedback"></div>
                                        <div class="identityImage-file-uploaded file-uploaded-container">
                                            <div class="card-content-upload text-center p-3">
                                                <div class="card-content-type-upload">
                                                    <span class="identityImage-type"></span>
                                                </div>
                                            </div>
                                            <div class="ml-3 text-left">
                                                <div class="d-flex">
                                                    <p class="identityImage-name"></p>
                                                    <span id="identityImage-span-close"
                                                          class="icon-close ml-3 card-close-file"
                                                          onclick="changeFile(this)"></span>
                                                </div>
                                                <div class="card-content-progress"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="ml-5 mr-5 hr-gray">
                                <div class="pl-5 pr-5 text-right">
                                    <button type="button" class="btn-3 quartenary-color mb-5" onclick="prevPage(3, 2)">
                                        Voltar
                                    </button>
                                    <button type="submit" class="btn-3 tertiary-color mb-5">Cadastrar</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>
<script>
    $(function () {
        let identity = $("#identity");
        let rg = $("#rg");
        let phone = $("#phone");
        let postcode = $("#postcode");

        identity.mask('000.000.000-00', {reverse: true});
        rg.mask('0000000-0', {reverse: true});
        phone.mask('00 00000-0000', {reverse: true});
        postcode.mask('00000-000', {reverse: true});
    });

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
                cache:false,
                contentType: false,
                processData: false,
            }).done(function (returnData) {
                if (returnData == 'success') {
                    swal({
                        icon: "success",
                        title: "Tudo certo!",
                        text: "Acesse seu email para confirmar seu cadastro e criar sua senha.",
                    }).then((result) => {
                        window.location.href = "<?= $router->route('web.home') ?>";
                    });
                } else if (returnData == 'already_exist') {
                    swal({
                        icon: "error",
                        title: "Erro",
                        text: "Já existe alguém cadastrado com esses dados.",
                    });
                } else if (returnData == 'identity_fail') {
                    swal({
                        icon: "error",
                        title: "Erro",
                        text: "CPF inválido. Por favor, insira um CPF válido.",
                    });
                } else if (returnData == 'require_registration'){
                    swal({
                        icon: "warning",
                        title: "Atenção",
                        text: "Não será possível realizar o cadastro. Por favor, dirija-se a secretaria de economia e realize seu cadastro mercantil de pessoa física ou jurídica para então dar prosseguimento com o do Orditi.",
                    });
                }else {
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Não foi possível realizar o cadastro. Por favor, tente novamente mais tarde.",
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
        } else {
            swal({
                icon: "warning",
                title: "Ops!",
                text: "Preencha todos os campos",
            });
            fieldsetDisable.removeAttr("disabled");
            $("#loader-div").hide();
        }
    });

    function validateCpf(e) {
        $("#loader-div").show();
        let cpf = formatedCPF(e);

        if (checkCpf(cpf) == false) {
            $("#loader-div").hide();
            swal({
                icon: "error",
                title: "Erro",
                text: "O CPF digitado não é válido. Por favor, insira um CPF válido e tente novamente.",
            });
        } else {
            let data = {'cpf': cpf};
            $.post("<?= $router->route("web.checkAccount"); ?>", data, function (e) {
                $("#loader-div").hide();
                if (e == 0) {
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
