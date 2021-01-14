<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/layers-control.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/util.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>" />
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<?php $v->end(); ?>


<div class="container mt-5">
    <div class="row">
        <div class="col-xl-6 m-l-auto mb-5">
            <div class="web-div-box mt-5">
                <div class="divBoxInfo">
                    <img style="max-width: 100%; min-width: 100%;" src="<?= $zone->foto ?>">
                </div>
            </div>
        </div>

        <div class="col-xl-6 m-l-auto mt-5 mb-5">
            <div class="web-div-box">
                <div class="divBoxInfo">
                    <p style="font-weight: bold"><img src="<?= url('themes/assets/img/icone-zona.png') ?>"> Informações da Zona</p>
                    <hr style="margin-bottom: 0">
                    <div class="col-xl-12">
                        <div class="profile-div-data">
                            <img src="<?= url('themes/assets/img/icone-titulo.png') ?>">
                            Local: <?= $zone->nome ?>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="profile-div-data">
                            <img src="<?= url('themes/assets/img/icone-descricao.png') ?>">
                            Descrição: <?= $zone->detalhes ?>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="profile-div-data">
                            <img src="<?= url('themes/assets/img/icone-vagas.png') ?>">
                            Vagas totais: <?= $zone->limite_ambulantes ?>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="profile-div-data">
                            <img src="<?= url('themes/assets/img/icone-vagas.png') ?>">
                            Disponíveis: <?= $zone->limite_ambulantes - $zone->quantidade_ambulantes ?>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="profile-div-data">
                            <img src="<?= url('themes/assets/img/icone-vagas-2.png') ?>">
                            Ocupadas: <?= $zone->quantidade_ambulantes ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!$salesmans == NULL): ?>
            <div class="col-xl-12 mt-5 mb-5">
                <div class="web-div-box">
                    <div class="box-div-info">
                        <p style="font-weight: bold"><img src="<?= url('themes/assets/img/icone-barraca.png') ?>"> Ambulantes cadastrados na zona</p>
                        <hr style="margin-bottom: 0">
                        <div class="box-div-info-overflow-x">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">CPF</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Fone</th>
                                    <th scope="col">Email</th>
                                </tr>
                                </thead>
                                <tbody id="table-data">
                                <?php if($salesmans !== NULL):
                                    foreach ($salesmans as $salesman): ?>
                                        <tr onclick="openPage('<?= $salesman->id ?>')">
                                            <td><?= $salesman->identidade ?></td>
                                            <td><?= $salesman->nome ?></td>
                                            <td><?= $salesman->fone ?></td>
                                            <td><?= $salesman->email ?></td>
                                        </tr>
                                    <?php endforeach; endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-xl-12 mb-5">
            <div class="web-div-box mt-5">
                <div class="divBoxInfo">
                    <p style="font-weight: bold"><img src="<?= url('themes/assets/img/icone-zona.png') ?>"> Localização</p>
                    <hr style="margin-bottom: 0">
                    <div class="map-container">
                        <div id="mapZone"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script src="<?= url("themes/assets/js/leaflet.markercluster-src.js"); ?>"></script>
<script src="<?= url("themes/assets/js/Leaflet.LinearMeasurement.js"); ?>"></script>
<script>
    function openPage(data) {
        window.open("<?= url('salesman'); ?>/"+ data, '_blank');
    }

    $(function() {
        var map = null;
        var map_tiles = {};
        var ctr_tiles = {};
        var map_layers = {};
        var ctr_layers = {};

        map_tiles['Mapa Jawg'] = L.tileLayer('https://{s}.tile.jawg.io/jawg-light/{z}/{x}/{y}{r}.png?access-token=C1vu4LOmp14JjyXqidSlK8rjeSlLK1W59o1GAfoHVOpuc6YB8FSNyOyHdoz7QIk6', {
            maxNativeZoom: 19,
            maxZoom: 18,
            minZoom: 10
        });
        ctr_tiles["Mapa Jawg"] = map_tiles["Mapa Jawg"];

        map_tiles['Satelite'] = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxNativeZoom: 19,
            maxZoom: 20,
            minZoom: 10,
            subdomains:['mt0','mt1','mt2','mt3']
        });
        ctr_tiles["Satelite"] = map_tiles["Satelite"];

        map_layers["Zona"] = L.layerGroup();
        ctr_layers["Zona"] = map_layers["Zona"];

        map_layers["Centroide"] = L.layerGroup();
        ctr_layers["Centroide"] = map_layers["Centroide"];

        map = L.map('mapZone', {
            center: [<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>],
            layers: [map_tiles["Satelite"]],
            zoomControl: true,
            maxZoom: 18,
            minZoom: 10,
            zoom: 17
        });

        L.control.layers(ctr_tiles, ctr_layers).addTo(map);

        let paid = L.icon({
            iconUrl:"<?= url("themes/assets/img/marker-1.png"); ?>",
            shadowUrl:"<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize:[31, 40],
            shadowSize:[41, 41],
            iconAnchor:[15, 41],
            shadowAnchor:[13, 41],
            popupAnchor:[0, -41]
        });

        let pending = L.icon({
            iconUrl:"<?= url("themes/assets/img/marker-0.png"); ?>",
            shadowUrl:"<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize:[31, 40],
            shadowSize:[41, 41],
            iconAnchor:[15, 41],
            shadowAnchor:[13, 41],
            popupAnchor:[0, -41]
        });

        let expired = L.icon({
            iconUrl:"<?= url("themes/assets/img/marker-2.png"); ?>",
            shadowUrl:"<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize:[31, 40],
            shadowSize:[41, 41],
            iconAnchor:[15, 41],
            shadowAnchor:[13, 41],
            popupAnchor:[0, -41]
        });

        map.on('overlayadd', function(e) {
            var groupMarker = new L.MarkerClusterGroup({
                disableClusteringAtZoom: 14,
                showCoverageOnHover: true,
                zoomToBoundsOnClick: true,
                spiderfyOnMaxZoom: true
            });

            if(e.name === "Centroide"){
                <?php
                $aux = $zone->limite_ambulantes - $zone->quantidade_ambulantes;
                $aux = intval(($zone->quantidade_ambulantes * 100)/$zone->limite_ambulantes);
                if($aux <= 49): ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>],{icon:paid}).addTo(groupMarker);
                <?php elseif ($aux >= 50 && $aux <= 99): ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>],{icon:pending}).addTo(groupMarker);
                <?php else: ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>],{icon:expired}).addTo(groupMarker);
                <?php endif; ?>
            }else if(e.name === "Zona"){
                let area = [];
                let aux = [];

                area = JSON.parse('<?php echo(json_encode($zone->poligono)); ?>');

                area.forEach(function (e) {
                    aux.push([e[1], e[0]]);
                });

                <?php
                $aux = $zone->limite_ambulantes - $zone->quantidade_ambulantes;
                $aux = intval(($zone->quantidade_ambulantes * 100)/$zone->limite_ambulantes);
                if($aux <= 49):
                    $color = "#5ea9a4";
                elseif ($aux >= 50 && $aux <= 99):
                    $color = "#f5a42c";
                else:
                    $color = "#ed2e54";
                endif; ?>
                L.polygon(aux,{ color: '<?= $color; ?>', fillColor: '<?= $color; ?>' }).bindPopup('<div style="text-align: center;">Zona</div>').addTo(map_layers[e.name]);
            }
            map_layers[e.name].addLayer(groupMarker);
        });

        map.addLayer(map_layers["Zona"]);
    });
</script>
<?php $v->end(); ?>
