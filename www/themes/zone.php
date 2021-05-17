<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/layers-control.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/util.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<?php $v->end(); ?>

<?php
$aux = $zone->limite_ambulantes - $zone->quantidade_ambulantes;
$aux = intval(($zone->quantidade_ambulantes * 100) / $zone->limite_ambulantes);
if ($aux <= 49):
    $color = "#4bc2ce";
    $backgroundClass = "background-green";
elseif ($aux >= 50 && $aux <= 99):
    $color = "#ffbc00";
    $backgroundClass = "background-yellow";

else:
    $color = "#f05977";
    $backgroundClass = "background-red";

endif; ?>

<div class="container-fluid mt-5 container-white">
    <div class="row">
        <div class="col-md-8 p-0">
            <div class="map-container">
                <div id="mapZone" class="<?= $salesmans ? 'mapInfo' : 'mapZone'; ?>"></div>
            </div>
            <?php if ($salesmans): ?>
                <div class="col-xl-12">
                    <div class="web-div-box">
                        <div class="box-div-info ml-3">
                            <h4 class="black-div-zone-info-text font-weight-normal">Lista de Ambulantes</h4>
                            <p class="subdiv-zone-info-text-p">Todos os ambulantes cadastrados na Zona</p>
                            <hr style="margin-bottom: 0">
                            <div class="box-div-info-overflow-x">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="table-col-5">CPF <span
                                                    class="icon-arrow_downward"></span></th>
                                        <th scope="col" class="table-col-5">Nome <span class="icon-arrow_upward"></span>
                                        </th>
                                        <th scope="col" class="table-col-5">Telefone <span
                                                    class="icon-arrow_downward"></span></th>
                                        <th scope="col" class="table-col-5">Email <span
                                                    class="icon-arrow_downward"></span></th>
                                        <th scope="col" class="table-col-4">Status <span
                                                    class="icon-arrow_downward"></span></th>
                                    </tr>
                                    </thead>
                                    <tbody id="table-data">
                                    <?php foreach ($salesmans as $salesman):
                                        switch ($salesman->situacao):
                                            case 0:
                                                $trClass = 'border-left-yellow';
                                                break;
                                            case 1:
                                                $trClass = 'border-left-green';
                                                break;
                                            default:
                                                $trClass = 'border-left-red';
                                                break;
                                        endswitch; ?>
                                        <tr class="<?= $trClass ?>">
                                            <td><?= $salesman->cpf ?></td>
                                            <td><?= $salesman->nome ?></td>
                                            <td><?= $salesman->telefone ?></td>
                                            <td><?= $salesman->email ?></td>
                                            <td>
                                                <?php switch ($salesman->situacao):
                                                case 0: ?>
                                                <div class="status-button tertiary">Pendente</div>
                                            </td>
                                            <?php break;
                                            case 1: ?>
                                                <div class="status-button primary">Ativo</div>
                                                </td>
                                                <?php break;
                                            default: ?>
                                                <div class="status-button secondary">Bloqueado</div>
                                                </td>
                                                <?php break; endswitch; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4 p-0" id="sidebar-inter">
            <iframe src="https://www.google.com/maps/embed?pb=!4v1617115456065!6m8!1m7!1sh2uwEPd3HSSwcRMfhM_hRQ!2m2!1d<?= $zone->centroide[1] ?>!2d<?= $zone->centroide[0] ?>!3f343.7!4f3.739999999999995!5f0.7820865974627469" width="403" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            <div class="m-5">
                <h4 class="black-div-zone-info-text font-weight-normal"><?= $zone->nome; ?></h4>
                <p class="subdiv-zone-info-text-p-black"><?= $zone->descricao ?></p>
                <hr>
            </div>

            <div class="pl-5 pr-5">
                <div class="d-flex div-zone-info-data">
                    <div class="p-3 text-center div-circle <?= $backgroundClass ?>">
                        <span class="icon-all_inclusive zone-info-icons"></span>
                    </div>
                    <div class="ml-3">
                        <h5 class="m-0 mt-4 div-zone-info-text">
                            <span class="font-weight-normal">Vagas totais:</span>
                            <?= $zone->limite_ambulantes; ?>
                        </h5>
                    </div>
                </div>

                <div class="d-flex div-zone-info-data mt-4">
                    <div class="p-3 text-center div-circle <?= $backgroundClass ?>">
                        <span class="icon-lock_open zone-info-icons"></span>
                    </div>
                    <div class="ml-3">
                        <h5 class="m-0 mt-4 div-zone-info-text">
                            <span class="font-weight-normal">Vagas disponíveis:</span>
                            <?= ($zone->limite_ambulantes - $zone->quantidade_ambulantes); ?>
                        </h5>
                    </div>
                </div>

                <div class="d-flex div-zone-info-data mt-4">
                    <div class="p-3 text-center div-circle <?= $backgroundClass ?>">
                        <span class="icon-flag zone-info-icons"></span>
                    </div>
                    <div class="ml-3">
                        <h5 class="m-0 mt-4 div-zone-info-text">
                            <span class="font-weight-normal">Vagas ocupadas:</span>
                            <?= $zone->quantidade_ambulantes; ?>
                        </h5>
                    </div>
                </div>

                <div class="d-flex div-zone-info-data mt-4">
                    <div class="p-3 text-center div-circle <?= $backgroundClass ?>">
                        <span class="icon-map-pin zone-info-icons"></span>
                    </div>
                    <div class="ml-3">
                        <h5 class="m-0 mt-4 div-zone-info-text">
                            <span class="font-weight-normal">Vagas fixas:</span>
                            <?= $zone->vagas_fixas; ?>
                        </h5>
                    </div>
                    <a href="<?= url('editFixedZones/'). md5($zone->id) ?>" class="float-right ml-5 mt-4">
                        <span class="icon-edit"></span>
                        Editar
                    </a>
                </div>
                <hr>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script src="<?= url("themes/assets/js/leaflet.markercluster-src.js"); ?>"></script>
<script src="<?= url("themes/assets/js/Leaflet.LinearMeasurement.js"); ?>"></script>
<script>
    function openPage(data) {
        window.open("<?= $router->route('web.salesman'); ?>/" + data, '_blank');
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
            zoom: 20
        });

        L.control.layers(ctr_tiles, ctr_layers).addTo(map);

        let paid = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-zone-green.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        let pending = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-zone-yellow.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        let expired = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-zone-red.png"); ?>",
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
