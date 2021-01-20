<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
    <link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>" />
    <script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
<?php $v->end(); ?>

<div id="map" class="mt-5"></div>
<div class="container-fluid" style="background-color: #fff;">
    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-xl-12">
                <h2 class="black-title-section">Cadastrar nova Zona</h2>
                <p class="subtitle-section-p">Descreva todos os dados da zona desenhada acima.</p>
                <hr>
            </div>
            <div class="col-xl-12">
                <form id="form" method="POST" class="formStyleWidth" action="">
                    <div id="inputHidden"></div>
                    <div class="row">
                        <div class="col-xl-7">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Nome:</label>
                                        <input type="text" class="form-input" id="zoneName" name="zoneName"
                                               title="Nome do local" placeholder="Insira o nome do local" required>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Descrição: <span class="spanAlert">(Opcional)</span></label>
                                        <input type="text" class="form-input" id="description" name="description"
                                               title="Descrição do local" placeholder="Insira a descrição do local">
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Foto do local: <span class="spanAlert">(Opcional)</span></label>
                                        <input type="file" class="form-input" id="localImage" name="localImage"
                                               accept="image/png, image/jpg, image/jpeg">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Vagas totais:</label>
                                        <input type="number" class="form-input" id="available" name="available"
                                               title="Vagas disponíveis" min="0" placeholder="Insira a quantidade de vagas totais" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Vagas já ocupadas:</label>
                                        <input type="number" class="form-input" id="occupied" name="occupied"
                                               title="Vagas ocupadas" min="0" placeholder="Insira a quantidade de vagas ocupadas" required>
                                    </div>
                                </div>

                                <div class="col-xl-12 text-right">
                                    <hr>
                                    <button type="button" class="btn-3 secondary-color">
                                        Resetar
                                    </button>
                                    <button type="submit" class="btn-3 primary">
                                        Cadastrar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 mt-5">
                            <img src="<?= url('themes/assets/img/map.svg') ?>" class="w-100">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php $v->start("scripts"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script>
    $('#form').on('submit',(function(e) {
        e.preventDefault();
        $("#loader-div").show();

        if ($('#inputHidden').children().length != 0) {
            let data = new FormData(this);
            $.ajax({
                type:'POST',
                url: "<?= $router->route("web.validateZone"); ?>",
                data:data,
                cache:false,
                contentType: false,
                processData: false,
                success:function(returnData){
                    $("#loader-div").hide();
                    if(returnData == 1){
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
                    } else {
                        swal({
                            icon: "error",
                            title: "Erro!",
                            text: "Não foi possível cadastrar a zona.",
                        });
                    }
                    console.log(returnData);
                },
                error: function(returnData){
                    $("#loader-div").hide();
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Não foi possível cadastrar a zona.",
                    });
                    console.log(returnData);
                }
            });
        } else {
            swal({
                icon: "warning",
                title: "Alerta!",
                text: "Você precisa desenhar uma zona no mapa."
            });
        }
    }));

    $('#localImage').change(function(e){
        var fileName = e.target.files[0].name;
        var ext = fileName.substr(fileName.lastIndexOf('.') + 1);
        if(ext === 'jpg' || ext === 'jpeg' || ext === 'png' || ext === 'JPG' || ext === 'JPEG' || ext === 'PNG'){
            if(e.target.files[0].size > 1133695){
                alert("Por favor, insira uma imagem com no máximo 1mb de tamanho.");
                $('#localImage').val('');
            }
        }else{
            alert("O tipo do anexo é inválido. Por favor, insira uma imagem em formato JPEG, JPG ou PNG.");
            $('#localImage').val('');
        }
    });

    $(document).ready(function () {
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
            subdomains:['mt0','mt1','mt2','mt3']
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

        map.on('draw:created', function (e) {
            let type = e.layerType;

            if(layer){
                drawnItems.removeLayer(layer);
            }

            layer = e.layer
            drawnItems.addLayer(layer);

            let points = e.layer.editing.latlngs[0][0];
            points = JSON.stringify(points);

            let inputHidden = "<input id='polygon' type='hidden' name='geojson' value='"+ points + "'>";
            $("#inputHidden").empty();
            $("#inputHidden").append(inputHidden);
        });

        map.on('draw:deleted', function (e) {
            $("#inputHidden").empty();
        })
    });
</script>
<?php $v->end(); ?>

