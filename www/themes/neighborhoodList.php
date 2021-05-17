<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<?php $v->end(); ?>

<div class="container-fluid mt-5 container-white border-bottom-gray">
    <div class="row">
        <div class="col-xl-12 mb-5 p-5">
            <h3 class="ml-3 title-section">Lista de bairros</h3>
            <p class="ml-3 subtitle-section-p">Listagem dos ambulantes por bairros</p>
            <hr>

            <div class="row">
                <div class="col-xl-6 mt-5">
                    <div class="div-gray-bg border-top-green p-5">
                        <h4 class="black-title-section">Bairros cadastrados</h4>
                        <hr>
                        <div style="height: 50vh; overflow-y: auto;">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Ambulantes</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody id="neighborhoodList">
                                <?php if ($neighborhoods): $aux = 1;
                                    foreach ($neighborhoods as $neighborhood): ?>
                                        <tr>
                                            <td><?= $aux; ?></td>
                                            <td><?= $neighborhood->nome; ?></td>
                                            <td><?= $neighborhood->quantidade_ambulantes; ?></td>
                                            <td>
                                                <span class="icon-eye" onclick="openNeighborhood('<?= md5($neighborhood->id) ?>')"></span>
                                                <span class="icon-search ml-3" onclick="findNeighborhood('<?= md5($neighborhood->id) ?>')"></span>
                                                <span class="icon-download ml-3" onclick="exportNeighborhood('<?= md5($neighborhood->id) ?>')"></span>
                                            </td>
                                        </tr>
                                        <?php $aux++; endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 mt-5">
                    <div class="div-gray-bg border-top-green p-5">
                        <h4 class="black-title-section">Ambulantes da região</h4>
                        <hr>
                        <div style="height: 50vh; overflow-y: auto;">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cpf</th>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                </tr>
                                </thead>
                                <tbody id="salesmanTable">
                                </tbody>
                            </table>
                            <div id="imageSection">
                                <div class="text-center">
                                    <img class="mt-5 w-50" src="<?= url('themes/assets/img/empty.svg') ?>">
                                    <p class="mt-5 subtitle-section-p">Selecione um bairro ao lado</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 mt-5">
                    <div class="div-gray-bg border-top-green p-5">
                        <div id="neighborhoodMap"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>
<script>
    let map = null;
    let mapTiles = {};
    let ctrTiles = {};
    let ctrLayers = {};
    let polygon = {};
    let markers = [];

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

    $(function () {
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

        map = L.map('neighborhoodMap', {
            center: [-9.663136558749533, -35.71422457695007],
            layers: [mapTiles["Mapa Jawg"]],
            zoomControl: false,
            maxZoom: 20,
            minZoom: 10,
            zoom: 14
        });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        map.invalidateSize(true);
    });

    function findNeighborhood(value) {
        $("#loader-div").show();

        const data = {'id': value};

        $.ajax({
            type: "POST",
            url: "<?= url("findNeighborhood") ?>",
            data: data
        }).done(function (returnData) {
            const response = JSON.parse(returnData)[0];
            let count = 1;
            let aux = [];

            for (let i = 0; i < response.coordinates.length; i++) {
                aux.push([response.coordinates[i][0], response.coordinates[i][1]]);
            }

            map.setView(new L.LatLng(response.centroid[0], response.centroid[1]));

            if (polygon !== undefined) {
                map.removeLayer(polygon);
            }

            if (markers.length > 0) {
                markers.forEach((marker) => {
                    map.removeLayer(marker);
                })
            }

            polygon = L.polygon(aux, {
                color: "#4bc2ce",
                fillColor: "#4bc2ce"
            }).bindPopup(
                '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                '</div><div class="zoneInfo mt-4">'+ response.neighborhoodName +'<br>' +
                '<br><div class="textPopup"><a target="_blank" href="<?= url('neighborhood') ?>/'+ response.neighborhoodId +'">' +
                'Visualizar</a></div></div></div></div>'
            ).addTo(map);

            $('#salesmanTable').empty();
            $('#imageSection').empty();

            if (response.salesmans.length > 0) {
                response.salesmans.forEach(function (salesman) {
                    $('#salesmanTable').append(
                        '<tr onclick="openLicense(\'' + salesman.licenseId + '\')"><td>' + count + '</td><td>' + salesman.identity + '</td><td>' + salesman.name + '</td><td>' + salesman.phone + '</td></tr>'
                    );
                    let marker = L.icon();

                    switch (salesman.status) {
                        case '0':
                            marker = pending;
                            break;
                        case '1':
                            marker = paid;
                            break;
                        default:
                            marker = expired;
                            break;
                    }

                    let theMarker = {};
                    theMarker = L.marker([salesman.lat, salesman.lng], {icon: marker}).bindPopup(
                        '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
                        'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
                        '</div><div class="zoneInfo mt-4">' + count + ' - ' + salesman.name + '<br>' +
                        '<br><div class="textPopup"><a target="_blank" href="<?= url('licenseInfo/1/') ?>/' + salesman.licenseId + '"> ' +
                        'Visualizar</a></div></div></div></div>'
                    ).addTo(map);

                    markers.push(theMarker);
                    count++;
                });
            } else {
                $('#imageSection').append(
                    '<div class="text-center"><img class="mt-5 w-50" src="<?= url('themes/assets/img/empty-list.svg') ?>">' +
                '<p class="mt-5 subtitle-section-p">Não há ambulantes cadastrados nesse bairro.</p></div>'
                );
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
    }

    function openLicense(license) {
        window.open('<?= url('licenseInfo/1/') ?>' + license, '_blank');
    }

    function openNeighborhood(neighbodhood) {
        window.open('<?= url('neighborhood/') ?>' + neighbodhood, '_blank');
    }

    function exportNeighborhood(neighbodhood) {
        window.location.href = '<?= url('exportNeighborhood/') ?>' + neighbodhood;
    }
</script>
<?php $v->end(); ?>
