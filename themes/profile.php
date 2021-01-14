<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/layers-control.css"); ?>"/>
<link rel="stylesheet" href="<?= url("themes/assets/css/util.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>" />
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<?php $v->end(); ?>

<div class="modal fade" id="userAttach" tabindex="-1" role="dialog" aria-labelledby="userAttachLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Usuário / anexos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>
                    <tbody id="table-data">
                    <?php if($uploads && count($uploads) > 0):
                        $aux = 1;
                        foreach($uploads as $upload): ?>
                            <tr>
                                <th scope="row"><?= $aux ?></th>
                                <td><?= $upload['fileName'] ?></td>
                                <td style="display: flex">
                                    <form action="<?= url('downloadFile/'. $upload['groupName'] .'/'. $upload['userId']
                                        .'/'. $upload['fileName']) ?>">
                                        <button class="btn" type="submit">
                                            <span class="icon-download"></span>
                                            Baixar
                                        </button>
                                    </form>
                                    <button class="btn" type="submit" onclick="openFile('<?= $upload['groupName'] .'/'.
                                    $upload['userId'] .'/'. $upload['fileName'] ?>')">
                                        <span class="icon-image"></span>
                                        Visualizar
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

<div class="modal fade" id="userPayments" tabindex="-1" role="dialog" aria-labelledby="userPaymentsLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Usuário / pagamentos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-div-info-overflow-x background-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Status</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Validade</th>
                            <th scope="col">Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($payments !== NULL):
                            foreach ($payments as $payment):
                                if($payment->id_empresa == null):
                                    if($payment->status == 3 || $payment->status == 0):
                                        $status = "Pendente";
                                    elseif ($payment->status == 1):
                                        $status = "Pago";
                                    else:
                                        $status = "Vencido";
                                    endif;
                                    if($payment->tipo == 0):
                                        $type = "Multa";
                                    else:
                                        $type = "Pagamento";
                                    endif; ?>
                                    <tr class="row100 tableLink">
                                        <?php if ($payment->status == 2): ?>
                                            <td class="statusExpired"><?= $status ?></td>
                                        <?php elseif($payment->status == 0 || $payment->status == 3): ?>
                                            <td class="statusPendent"><?= $status ?></td>
                                        <?php else: ?>
                                            <td class="statusPaid"><?= $status ?></td>
                                        <?php endif;?>
                                        <td>R$ <?= $payment->valor  ?>,00</td>
                                        <td><?= $type ?></td>
                                        <td><?= date('d-m-Y', strtotime($payment->pagar_em)); ?></td>
                                        <td>
                                            <?php if ($payment->status == 2): ?>
                                                <a class="btn btn-style-5" href="http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?<?= $payment->cod_referencia ?>" target="_blank">Pagar boleto</a>
                                            <?php elseif($payment->status == 0 || $payment->status == 3): ?>
                                                <a class="btn btn-warning" href="http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?<?= $payment->cod_referencia ?>" target="_blank">Pagar boleto</a>
                                            <?php else: ?>
                                                Não há ações
                                            <?php endif;?>
                                        </td>
                                    </tr>
                                <?php endif; endforeach; endif;?>
                        </tbody>
                    </table>
                </div>
                <div class="divInfo mb-5">
                    <p class="pInfo">Os status dos pagamentos serão atualizados todos os dias as 18:00.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if($salesman->suspenso == 0 &&  $salesman->latitude != null && $salesman->longitude != null): ?>
<div class="modal fade" id="userLocation" tabindex="-1" role="dialog" aria-labelledby="userLocationLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Usuário / localização</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="map-container">
                    <div id="mapProfile"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if($_SESSION['user']['login'] === 3): ?>
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Ambulante / Notificar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form" method="POST">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <h5>Título:</h5>
                                    <input type="text" class="form-control" id="title" name="title" title="Insira um título para a notificação" placeholder="Ex.: Local irregular" required>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <h5>Data da notificação:</h5>
                                    <input type="date" class="form-control" id="date" name="date" title="Data da notificação" required>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <h5>Hora da notificação:</h5>
                                    <input type="time" class="form-control" id="time" name="time" title="Hora da notificação" required>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <h5>Multa <span class="spanAlert">(Opcional)</span>:</h5>
                                    <input type="number" class="form-control" id="penality" name="penality" title="Valor da multa" placeholder="Insira a o valor da multa" min="0">
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <h5>Fiscal:</h5>
                                    <select id="agentSelect" class="form-control" name="agentSelect"  required>
                                        <option style="display: none;" class="opt0" value="0">Selecione o fiscal responsável pela notificação</option>
                                        <?php foreach ($agents as $agent): ?>
                                            <option value="<?= $agent->id ?>"><?= $agent->nome ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <?php if($salesman->suspenso == 0): ?>
                                    <div class="form-group">
                                        <h5>Suspender ambulante?</h5>
                                        <input type="radio" name="blockAccess" id="yesBlockAcess" value="1">
                                        <label for="yesBlockAcess">Sim</label>
                                        <input type="radio" name="blockAccess" id="noBlockAcess" value="0" checked>
                                        <label for="noBlockAcess">Não</label>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-xl-12">
                                <div class="form-group">
                                    <h5>Descrição:</h5>
                                    <textarea class="form-control notification-textarea" id="description" name="description" rows="3" placeholder="Ex.: Indivíduo notificado por estar atuando em local diferente do cadastrado no sistema" required></textarea>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <button style="width: auto" type="submit" class="btn btn-style-1">
                                    <span class="icon-add"></span>
                                    Cadastrar Notificação
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if($salesman->suspenso == 0 && ($salesman->latitude == null || $salesman->longitude == null)): ?>
<div class="modal fade" id="newLocation" tabindex="-1" role="dialog" aria-labelledby="newLocationTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    Sua conta não está mais suspensa. Por favor, clique no mapa para selecionar uma
                    região em que deseja ficar.
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-div-info-overflow-x background-body">

                    <div id="newZoneSelect"></div>
                    <hr>
                    <button type="button" class="btn btn-style-1" onclick="confirmNewZoneModal()">
                        Confirmar localização
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="container-fluid mt-5" style="background-color: #fff;">
    <div class="container pt-5">
        <div class="row">
            <div class="col-sm-3">
                <div class="text-center mb-5">
                    <img src="<?= $userImage ?>" class="avatar img-circle img-thumbnail" alt="avatar">
                </div>

                <ul class="list-group box-div-info-no-padding mb-5">
                    <li class="list-group-item panel-heading">
                        <span class="icon-shopping_cart mr-2"></span>
                        Produtos e/ou serviços
                    </li>
                    <?php foreach (str_split($salesman->produto) as $product):
                        if($product == 0): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Gêneros e produtos alimentícios em geral</span></li>
                        <?php elseif ($product == 1): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Bebidas alcoólicas</span></li>
                        <?php elseif ($product == 2): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Bebidas não alcoólicas</span></li>
                        <?php elseif ($product == 3): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Brinquedos e artigos ornamentais</span></li>
                        <?php elseif ($product == 4): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Confecções, calçados e artigos de uso pessoal</span></li>
                        <?php elseif ($product == 5): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Louças, ferragens, artefatos de plástico,borracha,
                                    couro e utensílios domésticos</span></li>
                        <?php elseif ($product == 6): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Artesanato, antiguidades e artigos de arte em geral</span></li>
                        <?php elseif ($product == 7): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left"><?= $salesman->relato_atividade ?></span></li>
                        <?php endif; endforeach; ?>
                </ul>

                <ul class="list-group box-div-info-no-padding">
                    <li class="list-group-item panel-heading">
                        <span class="icon-calendar mr-2"></span>
                        Dias trabalhados
                    </li>
                    <?php foreach (str_split($salesman->atendimento_dias) as $days):
                        if($days == 0): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Domingo</span></li>
                        <?php elseif ($days == 1): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Segunda-Feira</span></li>
                        <?php elseif ($days == 2): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Terça-Feira</span></li>
                        <?php elseif ($days == 3): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Quarta-Feira</span></li>
                        <?php elseif ($days == 4): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Quinta-Feira</span></li>
                        <?php elseif ($days == 5): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Sexta-Feira</span></li>
                        <?php elseif ($days == 6): ?>
                            <li class="list-group-item text-left text-left-action background-body">
                                <span class="pull-left">Sábado</span></li>
                        <?php endif; endforeach; ?>
                </ul>
            </div>

            <div class="col-sm-9">
                <?php if($_SESSION['user']['login'] === 3): ?>
                    <ul class="nav nav-tabs">
                        <li class="active"><a class="profileCrumb" data-toggle="tab" href="#profile">Perfil</a></li>
                        <li><a class="profileCrumb" data-toggle="tab" href="#createdNotifications">Histórico</a></li>
                    </ul>
                <?php endif; ?>

                <div class="tab-content">
                    <div class="tab-pane active" id="profile">
                        <?php if($salesman->suspenso == 1): ?> <h2><span style="color: #ed2e54 !important;"> (Suspenso) </span></h2><?php endif; ?>
                        <?php if($_SESSION['user']['login'] === 3 && $salesman->suspenso == 1): ?>
                            <p class="suspension" id="suspension">Cancelar suspensão</p>
                        <?php endif; ?>

                        <div class="web-div-box mt-5 mb-5">
                            <div class="box-div-info-no-padding">
                                <div class="table-title">
                                    <span class="icon-user-o mr-2"></span>
                                    Informações pessoais
                                </div>
                                <div class="padding-header background-body">
                                    <div class="row box-div-info-overflow-x">
                                        <?php if($company != NULL): ?>
                                            <div class="col-xl-12">
                                                <div class="profile-div-data">
                                                    <h5>
                                                        <span class="icon-building mr-2"></span>
                                                        Empresa: <?= $company->nome_fantasia ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-confirmation_number mr-2"></span>
                                                    CPF: <?= $salesman->identidade; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-user-secret mr-2"></span>
                                                    RG: <?= $salesman->rg; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-user mr-2"></span>
                                                    Nome: <?= $salesman->nome; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-pregnant_woman mr-2"></span>
                                                    Mãe: <?= $salesman->nome_materno; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-phone mr-2"></span>
                                                    Fone: <?= $salesman->fone; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-markunread_mailbox mr-2"></span>
                                                    Email: <?= $salesman->email; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-area-chart mr-2"></span>
                                                    Área ocupada: <?= $salesman->area_equipamento; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-verified_user mr-2"></span>
                                                    Situação:
                                                <?php switch ($salesman->situacao):
                                                    case 0: ?>
                                                        Pendente
                                                        <?php break; case 1: ?>
                                                        Ativo
                                                        <?php break; case 2: ?>
                                                        Inadimplente
                                                        <?php break; endswitch; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-hourglass-start mr-2"></span>
                                                    Início do atendimento:
                                                <?= date('H:i', strtotime($salesman->atendimento_inicio)); ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-hourglass-end mr-2"></span>
                                                    Fim do atendimento:
                                                <?= date('H:i', strtotime($salesman->atendimento_fim)); ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-home mr-2"></span>
                                                    Endereço residencial:
                                                <?= $salesman->endereco; ?>, <?= $salesman->numero; ?>,
                                                <?= $salesman->bairro; ?>, <?= $salesman->cidade; ?>
                                                </h5>
                                            </div>
                                        </div>

                                        <div class="col-xl-12">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-street-view mr-2"></span>
                                                    Localização: <?= $salesman->end_local; ?>
                                                </h5>
                                            </div>
                                        </div>

                                        <div class="col-xl-12">
                                            <div class="profile-div-data">
                                                <button class="btn btn-style-1" data-toggle="modal"
                                                        data-target="#userAttach">
                                                    <span class="icon-file mr-2"></span>
                                                    Anexos enviados
                                                </button>
                                                <button class="btn btn-style-4" data-toggle="modal"
                                                        data-target="#userPayments">
                                                    <span class="icon-monetization_on mr-2"></span>
                                                    Pagamentos
                                                </button>
                                                <?php if($salesman->suspenso == 0 &&  $salesman->latitude != null && $salesman->longitude != null): ?>
                                                <button class="btn btn-style-3" data-toggle="modal"
                                                        data-target="#userLocation" onclick="debugMap()">
                                                    <span class="icon-map mr-2'"></span>
                                                    Localização no mapa
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($_SESSION['user']['login'] === 3): ?>
                        <div class="tab-pane mb-5" id="createdNotifications">
                            <div class="web-div-box mt-5">
                                <div class="box-div-info-no-padding">
                                    <div class="table-title">
                                        <span class="icon-history"></span>
                                        Histórico de notificações
                                    </div>
                                    <div class="padding-header background-body">
                                        <div class="box-div-info-overflow-x background-body">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Data/Hora</th>
                                                    <th scope="col">Fiscal</th>
                                                    <th scope="col">Título</th>
                                                    <th scope="col">Descrição</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if($notifications !== NULL):
                                                    foreach ($notifications as $notification): ?>
                                                        <tr class="row100 tableLink">
                                                            <td class="cell100 column5"><?= date('d-m-Y', strtotime($notification->data_notificacao)); ?> | <?= date('H:i', strtotime($notification->hora_notificacao)); ?></td>
                                                            <td class="cell100 column6"><?= explode(" ", $notification->fiscal_nome)[0]; ?> <?= explode(" ", $notification->fiscal_nome)[1]; ?></td>
                                                            <td class="cell100 column7"><?= $notification->titulo; ?></td>
                                                            <td class="cell100 column8"><?= $notification->descricao; ?></td>
                                                        </tr>
                                                    <?php endforeach; endif;?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button class="btn btn-style-4" title="Notificar ambulante" type="button"
                                                data-toggle="modal" data-target="#exampleModalLong" style="top: -5px;">
                                            <span class="icon-add_alert"></span>
                                            Notificar ambulante
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script src="<?= url("themes/assets/js/leaflet.markercluster-src.js"); ?>"></script>
<script src="<?= url("themes/assets/js/Leaflet.LinearMeasurement.js"); ?>"></script>
<script>
    let newZoneLat;
    let newZoneLng;
    let zoneConfirm = false;
    let map;
    let mapTiles = {};
    let ctrTiles = {};
    let mapLayers = {};
    let ctrLayers = {};

    $(function(){
        let paid = L.icon({
            iconUrl:"<?= url("themes/assets/img/marker-1.png"); ?>",
            shadowUrl:"<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize:[31, 40],
            shadowSize:[41, 41],
            iconAnchor:[15, 41],
            shadowAnchor:[13, 41],
            popupAnchor:[0, -41]
        });

        let pending = L.icon({
            iconUrl:"<?= url("themes/assets/img/marker-0.png"); ?>",
            shadowUrl:"<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize:[31, 40],
            shadowSize:[41, 41],
            iconAnchor:[15, 41],
            shadowAnchor:[13, 41],
            popupAnchor:[0, -41]
        });

        let expired = L.icon({
            iconUrl:"<?= url("themes/assets/img/marker-2.png"); ?>",
            shadowUrl:"<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize:[31, 40],
            shadowSize:[41, 41],
            iconAnchor:[15, 41],
            shadowAnchor:[13, 41],
            popupAnchor:[0, -41]
        });

        mapTiles['Mapa Jawg'] = L.tileLayer('https://{s}.tile.jawg.io/jawg-light/{z}/{x}/{y}{r}.png?access-token=C1vu4LOmp14JjyXqidSlK8rjeSlLK1W59o1GAfoHVOpuc6YB8FSNyOyHdoz7QIk6', {
            maxNativeZoom: 19,
            maxZoom: 20,
            minZoom: 10
        });
        ctrTiles["Mapa Jawg"] = mapTiles["Mapa Jawg"];

        mapTiles['Mapa OSM'] = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxNativeZoom: 19,
            maxZoom: 19,
            minZoom: 10
        });
        ctrTiles['Mapa OSM'] = mapTiles['Mapa OSM'];

        mapTiles['Satelite'] = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxNativeZoom: 19,
            maxZoom: 20,
            minZoom: 10,
            subdomains:['mt0','mt1','mt2','mt3']
        });
        ctrTiles["Satelite"] = mapTiles["Satelite"];

        <?php if($salesman->suspenso == 1): ?>
        swal({
            icon: "warning",
            title: "Atenção",
            text: "Este usuário teve seu perfil suspenso. Por favor, verifique seu email para visualizar mais detalhes" +
                " ou dirija-se a SEMSCS para mais informações. Seus boletos pendentes ainda deverão ser pagos.",
        });
        <?php elseif($salesman->suspenso == 0 && ($salesman->latitude == null || $salesman->longitude == null)): ?>
        var theMarker = {};
        let area = [];
        let aux = [];

        map = L.map('newZoneSelect', {
            center: [-9.6435441, -35.7257695],
            layers: [mapTiles["Mapa OSM"]],
            zoomControl: true,
            maxZoom: 20,
            minZoom: 10,
            zoom: 14
        });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        <?php if($zones != NULL):
        foreach ($zones as $zone):
        $aux = intval(($zone->quantidade_ambulantes * 100)/$zone->limite_ambulantes);
        if($aux <= 49):
            $color = "#5ea9a4";
        elseif ($aux >= 50 && $aux <= 99):
            $color = "#f5a42c";
        else:
            $color = "#ed2e54";
        endif; ?>

        area = JSON.parse('<?= json_encode($zone->poligono) ?>');

        area.forEach(function (e) {
            aux.push([e[1], e[0]]);
        });

        L.polygon(aux,{ color: '<?= $color ?>', fillColor: '<?= $color ?>' }).addTo(map);
        aux = [];

        <?php endforeach;
        endif;?>

        map.on('click', (e) => {
            newZoneLat = e.latlng.lat;
            newZoneLng = e.latlng.lng;

            map.setView(new L.LatLng(e.latlng.lat, e.latlng.lng), 14);

            if (theMarker !== undefined) {
                map.removeLayer(theMarker);
            }

            let data = {'latitude': newZoneLat, 'longitude': newZoneLng, 'id': <?= $salesman->id ?>};

            $.post("<?= $router->route("web.checkZone"); ?>", data, function (returnData) {
                if(returnData == 1){
                    zoneConfirm = true;
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng],{icon:paid}).bindPopup('Local selecionado').addTo(map);
                }else if(returnData == 2){
                    zoneConfirm = true;
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng],{icon:pending}).bindPopup('Local selecionado').addTo(map);
                }else{
                    zoneConfirm = false;
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng],{icon:expired}).bindPopup('Local selecionado').addTo(map);
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Essa zona já está com seu limite máximo de ambulantes.",
                    });
                }
            }, "html").fail(function () {
                swal({
                    icon: "error",
                    title: "Erro!",
                    text: "Erro ao processar requisição.",
                });
            });
        });

        $("#newLocation").modal('show');
        setTimeout(function() {
            map.invalidateSize();
        }, 500);
        <?php endif; ?>

        <?php if($salesman->latitude != null || $salesman->longitude != null): ?>
        mapLayers["Ponto cadastrado"] = L.layerGroup();
        ctrLayers["Ponto cadastrado"] = mapLayers["Ponto cadastrado"];

        mapLayers["Zona"] = L.layerGroup();
        ctrLayers["Zona"] = mapLayers["Zona"];

        map = L.map('mapProfile', {
            center: [<?= $salesman->latitude; ?>, <?= $salesman->longitude; ?>],
            layers: [mapTiles["Mapa OSM"]],
            zoomControl: true,
            maxZoom: 20,
            minZoom: 10,
            zoom: 14
        });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        map.on('overlayadd', function(e) {
            var groupMarker = new L.MarkerClusterGroup({
                disableClusteringAtZoom: 14,
                showCoverageOnHover: true,
                zoomToBoundsOnClick: true,
                spiderfyOnMaxZoom: true
            });

            if(e.name === "Ponto cadastrado"){
                <?php if($salesman->situacao == 0 || $salesman->situacao == 3): ?>
                L.marker(['<?= $salesman->latitude; ?>','<?= $salesman->longitude; ?>'],{icon:pending}).bindPopup('Local cadastrado').addTo(groupMarker);
                <?php elseif ($salesman->situacao == 1): ?>
                L.marker(['<?= $salesman->latitude; ?>','<?= $salesman->longitude; ?>'],{icon:paid}).bindPopup('Local cadastrado').addTo(groupMarker);
                <?php else: ?>
                L.marker(['<?= $salesman->latitude; ?>','<?= $salesman->longitude; ?>'],{icon:expired}).bindPopup('Local cadastrado').addTo(groupMarker);
                <?php endif; ?>
            }else if(e.name === "Zona"){
                let area = [];
                let aux = [];

                <?php if($zone != NULL):
                $aux = $zone->limite_ambulantes - $zone->quantidade_ambulantes;
                $aux = intval(($zone->quantidade_ambulantes * 100)/$zone->limite_ambulantes);
                if($aux <= 49):
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

                L.polygon(aux,{ color: '<?= $color ?>', fillColor: '<?= $color ?>' }).bindPopup('<div class="textPopup">Local: <?= $zone->nome ?> </div> <br> <div class="zoneInfo">Vagas: <?= $zone->limite_ambulantes ?> <br> Ocupadas: <?= $zone->quantidade_ambulantes ?> <br> Disponíveis: <?= $zone->limite_ambulantes - $zone->quantidade_ambulantes ?> </div> <br> <div class="textPopup"> <a href="<?= url("zone/". $zone->id) ?>" target="_blank">Mais informações</a></div>').addTo(mapLayers[e.name]);
                aux = [];

                <?php
                endif;?>
            }
            mapLayers[e.name].addLayer(groupMarker);
        });

        map.addLayer(mapLayers["Ponto cadastrado"]);
        <?php endif; ?>

        <?php if($_SESSION['user']['login'] === 3): ?>
        $('#form').on('submit',(function(e) {
            e.preventDefault();
            $("#loader-div").show();

            let id1 = <?= $salesman->id; ?>;
            let id2 = $("#agentSelect").val();
            let title = $("#title").val();
            let date = $("#date").val();
            let time = $("#time").val();
            let description = $("#description").val();
            let penality = $("#penality").val();
            let blockAccess = null;

            if($('#yesBlockAcess').is(':checked')) {
                blockAccess = 1;
            }else{
                blockAccess = 0;
            }

            if(id2 !== 0){
                let data = {'id1': id1, 'id2': id2, 'title': title, 'date': date, 'time': time, 'description': description, 'penality': penality, 'blockAccess': blockAccess};

                $.post("<?= $router->route("web.createNotification"); ?>", data, function (returned) {
                    $("#loader-div").hide();
                    if(returned == 1){
                        swal({
                            icon: "success",
                            title: "Sucesso!",
                            text: "A notificação foi cadastrada.",
                        }).then((value) => {
                            location.reload();
                        });
                    } else {
                        swal({
                            icon: "error",
                            title: "Erro!",
                            text: "Não foi possível cadastrar a notificação. Tente novamente mais tarde.",
                        });
                    }
                    console.log(returned);
                }, "html").fail(function () {
                    $("#loader-div").hide();
                    swal({
                        icon: "error",
                        title: "Erro!",
                        text: "Erro ao processar requisição.",
                    });
                });
            }else{
                $("#loader-div").hide();
                swal({
                    icon: "error",
                    title: "Erro!",
                    text: "Selecione um agente.",
                });
            }
        }));

        $("#suspension").on('click', function (e) {
            swal({
                icon: "warning",
                title: "Atenção",
                text: "Deseja realmente cancelar a suspensão desse ambulante?",
                buttons: {
                    cancel: "Não",
                    confirm: "Sim"
                }
            }).then((value) => {
                if(value) {
                    $("#loader-div").show();
                    let data = {'id': <?= $salesman->id; ?>};
                    $.post("<?= $router->route("web.removeSuspension"); ?>", data, function (returned) {
                        if (returned == 1) {
                            swal({
                                icon: "success",
                                title: "Sucesso!",
                                text: "O perfil não está mais suspenso.",
                            }).then(value1 => {
                                location.reload();
                            });
                        } else {
                            swal({
                                icon: "error",
                                title: "Erro!",
                                text: "Não foi possível realizar essa ação.",
                            });
                        }
                    }, "html").fail(function () {
                        $("#loader-div").hide();
                        swal({
                            icon: "error",
                            title: "Erro!",
                            text: "Erro ao processar requisição.",
                        });
                    });
                }
            });
        });
        <?php endif; ?>
    });

    <?php if($salesman->latitude == null || $salesman->longitude == null): ?>
        function closeNewZoneModal() {
            $("#newZoneDiv").hide();
        }

        function confirmNewZoneModal() {
            if(zoneConfirm === true){
                let data = {'latitude': newZoneLat, 'longitude': newZoneLng, 'id': <?= $salesman->id ?>};
                $.post("<?= $router->route("web.zoneConfirm"); ?>", data, function (returnConfirm) {
                    if(returnConfirm == 0){
                        swal({
                            icon: "error",
                            title: "Erro!",
                            text: "Já existe alguém no ponto selecionado. Por favor, selecione outra localização.",
                        });
                    }else if(returnConfirm == 2){
                        swal({
                            icon: "error",
                            title: "Erro!",
                            text: "A zona selecionada já está em seu limite máximo de ambulantes. Por favor, selecione outra localização.",
                        });
                    }else{
                        swal({
                            icon: "success",
                            title: "Sucesso!",
                            text: "A localização foi atualizada.",
                        }).then((value) => {
                            location.reload();
                        });
                    }
                });
            }else {
                swal({
                    icon: "error",
                    title: "Erro!",
                    text: "O local não é permitido ou não foi selecionado.",
                });
            }
        }
    <?php endif; ?>

    function debugMap() {
        setTimeout(function() {
            map.invalidateSize();
        }, 500);
    }

    function openFile(url) {
        window.open('<?= url() ?>/themes/assets/uploads/'+ url, '_blank');
    }
</script>
<?php $v->end(); ?>
