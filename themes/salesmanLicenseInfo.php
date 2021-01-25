<?php $v->layout("_theme.php"); ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<?php $v->end(); ?>

<div class="container-fluid container-white mt-5 p-5">
    <div class="row">
        <div class="col-xl-12">
            <h3 class="black-title-section">Minha licença</h3>
            <p class="subtitle-section-p">Informações da licença de ambulante</p>
            <hr>
        </div>
        <div class="col-xl-3">
            <div class="div-gray-bg border-top-green p-5">
                <h4 class="black-title-section">Dias trabalhados</h4>
                <hr>
                <?php foreach (str_split($license->atendimento_dias) as $days):
                    if ($days == 0): ?>
                        <p class="subtitle-section-p">Domingo</p>
                    <?php elseif ($days == 1): ?>
                        <p class="subtitle-section-p">Segunda-Feira</p>
                    <?php elseif ($days == 2): ?>
                        <p class="subtitle-section-p">Terça-Feira</p>
                    <?php elseif ($days == 3): ?>
                        <p class="subtitle-section-p">Quarta-Feira</p>
                    <?php elseif ($days == 4): ?>
                        <p class="subtitle-section-p">Quinta-Feira</p>
                    <?php elseif ($days == 5): ?>
                        <p class="subtitle-section-p">Sexta-Feira</p>
                    <?php elseif ($days == 6): ?>
                        <p class="subtitle-section-p">Sábado</p>
                    <?php endif; endforeach; ?>

                <h4 class="black-title-section mt-5">Horário de trabalho</h4>
                <hr>
                <p class="subtitle-section-p"><?= $license->atendimento_hora_inicio ?>
                    - <?= $license->atendimento_hora_fim ?></p>
            </div>

            <div class="row m-0 mt-3 p-4 border-left-green div-request-license">
                <div class="col-xl-2 text-center mt-4">
                    <img src="<?= url('themes/assets/img/cash-payment.png') ?>">
                </div>
                <div class="col-xl-10">
                    <h4 class="black-title-section">Boleto</h4>
                    <p class="subtitle-section-p">Acessar boleto.</p>
                </div>
            </div>

            <div class="row m-0 mt-3 p-4 border-left-yellow div-request-license">
                <div class="col-xl-2 text-center mt-4">
                    <img src="<?= url('themes/assets/img/files.png') ?>">
                </div>
                <div class="col-xl-10">
                    <h4 class="black-title-section">Anexos</h4>
                    <p class="subtitle-section-p">Arquivos enviados.</p>
                </div>
            </div>

            <div class="row m-0 mt-3 p-4 border-left-red div-request-license">
                <div class="col-xl-2 text-center mt-4">
                    <img src="<?= url('themes/assets/img/alert.png') ?>">
                </div>
                <div class="col-xl-10">
                    <h4 class="black-title-section">Notificações</h4>
                    <p class="subtitle-section-p">Histórico de notif...</p>
                </div>
            </div>
        </div>
        <div class="col-xl-9">
            <div class="div-gray-bg border-top-green p-5">
                <h4 class="black-title-section">Informações da licença</h4>
                <hr>

                <div class="row">
                    <div class="col-xl-3 subtitle-section-p">
                        Tipo de equipamento:
                    </div>
                    <div class="col-xl-9 subtitle-section-p text-right">
                        <?= $license->tipo_equipamento ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xl-3 subtitle-section-p">
                        Relato da atividade:
                    </div>
                    <div class="col-xl-9 subtitle-section-p text-right">
                        <?php if ($license->relato_atividade):
                            echo $license->relato_atividade;
                        else:
                            echo 'Não informado';
                        endif; ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xl-3 subtitle-section-p">
                        Início da licença:
                    </div>
                    <div class="col-xl-9 subtitle-section-p text-right">
                        <?= $licenseValidate->data_inicio ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xl-3 subtitle-section-p">
                        Fim da licença:
                    </div>
                    <div class="col-xl-9 subtitle-section-p text-right">
                        <?= $licenseValidate->data_fim ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xl-3 subtitle-section-p">
                        Endereço:
                    </div>
                    <div class="col-xl-9 subtitle-section-p text-right">
                        <?= $license->local_endereco ?>
                    </div>
                </div>

                <h4 class="black-title-section mt-5">Produtos ofertados:</h4>
                <hr>
                <?php foreach (str_split($license->produto) as $product):
                    if ($product == 0): ?>
                        <p class="subtitle-section-p">Gêneros e produtos alimentícios em geral</p>
                    <?php elseif ($product == 1): ?>
                        <p class="subtitle-section-p">Bebidas alcoólicas</p>
                    <?php elseif ($product == 2): ?>
                        <p class="subtitle-section-p">Bebidas não alcoólicas<p>
                    <?php elseif ($product == 3): ?>
                        <p class="subtitle-section-p">Brinquedos e artigos ornamentais</p>
                    <?php elseif ($product == 4): ?>
                        <p class="subtitle-section-p">Confecções, calçados e artigos de uso pessoal</p>
                    <?php elseif ($product == 5): ?>
                        <p class="subtitle-section-p">Louças, ferragens, artefatos de plástico,borracha,
                            couro e utensílios domésticos</p>
                    <?php elseif ($product == 6): ?>
                        <p class="subtitle-section-p">Artesanato, antiguidades e artigos de arte em geral</p>
                    <?php elseif ($product == 7): ?>
                        <p class="subtitle-section-p"><?= $license->relato_atividade ?></p>
                    <?php endif; endforeach; ?>
            </div>
            <div class="div-gray-bg border-top-green mt-5 p-5">
                <h4 class="black-title-section">Local de trabalho</h4>
                <p class="subtitle-section-p">Geolocalização da licença</p>
                <hr>
                <div id="mapProfile"></div>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>
<script>
    let map;
    let mapTiles = {};
    let ctrTiles = {};
    let mapLayers = {};
    let ctrLayers = {};

    $(function () {
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

        mapTiles['Mapa Jawg'] = L.tileLayer('https://{s}.tile.jawg.io/jawg-light/{z}/{x}/{y}{r}.png?access-token=C1vu4LOmp14JjyXqidSlK8rjeSlLK1W59o1GAfoHVOpuc6YB8FSNyOyHdoz7QIk6', {
            maxNativeZoom: 20,
            maxZoom: 20,
            minZoom: 10
        });
        ctrTiles["Mapa Jawg"] = mapTiles["Mapa Jawg"];

        mapTiles['Mapa OSM'] = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxNativeZoom: 20,
            maxZoom: 20,
            minZoom: 10
        });
        ctrTiles['Mapa OSM'] = mapTiles['Mapa OSM'];

        mapTiles['Satelite'] = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxNativeZoom: 20,
            maxZoom: 20,
            minZoom: 10,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });
        ctrTiles["Satelite"] = mapTiles["Satelite"];

        map = L.map('mapProfile', {
            center: [<?= $license->latitude; ?>, <?= $license->longitude; ?>],
            layers: [mapTiles["Mapa Jawg"]],
            zoomControl: true,
            maxZoom: 20,
            minZoom: 10,
            zoom: 18
        });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        <?php if($licenseStatus == 0 || $licenseStatus == 3): ?>
        L.marker(['<?= $license->latitude; ?>', '<?= $license->longitude; ?>'], {icon: pending}).bindPopup('Local cadastrado').addTo(map);
        <?php elseif ($license->situacao == 1): ?>
        L.marker(['<?= $license->latitude; ?>', '<?= $license->longitude; ?>'], {icon: paid}).bindPopup('Local cadastrado').addTo(map);
        <?php else: ?>
        L.marker(['<?= $license->latitude; ?>', '<?= $license->longitude; ?>'], {icon: expired}).bindPopup('Local cadastrado').addTo(map);
        <?php endif; ?>
    });

    function debugMap() {
        setTimeout(function () {
            map.invalidateSize();
        }, 500);
    }
</script>
<?php $v->end(); ?>
