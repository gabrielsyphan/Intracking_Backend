<?php $v->layout("_theme.php"); ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<?php $v->end(); ?>

<div id="modal-1" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-xl-10 p-5 container-white modal-overflow">
                <div class="row">
                    <div class="col-8">
                        <h3 class="black-title-section">Meus anexos</h3>
                    </div>
                    <div class="col-4 text-right mt-3">
                        <span class="icon-close" onclick="closeModal(1)"></span>
                    </div>
                </div>
                <p class="subtitle-section-p">Arquivos enviados por você durante seu cadastro.</p>
                <hr>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>
                    <tbody id="table-data">
                    <?php if ($uploads && count($uploads) > 0):
                        $aux = 1;
                        foreach ($uploads as $upload): ?>
                            <tr>
                                <th scope="row"><?= $aux ?></th>
                                <td><?= $upload['fileName'] ?></td>
                                <td style="display: flex">
                                    <form action="<?= url('downloadFile/' . $upload['groupName'] . '/' . $upload['userId']
                                        . '/' . $upload['fileName']) ?>">
                                        <button class="btn" type="submit">
                                            <span class="icon-download"></span>
                                        </button>
                                    </form>
                                    <button class="btn" type="submit"
                                            onclick="openFile('<?= $upload['groupName'] . '/' .
                                            $upload['userId'] . '/' . $upload['fileName'] ?>')">
                                        <span class="icon-image"></span>
                                    </button>
                                </td>
                            </tr>
                            <?php $aux++; endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal-2" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-xl-10 p-5 container-white modal-overflow">
                <div class="row">
                    <div class="col-8">
                        <h3 class="black-title-section">Meus pagamentos</h3>
                    </div>
                    <div class="col-4 text-right mt-3">
                        <span class="icon-close" onclick="closeModal(2)"></span>
                    </div>
                </div>
                <p class="subtitle-section-p">Todos os pagamentos referente às suas licenças.</p>
                <hr>
                <div class="box-div-info-overflow-x background-body">
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
                        <?php if ($payments):
                            foreach ($payments as $payment):
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
                                <?php endif; endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-3" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-xl-12 p-5 container-white modal-overflow">
                <div class="row">
                    <div class="col-8">
                        <h3 class="black-title-section">Notificações</h3>
                    </div>
                    <div class="col-4 text-right mt-3">
                        <span class="icon-close" onclick="closeModal(3)"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <p class="subtitle-section-p">
                            Histórico de notificações do ambulante.
                        </p>
                    </div>
                    <div class="col-xl-6 text-right">
                        <a id="newNotification" href="#" data-toggle="collapse" data-target="#demo">Novo chamado</a>
                    </div>
                    <div id="demo" class="col-xl-12 collapse">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <h5>Título:</h5>
                                    <input type="text" class="form-input" id="title" name="title" title="Insira um título para a notificação" placeholder="Ex.: Local irregular" required>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <h5>Data da notificação:</h5>
                                    <input type="date" class="form-input" id="date" name="date" title="Data da notificação" required>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <h5>Hora da notificação:</h5>
                                    <input type="time" class="form-input" id="time" name="time" title="Hora da notificação" required>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <h5>Multa <span class="spanAlert">(Opcional)</span>:</h5>
                                    <input type="number" class="form-input" id="penality" name="penality" title="Valor da multa" placeholder="Insira a o valor da multa" min="0">
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <h5>Fiscal:</h5>
                                    <select id="agentSelect" class="form-input" name="agentSelect"  required>
                                        <option style="display: none;" class="opt0" value="0">Selecione o fiscal responsável pela notificação</option>
                                        <?php foreach ($agents as $agent): ?>
                                            <option value="<?= $agent->id ?>"><?= $agent->nome ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="form-group">
                                    <h5>Descrição:</h5>
                                    <textarea class="form-input notification-textarea" id="description" name="description" rows="3" placeholder="Ex.: Indivíduo notificado por estar atuando em local diferente do cadastrado no sistema" required></textarea>
                                </div>
                            </div>

                            <div class="col-xl-12 text-right">
                                <button style="width: auto" type="submit" class="btn-3 primary">
                                    Cadastrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row m-0 mt-3 p-4 border-left-yellow div-request-license" onclick="openModal(1)">
                    <div class="col-xl-12">
                        <h4 class="black-title-section">Local errado</h4>
                        <p class="subtitle-section-p">Ambulante estava vendendo seus produtos na rua X quando na verdade
                            ele deveria estar na rua Y. Informei a ele do erro e mandei-o de volta para casa.</p>
                        <h5 class="black-title-section text-right">Lucas Gabriel Peixoto de Oliveira</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid container-white mt-5 p-5">
    <div class="row">
        <div class="col-xl-12">
            <h3 class="black-title-section">Minha licença</h3>
            <p class="subtitle-section-p">Informações da licença de ambulante</p>
            <hr>
        </div>
        <div class="col-xl-3">
            <div class="div-gray-bg border-top-green p-5 mb-5">
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

            <div class="row m-0 mt-3 p-4 border-left-green div-request-license mb-5" onclick="openModal(2)">
                <div class="col-2 text-center mt-4">
                    <img src="<?= url('themes/assets/img/cash-payment.png') ?>">
                </div>
                <div class="col-10">
                    <h4 class="black-title-section">Boleto</h4>
                    <p class="subtitle-section-p">Acessar boleto.</p>
                </div>
            </div>

            <div class="row m-0 mt-3 p-4 border-left-yellow div-request-license mb-5" onclick="openModal(1)">
                <div class="col-2 text-center mt-4">
                    <img src="<?= url('themes/assets/img/files.png') ?>">
                </div>
                <div class="col-10">
                    <h4 class="black-title-section">Anexos</h4>
                    <p class="subtitle-section-p">Arquivos enviados.</p>
                </div>
            </div>

            <?php if ($_SESSION['user']['login'] === 3): ?>
                <div class="row m-0 mt-3 p-4 border-left-red div-request-license mb-5" onclick="openModal(3)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/alert.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4 class="black-title-section">Notificações</h4>
                        <p class="subtitle-section-p">Histórico de notif...</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-xl-9 mb-5">
            <div class="div-gray-bg border-top-green p-5">
                <h4 class="black-title-section">Informações da licença</h4>
                <hr>

                <div class="row">
                    <div class="col-3 subtitle-section-p">
                        Proprietário:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $user ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Tipo de equipamento:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $license->tipo_equipamento ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Início da licença:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $licenseValidate->data_inicio ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Fim da licença:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $licenseValidate->data_fim ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Relato da atividade:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?php if ($license->relato_atividade):
                            echo $license->relato_atividade;
                        else:
                            echo 'Não informado';
                        endif; ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Endereço:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
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
