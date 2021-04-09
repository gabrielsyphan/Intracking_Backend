<?php $v->layout("_theme.php"); ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
<?php $v->end(); ?>

<div class='container-fluid mt-5' style='background-color: #fff;'>
    <div class="row">
        <div class="col-md-6">
            <form class="p-5" method="POST" action="<?= $router->route("web.validateEditFixedZone"); ?>">
                <h3 class='black-title-section'>Vagas fixas - <?= $zone->nome ?></h3>
                <p class='subtitle-section-p'><?= $zone->descricao ?></p>
                <hr>

                <div class="form-group">
                    <label>Identificador da vaga que deseja editar:</label>
                    <div class="d-flex">
                        <select id="fixedSelect" class="form-input w-75 b-radius-left" name="fixedSelect">
                            <option value="0" selected disabled hidden>Selecione uma opção</option>
                            <?php foreach ($fixed as $zFixed): ?>
                                <option value="<?= $zFixed->cod_identificador ?>"><?= $zFixed->cod_identificador ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn primary c-white w-25 b-radius-right" type="button" onclick="newFixedArea()">
                            <span class="icon-add"></span>
                            Nova
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" id="fixedName" class="form-input" name="fixedName">
                </div>

                <div class="form-group">
                    <label>Descrição:</label>
                    <input type="text" id="fixedDescription" class="form-input" name="fixedDescription">
                </div>

                <div class="form-group">
                    <label>Valor:</label>
                    <input type="text" id="fixedValue" class="form-input" name="fixedValue">
                </div>

                <div class="mt-5">
                    <h5>Situação: <span id="fixedStatus"></span></h5>
                </div>

                <div id="inputHidden"></div>

                <button class="btn primary c-white float-right mb-5">Atualizar</button>
            </form>
        </div>
        <div class="col-md-6 pr-0">
            <div id="editFixMap"></div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script>
    $(document).ready(function () {
        let mapTiles = {};
        let ctrTiles = {};
        let ctrLayers = {};
        let layer;

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

        map = L.map('editFixMap', {
            center: [<?= $centroid[1] ?>, <?= $centroid[0] ?>],
            layers: [mapTiles["Mapa Jawg"]],
            zoomControl: true,
            maxZoom: 20,
            minZoom: 10,
            zoom: 16
        });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        let area = [];
        let auxArea = [];
        <?php if($polygon): ?>
        area = JSON.parse('<?= json_encode($polygon) ?>');
        area.forEach(function (e) {
            auxArea.push([e[1], e[0]]);
        });

        L.polygon(auxArea, {color: '#5ea9a4', fillColor: '#5ea9a4'}).addTo(map);
        auxArea = [];
        <?php endif;?>

        let drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        let drawControl = new L.Control.Draw({
            position: 'topright',
            draw: {
                polygon: {
                    shapeOptions: {
                        color: '#4bc2ce'
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

        map.on('draw:created', function (e) {
            console.log(e);
            let type = e.layerType;

            if (layer) {
                drawnItems.removeLayer(layer);
                $("#inputHidden").empty();
            }

            layer = e.layer;
            drawnItems.addLayer(layer);

            let points = e.layer.editing.latlngs[0][0];
            points = JSON.stringify(points);

            let inputHidden = "<input id='polygon' type='hidden' name='geojson' value='" + points + "'>";
            $("#inputHidden").empty();
            $("#inputHidden").append(inputHidden);
        });

        map.on('draw:deleted', function (e) {
            $("#inputHidden").empty();
        });

        map.on('draw:edited', function (e) {
            console.log(e.layers._layers);
        });

        $('#fixedSelect').on('change', function (e) {
            $("#loader-div").show();

            if (layer) {
                drawnItems.removeLayer(layer);
            }

            const option = {referenceCode: $('#fixedSelect').val()};
            $.ajax({
                type: "POST",
                url: "<?= url("zoneFixedData") ?>",
                data: option
            }).done(function (returnData) {
                let response = JSON.parse(returnData);
                if (response['success']) {
                    response = response['success'];
                    $('#fixedStatus').empty();
                    if (response.license) {
                        $('#fixedStatus').append('Ocupada');
                    } else {
                        $('#fixedStatus').append('Disponível');
                    }

                    if (response.name) {
                        $('#fixedName').val(response.name);
                        $('#fixedName').attr('placeholder', 'Nome da vaga');
                    } else {
                        $('#fixedName').val('');
                        $('#fixedName').attr('placeholder', 'Este local não possui um nome definido.');
                    }

                    if (response.description) {
                        $('#fixedDescription').val(response.description);
                        $('#fixedDescription').attr('placeholder', 'Descrição da vaga');
                    } else {
                        $('#fixedDescription').val('');
                        $('#fixedDescription').attr('placeholder', 'Este local não possui uma descrição definida.');
                    }

                    if (response.value) {
                        $('#fixedValue').val(response.value);
                        $('#fixedValue').attr('placeholder', 'Valor da vaga');
                    } else {
                        $('#fixedValue').val('');
                        $('#fixedValue').attr('placeholder', 'Este local não possui um valor definido.');
                    }

                    let aux = [];
                    let points2 = [];
                    if (response.polygon) {
                        for (let i = 0; i < response.polygon.length; i++) {
                            aux.push([response.polygon[i][1], response.polygon[i][0]]);
                            points2.push({'lat': response.polygon[i][1], 'lng': response.polygon[i][0]});
                        }

                        points2.splice(-1, 1);
                        points = JSON.stringify(points2);
                        let inputHidden = "<input id='polygon' type='hidden' name='geojson' value='" + points + "'>";
                        $("#inputHidden").empty();
                        $("#inputHidden").append(inputHidden);

                        layer = L.polygon(aux, {
                            color: "#4bc2ce",
                            fillColor: "#4bc2ce"
                        }).bindPopup('Vaga cadastrada').addTo(drawnItems);
                    }
                } else {
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Não foi possível processar a requisição, verifique sua conexão com a internet e tente novamente.",
                    });
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

        const _thisForm = $(this);
        const data = new FormData(this);

        if ($('#inputHidden').children().length != 0) {
            $("#loader-div").show();
            $.ajax({
                type: _thisForm.attr('method'),
                url: _thisForm.attr('action'),
                data: data,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function (returnData) {
                swal({
                    icon: "success",
                    title: "Sucesso!",
                    text: "Os dados da vaga foram atualizados."
                });
            }).fail(function () {
                swal({
                    icon: "error",
                    title: "Erro!",
                    text: "Erro ao processar requisição",
                });
                console.log(returnData);
            }).always(function () {
                $("#loader-div").hide();
            });
        } else {
            swal({
                icon: "warning",
                title: "Ops..!",
                text: "Você precisa desenhar algo no mapa.",
            });
        }
    });

    function newFixedArea() {
        swal({
            icon: "warning",
            title: "Tem certeza?",
            text: "Realmente deseja adicionar uma nova vaga nesse local?",
            buttons: ["Cancelar", "Sim"]
        }).then(value => {
            if (value) {
                const referenceCode = $('select[name=fixedSelect] option').filter(':selected').val();

                const data = {'zone': '<?= md5($zone->id) ?>'};

                $("#loader-div").show();
                $.ajax({
                    type: 'POST',
                    url: '<?= $router->route('web.newFixedArea') ?>',
                    data: data,
                }).done(function (returnData) {
                    if (returnData == true) {
                        swal({
                            icon: "success",
                            title: "Sucesso!",
                            text: "Os dados da vaga foram atualizados."
                        }).then(value1 => {
                            location.reload();
                        });
                    } else {
                        swal({
                            icon: "warning",
                            title: "Ops...!",
                            text: "Não foi possível cadastrar uma nova vaga.",
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
                });
            }
        });
    }
</script>
<?php $v->end(); ?>
