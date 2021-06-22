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

<div class="container-fluid mt-5 mb-4">
    <div class="row">
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Total de Vagas Ofertadas
                            </h4>
                            <hr>
                            <h2 class="title-section"> <?= $zone->limite_ambulantes; ?></h2>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-all_inclusive card-icon registered-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Vagas Disponíveis
                            </h4>
                            <hr>
                            <h2 class="title-section">
                                <?= ($zone->limite_ambulantes - $zone->quantidade_ambulantes); ?>
                            </h2>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-lock_open card-icon paid-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Vagas Ocupadas
                            </h4>
                            <hr>
                            <h2 class="title-section">    <?= $zone->quantidade_ambulantes; ?></h2>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-flag card-icon pending-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="web-div-box">
                <div class="box-div-info p-5">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="title-section">
                                Vagas com Espaço Fixo
                            </h4>
                            <hr>
                            <h2 class="title-section"><?= $zone->vagas_fixas; ?></h2>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <span class="title-section icon-map-pin card-icon expired-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12 ">
            <div class="web-div-box">
                <div class="box-div-info">
                    <h3 class="ml-3 title-section">Lista de Vagas Fixas</h3>
                    <p class="ml-3 subtitle-section-p">Todas as vagas fixas do mercado</p>
                    <div class="box-div-info-overflow-x">
                        <hr class="mb-0">
                        <?php if (!$fixed): ?>
                            <div class="p-5 mt-5 text-center">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                                <p class="mt-5 subtitle-section-p">Ops! Não encontramos nenhuma licença para exibir.
                                    😥</p>
                            </div>
                        <?php else: ?>
                            <div class="mb-2" style="height: 50vh; overflow-y: auto;">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">Identificador</th>
                                        <th scope="col">Nome</th>
                                        <th>Valor</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table-data">
                                    <?php foreach ($fixed as $fix):
                                        is_null($fix->valor) ? $fix_valor = '-' : $fix_valor = 'R$ ' . $fix->valor;
                                        is_null($fix->nome) ? $fix_nome = '-' : $fix_nome = $fix->nome;

                                        if (is_null($fix->id_licenca)):
                                            $divStatus = 'primary';
                                            $textStatus = 'Disponível';
                                            $trClass = 'border-left-green';
                                        else:
                                            $divStatus = 'secondary';
                                            $textStatus = 'Ocupado';
                                            $trClass = 'border-left-red';
                                        endif; ?>

                                        <tr class="<?= $trClass ?>" onclick="editZoneFixed('<?= md5($zone->id); ?>')">
                                            <td><?= $fix->cod_identificador ?></td>
                                            <td><?= $fix_nome ?></td>
                                            <td> <?= $fix_valor ?></td>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="status-circle <?= $divStatus; ?> t-5"></div>
                                                    <?= $textStatus; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-xl-12 mb-5">
            <div class="web-div-box">
                <div class="box-div-info">
                    <h3 class="ml-3 title-section">Lista de pagamentos</h3>
                    <p class="ml-3 subtitle-section-p">Todos os pagamentos cadastrados no Orditi</p>

                    <div class="div-box-span-icon mt-4">
                        <div class="div-table-search">
                            <input id="text" onkeyup="tableFilter()" class="input-table-search" type="text"
                                   placeholder="Filtrar por proprietário...">
                            <div class="circle-button primary search">
                                <span class="icon-search"></span>
                            </div>
                        </div>

                        <div class="dropleft">
                            <div class="ml-3 circle-button secondary" id="dropdownMenuButton"
                                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                 title="Exportar tabela">
                                <span class="icon-download"></span>
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?= url('exportData/2') ?>">
                                    Exportar tabela em xls
                                </a>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="box-div-info-overflow-x">
                        <?php if (!$payments): ?>
                            <div class="p-5 mt-5 text-center">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                                <p class="mt-5 subtitle-section-p">Ops! Não encontramos nenhum pagamento para exibir
                                    aqui. 😥</p>
                            </div>
                        <?php else: ?>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" class="table-col-2" onclick="changeFilter(0)">
                                        <div class="d-flex">
                                            <div class="marker" id="0"></div>
                                            Valor
                                        </div>
                                    </th>
                                    <th scope="col" class="table-col-2" onclick="changeFilter(1)">
                                        <div class="d-flex">
                                            <div class="marker" id="1"></div>
                                            Vencimento
                                        </div>
                                    </th>
                                        <th scope="col" class="table-col-1" onclick="changeFilter(2)">
                                            <div class="d-flex">
                                                <div class="marker" id="2"></div>
                                                Box
                                            </div>
                                        </th>
                                        <th scope="col" class="table-col-2" onclick="changeFilter(3)">
                                            <div class="marker" id="3"></div>
                                            Tipo
                                        </th>
                                    <th scope="col" class="table-col-2" onclick="changeFilter(4)">
                                        <div class="marker" id="4"></div>
                                        Status
                                    </th>
                                    <th scope="col" onclick="changeFilter(5)">
                                        <div class="marker active" id="5"></div>
                                        Proprietário
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="table-data">
                                <?php
                                if ($payments !== NULL):
                                    foreach ($payments as $payment):
                                        switch ($payment->status):
                                            case 1:
                                                $divStatus = 'primary';
                                                $textStatus = 'Pago';
                                                $trClass = 'border-left-green';
                                                break;
                                            case 2:
                                                $divStatus = 'secondary';
                                                $textStatus = 'Vencido';
                                                $trClass = 'border-left-red';
                                                break;
                                            default:
                                                $divStatus = 'tertiary';
                                                $textStatus = 'Pendente';
                                                $trClass = 'border-left-yellow';
                                                break;
                                        endswitch; ?>
                                        <tr class="<?= $trClass ?>"
                                            onclick="openPage('<?= $payment->cod_referencia ?>')">
                                            <td>R$ <?= $payment->valor ?>,00</td>
                                            <td><?= date('d/m/Y', strtotime($payment->pagar_em)); ?></td>
                                                <td><?= $payment->name_box ?></td>
                                                <td>
                                                    <?php switch ($payment->tipo):
                                                        case 1: ?>
                                                            Recorrente
                                                            <?php break;
                                                        default: ?>
                                                            Vencido
                                                            <?php break; endswitch; ?>
                                                </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="status-circle <?= $divStatus; ?> t-5"></div>
                                                    <?= $textStatus; ?>
                                                </div>
                                            </td>
                                            <td><?= $payment->name ?></td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                            <div class="text-center p-4 empty-table">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty.svg') ?>">
                                <h4 class="black-title-section">Ops.......!</h4>
                                <p class="subtitle-section-p">Nenhum dado foi encontrado</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-5 mb-2">
    <div class="row">
        <div class="col-sm-6 mb-5" onclick="debugMap()">
            <div id="mapProfile">
                <div id="mapZone" class="<?= $salesmans ? 'mapInfo' : 'mapMarketPlace'; ?>"></div>
            </div>
        </div>

        <div class="col-sm-6 mb-5">
            <iframe src="https://www.google.com/maps/embed?pb=!4v1617115456065!6m8!1m7!1sh2uwEPd3HSSwcRMfhM_hRQ!2m2!1d<?= $zone->centroide[1] ?>!2d<?= $zone->centroide[0] ?>!3f343.7!4f3.739999999999995!5f0.7820865974627469"
                    class="mapMarketPlace" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script src="<?= url("themes/assets/js/leaflet.markercluster-src.js"); ?>"></script>
<script src="<?= url("themes/assets/js/Leaflet.LinearMeasurement.js"); ?>"></script>
<script>
    let selectedOption = 5;
    let options = ['valor', 'vencimento', 'box', 'tipo', 'status', 'proprietário'];
    let market = ""
    let sector = ""

    function openPage(data) {
        window.open("http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?" + data, '_blank');
    }

    function changeFilter(num) {
        selectedOption = num;
        input = document.getElementById("text");
        input.placeholder = "Filtrar por " + options[num] + "...";
        mark = document.getElementById(num);
        markers = document.getElementsByClassName("marker");
        for (var i = 0; i < markers.length; i++) {
            markers[i].className = "marker"
        }
        mark.className = "marker active";
    }

    function tableFilter() {

        let input, filter, table, tr, td, i, txtValue;

        input = document.getElementById("text");
        filter = input.value.toUpperCase();
        table = document.getElementById("table-data");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[selectedOption];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
                console.log(txtValue);
            }
        }
        if ($('tr:visible').length === 1) {
            $('.empty-table').show();
        } else {
            if ($('.empty-table').show()) {
                $('.empty-table').hide()
            }
        }
    }
</script>
<script>
    function editZoneFixed(zoneId) {
        window.location.href = '<?= url('editFixedZones/') ?>' + '/' + zoneId;
    }

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


