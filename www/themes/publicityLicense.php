<?php $v->layout("_theme.php"); ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/layers-control.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<link rel="stylesheet" href="<?= url("themes/assets/css/multiples.css"); ?>" type="text/css">
<?php $v->end(); ?>

<div class="container-fluid container-white mt-5">
    <div class="p-5">
        <form id="form-license-company" method="POST" action="<?= $router->route('web.validatePublicityLicense') ?>">
            <div id="inputHidden"></div>
            <input type="hidden" name="userId" value="<?= $userId ?>">
            <fieldset>
                <div class="row mb-5">
                    <div class="col-xl-12 pb-3">
                        <h2 class="black-title-section">Licenciamento de Publicidade e Propaganda</h2>
                        <p class="subtitle-section-p">Para interessados na instalação de
                            engenhos de publicidade</p>
                        <hr>
                    </div>

                    <div class="col-xl-6 ">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Informações da publicidade/propaganda</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Tipo de publicidade/propaganda:</label>
                                        <select id="typeSelect" class="form-input" name="typeSelect[]" onchange="checkOther()" required>
                                            <option value="0"> Outdoor fixo para fixação de cartazes substituíveis</option>
                                            <option value="1">Indicadores de hora ou temperatura</option>
                                            <option value="2">Indicadores de bairros e locais turísticos</option>
                                            <option value="3">Anúncios provisórios</option>
                                            <option value="4">Panfletos e prospectos</option>
                                            <option value="5">Panfletos e prospectos</option>
                                            <option value="6">Anúncio em veículos</option>
                                            <option value="7">Infláveis</option>
                                            <option value="8">Faixas</option>
                                            <option value="9"> Bancos, mesas, sombrinhas e protetores de árvores em locais públicos ou de permissionários públicos</option>
                                            <option value="10"> Postes indicativos de paradas de coletivos</option>
                                            <option value="11"> Anúncios em abrigos</option>
                                            <option value="12"> Boias e flutuantes</option>
                                            <option value="13"> Postes indicadores de logradouros</option>
                                            <option value="14"> Anúncios indicativos</option>
                                            <option value="15"> Anúncios publicitários</option>
                                            <option value="16"> Lixeiras</option>
                                            <option value="17"> Engenhos publicitários movimentados</option>
                                            <option value="18"> Engenhos publicitários rígidos</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Descreva a estrutura:</label>
                                        <input type="text" class="form-input" id="other" name="other"
                                               placeholder="Ex.: Balão, boneco inflável, Outdoor" disabled>
                                        <div class="invalid-feedback"></div>

                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Onde vai ser instalado:</label>
                                        <input type="text" class="form-input" id="place" name="place"
                                               placeholder="Ex.: Próximo à praça" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Horário para o fim das vendas:</label>
                                        <input type="text" class="form-input" id="days" name="days">
                                        <input type="text" class="form-control date" placeholder="Pick the multiple dates">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 mt-lg-5 mt-xl-0">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Especificações e dimensões</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Descreva brevemente o projeto:</label>
                                        <textarea type="text" class="form-input" id="description"
                                                  name="description"
                                                  placeholder="Ex.: Trabalho com a venda de produtos para cabelo."
                                                  required></textarea>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <input type="hidden" name="licenseId" value="<?=$license->id_licenca ?>">
                                    <div class="form-group mt-3">
                                        <label>Possui iluminação (embutida ou externa):</label>
                                        <div class="row w-100">
                                            <div class="col-6" class="form-group">
                                                <input type="radio" id="approve" name="light" value="y" required>
                                                <label for="approve">Sim</label><br>
                                            </div>
                                            <div class="col-6" class="form-group">
                                                <input type="radio" id="reject" name="light" value="n" required>
                                                <label for="reject">Não</label><br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label>Tipo de via:</label>
                                        <div class="row w-100">
                                            <div class="col-4" class="form-group">
                                                <input type="radio" id="arterial" name="road" value="arterial" required>
                                                <label for="arterial">Arterial</label><br>
                                            </div>
                                            <div class="col-4" class="form-group">
                                                <input type="radio" id="regional" name="road" value="regional" required>
                                                <label for="regional">Regional</label><br>
                                            </div>
                                            <div class="col-4" class="form-group">
                                                <input type="radio" id="orla" name="light" value="orla" required>
                                                <label for="orla">Orla</label><br>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label>Largura:</label>
                                        <input type="text" class="form-input" id="width" name="width"
                                               placeholder="Ex.: 10" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label>Comprimento:</label>
                                        <input type="text" class="form-input" id="length" name="length"
                                               placeholder="Ex.: 5.23" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label>Altura:</label>
                                        <input type="text" class="form-input" id="height" name="height"
                                               placeholder="Ex.: 5.23">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 mt-5">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Informações do responsável</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Tipo de requerente:</label>
                                        <select id="typeRequestSelect" class="form-input" name="typeRequestSelect[]" onchange="changeRequest()" required>
                                            <option value="0">Pessoa física</option>
                                            <option value="1">Pessoa Jurídica</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>CNPJ:</label>
                                        <input type="text" class="form-input" id="cnpj" name="cnpj"
                                               placeholder="Ex.: 00.000.000/0000-00" disabled>
                                        <div class="invalid-feedback" ></div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>CMC:</label>
                                        <input type="text" class="form-input" id="cmc" name="cmc"
                                               placeholder="Ex.: 0000000000" disabled="true">
                                        <div class="invalid-feedback" ></div>
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
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>ART do técnico:</label>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 text-left">
                                            <label class="label-file equipmentImage-file text-center"
                                                   for="equipmentImage"><span
                                                        class="icon-plus mr-2"></span> Selecionar </label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="artImage" name="ARTImage"
                                                   accept="image/png, image/jpg, image/jpeg">
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

                                <div class="col-xl-6">
                                    <div class="row form-group">
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>Alvará de Localização:</label>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 text-left">
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
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>Comprovante residência:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-12 text-left">
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
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>Autorização do proprietário:</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-12 text-left">
                                            <label class="label-file businessLicense-file text-center"
                                                   for="businessLicense"><span
                                                        class="icon-plus mr-2"></span> Selecionar</label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="businessLicense" name="consentLicense"
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
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>Croqui do anúncio:</label>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 text-left">
                                            <label class="label-file equipmentImage-file text-center"
                                                   for="equipmentImage"><span
                                                        class="icon-plus mr-2"></span> Selecionar </label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="equipmentImage" name="equipmentImage"
                                                   accept="image/png, image/jpg, image/jpeg">
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

                                <div class="col-xl-6">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>Foto do local de instalação:</label>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 text-left">
                                            <label class="label-file equipmentImage-file text-center"
                                                   for="equipmentImage"><span
                                                        class="icon-plus mr-2"></span> Selecionar </label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="equipmentImage" name="equipmentImage"
                                                   accept="image/png, image/jpg, image/jpeg">
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 mt-5">
                    <div class="div-gray-bg border-top-green p-5">
                        <h4 class="black-title-section">Local</h4>
                        <p class="subtitle-section-p">Marque no mapa o local onde pretende instalar o equipamento</p>
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
        $('#workedDays').multiselect();
        $('#typeSelect').multiselect();
        $('#typeRequestSelect').multiselect();
        $('#days').datepicker({
            multidate: true,
            format: 'dd-mm-yyyy'
        });
    });

    $(document).ready(function () {
        $("#width").mask('00.00', {reverse: true});
        $("#length").mask('00.00', {reverse: true});
        $("#cnpj").mask("99.999.999/9999-99");
        $("#cmc").mask('0000000000', {reverse: true});
    });

    function changeRequest(){
        console.log(document.getElementById("typeRequestSelect").value);
        if (document.getElementById("typeRequestSelect").value == '1'){
            document.getElementById("cnpj").disabled = false;
            document.getElementById("cmc").disabled = false;
        } else {
            document.getElementById("cnpj").disabled = true;
            document.getElementById("cmc").disabled = true;
        }
    }

    function checkOther(){
        if (document.getElementById("typeSelect").value == 'Outro'){
            document.getElementById("other").value = "";
            document.getElementById("other").disabled = false;
        } else {
            document.getElementById("other").disabled = true;
        }
    }

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
            console.log(123);
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
            $("#loader-div").hide();
        });

    });
</script>
<?php $v->end(); ?>
