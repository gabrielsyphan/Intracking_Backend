<?php $v->layout("_theme.php") ?>

<div class="container">
    <div class="row mt-5 p-4 justify-content-center">
        <div class="col-xl-8">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="mb-5 text-center">
                        <img class="mt-5 mb-5" style="width: 40%" src="<?= url('themes/assets/img/agent.svg') ?>">

                        <h1 class="h2-title-header-black">Cadastrar Fiscal</h1>
                        <div class="pr-5 pl-5">
                            <p class="pr-5 pl-5 mr-5 ml-5">Aqui você poderá cadastrar os agentes que farão a
                                fiscalização dos ambulantes</p>
                        </div>
                    </div>
                    <form id="form" method="POST" class="mt-5" action="<?= $router->route("web.validateNewAgent"); ?>">
                        <fieldset>
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
                                                            class="icon-plus mr-2"></span> Selecionar Arquivo</label>
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
                                                            <span class="icon-close ml-3 card-close-file"
                                                                  onclick="changeFile()"></span>
                                                        </div>
                                                        <div class="card-content-progress"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-12 mb-3 text-center">
                                            <hr class="m-0">
                                            <button type="submit" class="btn-3 primary-color mt-5 item-max-width">
                                                REGISTRAR
                                            </button>
                                        </div>
                                    </div>
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
    $("#registration").mask('000000-0', {reverse: true});

    $("#identity").mask('000.000.000-00', {reverse: true});

    $("#form").on('submit', function (e) {
        e.preventDefault();

        $("#loader-div").show();

        //armazena o forulario submetido em _thisForm
        const _thisForm = $(this);

        //limpa qualquer validacao
        _thisForm.find(".is-invalid").removeClass("is-invalid").next().text("");

        let validate = true;

        //validacao campos em branco backend
        _thisForm.find("[name=name], [name=identity], [name=registration], [name=email]").each(function () {
            if (!$(this).val()) {
                $(this).addClass("is-invalid").next().text("Campo obrigatório!");
                validate = false;
            }
        });

        let name = $("#name").val();
        name = name.split(' ');

        if (!name[1]) {
            $("#name").addClass("is-invalid").next().text("Insira seu nome completo!");

            validate = false;
        }

        if (!validate) {
            $("#loader-div").hide();
            return false;
        }

        const data = $(this).serialize();
        console.log(data);

        const fieldsetDisable = _thisForm.find("fieldset");
        fieldsetDisable.attr("disabled", true);

        $.ajax({
            url: _thisForm.attr("action"),
            type: _thisForm.attr("method"),
            data: data,
            dataType: "json"
        }).done(function (data) {
            // validacao campos em branco back-end
            if (data.required) {
                $.each(data.required, function (index, value) {
                    _thisForm.find(`[name=${value}]`).addClass("is-invalid").next().text("Campo obrigatório!");
                });
            }

            // validacao de campos de formato invalido
            if (data.formatInvalid) {
                for (let prop in data.formatInvalid) {
                    _thisForm.find(`[name=${prop}]`).addClass("is-invalid").next().text(data.formatInvalid[prop]);
                }
            }

            if (data.validateResponse == "registrationError") {
                swal({
                    title: "Atenção",
                    text: "Ocorreu um erro ao enviar e-mail de confirmação.",
                    icon: "warning",
                    button: "Entendi",
                });
            } else if (data.validateResponse == "success") {
                swal({
                    title: "Sucesso",
                    text: "Cadastro realizado com sucesso!",
                    icon: "success",
                    button: "Entendi",
                });

                $("#form").trigger("reset");
                changeFile();
            } else if (data.validateResponse == "registrationExist") {
                swal({
                    title: "Atenção",
                    text: "Essa matrícula já pertence a um usuário.",
                    icon: "warning",
                    button: "Entendi",
                });
            }
        }).fail(function (e) {
            console.log(e);
        }).always(function () {
            fieldsetDisable.removeAttr("disabled");
            $("#loader-div").hide();
        })
    });

    function changeFile() {
        $(".localImage-file-uploaded").hide();
        $(".hidden-input-file").val(null);
        $(".localImage-file").show();
        $(".localImage-type").empty();
        $(".localImage-name").empty();
    }
</script>
<?php $v->end(); ?>

