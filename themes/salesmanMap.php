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
        let map = null;
        let mapTiles = {};
        let ctrTiles = {};
        let mapLayers = {};
        let ctrLayers = {};
        let request = false;
        let users = [];

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

        mapTiles['Satelite 2'] = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxNativeZoom: 19,
            maxZoom: 20,
            minZoom: 10
        });
        ctrTiles["Satelite 2"] = mapTiles["Satelite 2"];

        <?php if($zones): ?>
            mapLayers["Áreas"] = L.layerGroup();
            ctrLayers["Áreas"] = mapLayers["Áreas"];

            mapLayers["Desenho das Áreas"] = L.layerGroup();
            ctrLayers["Desenho das Áreas"] = mapLayers["Desenho das Áreas"];
        <?php endif; ?>

        mapLayers["Bairros"] = L.layerGroup();
        ctrLayers["Bairros"] = mapLayers["Bairros"];

        mapLayers["Licença de Ambulante - Em dia"] = L.layerGroup();
        ctrLayers["Licença de Ambulante - Em dia"] = mapLayers["Licença de Ambulante - Em dia"];

        mapLayers["Licença de Ambulante - Pendentes"] = L.layerGroup();
        ctrLayers["Licença de Ambulante - Pendentes"] = mapLayers["Licença de Ambulante - Pendentes"];

        mapLayers["Licença de Ambulante - Vencidos"] = L.layerGroup();
        ctrLayers["Licença de Ambulante - Vencidos"] = mapLayers["Licença de Ambulante - Vencidos"];

        map = L.map('salesmanMap', {
            center: [-9.663136558749533, -35.71422457695007],
            layers: [mapTiles["Mapa Jawg"]],
            zoomControl: false,
            maxZoom: 20,
            minZoom: 10,
            zoom: 14
        });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        let userMarker = L.icon({
            iconUrl: "<?= url("themes/assets/img/userMarker.png"); ?>",
            shadowUrl: "<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize: [31, 40],
            shadowSize: [41, 41],
            iconAnchor: [15, 41],
            shadowAnchor: [13, 41],
            popupAnchor: [0, -41]
        });

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
        geolocation();

        map.on('overlayadd', function (e) {
            let groupMarker = new L.MarkerClusterGroup({
                disableClusteringAtZoom: 14,
                showCoverageOnHover: true,
                zoomToBoundsOnClick: true,
                spiderfyOnMaxZoom: true
            });
            
            if (e.name == "Licença de Ambulante - Em dia") {
                <?php if($paids):
                foreach ($paids as $paid):
                if ($paid->status == 1): ?>
                L.marker([<?= $paid->latitude ?>, <?= $paid->longitude ?>], {icon: paid})
                    .bindPopup('' +
                        '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                        'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                        '</div><div class="zoneInfo"><?= $paid->cpf ?><br>' +
                        '<br><?= $paid->nome ?><br><br><?= $paid->telefone ?>' +
                        '<div class="textPopup mt-4"><a href="<?= url('licenseInfo/1/') . $paid->id_licenca ?>">' +
                        'Visualizar</a></div></div></div></div>').addTo(groupMarker);
                <?php endif; endforeach; endif; ?>
            } else if (e.name == "Licença de Ambulante - Pendentes") {
                <?php if($pendings):
                foreach ($pendings as $pending):
                if($pending->status == 0 || $pending->status == 3): ?>
                L.marker([<?= $pending->latitude ?>, <?= $pending->longitude ?>], {icon: pending})
                    .bindPopup('' +
                        '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                        'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                        '</div><div class="zoneInfo"><?= $pending->cpf ?><br>' +
                        '<br><?= $pending->nome ?><br><br><?= $pending->telefone ?>' +
                        '<div class="textPopup mt-4"><a href="<?= url('licenseInfo/1/') . $pending->id_licenca ?>">' +
                        'Visualizar</a></div></div></div></div>').addTo(groupMarker);
                <?php endif; endforeach; endif; ?>
            } else if (e.name == "Licença de Ambulante - Vencidos") {
                <?php if($expireds):
                foreach ($expireds as $expired):
                if ($expired->status == 2): ?>
                L.marker([<?= $expired->latitude ?>, <?= $expired->longitude ?>], {icon: expired})
                    .bindPopup('' +
                        '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                        'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                        '</div><div class="zoneInfo"><?= $expired->cpf ?><br>' +
                        '<br><?= $expired->nome ?><br><br><?= $expired->telefone ?>' +
                        '<div class="textPopup mt-4"><a href="<?= url('licenseInfo/1/') . $expired->id_licenca ?>">' +
                        'Visualizar</a></div></div></div></div>').addTo(groupMarker);
                <?php endif; endforeach; endif; ?>
            } else if (e.name == "Desenho das Áreas") {
                let area = [];
                let aux = [];
                <?php if($zones):
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
                }).bindPopup('<div class="textPopup">Local: <?= $zone->nome ?> </div> <br> <div class="zoneInfo">Vagas: <?= $zone->limite_ambulantes ?> <br> Ocupadas: <?= $zone->quantidade_ambulantes ?> <br> Disponíveis: <?= $zone->limite_ambulantes - $zone->quantidade_ambulantes ?><br> Fixas: <?= $zone->vagas_fixas ?></div> <br> <div class="textPopup"> <a href="<?= url("zone/" . md5($zone->id)) ?>" target="_blank">Mais informações</a></div>').addTo(mapLayers[e.name]);
                aux = [];

                <?php endforeach;
                endif;?>
            } else if (e.name == "Bairros") {
                if (request === false) {
                    $("#loader-div").show();
                    $.ajax({
                        type: "POST",
                        url: "<?= url("neighborhoodPolygon") ?>",
                        cache: false,
                        contentType: false,
                        processData: false,
                    }).done(function (returnData) {
                        const neighborhoods = JSON.parse(returnData);

                        neighborhoods.forEach(function (neighborhood) {
                            let aux = [];
                            for (let i = 0; i < neighborhood.polygon.length; i++) {
                                aux.push([neighborhood.polygon[i][0], neighborhood.polygon[i][1]]);
                            }

                            <?php if ($_SESSION['user']['login'] === 3): ?>
                            L.polygon(aux, {
                                color: "#4bc2ce",
                                fillColor: "#4bc2ce"
                            }).bindPopup(
                                '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                                'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                                '</div><div class="zoneInfo mt-4">' + neighborhood.name + '<br>' +
                                '<br><div class="textPopup"><a href="<?= url('neighborhood') ?>/' + neighborhood.id + '">Visualizar</a></div></div></div></div>'
                            ).addTo(mapLayers[e.name]);
                            <?php else: ?>
                            L.polygon(aux, {
                                color: "#4bc2ce",
                                fillColor: "#4bc2ce"
                            }).bindPopup(
                                '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                                'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                                '</div><div class="zoneInfo mt-4">' + neighborhood.name + '<br>' +
                                '<br></div></div></div>'
                            ).addTo(mapLayers[e.name]);
                            <?php endif; ?>
                        });
                        request = true;
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
                }
            } else {
                <?php if($zones !== NULL): foreach ($zones as $zone) :
                $aux = $zone->limite_ambulantes - $zone->quantidade_ambulantes;
                $aux = intval(($zone->quantidade_ambulantes * 100) / $zone->limite_ambulantes);
                if($aux <= 49): ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>], {icon: zone_green})
                    .bindPopup('' +
                        '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                        'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                        '<?= $zone->nome ?></div><div class="zoneInfo"><?= $zone->limite_ambulantes ?> vagas<br>' +
                        '<?= $zone->quantidade_ambulantes ?> ocupadas<br>' +
                        '<?= $zone->limite_ambulantes - $zone->quantidade_ambulantes ?> disponíveis</div><br>' +
                        '<div class="textPopup"><a href="<?= url("zone/" . md5($zone->id)) ?>" target="_blank">' +
                        'Mais informações</a></div></div></div></div>').addTo(groupMarker);
                <?php elseif ($aux = 50 && $aux <= 99): ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>], {icon: zone_yellow})
                    .bindPopup('' +
                        '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                        'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                        '<?= $zone->nome ?></div><div class="zoneInfo"><?= $zone->limite_ambulantes ?> vagas<br>' +
                        '<?= $zone->quantidade_ambulantes ?> ocupadas<br>' +
                        '<?= $zone->limite_ambulantes - $zone->quantidade_ambulantes ?> disponíveis</div><br>' +
                        '<div class="textPopup"><a href="<?= url("zone/" . md5($zone->id)) ?>" target="_blank">' +
                        'Mais informações</a></div></div></div></div>').addTo(groupMarker);
                <?php else: ?>
                L.marker([<?= $zone->centroide[1] ?>, <?= $zone->centroide[0] ?>], {icon: zone_red})
                    .bindPopup('' +
                        '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                        'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                        '<?= $zone->nome ?></div><div class="zoneInfo"><?= $zone->limite_ambulantes ?> vagas<br>' +
                        '<?= $zone->quantidade_ambulantes ?> ocupadas<br>' +
                        '<?= $zone->limite_ambulantes - $zone->quantidade_ambulantes ?> disponíveis</div><br>' +
                        '<div class="textPopup"><a href="<?= url("zone/" . md5($zone->id)) ?>" target="_blank">' +
                        'Mais informações</a></div></div></div></div>').addTo(groupMarker);
                <?php endif;
                endforeach; endif; ?>
            }


            mapLayers[e.name].addLayer(groupMarker);

            map.addLayer(mapLayers);
        });

        function geolocation() {

            setInterval(function loarMarkers() {
                geolocations.forEach(element => {
                    let user = {};
                    let aux = 0;

                    users.forEach(marker => {
                        if (marker.id == element.user.id) {
                            map.removeLayer(marker.marker);
                        }

                        delete users[aux];
                        aux++;
                    });

                    user = {
                        marker: L.marker([element.lat, element.lng], {icon: userMarker}).bindPopup(element.user.nome).addTo(map),
                        id: element.user.id
                    };
                    users.push(user);
                });
            }, 3000);
        }
    });
</script>
<?php $v->end(); ?>
