<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/util.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<?php $v->end(); ?>

<div class="row">
    <div class="col-12">
        <div class="divMap">
            <div id="salesmanMap"></div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script src="<?= url("themes/assets/js/leaflet.markercluster-src.js"); ?>"></script>
<script>
    $(function () {
        var map = null;
        var mapTiles = {};
        var ctrTiles = {};
        var mapLayers = {};
        var ctrLayers = {};

        mapTiles['Mapa Jawg'] = L.tileLayer('https://{s}.tile.jawg.io/jawg-light/{z}/{x}/{y}{r}.png?access-token=C1vu4LOmp14JjyXqidSlK8rjeSlLK1W59o1GAfoHVOpuc6YB8FSNyOyHdoz7QIk6', {
            maxNativeZoom: 19,
            maxZoom: 20,
            minZoom: 10
        });
        ctrTiles["Mapa Jawg"] = mapTiles["Mapa Jawg"];

        mapTiles['Mapa OSM'] = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxNativeZoom: 19,
            maxZoom: 20,
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

        mapLayers["Área das zonas"] = L.layerGroup();
        ctrLayers["Área das zonas"] = mapLayers["Área das zonas"];

        mapLayers["Zonas"] = L.layerGroup();
        ctrLayers["Zonas"] = mapLayers["Zonas"];

        <?php if($salesmans !== NULL): ?>
        mapLayers["Ambulantes - Em dia"] = L.layerGroup();
        ctrLayers["Ambulantes - Em dia"] = mapLayers["Ambulantes - Em dia"];

        mapLayers["Ambulantes - Pendentes"] = L.layerGroup();
        ctrLayers["Ambulantes - Pendentes"] = mapLayers["Ambulantes - Pendentes"];

        mapLayers["Ambulantes - Vencidos"] = L.layerGroup();
        ctrLayers["Ambulantes - Vencidos"] = mapLayers["Ambulantes - Vencidos"];

        // mapLayers["Denúncias"] = L.layerGroup();
        // ctrLayers["Denúncias"] = mapLayers["Denúncias"];
        <?php endif; ?>

        map = L.map('salesmanMap', {
            center: [-9.663136558749533, -35.71422457695007],
            layers: [mapTiles["Mapa Jawg"]],
            zoomControl: false,
            maxZoom: 20,
            minZoom: 10,
            zoom: 14
        });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        let pending = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-user-yellow.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        let paid = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-user-green.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        let expired = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-user-red.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        let zone_green = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-zone-green.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        let zone_yellow = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-zone-yellow.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        let zone_red = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-zone-red.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

        map.invalidateSize(true);

        map.on('overlayadd', function (e) {
            var groupMarker = new L.MarkerClusterGroup({
                disableClusteringAtZoom: 14,
                showCoverageOnHover: true,
                zoomToBoundsOnClick: true,
                spiderfyOnMaxZoom: true
            });

            if (e.name == "Ambulantes - Em dia") {
                <?php if($salesmans):
                foreach ($salesmans as $salesman):
                    if($salesman->status == 0 || $salesman->status == 3): ?>
                        L.marker([<?= $salesman->latitude ?>, <?= $salesman->longitude ?>], {icon: pending})
                         .bindPopup('Ambulante').addTo(groupMarker);
                <?php endif; endforeach; endif; ?>
            } else if (e.name == "Ambulantes - Pendentes") {
                <?php if($salesmans):
                foreach ($salesmans as $salesman):
                    if ($salesman->status == 1): ?>
                        L.marker([<?= $salesman->latitude ?>, <?= $salesman->longitude ?>], {icon: paid})
                            .bindPopup('Ambulante').addTo(groupMarker);
                <?php endif; endforeach; endif; ?>
            } else if (e.name == "Ambulantes - Vencidos") {
                <?php if($salesmans):
                foreach ($salesmans as $salesman):
                    if ($salesman->status == 2): ?>
                        L.marker([<?= $salesman->latitude ?>, <?= $salesman->longitude ?>], {icon: expired})
                            .bindPopup('Ambulante').addTo(groupMarker);
                <?php endif; endforeach; endif; ?>
            } else if (e.name == "Área das zonas"){
                let area = [];
                let aux = [];
                <?php if($zones != NULL):
                foreach ($zones as $zone):
                $aux = $zone->limite_ambulantes - $zone->quantidade_ambulantes;
                $aux = intval(($zone->quantidade_ambulantes * 100) / $zone->limite_ambulantes);
                if ($aux <= 49):
                    $color = "#5ea9a4";
                elseif ($aux >= 50 && $aux <= 99):
                    $color = "#f5a42c";
                else:
                    $color = "#ed2e54";
                endif;
                ?>
                area = JSON.parse('<?= json_encode($zone->poligono) ?>');

                area.forEach(function (e) {
                    aux.push([e[1], e[0]]);
                });

                L.polygon(aux, {
                    color: '<?= $color ?>',
                    fillColor: '<?= $color ?>'
                }).bindPopup('<div class="textPopup">Local: <?= $zone->nome ?> </div> <br> <div class="zoneInfo">Vagas: <?= $zone->limite_ambulantes ?> <br> Ocupadas: <?= $zone->quantidade_ambulantes ?> <br> Disponíveis: <?= $zone->limite_ambulantes - $zone->quantidade_ambulantes ?> </div> <br> <div class="textPopup"> <a href="<?= url("zone/" . $zone->id) ?>" target="_blank">Mais informações</a></div>').addTo(mapLayers[e.name]);
                aux = [];

                <?php endforeach;
                endif;?>
            } else {
                <?php if($zones !== NULL): foreach ($zones as $zone) :
                $aux = $zone->limite_ambulantes - $zone->quantidade_ambulantes;
                $aux = intval(($zone->quantidade_ambulantes * 100) / $zone->limite_ambulantes);
                if($aux <= 49): ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>], {icon: zone_green})
                    .bindPopup('' +
                        '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                        'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                        '<?= $zone->nome ?><hr style="margin: 10px 0;"></div><div class="zoneInfo"><?= $zone->limite_ambulantes ?> vagas<br>' +
                        '<?= $zone->quantidade_ambulantes ?> ocupadas<br>' +
                        '<?= $zone->limite_ambulantes - $zone->quantidade_ambulantes ?> disponíveis</div><br>' +
                        '<div class="textPopup"><a href="<?= url("zone/" . $zone->id) ?>" target="_blank">' +
                        'Mais informações</a></div></div></div></div>').addTo(groupMarker);
                <?php elseif ($aux = 50 && $aux <= 99): ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>], {icon: zone_yellow})
                    .bindPopup('' +
                        '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                        'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                        '<?= $zone->nome ?><hr style="margin: 10px 0;"></div><div class="zoneInfo"><?= $zone->limite_ambulantes ?> vagas<br>' +
                        '<?= $zone->quantidade_ambulantes ?> ocupadas<br>' +
                        '<?= $zone->limite_ambulantes - $zone->quantidade_ambulantes ?> disponíveis</div><br>' +
                        '<div class="textPopup"><a href="<?= url("zone/" . $zone->id) ?>" target="_blank">' +
                        'Mais informações</a></div></div></div></div>').addTo(groupMarker);
                <?php else: ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>], {icon: zone_red})
                    .bindPopup('' +
                        '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                        'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                        '<?= $zone->nome ?><hr style="margin: 10px 0;"></div><div class="zoneInfo"><?= $zone->limite_ambulantes ?> vagas<br>' +
                        '<?= $zone->quantidade_ambulantes ?> ocupadas<br>' +
                        '<?= $zone->limite_ambulantes - $zone->quantidade_ambulantes ?> disponíveis</div><br>' +
                        '<div class="textPopup"><a href="<?= url("zone/" . $zone->id) ?>" target="_blank">' +
                        'Mais informações</a></div></div></div></div>').addTo(groupMarker);
                <?php endif;
                endforeach; endif; ?>
            }

            mapLayers[e.name].addLayer(groupMarker);

            map.addLayer(e.name);
        });
    });
</script>
<?php $v->end(); ?>
