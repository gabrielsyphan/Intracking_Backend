<?php $v->layout('_theme.php'); ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/multiples.css"); ?>" type="text/css">
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<?php $v->end(); ?>

<div class='container-fluid mt-5' style='background-color: #fff;'>
    <div class='p-5'>
        <form id='form-license-salesman' method='POST' action='<?= $router->route('web.validateMarketLicense') ?>'>
            <fieldset>
                <input type='hidden' name='userId' value='<?= $userId ?>'>
                <div class='row mb-5'>
                    <div class='col-12 pb-3'>
                        <h2 class='black-title-section'>Licença de Mercado</h2>
                        <p class='subtitle-section-p'>Voltada para vendedores que atuam em um mercado.</p>
                        <hr>
                    </div>
                    <div class="col-xl-6 mt-5">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Informações de trabalho</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Produtos e/ou serviços:</label>
                                        <select id="productSelect" class="form-input" name="productSelect[]" multiple="multiple" required>
                                            <option value="0">Gêneros e produtos alimentícios em geral</option>
                                            <option value="1">Bebidas não alcoólicas</option>
                                            <option value="2">Bebidas alcoólicas</option>
                                            <option value="3">Brinquedos e artigos ornamentais</option>
                                            <option value="4">Confecções, calçados e artigos de usopessoal</option>
                                            <option value="5">Louças, ferragens, artefatos de plástico,borracha, couro e utensílios
                                                domésticos
                                            </option>
                                            <option value="6">Artesanato, antiguidades e artigos dearte em geral</option>
                                            <option value="7">Outros artigos não especificados nos itens anteriores</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Descreva outro produto ofertado: <span class="spanAlert">(Se não encontrado na lista acima)</span>:</label>
                                        <textarea type="text" class="form-input" id="productDescription" name="productDescription"
                                                  placeholder="Ex.: Trabalho com a venda de produtos para cabelo." required></textarea>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Selecione o mercado que deseja trabalhar</label>
                                        <select id="marketSelect" class="form-input" name="marketSelect" required>
                                            <option value="0" selected disabled hidden>Selecione uma opção</option>
                                            <?php if ($zones): foreach ($zones as $zone): ?>
                                                <option value="<?= md5($zone->id) ?>"><?= $zone->nome ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Selecione a vaga que deseja ocupar</label>
                                        <select id="fixedSelect" class="form-input" name="fixedSelect" required>
                                            <option value="0" selected disabled hidden>Selecione uma opção</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 mt-5">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Horários e anexos</h4>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Dias trabalhados:</label>
                                        <select id="workedDays" class="form-input" multiple="multiple" name="workedDays[]" required>
                                            <option value="0">Domingo</option>
                                            <option value="1">Segunda-Feira</option>
                                            <option value="2">Terça-Feira</option>
                                            <option value="3">Quarta-Feira</option>
                                            <option value="4">Quinta-Feira</option>
                                            <option value="5">Sexta-Feira</option>
                                            <option value="6">Sabado</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Horário para o Início das vendas:</label>
                                        <input type="time" class="form-input" id="initHour" name="initHour"
                                               placeholder="Digite o nome de sua mãe" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Horário para o fim das vendas:</label>
                                        <input type="time" class="form-input" id="endHour" name="endHour"
                                               placeholder="Digite o nome de sua mãe" required>
                                        <div class="invalid-feedback"></div>
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
                            <div id="map"></div>
                        </div>
                    </div>

                    <div class="col-xl-12 mt-5">
                        <button type="submit" class="btn primary c-white float-right">Cadastrar</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script type="text/javascript" src="<?= url("themes/assets/js/multiples.js"); ?>"></script>
<script>
    $(document).ready(function () {
        $('#workedDays').multiselect();
        $('#productSelect').multiselect();

        let mapTiles = {};
        let ctrTiles = {};
        let ctrLayers = {};
        let layer = {};
        let layer2 = {};

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

        map = L.map('map', {
            center: [-9.6435441, -35.7257695],
            layers: [mapTiles["Mapa Jawg"]],
            zoomControl: true,
            maxZoom: 20,
            minZoom: 10,
            zoom: 14
        });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        $('#marketSelect').on('change', function (e) {
            $("#loader-div").show();

            const option = {referenceCode: $('#marketSelect').val()};
            $.ajax({
                type: "POST",
                url: "<?= $router->route('web.marketData')?>",
                data: option
            }).done(function (returnData) {
                let response = JSON.parse(returnData);

                map.setView(new L.LatLng(response['zoneData'].lat, response['zoneData'].lng), 18);
                let aux = [];
                let points2 = [];
                if (response['zoneData'].polygon) {
                    map.removeLayer(layer);
                    for (let i = 0; i < response['zoneData'].polygon.length; i++) {
                        aux.push([response['zoneData'].polygon[i][1], response['zoneData'].polygon[i][0]]);
                    }

                    layer = L.polygon(aux, {
                        color: "#4bc2ce",
                        fillColor: "#4bc2ce"
                    }).bindPopup(response['zoneData'].name).addTo(map);
                }

                if (response['success']) {
                    response = response['success'];
                    $("#fixedSelect").empty();
                    response.forEach((element) => {
                        let name = '';
                        if (element.name == null) {
                            name = element.referenceCode;
                        } else {
                            name = element.name;
                        }

                        $("#fixedSelect").append(new Option(name, element.referenceCode));
                    });
                } else {
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Não foi possível processar a requisição, verifique sua conexão com a internet e tente novamente.",
                    });
                }
                console.log(returnData)
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

        $('#fixedSelect').on('change', function (e) {
            $("#loader-div").show();

            const option = {referenceCode: $('#fixedSelect').val()};
            map.removeLayer(layer2);
            $.ajax({
                type: "POST",
                url: "<?= $router->route('web.zoneFixedData')?>",
                data: option
            }).done(function (returnData) {
                let response = JSON.parse(returnData);
                if (response['success']) {
                    response = response['success'];
                    let aux = [];
                    let points2 = [];
                    if (response.polygon) {
                        for (let i = 0; i < response.polygon.length; i++) {
                            aux.push([response.polygon[i][1], response.polygon[i][0]]);
                        }

                        layer2 = L.polygon(aux, {
                            color: "#4bc2ce",
                            fillColor: "#4bc2ce"
                        }).bindPopup(response.name).addTo(map);
                    } else {
                        swal({
                            icon: "error",
                            title: "Erro!",
                            text: "Não foi possível processar a requisição, verifique sua conexão com a internet e tente novamente.",
                        });
                    }
                }
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
            if (returnData == 'success') {
                swal({
                    icon: "success",
                    title: "Sua licença foi cadastrada!",
                    text: "Acesse o menu 'Minhas Licenças' para visualiza-la.",
                }).then(() => {
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
