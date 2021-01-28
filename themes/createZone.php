<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>" />
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<?php $v->end(); ?>

<div class="container-fluid container-white mt-5">
    <div class="p-5">
        <form id="form-create-zone" action="<?= $router->route('web.validateZone') ?>" method="POST">
            <fieldset class="row">
                <div id="inputHidden"></div>
                <div class="col-xl-12">
                    <h2 class="black-title-section">Cadastrar nova Zona</h2>
                    <p class="subtitle-section-p">Descreva todos os dados da zona desenhada acima.</p>
                </div>
                <div class="col-xl-6 mt-5">
                    <div class="div-gray-bg border-top-green p-5">
                        <h4 class="black-title-section">Informações da zona</h4>
                        <hr>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label>Nome:</label>
                                    <input type="text" class="form-input" id="zoneName" name="zoneName" title="Nome do local" placeholder="Insira o nome do local">
                                    <div class="invalidate-feedback"></div>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label>Descrição: <span class="spanAlert">(Opcional)</span></label>
                                    <input type="text" class="form-input" id="description" name="description" title="Descrição do local" placeholder="Insira a descrição do local">
                                    <div class="invalidate-feedback"></div>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label>Foto do local: <span class="spanAlert">(Opcional)</span></label>
                                    <input style="background: white;" type="file" class="form-input" id="zoneImage" name="zoneImage" accept="image/png, image/jpg, image/jpeg">
                                    <div class="invalidate-feedback"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Vagas totais:</label>
                                    <input type="number" class="form-input" id="available" name="available" title="Vagas disponíveis" min="0" placeholder="Insira a quantidade de vagas totais">
                                    <div class="invalidate-feedback"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Vagas já ocupadas:</label>
                                    <input type="number" class="form-input" id="occupied" name="occupied" title="Vagas ocupadas" min="0" placeholder="Insira a quantidade de vagas ocupadas">
                                    <div class="invalidate-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mt-5">
                    <div class="div-gray-bg border-top-green p-5">
                        <h4 class="black-title-section">Realize o desenho da zona no mapa</h4>
                        <hr>
                        <div class="row">
                            <div id="map"></div>
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
<?php $v->start("scripts"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script>
    $("#registration").mask('000000-0');

    $("#identity").mask('000.000.000-00');

    $('form').on('submit', function(e) {
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
                    if (returnData == 1) {
                        swal({
                            icon: "success",
                            title: "Sucesso!",
                            text: "A zona foi cadastrada!"
                        }).then((element) => {
                            $("#zoneName").val('');
                            $("#description").val('');
                            $("#localImage").val('');
                            $("#occupied").val('');
                            $("#available").val('');
                        });
                        $("#form").trigger("reset");
                    } else {
                        swal({
                            icon: "error",
                            title: "Erro!",
                            text: "Não foi possível cadastrar a zona.",
                        });
                    }
                    console.log(returnData);
                }).fail(function () {
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Erro ao processar requisição",
                    });
                    console.log(returnData);
                }).always(function () {
                    $("#loader-div").hide();
                    fieldsetDisable.removeAttr("disabled");
                });
            }
        }
    });

    $('#localImage').change(function(e) {
        var fileName = e.target.files[0].name;
        var ext = fileName.substr(fileName.lastIndexOf('.') + 1);
        if (ext === 'jpg' || ext === 'jpeg' || ext === 'png' || ext === 'JPG' || ext === 'JPEG' || ext === 'PNG') {
            if (e.target.files[0].size > 1133695) {
                alert("Por favor, insira uma imagem com no máximo 1mb de tamanho.");
                $('#localImage').val('');
            }
        } else {
            alert("O tipo do anexo é inválido. Por favor, insira uma imagem em formato JPEG, JPG ou PNG.");
            $('#localImage').val('');
        }
    });

    $(document).ready(function() {
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

        map = L.map('map', {
            center: [-9.6435441, -35.7257695],
            layers: [mapTiles["Mapa OSM"]],
            zoomControl: true,
            maxZoom: 20,
            minZoom: 10,
            zoom: 14
        });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        let drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        let drawControl = new L.Control.Draw({
            position: 'topright',
            draw: {
                polygon: {
                    shapeOptions: {
                        color: 'purple'
                    },
                    allowIntersection: false,
                    drawError: {
                        color: 'orange',
                        timeout: 1000
                    },
                    showLength: true
                },
                polyline: false,
                rect: false,
                circle: false,
                marker: false,
                rectangle: false,
                circlemarker: false
            },
            edit: {
                featureGroup: drawnItems
            }
        });
        map.addControl(drawControl);

        let layer;

        map.on('draw:created', function(e) {
            let type = e.layerType;

            if (layer) {
                drawnItems.removeLayer(layer);
            }

            layer = e.layer
            drawnItems.addLayer(layer);

            let points = e.layer.editing.latlngs[0][0];
            points = JSON.stringify(points);

            let inputHidden = "<input id='polygon' type='hidden' name='geojson' value='" + points + "'>";
            $("#inputHidden").empty();
            $("#inputHidden").append(inputHidden);
        });

        map.on('draw:deleted', function(e) {
            $("#inputHidden").empty();
        })
    });
</script>
<?php $v->end(); ?>
