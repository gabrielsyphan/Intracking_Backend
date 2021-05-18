<?php $v->layout("_theme.php"); ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/layers-control.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/util.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<link rel="stylesheet" href="<?= url("themes/assets/css/multiples.css"); ?>" type="text/css">
<?php $v->end(); ?>

<div class="container-fluid container-white mt-5">
    <div class="p-5">
        <form id="form-license-foodTrucks" method="POST" action="<?= $router->route('web.validateFoodTruckLicense') ?>">
            <fieldset>
                <div id="inputHidden"></div>
                <div class="row mb-5">
                    <div class="col-xl-12 pb-3">
                        <h2 class="black-title-section">Licença de Food Trucks</h2>
                        <p class="subtitle-section-p">Licença para o exercício de atividades
                            econômicas em área pública..</p>
                        <hr>
                    </div>

                    <div class="col-xl-6  mt-5">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Informações da empresa</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>CNPJ:</label>
                                        <input type="text" class="form-input" id="cnpj" name="cnpj"
                                               placeholder="Ex.: 00.000.000/0000-00"
                                               onfocusout="validateCnpj(this)"
                                               required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>CMC:</label>
                                        <input type="text" class="form-input" id="cmc" name="cmc"
                                               placeholder="Ex.: 0000000000" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Nome de fantasia:</label>
                                        <input type="text" class="form-input" id="fantasyName" name="fantasyName"
                                               placeholder="Digite o nome de fantasia da empresa" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <h4 class="black-title-section pt-5">Informações dos produtos</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Produto e/ou serviços:</label>
                                        <select id="productSelect" class="form-input" name="productSelect[]"
                                                multiple="multiple" required>
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

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Descreva os equipamentos:</label>
                                        <textarea type="text" class="form-input" id="equipmentDescription"
                                                  name="equipmentDescription"
                                                  placeholder="Ex.: algum texto de apoio." required></textarea>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Descrava os equipamentos fixos ao veículo e mobiliário:</label>
                                        <textarea type="text" class="form-input" id="infoDescription"
                                                  name="infoDescription"
                                                  placeholder="Ex.: Toldos, retráteis, mesas, bancos e/ou cadeiras."
                                                  required
                                        ></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6  mt-5 ">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Anexos</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12 ">
                                    <div class="row form-group">
                                        <div class="col-xl-7">
                                            <div class="form-group">
                                                <label>Comprovante residência:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-5 text-left">
                                            <label class="label-file proofAddress-file text-center"
                                                   for="proofAddress"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file"
                                                   onchange="uploadImage(this)"
                                                   id="proofAddress" name="proofAddress"
                                                   accept="image/png, image/jpg, image/jpeg" required>
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

                                <div class="col-xl-12 ">
                                    <div class="row">
                                        <div class="col-xl-7">
                                            <div class="form-group">
                                                <label>Insira uma foto do seu equipamento:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-5 text-left">
                                            <label class="label-file equipmentImage-file text-center"
                                                   for="equipmentImage"><span
                                                        class="icon-plus mr-2"></span> Selecionar </label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="equipmentImage" name="equipmentImage"
                                                   accept="image/png, image/jpg, image/jpeg" required>
                                            <div class="invalid-feedback"></div>
                                            <div class="equipmentImage-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="equipmentImage-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="equipmentImage-name"></p>
                                                        <span id="equipmentImage-span-close"
                                                              class="icon-close ml-3 card-close-file equipmentImage"
                                                              onclick="changeFile(this)"></span>
                                                    </div>
                                                    <div class="card-content-progress"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-12 mt-4">
                                    <div class="row form-group">
                                        <div class="col-xl-7">
                                            <div class="form-group">
                                                <label>Cadastro do CNPJ:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-5 text-left">
                                            <label class="label-file cnpjRegistration-file text-center"
                                                   for="cnpjRegistration"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file"
                                                   onchange="uploadImage(this)"
                                                   id="cnpjRegistration" name="cnpjRegistration"
                                                   accept="image/png, image/jpg, image/jpeg" required>
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

                                <div class="col-xl-12 ">
                                    <div class="row form-group">
                                        <div class="col-xl-7">
                                            <div class="form-group">
                                                <label>Contrato social</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-5 text-left">
                                            <label class="label-file socialContract-file text-center"
                                                   for="socialContract"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file"
                                                   onchange="uploadImage(this)"
                                                   id="socialContract" name="socialContract"
                                                   accept="image/png, image/jpg, image/jpeg" required>
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

                                <div class="col-xl-12">
                                    <div class="row form-group">
                                        <div class="col-xl-7">
                                            <div class="form-group">
                                                <label>Alvará de funcionamento:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-5 text-left">
                                            <label class="label-file businessLicense-file text-center"
                                                   for="businessLicense"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file"
                                                   onchange="uploadImage(this)"
                                                   id="businessLicense" name="businessLicense"
                                                   accept="image/png, image/jpg, image/jpeg" required>
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

                                <div class="col-xl-12">
                                    <div class="row form-group">
                                        <div class="col-xl-7">
                                            <div class="form-group">
                                                <label>Carteira de Habilitação:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-5 text-left">
                                            <label class="label-file driverLicense-file text-center"
                                                   for="driverLicense"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file"
                                                   onchange="uploadImage(this)"
                                                   id="driverLicense" name="driverLicense"
                                                   accept="image/png, image/jpg, image/jpeg" required>
                                            <div class="invalid-feedback"></div>
                                            <div class="driverLicense-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="driverLicense-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="driverLicense-name"></p>
                                                        <span id="odriverLicense-span-close"
                                                              class="icon-close ml-3 card-close-file driverLicense"
                                                              onclick="changeFile(this)"></span>
                                                    </div>
                                                    <div class="card-content-progress"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="row form-group">
                                        <div class="col-xl-7">
                                            <div class="form-group">
                                                <label>Licenciamento do Veículo (CRLV):</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-5 text-left">
                                            <label class="label-file carLicense-file text-center"
                                                   for="carLicense"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file"
                                                   onchange="uploadImage(this)"
                                                   id="carLicense" name="carLicense"
                                                   accept="image/png, image/jpg, image/jpeg" required>
                                            <div class="invalid-feedback"></div>
                                            <div class="carLicense-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="carLicense-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="carLicense-name"></p>
                                                        <span id="carLicense-span-close"
                                                              class="icon-close ml-3 card-close-file carLicense"
                                                              onclick="changeFile(this)"></span>
                                                    </div>
                                                    <div class="card-content-progress"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="row form-group">
                                        <div class="col-xl-7">
                                            <div class="form-group">
                                                <label>Certificado de realização de curso de boas práticas de
                                                    manipulação de
                                                    alimentos:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-5 text-left">
                                            <label class="label-file practiceCertification-file text-center"
                                                   for="practiceCertification"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file"
                                                   onchange="uploadImage(this)"
                                                   id="practiceCertification" name="practiceCertification"
                                                   accept="image/png, image/jpg, image/jpeg" required>
                                            <div class="invalid-feedback"></div>
                                            <div class="practiceCertification-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="practiceCertification-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="practiceCertification-name"></p>
                                                        <span id="practiceCertification-span-close"
                                                              class="icon-close ml-3 card-close-file practiceCertification"
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


                    <div class="col-xl-12 mt-5">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Local de trabalho</h4>
                            <p class="subtitle-section-p">Marque no mapa o local em que deseja trabalhar.</p>
                            <hr>
                            <div id="mapCreateAccount"></div>
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
<script src="<?= url("themes/assets/js/leaflet.markercluster-src.js"); ?>"></script>
<script src="<?= url("themes/assets/js/Leaflet.LinearMeasurement.js"); ?>"></script>
<script type="text/javascript" src="<?= url("themes/assets/js/multiples.js"); ?>"></script>
<script>
    $(document).ready(function () {
        $('#productSelect').multiselect();
        $("#cnpj").mask("99.999.999/9999-99");
        $("#cmc").mask('0000000000', {reverse: true});
    });

    $(function () {
        let theMarker = {};
        let map = null;
        let mapTiles = {};
        let ctrTiles = {};
        let ctrLayers = {};

        mapTiles['Mapa Jawg'] = L.tileLayer('https://{s}.tile.jawg.io/jawg-light/{z}/{x}/{y}{r}.png?access-token=C1vu4LOmp14JjyXqidSlK8rjeSlLK1W59o1GAfoHVOpuc6YB8FSNyOyHdoz7QIk6', {
            maxNativeZoom: 19,
            maxZoom: 20,
            minZoom: 10
        });
        ctrTiles["Mapa Jawg"] = mapTiles["Mapa Jawg"];

        mapTiles['Mapa OSM'] = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxNativeZoom: 19,
            maxZoom: 19,
            minZoom: 10
        });
        ctrTiles['Mapa OSM'] = mapTiles['Mapa OSM'];

        mapTiles['Satelite'] = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxNativeZoom: 19,
            maxZoom: 20,
            minZoom: 10,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });
        ctrTiles["Satelite"] = mapTiles["Satelite"];

        map = L.map('mapCreateAccount', {
            center: [-9.6435441, -35.7257695],
            layers: [mapTiles["Mapa Jawg"]],
            zoomControl: true,
            maxZoom: 20,
            minZoom: 10,
            zoom: 14
        });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        let pending = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-0.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        let paid = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-1.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        let expired = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-2.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        map.on('click', (e) => {
            let inputHidden = "<input id='latitude' type='hidden' name='latitude' value='" + e.latlng.lat + "'><input id='longitude' type='hidden' name='longitude' value='" + e.latlng.lng + "'>";
            $("#inputHidden").empty();
            $("#inputHidden").append(inputHidden);

            if (theMarker !== undefined) {
                map.removeLayer(theMarker);
            }

            let data = {'latitude': e.latlng.lat, 'longitude': e.latlng.lng};
            $.post("<?= $router->route("web.checkZone"); ?>", data, function (returnData) {
                map.setView(new L.LatLng(e.latlng.lat, e.latlng.lng));
                if (returnData == 1) {
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng], {icon: paid}).bindPopup('Local selecionado').addTo(map);
                } else if (returnData == 2) {
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng], {icon: pending}).bindPopup('Local selecionado').addTo(map);
                } else {
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng], {icon: expired}).bindPopup('Local selecionado').addTo(map);
                    swal({
                        icon: "warning",
                        title: "Ops..!",
                        text: "Esta área já está em seu limite máximo de ambulantes.",
                    });
                }
            }, "html").fail(function () {
                alert("Erro ao processar requisição!");
            });
        });
    });

    $('form').on('submit', function (e) {
        e.preventDefault();
        $("#loader-div").show();

        const _thisForm = $(this);
        const data = new FormData(this);



        $.ajax({
            type: _thisForm.attr('method'),
            url: _thisForm.attr('action'),
            data: data,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (returnData) {
            console.log(returnData);
            returnData = JSON.parse(returnData);
            if (returnData[0].success) {
                swal({
                    icon: "success",
                    title: "Sua licença foi cadastrada!",
                    text: "Acesse o menu 'Minhas Licenças' para visualiza-la.",
                }).then((result) => {
                    console.log(result);
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
            $("#loader-div").hide();
        });
    });

    function validateCnpj(e) {
        $("#loader-div").show();
        let cnpj = e;

        if (checkCnpj(cnpj) == false) {
            $("#loader-div").hide();
            swal({
                icon: "error",
                title: "Erro",
                text: "O CNPJ digitado não é válido. Por favor, insira um CNPJ válido e tente novamente.",
            });
        }else{
            $("#loader-div").hide();
        }
    }

</script>
<?php $v->end(); ?>
