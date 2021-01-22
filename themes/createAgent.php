<?php $v->layout("_theme.php") ?>

<div class="container-fluid container-white mt-5">
    <div class="p-5">
        <form id="form" method="POST" action="<?= $router->route("web.validateNewAgent"); ?>">
            <fieldset class="row">
                <div class="col-xl-12">
                    <h2 class="black-title-section">Cadastrar de fiscal</h2>
                    <p class="subtitle-section-p">Descreva as informações do fiscal.</p>
                </div>
                <div class="col-xl-6 mt-5">
                    <div class="div-gray-bg border-top-green p-5">
                        <h4 class="black-title-section">Dados do fiscal</h4>
                        <hr>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="form-group">
                                            <label>Nome:</label>
                                            <input type="text" class="form-input" id="name"
                                                   name="name"
                                                   title="Nome do fiscal" placeholder="Seu Nome">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label>CPF:</label>
                                            <input type="text" class="form-input" id="identity"
                                                   onfocusout="checkCpf(this)" name="identity"
                                                   title="CPF do fiscal" placeholder="Seu CPF">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label>Matrícula:</label>
                                            <input type="text" class="form-input" id="registration"
                                                   name="registration"
                                                   title="Matrícula do fiscal" placeholder="Sua Matrícula"
                                            >
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label>E-mail:</label>
                                            <input type="email" class="form-input" id="email" name="email"
                                                   title="Email do fiscal" placeholder="Seu E-mail">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label>Confirmar E-mail:</label>
                                            <input type="email" class="form-input" id="confirm_email"
                                                   name="confirm_email"
                                                   title="Email do fiscal" placeholder="Confirme Seu E-mail">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group align-items-center">
                                            <label class="label-left">Foto de perfil: <span class="spanAlert">(Opcional)</span></label>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="localImage"
                                                   class="label-file text-center item-max-width localImage-file"><span
                                                        class="icon-plus mr-2"></span> Selecionar
                                                Arquivo</label>
                                            <input type="file" class="hidden-input-file" id="localImage"
                                                   name="localImage"
                                                   accept="image/png, image/jpg, image/jpeg"
                                                   onchange="uploadImage(this)">
                                            <div class="localImage-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="localImage-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="localImage-name"></p>
                                                        <span id="localImage-span-close" class="icon-close ml-3 card-close-file"
                                                              onclick="changeFile(this)"></span>
                                                    </div>
                                                    <div class="card-content-progress"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mt-5">
                    <div class="div-gray-bg border-top-green p-5 text-center">
                        <img class="mt-5 mb-5" style="width: 66%" src="<?= url('themes/assets/img/agent.svg') ?>">

                        <h1 class="black-title-section">Cadastrar Fiscal</h1>
                        <div class="pr-5 pl-5">
                            <p class="subtitle-section-p">Aqui você poderá cadastrar os agentes que farão a
                                fiscalização dos ambulantes</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 text-right mt-5 mb-5">
                    <button type="button" class="btn-3 secondary-color">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-3 primary">
                        Cadastrar
                    </button>
                    <hr>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<?php $v->start('scripts'); ?>
    <script>
        $("#registration").mask('000000-0');

        $("#identity").mask('000.000.000-00');

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
                        $("#form").trigger("reset");
                        changeFile();
                    } else if (returnData == 'already_exist') {
                        swal({
                            icon: "error",
                            title: "Erro",
                            text: "Já existe alguém cadastrado com esses dados.",
                        });
                    } else if (returnData == 'registrationError') {
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
<?php $v->end(); ?>
