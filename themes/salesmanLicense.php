<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/layers-control.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/util.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>" />
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<link rel="stylesheet" href="<?= url("themes/assets/css/multiples.css"); ?>" type="text/css">
<?php $v->end(); ?>

<div class="container-fluid mt-5" style="background-color: #fff;">
    <div class="container pt-5 pb-5">
        <form>
            <fieldset>
                <div class="row mb-5">
                    <div class="col-xl-12 pb-3">
                        <h2 class="black-title-section">Licença de Ambulante</h2>
                        <p class="subtitle-section-p">Voltada as pessoas que exercem a profissão de ambulante.</p>
                        <hr>
                    </div>
                    <div class="col-xl-4">
                        <div class="form-group">
                            <label>Como vai vender?:</label>
                            <input type="text" class="form-input" id="howWillSell" name="howWillSell" placeholder="Ex.: Barraca, carrinho..." required>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="form-group">
                            <label>Largura ocupada em metros</label>
                            <input type="text" class="form-input" id="width" name="width" placeholder="Ex.: 10" required>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="form-group">
                            <label>Comprimento ocupado em metros:</label>
                            <input type="text" class="form-input" id="length" name="length" placeholder="Ex.: 5.23" required>
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <div class="form-group">
                            <label>Produtos e/ou serviços:</label>
                            <select id="productSelect" class="form-input" name="productSelect[]" multiple="multiple" required>
                                <option value="0">Gêneros e produtos alimentícios em geral</option>
                                <option value="1">Bebidas não alcoólicas</option>
                                <option value="2">Bebidas alcoólicas</option>
                                <option value="3">Brinquedos e artigos ornamentais</option>
                                <option value="4">Confecções, calçados e artigos de usopessoal</option>
                                <option value="5">Louças, ferragens, artefatos de plástico,borracha, couro e utensílios domésticos</option>
                                <option value="6">Artesanato, antiguidades e artigos dearte em geral</option>
                                <option value="7">Outros artigos não especificados nos itens anteriores</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <div class="form-group">
                            <label>Descreva seu produto ou serviço ofertado: <span class="spanAlert">(Somente os não especificados na lista acima)</span></label>
                            <textarea type="text" class="form-input" id="productDescription" name="productDescription" placeholder="Ex.: Trabalho com a venda de produtos para cabelo."></textarea>
                        </div>
                    </div>

                    <div class="col-xl-4">
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
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="form-group">
                            <label>Horário para o ínicio das vendas:</label>
                            <input type="time" class="form-input" id="initHour" name="initHour" placeholder="Digite o nome de sua mãe" required>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="form-group">
                            <label>Horário para o fim das vendas:</label>
                            <input type="time" class="form-input" id="endHour" name="endHour" placeholder="Digite o nome de sua mãe" required>
                        </div>
                    </div>

                    <div class="col-xl-6 mt-4">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>Insira uma foto do seu equipamento (barraca, carrinho etc):</label>
                                </div>
                            </div>

                            <div class="col-xl-6 text-left">
                                <label class="label-file equipmentImage-file text-center" for="equipmentImage"><span
                                        class="icon-plus mr-2"></span> Selecionar Arquivo</label>
                                <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                       id="equipmentImage" name="equipmentImage" accept="image/png, image/jpg, image/jpeg">
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
                    <div class="col-xl-12 mt-5">
                        <h3 class="black-title-section">Local de trabalho</h3>
                        <p class="subtitle-section-p">Marque no mapa o local em que deseja trabalhar.</p>
                        <hr>
                        <div id="mapCreateAccount"></div>
                    </div>
                    <div class="col-xl-12 mt-5 text-right">
                        <hr>
                        <button type="reset" class="btn-3 secondary-color">
                            Limpar campos
                        </button>
                        <button class="btn-3 primary" type="button">
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
    $(document).ready(function() {
        $('#workedDays').multiselect();
        $('#productSelect').multiselect();
    });
    let lat = 0;
    let lng = 0;
    $(function() {
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
            layers: [mapTiles["Mapa OSM"]],
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
            lat = e.latlng.lat;
            lng = e.latlng.lng;

            if (theMarker !== undefined) {
                map.removeLayer(theMarker);
            }

            let data = {'latitude': lat, 'longitude': lng};
            $.post("<?= $router->route("web.checkZone"); ?>", data, function (returnData) {
                map.setView(new L.LatLng(e.latlng.lat, e.latlng.lng), 14);
                if (returnData == 1) {
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng], {icon: paid}).bindPopup('Local selecionado').addTo(map);
                } else if (returnData == 2) {
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng], {icon: pending}).bindPopup('Local selecionado').addTo(map);
                } else {
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng], {icon: expired}).bindPopup('Local selecionado').addTo(map);
                    alert("Essa área já está com seu limite máximo de ambulante.");
                }
            }, "html").fail(function () {
                alert("Erro ao processar requisição!");
            });
        });
    });
</script>
<?php $v->end(); ?>
