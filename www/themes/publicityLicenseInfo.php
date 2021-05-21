<?php $v->layout("_theme.php"); ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/layers-control.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<link rel="stylesheet" href="<?= url("themes/assets/css/multiples.css"); ?>" type="text/css">
<?php $v->end(); ?>

<div id="modal-1" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-xl-10 p-5 container-white modal-overflow mh-80">
                <div class="row">
                    <div class="col-8">
                        <h3 class="black-title-section">Meus anexos</h3>
                    </div>
                    <div class="col-4 text-right mt-3">
                        <span class="icon-close" onclick="closeModal(1)"></span>
                    </div>
                </div>
                <p class="subtitle-section-p">Arquivos enviados durante o cadastro da licença.</p>
                <hr>
                <div class="row m-0 p-4">
                    <div class="col-xl-12 mb-3 pl-5 pr-5">
                        <?php if ($uploads && count($uploads) > 0): $aux = 1;
                            foreach ($uploads as $upload): ?>
                                <div class="row div-gray-bg mb-5 p-5">
                                    <div class="col-xl-3 p-0 text-center">
                                        <img style="width: 150px;"
                                             src="<?= url('/themes/assets/uploads/') ?><?= $upload['groupName'] . '/' .
                                             $upload['userId'] . '/' . $upload['fileName'] ?>">
                                    </div>
                                    <div class="col-xl-9 text-sm-center text-md-left">
                                        <h5 class="mt-5 mt-md-3"><?= explode(".", $upload['fileName'])[0] ?></h5>
                                        <p class="subtitle-section-p">Para editar ou visualizar a imagem, acione os
                                            botões abaixo.</p>
                                        <div class="text-right mt-5 pt-3 d-flex">
                                            <form class="mr-2"
                                                  action="<?= url('downloadFile/' . $upload['groupName'] . '/' . $upload['userId']
                                                      . '/' . $upload['fileName']) ?>">
                                                <button class="btn-3 primary">Baixar</button>
                                            </form>
                                            <button class="btn-3 secondary-color"
                                                    onclick="openFile('<?= $upload['groupName'] . '/' .
                                                    $upload['userId'] . '/' . $upload['fileName'] ?>')">Visualizar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php $aux++; endforeach; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-2" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-10 p-5 container-white">
                <h3 class="black-title-section">Meus pagamentos</h3>
                <p class="subtitle-section-p">Todos os pagamentos referente às suas licenças.</p>
                <hr>
                <div class="div-box-span-icon mt-5">
                    <span class="icon-close" onclick="closeModal(2)"></span>
                </div>
                <div class="box-div-info-overflow-x background-body">
                    <?php if ($payments): ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Status</th>
                                <th>Valor</th>
                                <th>Tipo</th>
                                <th>Validade</th>
                                <th>Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($payments as $payment):
                                if ($payment->id_empresa == null):
                                    if ($payment->status == 3 || $payment->status == 0):
                                        $divStatus = 'statusPendent';
                                        $textStatus = 'Pendente';
                                        $trClass = 'border-left-yellow';
                                    elseif ($payment->status == 1):
                                        $divStatus = 'statusPaid';
                                        $textStatus = 'Pago';
                                        $trClass = 'border-left-green';
                                    else:
                                        $divStatus = 'statusExpired';
                                        $textStatus = 'Vencido';
                                        $trClass = 'border-left-red';
                                    endif;
                                    if ($payment->tipo == 0):
                                        $type = "Multa";
                                    else:
                                        $type = "Pagamento";
                                    endif; ?>
                                    <tr class="<?= $trClass ?>">
                                        <td class="<?= $divStatus ?>"><?= $textStatus ?></td>
                                        <td>R$ <?= $payment->valor ?>,00</td>
                                        <td><?= $type ?></td>
                                        <td><?= date('d-m-Y', strtotime($payment->pagar_em)); ?></td>
                                        <td>
                                            <?php if ($payment->status == 2): ?>
                                                <a class="btn-3 secondary"
                                                   href="http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?<?= $payment->cod_referencia ?>"
                                                   target="_blank">Pagar</a>
                                            <?php elseif ($payment->status == 0 || $payment->status == 3): ?>
                                                <a class="btn-3 tertiary"
                                                   href="http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?<?= $payment->cod_referencia ?>"
                                                   target="_blank">Pagar</a>
                                            <?php else: ?>
                                                Não há ações
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="p-5 mt-5 text-center">
                            <img style="width: 20%" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                            <p class="mt-5 subtitle-section-p">Você ainda não possui boletos cadastrados.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-3" class="div-modal">
    <div class="container pt-3">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-10 p-5 container-white">
                <h3 class="black-title-section">Validar licença</h3>
                <hr>
                <div class="div-box-span-icon mt-5">
                    <span class="icon-close" onclick="closeModal(3)"></span>
                </div>
                <div class="box-div-info-overflow-x background-body">
                    <form id="form-license-company" method="POST"
                          action="<?= $router->route('web.confirmPublicityLicense') ?>">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xl-12">
                                    <input type="hidden" name="licenseId" value="<?= $license->id_licenca ?>">
                                    <div class="form-group mt-3">
                                        <label>Ação:</label>
                                        <div class="row w-100">
                                            <div class="col-6" class="form-group">
                                                <input type="radio" id="approve" name="status" value="approve">
                                                <label for="approve">Aprovar</label><br>
                                            </div>
                                            <div class="col-6" class="form-group">
                                                <input type="radio" id="reject" name="status" value="reject">
                                                <label for="reject">Rejeitar</label><br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Comentários:</label>
                                        <textarea type="text" class="form-input" id="comment"
                                                  name="comment"
                                                  placeholder="Ex.: Trabalho com a venda de produtos para cabelo."
                                                  required></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>Fotos da Vistoria:</label>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 text-left">
                                            <label class="label-file equipmentImage-file text-center"
                                                   for="equipmentImage"><span
                                                        class="icon-plus mr-2"></span> Selecionar </label>
                                            <input class="hidden-input-file" type="file" onchange="uploadImage(this)"
                                                   id="artImage" name="ARTImage"
                                                   accept="image/png, image/jpg, image/jpeg">
                                            <div class="invalid-feedback"></div>
                                            <div class="equipmentImage-file-uploaded file-uploaded-container">
                                                <div class="card-content-upload text-center p-3">
                                                    <div class="card-content-type-upload">
                                                        <span class="equipmentImage-type"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 text-left">
                                                    <div class="d-flex">
                                                        <p class="equipmentImage-name"></p>
                                                        <span id="equipmentImage-span-close"
                                                              class="icon-close ml-3 card-close-file equipmentImage"
                                                              onclick="changeFile(this)"></span>
                                                    </div>
                                                    <div class="card-content-progress"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 mt-5 text-right">
                                    <hr>
                                    <button type="reset" class="btn-3 secondary-color">
                                        Cancelar
                                    </button>
                                    <button class="btn-3 primary" type="submit">
                                        Enviar avaliação
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-4" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-xl-10 p-5 modal-overflow container-white">
                <div class="row">
                    <div class="col-8">
                        <h3 class="black-title-section">Geolocalização</h3>
                    </div>
                    <div class="col-4 text-right mt-3">
                        <span class="icon-close" onclick="closeModal(4)"></span>
                    </div>
                </div>
                <p class="subtitle-section-p">Localização da licença no mapa.</p>
                <hr>
                <div id="mapProfile"></div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid container-white mt-5 p-5">
    <div class="row">
        <div class="col-12">
            <h3 class="black-title-section">Minha licença</h3>
            <p class="subtitle-section-p">Informações da licença de publicidade e propaganda</p>
            <hr>
            <div class="row">
                <?php if ($_SESSION['user']['role'] == 4): ?>
                <div class="col-md-4" onclick="openModal(3)">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-10 p-0 mb-5 cursor-pointer">
                            <div class="p-4 text-center background-green b-radius-top">
                                <div class="circle-card-option">
                                    <span class="icon-drivers-license"></span>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="p-5 text-center gray-box b-radius-bottom">
                                Validar licença
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif;?>

                <div class="col-md-4" onclick="debugMap()">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-10 p-0 mb-5 cursor-pointer">
                            <div class="p-4 text-center background-green b-radius-top">
                                <div class="circle-card-option">
                                    <span class="icon-map2"></span>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="p-5 text-center gray-box b-radius-bottom">
                                Geolocalização
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" onclick="openModal(1)">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-10 p-0 mb-5 cursor-pointer">
                            <div class="p-4 text-center background-green b-radius-top">
                                <div class="circle-card-option">
                                    <span class="icon-attachment"></span>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="p-5 text-center gray-box b-radius-bottom">
                                Visualizar anexos
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="div-gray-bg border-top-green p-5">
                <h4 class="black-title-section">Informações da licença</h4>
                <hr>

                <div class="row">
                    <div class="col-3 subtitle-section-p">
                        Cpf:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $user->cpf ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Proprietário:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $user->nome ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Descrição do equipamento:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->tipo ?> - <?= $license->descricao ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Dimensões do equipamento:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->dimensoes ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Estado:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?php
                        switch ($license->license->status):
                            case 1:
                                $divStatus = 'primary';
                                $textStatus = 'Ativo';
                                break;
                            case 2:
                                $divStatus = 'secondary';
                                $textStatus = 'Bloqueado';
                                break;
                            case 3:
                                $divStatus = 'primary';
                                $textStatus = 'Aprovado';
                                break;
                            default:
                                $divStatus = 'tertiary';
                                $textStatus = 'Pendente';
                                $trClass = 'border-left-yellow';
                                break;
                        endswitch; ?>
                        <div class="d-flex">
                            <div class="status-circle <?= $divStatus; ?> t-5"></div>
                            <?= $textStatus; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-6" onclick="openModal(2)">
                    <div class="row m-0 mt-3 p-4 border-left-green div-request-license">
                        <div class="col-2 text-center mt-4">
                            <img src="<?= url('themes/assets/img/cash-payment.png') ?>">
                        </div>
                        <div class="col-10">
                            <h4 class="black-title-section">Boleto</h4>
                            <p class="subtitle-section-p">Acessar boleto.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row m-0 mt-3 p-4 border-left-green-light div-request-license mb-5"
                         onclick="openOrder()">
                        <div class="col-2 text-center mt-4">
                            <img src="<?= url('themes/assets/img/order.png') ?>">
                        </div>
                        <div class="col-10">
                            <h4 class="black-title-section">Alvará</h4>
                            <p class="subtitle-section-p">Acessar alvará.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script src="<?= url("themes/assets/js/leaflet.markercluster-src.js"); ?>"></script>
<script src="<?= url("themes/assets/js/Leaflet.LinearMeasurement.js"); ?>"></script>
<script type="text/javascript" src="<?= url("themes/assets/js/multiples.js"); ?>"></script>
<script>
    let map;
    let mapTiles = {};
    let ctrTiles = {};
    let mapLayers = {};
    let ctrLayers = {};

    $(function () {
        let paid = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-user-green.png"); ?>",
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

        let expired = L.icon({
            iconUrl: "<?= url("themes/assets/img/marker-user-red.png"); ?>",
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

        L.marker(['<?= $license->latitude; ?>', '<?= $license->longitude; ?>'], {icon: paid}).bindPopup('Local cadastrado').addTo(map);
    });

    function debugMap() {
        setTimeout(function () {
            map.invalidateSize();
        }, 500);
        openModal(4);
    }

    $('form').on('submit', function (e) {
        e.preventDefault();
        $("#loader-div").show();

        const _thisForm = $(this);
        const data = new FormData(this);

        $.ajax({
            type: _thisForm.attr('method'),
            url: _thisForm.attr('action'),
            data: data,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (returnData) {
            console.log(123);
            if (returnData == 'success') {
                swal({
                    icon: "success",
                    title: "Licença validada com sucesso!",
                    text: "Acesse o menu 'Minhas Licenças' para visualiza-la.",
                })
            } else {
                swal({
                    icon: "error",
                    title: "Erro!",
                    text: "Não foi possível validar essa licença. Tente novamente mais tarde.",
                });
            }
            console.log(returnData);
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

    });

    function generator() {
        let date = new Date()

        const clipboard = new ClipboardJS('#copy')

        clipboard.on('success', function (e) {
        });

        clipboard.on('error', function (e) {
            swal({
                icon: "error",
                title: "Erro!",
                text: "Não foi possível copiar o link.",
            });
        });
    }

    function openOrder() {
        window.open('<?= url('order') ?>/2/<?= md5($license->id) ?>', '_blank')
    }
</script>
<?php $v->end(); ?>
