<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>" />
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<?php $v->end(); ?>

<div class="container-fluid mt-5 pb-5 container-white border-bottom-gray">
    <div class="row">
        <div class="col-xl-12 p-5">
            <h3 class="ml-3 title-section"><?= $neighborhood->nome ?></h3>
            <p class="ml-3 subtitle-section-p">Lista de ambulantes cadastrados no bairro</p>
            <hr>

            <div class="div-box-span-icon mt-3" style="top: 30px;">
                <div class="div-table-search">
                    <input id="text" onkeyup="tableFilter()" class="input-table-search" type="text"
                           placeholder="Filtrar ambulante...">
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
                        <a class="dropdown-item" href="<?= url('exportNeighborhood/'). $neighborhood->id ?>">
                            Exportar tabela em xls
                        </a>
                    </div>
                </div>
            </div>

            <div class="row pt-5">
                <div class="col-xl-7">
                    <div class="div-gray-bg border-top-green p-5">
                        <h4 class="black-title-section">Lista de ambulantes</h4>
                        <hr>
                        <?php if ($users): ?>
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
                            <?php if ($users): $aux = 1;
                                foreach ($users as $user): ?>
                                    <tr onclick="openLicense('<?= $user['licenseId'] ?>')">
                                        <td><?= $aux ?></td>
                                        <td><?= $user['identity'] ?></td>
                                        <td><?= $user['name'] ?></td>
                                        <td><?= $user['phone'] ?></td>
                                    </tr>
                                    <?php $aux++; endforeach; endif; ?>
                            </tbody>
                        </table>
                        <div class="text-center p-4 empty-table">
                            <img style="width: 30%" src="<?= url('themes/assets/img/empty.svg') ?>">
                            <h4 class="black-title-section">Ops.......!</h4>
                            <p class="subtitle-section-p">Nenhum dado foi encontrado</p>
                        </div>
                        <?php else: ?>
                            <div class="p-5 mt-5 text-center">
                                <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                                <p class="mt-5 subtitle-section-p">Ops! Não há ambulantes cadastrados aqui.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-xl-5">
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
    $(function () {
        let map = null;
        let mapTiles = {};
        let ctrTiles = {};
        let ctrLayers = {};
        const neighborhoodId = '<?= md5($neighborhood->id) ?>';
        const neighborhood = JSON.parse('<?= json_encode($coordinates) ?>');
        let aux = [];

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
            center: [<?= $neighborhood->centroid[0] ?>, <?= $neighborhood->centroid[1] ?>],
            layers: [mapTiles["Mapa Jawg"]],
            zoomControl: false,
            maxZoom: 20,
            minZoom: 10,
            zoom: 14
        });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        map.invalidateSize(true);

        neighborhood.forEach(function (e) {
            aux.push([e[0], e[1]]);
        });

        console.log(neighborhood);

        L.polygon(aux, {
            color: "#4bc2ce",
            fillColor: "#4bc2ce"
        }).bindPopup(
            '<div class="container" style="width: 200px"><div class="row"><div class="col-2"' +
            'style="border-right:1px solid rgba(0, 0, 0, 0.1)"></div><div class="col-10"><div>' +
            '</div><div class="zoneInfo mt-4"><?= $neighborhood->nome ?><br>' +
            '<br></div></div></div>'
        ).addTo(map);
    });

    function tableFilter() {
        let input, filter, table, tr, td, i, txtValue;
        let selectedOption = 2;

        input = document.getElementById("text");
        filter = input.value.toUpperCase();
        table = document.getElementById("salesmanTable");
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

    function openLicense(licenseId) {
        window.location.href = "<?= url('licenseInfo/1/') ?>" + licenseId;
    }
</script>
<?php $v->end(); ?>
