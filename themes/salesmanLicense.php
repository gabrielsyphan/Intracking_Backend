<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/layers-control.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/util.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<link rel="stylesheet" href="<?= url("themes/assets/css/multiples.css"); ?>" type="text/css">
<?php $v->end(); ?>

<div class="container-fluid mt-5" style="background-color: #fff;">
    <div class="p-5">
        <form id="form-license-salesman" method="POST" action="<?= $router->route('web.validateSalesmanLicense'); ?>">
            <fieldset>
                <?php if ($company): ?>
                    <input type="hidden" name="companyId" value="<?= $company->id ?>">
                <?php endif; ?>
                <input type="hidden" name="userId" value="<?= $userId ?>">
                <div id="inputHidden"></div>
                <div class="row mb-5">
                    <div class="col-xl-12 pb-3">
                        <h2 class="black-title-section">Licença de Ambulante</h2>
                        <p class="subtitle-section-p">Voltada as pessoas que exercem a profissão de ambulante.</p>
                        <hr>
                    </div>
                    <div class="col-xl-6 mt-5">
                        <div class="div-gray-bg border-top-green p-5">
                            <h4 class="black-title-section">Informações de trabalho</h4>
                            <hr>
                            <div class="row">
                                <?php if(!$company): ?>
                                    <div class="col-xl-12">
                                        <div class="form-group">
                                            <label>Como vai vender?:</label>
                                            <input type="text" class="form-input" id="howWillSell" name="howWillSell"
                                                   placeholder="Ex.: Barraca, carrinho...">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label>Largura ocupada em metros</label>
                                            <input type="text" class="form-input" id="width" name="width" placeholder="Ex.: 10">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label>Comprimento em metros:</label>
                                            <input type="text" class="form-input" id="length" name="length" placeholder="Ex.: 5.23">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="form-group">
                                            <label>Produtos e/ou serviços:</label>
                                            <select id="productSelect" class="form-input" name="productSelect[]" multiple="multiple">
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
                                                      placeholder="Ex.: Trabalho com a venda de produtos para cabelo."></textarea>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col-xl-12">
                                        <div class="form-group">
                                            <label>Empresa responsável</label>
                                            <input class="form-input" value="<?= $company->nome_fantasia; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="form-group">
                                            <label>Como vai vender?:</label>
                                            <input type="text" class="form-input" id="howWillSell" name="howWillSell"
                                                   placeholder="Ex.: Barraca, carrinho...">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label>Largura ocupada em metros</label>
                                            <input type="text" class="form-input" id="width" name="width" placeholder="Ex.: 10">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label>Comprimento em metros:</label>
                                            <input type="text" class="form-input" id="length" name="length" placeholder="Ex.: 5.23">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="form-group">
                                            <label>Produtos e/ou serviços:</label>
                                            <select id="productSelect" class="form-input"  name="productSelect[]" multiple="multiple">
                                            <?php $productDescription = ""; foreach (str_split($company->produto) as $product): ?>
                                                <?php switch ($product):
                                                    case 0: ?>
                                                        <option class="subtitle-section-p" value="<?=$product;?>">Gêneros e produtos alimentícios em geral</option>
                                                    <?php break;
                                                    case 1: ?>
                                                    <option class="subtitle-section-p" value="<?=$product;?>">Bebidas alcoólicas</option>
                                                        <?php break;
                                                    case 2: ?>
                                                    <option class="subtitle-section-p" value="<?=$product;?>">Bebidas não alcoólicas<option>
                                                        <?php break;
                                                    case 3: ?>
                                                    <option class="subtitle-section-p" value="<?=$product;?>">Brinquedos e artigos ornamentais</option>
                                                        <?php break;
                                                    case 4: ?>
                                                    <option class="subtitle-section-p" value="<?=$product;?>">Confecções, calçados e artigos de uso pessoal</option>
                                                        <?php break;
                                                    case 5: ?>
                                                    <option class="subtitle-section-p" value="<?=$product;?>">Louças, ferragens, artefatos de plástico,borracha,
                                                        couro e utensílios domésticos</option>
                                                        <?php break;
                                                    case 6: ?>
                                                    <option class="subtitle-section-p" value="<?=$product;?>">Artesanato, antiguidades e artigos de arte em geral</option>
                                                        <?php break;
                                                    default: $productDescription = $company->outro_produto; ?>
                                                    <option class="subtitle-section-p" value="<?=$product;?>"><?= $company->outro_produto;?></option>
                                                <?php endswitch; ?>
                                            <?php endforeach;?>
                                            </select>

                                            <input type="hidden" name="productDescription" value="<?= $productDescription ?>">
                                        </div>
                                    </div>
                                <?php endif;?>
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
                                        <select id="workedDays" class="form-input" multiple="multiple" name="workedDays[]">
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
                                               placeholder="Digite o nome de sua mãe">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Horário para o fim das vendas:</label>
                                        <input type="time" class="form-input" id="endHour" name="endHour"
                                               placeholder="Digite o nome de sua mãe">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-xl-12 mt-4">
                                    <div class="row">
                                        <div class="col-xl-7">
                                            <div class="form-group">
                                                <label>Insira uma foto do seu equipamento (barraca, carrinho etc):</label>
                                            </div>
                                        </div>

                                        <div class="col-xl-5 text-left">
                                            <label class="label-file equipmentImage-file text-center" for="equipmentImage"><span
                                                        class="icon-plus mr-2"></span> Selecionar Arquivo</label>
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
        $('#workedDays').multiselect();
        $('#productSelect').multiselect();
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

        let area = [];
        let aux = [];
        <?php if($zones):
        foreach ($zones as $zone):
        $aux = intval(($zone->quantidade_ambulantes * 100) / $zone->limite_ambulantes);
        if ($aux <= 49):
            $color = "#5ea9a4";
        elseif ($aux >= 50 && $aux <= 99):
            $color = "#f5a42c";
        else:
            $color = "#ed2e54";
        endif; ?>
        area = JSON.parse('<?= json_encode($zone->poligono) ?>');
        area.forEach(function (e) {
            aux.push([e[1], e[0]]);
        });

        L.polygon(aux, {color: '<?= $color ?>', fillColor: '<?= $color ?>'}).addTo(map);
        aux = [];
        <?php endforeach; endif;?>

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
            }, "html") .fail(function () {
                alert("Erro ao processar requisição!");
            });
        });
    });

    $('form').on('submit', function (e) {
        e.preventDefault();
        $("#loader-div").show();

        const _thisForm = $(this);
        const data = new FormData(this);
        const fieldsetDisable = _thisForm.find('fieldset');
        fieldsetDisable.attr('disabled', true);

        if ($('#inputHidden').children().length != 0) {
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
                    fieldsetDisable.removeAttr("disabled");
                    $("#loader-div").hide();
                });
            }
        } else {
            swal({
                icon: "warning",
                title: "Alerta!",
                text: "Selecione uma região no mapa.",
            });
            fieldsetDisable.removeAttr("disabled");
            $("#loader-div").hide();
        }
    });
</script>
<?php $v->end(); ?>