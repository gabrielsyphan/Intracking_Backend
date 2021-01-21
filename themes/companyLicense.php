<?php $v->layout("_theme.php"); ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/multiples.css"); ?>" type="text/css">
<?php $v->end(); ?>

<div class="container-fluid mt-5" style="background-color: #fff;">
    <div class="p-5">
        <form id="form-license-company" method="POST" action="<?= $router->route('web.validateCompanyLicense') ?>">
            <fieldset>
                <div class="row mb-5">
                    <div class="col-xl-12 pb-3">
                        <h2 class="black-title-section">Licença da Empresa</h2>
                        <p class="subtitle-section-p">Para empresas com funcionários ambulantes.</p>
                        <hr>
                    </div>

                    <div class="col-xl-6">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Informações da empresa</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>CNPJ:</label>
                                        <input type="text" class="form-input" id="cnpj" name="cnpj"
                                               placeholder="Ex.: 00.000.000/0000-00">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>CMC:</label>
                                        <input type="text" class="form-input" id="cmc" name="cmc"
                                               placeholder="Ex.: 0000000000">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Nome de fantasia:</label>
                                        <input type="text" class="form-input" id="fantasyName" name="fantasyName"
                                               placeholder="Digite o nome de fantasia da empresa">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Informações de endereço</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-8">
                                    <div class="form-group">
                                        <label>Endereço da sede da empresa:</label>
                                        <input type="text" class="form-input" id="street" name="street"
                                               placeholder="Ex.: Avenida Fernandes Lima">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label>Número:</label>
                                        <input type="text" class="form-input" id="number" name="number"
                                               placeholder="Ex.: 35">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label>Bairro:</label>
                                        <input type="text" class="form-input" id="neighborhood" name="neighborhood"
                                               placeholder="Ex.: Centro">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label>Cidade:</label>
                                        <input type="text" class="form-input" id="city" name="city"
                                               placeholder="Ex.: Maceió">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label>CEP:</label>
                                        <input type="text" class="form-input" id="postcode" name="postcode"
                                               placeholder="Ex.: 57000000">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 mt-5">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Informações dos produtos</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>Produto e/ou serviços:</label>
                                        <select id="productSelect" class="form-input" name="productSelect[]"
                                                multiple="multiple"
                                        >
                                            <option value="0">Gêneros e produtos alimentícios em geral</option>
                                            <option value="1">Bebidas não alcoólicas</option>
                                            <option value="2">Bebidas alcoólicas</option>
                                            <option value="3">Brinquedos e artigos ornamentais</option>
                                            <option value="4">Confecções, calçados e artigos de usopessoal</option>
                                            <option value="5">Louças, ferragens, artefatos de plástico,borracha, couro e
                                                utensílios domésticos
                                            </option>
                                            <option value="6">Artesanato, antiguidades e artigos dearte em geral
                                            </option>
                                            <option value="7">Outros artigos não especificados nos itens anteriores
                                            </option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>Quantitativo de equipamentos:</label>
                                        <input type="number" class="form-input" id="equipamentAmount"
                                               name="equipamentAmount"
                                               min="0" placeholder="Ex.: 14">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Descreva outro produto ofertado: <span class="spanAlert">(Se não encontrado na lista acima)</span>:</label>
                                        <textarea type="text" class="form-input" id="productDescription"
                                                  name="productDescription"
                                                  placeholder="Ex.: Trabalho com a venda de produtos para cabelo."></textarea>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Relato da ativadade:</label>
                                        <textarea type="text" class="form-input" id="ativityDescription"
                                                  name="ativityDescription" placeholder="Ex.: Descreva sua atividade."
                                        ></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 mt-5">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Anexos</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="row form-group">
                                        <div class="col-xl-5">
                                            <div class="form-group">
                                                <label>Logo da empresa:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-7 text-left">
                                            <label class="label-file companyLogo-file text-center"
                                                   for="companyLogo"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="companyLogo" name="userImage"
                                                   accept="image/png, image/jpg, image/jpeg">
                                            <div class="invalid-feedback"></div>
                                            <div class="companyLogo-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="companyLogo-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="companyLogo-name"></p>
                                                        <span id="companyLogo-span-close"
                                                              class="icon-close ml-3 card-close-file companyLogo"
                                                              onclick="changeFile(this)"></span>
                                                    </div>
                                                    <div class="card-content-progress"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="row form-group">
                                        <div class="col-xl-5">
                                            <div class="form-group">
                                                <label>Cadastro do CNPJ:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-7 text-left">
                                            <label class="label-file cnpjRegistration-file text-center"
                                                   for="cnpjRegistration"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="cnpjRegistration" name="cnpjRegistration"
                                                   accept="image/png, image/jpg, image/jpeg">
                                            <div class="invalid-feedback"></div>
                                            <div class="cnpjRegistration-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="cnpjRegistration-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="cnpjRegistration-name"></p>
                                                        <span id="cnpjRegistration-span-close"
                                                              class="icon-close ml-3 card-close-file cnpjRegistration"
                                                              onclick="changeFile(this)"></span>
                                                    </div>
                                                    <div class="card-content-progress"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="row form-group">
                                        <div class="col-xl-5">
                                            <div class="form-group">
                                                <label>Comprovante residência:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-7 text-left">
                                            <label class="label-file proofAddress-file text-center"
                                                   for="proofAddress"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="proofAddress" name="proofAddress"
                                                   accept="image/png, image/jpg, image/jpeg">
                                            <div class="invalid-feedback"></div>
                                            <div class="proofAddress-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="proofAddress-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="proofAddress-name"></p>
                                                        <span id="proofAddress-span-close"
                                                              class="icon-close ml-3 card-close-file proofAddress"
                                                              onclick="changeFile(this)"></span>
                                                    </div>
                                                    <div class="card-content-progress"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="row form-group">
                                        <div class="col-xl-5">
                                            <div class="form-group">
                                                <label>Contrato social</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-7 text-left">
                                            <label class="label-file socialContract-file text-center"
                                                   for="socialContract"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="socialContract" name="socialContract"
                                                   accept="image/png, image/jpg, image/jpeg">
                                            <div class="invalid-feedback"></div>
                                            <div class="socialContract-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="socialContract-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="socialContract-name"></p>
                                                        <span id="socialContract-span-close"
                                                              class="icon-close ml-3 card-close-file socialContract"
                                                              onclick="changeFile(this)"></span>
                                                    </div>
                                                    <div class="card-content-progress"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="row form-group">
                                        <div class="col-xl-5">
                                            <div class="form-group">
                                                <label>Alvará de funcionamento:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-7 text-left">
                                            <label class="label-file businessLicense-file text-center"
                                                   for="businessLicense"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="businessLicense" name="businessLicense"
                                                   accept="image/png, image/jpg, image/jpeg">
                                            <div class="invalid-feedback"></div>
                                            <div class="businessLicense-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="businessLicense-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="businessLicense-name"></p>
                                                        <span id="businessLicense-span-close"
                                                              class="icon-close ml-3 card-close-file businessLicense"
                                                              onclick="changeFile(this)"></span>
                                                    </div>
                                                    <div class="card-content-progress"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="row form-group">
                                        <div class="col-xl-5">
                                            <div class="form-group">
                                                <label>Outros:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-7 text-left">
                                            <label class="label-file otherDocument-file text-center"
                                                   for="otherDocument"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="otherDocument" name="otherDocument"
                                                   accept="image/png, image/jpg, image/jpeg">
                                            <div class="invalid-feedback"></div>
                                            <div class="otherDocument-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="otherDocument-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="otherDocument-name"></p>
                                                        <span id="otherDocument-span-close"
                                                              class="icon-close ml-3 card-close-file otherDocument"
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

                    <div class="col-xl-12 mt-5 text-right">
                        <hr>
                        <button type="reset" class="btn-3 secondary-color">
                            Limpar campos
                        </button>
                        <button class="btn-3 primary" type="submit">
                            Cadastrar licença
                        </button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script type="text/javascript" src="<?= url("themes/assets/js/multiples.js"); ?>"></script>
<script>
    $(document).ready(function () {
        $('#productSelect').multiselect();

        $("#width").mask('00.00', {reverse: true});
        $("#length").mask('00.00', {reverse: true});
        $("#cnpj").mask("99.999.999/9999-99");
        $("#cmc").mask('0000000000', {reverse: true});
        $("#phone").mask('00 0000-0000', {reverse: true});
        $("#postcode").mask('00000000', {reverse: true});
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
                cache: false,
                contentType: false,
                processData: false,
            }).done(function (returnData) {
                if (returnData == 'success') {
                    swal({
                        icon: "success",
                        title: "Sua licença foi cadastrada!",
                        text: "Acesse o menu 'Minhas Licenças' para visualiza-la.",
                    }).then((result) => {
                        window.location.href = "<?= $router->route('web.licenseList') ?>";
                    });
                } else {
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Não foi possível cadastrar sua licença. Tente novamente mais tarde.",
                    });
                }
                console.log(returnData);
            }).fail(function (returnData) {
                swal({
                    icon: "error",
                    title: "Erro!",
                    text: "Erro ao processar requisição",
                });
                console.log(returnData);
            }).always(function () {
                fieldsetDisable.removeAttr("disabled");
                $("#loader-div").hide();
            });
        }
    });
</script>
<?php $v->end(); ?>
