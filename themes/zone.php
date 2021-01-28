<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/layers-control.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/util.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<?php $v->end(); ?>

<div class="container-fluid mt-5 container-white">
    <div class="row">
        <div class="col-xl-3 p-0" id="sidebar-inter">
            <div class="">
                <div class="">
                    <img class="w-100" src="<?= url('themes/assets/img/praca_teste.jpg'); ?>">
                </div>
            </div>
            <div class="m-5 text-center">
                <h3 class="black-title-section font-weight-normal"><?= $zone->nome; ?></h3>

                <p class="subtitle-section-p-black pl-2 pr-2"><?= $zone->descricao ?></p>
            </div>

            <div class="pl-5 pr-5">
                <div class="row div-gray-bg p-3">
                    <div class="col-xl-8">
                        <h5 class="title-section">
                            <img class="mr-2" src="<?= url('themes/assets/img/mapMarker.png') ?>">
                            Vagas Totais
                        </h5>
                    </div>
                    <div class="col-xl-4">
                        <h4 class="text-center">
                            <?= $zone->limite_ambulantes; ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="pl-5 pr-5 mt-3">
                <div class="row div-gray-bg p-3">
                    <div class="col-xl-8">
                        <h5 class="title-section">
                            <img class="mr-2" src="<?= url('themes/assets/img/open.png') ?>">
                            Disponíveis
                        </h5>
                    </div>
                    <div class="col-xl-4">
                        <h4 class="text-center">
                            <?= ($zone->limite_ambulantes - $zone->quantidade_ambulantes); ?>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="pl-5 pr-5 mt-3">
                <div class="row div-gray-bg p-3">
                    <div class="col-xl-8">
                        <h5 class="title-section ">
                            <img class="mr-2" src="<?= url('themes/assets/img/flag.png') ?>">
                            Ocupadas
                        </h5>
                    </div>
                    <div class="col-xl-4">
                        <h4 class="text-center">
                            <?= $zone->quantidade_ambulantes; ?>
                        </h4>
                    </div>
                </div>
            </div>
            
            <hr>
        </div>
        <div class="col-xl-9 p-0">
            <div class="map-container">
                <div id="mapZone" class="<?= $salesmans ? 'mapInfo' : 'mapZone'; ?>"></div>
            </div>
            <?php if ($salesmans): ?>
                <div class="col-xl-12 p-5">
                    <div class="web-div-box">
                        <div class="box-div-info">
                            <h4 class="black-title-section font-weight-normal">Ambulantes Cadastrados na Zona</h4>
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
                                    <?php if ($salesmans !== NULL):
                                        foreach ($salesmans as $salesman): ?>
                                            <tr onclick="openPage('<?= $salesman->id ?>')">
                                                <td><?= $salesman->cpf ?></td>
                                                <td><?= $salesman->nome ?></td>
                                                <td><?= $salesman->telefone ?></td>
                                                <td><?= $salesman->email ?></td>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script src="<?= url("themes/assets/js/leaflet.markercluster-src.js"); ?>"></script>
<script src="<?= url("themes/assets/js/Leaflet.LinearMeasurement.js"); ?>"></script>
<script>
    function openPage(data) {
        window.open("<?= $router->route('web.salesman'); ?>/"+ data, '_blank');
    }

    $(function () {
        var map = null;
        var map_tiles = {};
        var ctr_tiles = {};
        var map_layers = {};
        var ctr_layers = {};

        map_tiles['Mapa Jawg'] = L.tileLayer('https://{s}.tile.jawg.io/jawg-light/{z}/{x}/{y}{r}.png?access-token=C1vu4LOmp14JjyXqidSlK8rjeSlLK1W59o1GAfoHVOpuc6YB8FSNyOyHdoz7QIk6', {
            maxNativeZoom: 20,
            maxZoom: 20,
            minZoom: 10
        });
        ctr_tiles["Mapa Jawg"] = map_tiles["Mapa Jawg"];

        map_tiles['Satelite'] = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxNativeZoom: 20,
            maxZoom: 20,
            minZoom: 10,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });
        ctr_tiles["Satelite"] = map_tiles["Satelite"];

        map_layers["Zona"] = L.layerGroup();
        ctr_layers["Zona"] = map_layers["Zona"];

        map_layers["Centroide"] = L.layerGroup();
        ctr_layers["Centroide"] = map_layers["Centroide"];

        map = L.map('mapZone', {
            center: [<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>],
            layers: [map_tiles["Mapa Jawg"]],
            zoomControl: true,
            maxZoom: 20,
            minZoom: 10,
            zoom: 18
        });

        L.control.layers(ctr_tiles, ctr_layers).addTo(map);

        let paid = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-1.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        let pending = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-0.png"); ?>",
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

        map.on('overlayadd', function (e) {
            var groupMarker = new L.MarkerClusterGroup({
                disableClusteringAtZoom: 14,
                showCoverageOnHover: true,
                zoomToBoundsOnClick: true,
                spiderfyOnMaxZoom: true
            });

            if (e.name === "Centroide") {
                <?php
                $aux = $zone->limite_ambulantes - $zone->quantidade_ambulantes;
                $aux = intval(($zone->quantidade_ambulantes * 100) / $zone->limite_ambulantes);
                if($aux <= 49): ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>], {icon: paid}).addTo(groupMarker);
                <?php elseif ($aux >= 50 && $aux <= 99): ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>], {icon: pending}).addTo(groupMarker);
                <?php else: ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>], {icon: expired}).addTo(groupMarker);
                <?php endif; ?>
            } else if (e.name === "Zona") {
                let area = [];
                let aux = [];

                area = JSON.parse('<?php echo(json_encode($zone->poligono)); ?>');

                area.forEach(function (e) {
                    aux.push([e[1], e[0]]);
                });

                <?php
                $aux = $zone->limite_ambulantes - $zone->quantidade_ambulantes;
                $aux = intval(($zone->quantidade_ambulantes * 100) / $zone->limite_ambulantes);
                if ($aux <= 49):
                    $color = "#5ea9a4";
                elseif ($aux >= 50 && $aux <= 99):
                    $color = "#f5a42c";
                else:
                    $color = "#ed2e54";
                endif; ?>
                L.polygon(aux, {
                    color: '<?= $color; ?>',
                    fillColor: '<?= $color; ?>'
                }).bindPopup('<div style="text-align: center;">Zona</div>').addTo(map_layers[e.name]);
            }
            map_layers[e.name].addLayer(groupMarker);
        });

        map.addLayer(map_layers["Zona"]);
    });
</script>
<?php $v->end(); ?>
