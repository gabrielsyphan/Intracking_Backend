<?php $v->layout("_theme.php"); ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="<?= url("themes/assets/css/leaflet.css"); ?>"/>
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<?php $v->end(); ?>


<div id="modal-1" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-xl-10 p-5 modal-overflow mh-80 container-white">
                <div class="row">
                    <div class="col-8">
                        <h3 class="black-title-section">Meus anexos</h3>
                    </div>
                    <div class="col-4 text-right mt-3">
                        <span class="icon-close" onclick="closeModal(1)"></span>
                    </div>
                </div>
                <p class="subtitle-section-p">Arquivos enviados durante seu cadastro da licença.</p>
                <hr>
                <div class="row m-0 p-4">
                    <div class="col-xl-12 mb-3 pl-5 pr-5">
                        <?php if ($uploads && count($uploads) > 0): $aux = 1;
                            foreach ($uploads as $upload): ?>
                                <div class="row div-gray-bg mb-5 p-5">
                                    <div class="col-xl-3 p-0 text-center">
                                        <img class="img-uploaded"
                                             src="<?= url('themes/assets/uploads/') ?><?= $upload['groupName'] . '/' .
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

<div id="modal-4" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-xl-10 p-5 modal-overflow container-white">
                <div class="row">
                    <div class="col-8">
                        <h3 class="black-title-section">Geolocalização da licença</h3>
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

<?php switch ($licenseValidate->status):
    case 1:
        $classColor = 'secondary-color';
        $status = 'Ativo';
        break;
    case 2:
        $classColor = 'quartenary-color';
        $status = 'Suspenso';
        break;
    case 4:
        $classColor = 'quartenary-color';
        $status = 'Cancelado';
        break;
    default:
        $classColor = 'sextiary-color';
        $status = 'Pendente';
        break;
endswitch;?>

<div class="container-fluid container-white mt-5 p-5">
    <div class="row pb-5">
        <div class="col-xl-12 d-flex">
            <img src="<?= url('themes/assets/uploads') ?>/users/<?= $user->id ?>/<?= $userImage ?>"
                 class="license-image ml-5">
            <div class="d-block ml-4">
                <h3 class="black-title-section">
                    <?= $user->nome ?>
                    <span class="status-description <?= $classColor ?>">
                        Status: <?= $status ?>
                    </span>
                </h3>
                <p class="subtitle-section-p">Informações da licença do usuário</p>
            </div>
        </div>
        <div class="col-md-12 license-menu">
            <hr>
            <div class="row">
                <div class="col-md-3" onclick="changeWindow('info')">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-10 p-0 mb-5 cursor-pointer">
                            <div class="p-4 text-center background-green b-radius-top">
                                <div class="circle-card-option">
                                    <span class="icon-drivers-license"></span>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="p-5 text-center gray-box b-radius-bottom">
                                Visualizar licença
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3" onclick="changeWindow('payment')">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-10 p-0 mb-5 cursor-pointer">
                            <div class="p-4 text-center background-green b-radius-top">
                                <div class="circle-card-option">
                                    <span class="icon-payment"></span>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="p-5 text-center gray-box b-radius-bottom">
                                Visualizar pagamentos
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3" onclick="openModal(1)">
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

                <div class="col-md-3" onclick="openOrder()">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-10 p-0 mb-5 cursor-pointer">
                            <div class="p-4 text-center background-green b-radius-top">
                                <div class="circle-card-option">
                                    <span class="icon-file-pdf-o"></span>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="p-5 text-center gray-box b-radius-bottom">
                                Visualizar alvará
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-10 p-0 mb-5 cursor-pointer">
                            <div class="p-4 text-center background-green b-radius-top">
                                <div class="circle-card-option">
                                    <span class="icon-users"></span>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="p-5 text-center gray-box b-radius-bottom">
                                Cadastro de auxiliares
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3" onclick="debugMap()">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-10 p-0 mb-5 cursor-pointer">
                            <div class="p-4 text-center background-green b-radius-top">
                                <div class="circle-card-option">
                                    <span class="icon-map2"></span>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="p-5 text-center gray-box b-radius-bottom">
                                Geolocalização da licença
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3" onclick="licenseCancel('<?= md5($license->id) ?>')">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-10 p-0 mb-5 cursor-pointer">
                            <div class="p-4 text-center background-red b-radius-top">
                                <div class="circle-card-option-red">
                                    <span class="icon-cancel"></span>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="p-5 text-center gray-box b-radius-bottom">
                                Solicitar cancelamento da licença
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($_SESSION['user']['role'] == 2 || $_SESSION['user']['role'] == 3 ||  $_SESSION['user']['role'] == 4): ?>
                    <div class="col-md-3" onclick="changeWindow('block')">
                        <div class="row mt-3 justify-content-center">
                            <div class="col-md-10 p-0 mb-5 cursor-pointer">
                                <div class="p-4 text-center background-red b-radius-top">
                                    <div class="circle-card-option-red">
                                        <span class="icon-block"></span>
                                    </div>
                                </div>
                                <hr class="m-0">
                                <div class="p-5 text-center gray-box b-radius-bottom">
                                    Multar ou bloquear licença
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($companyConfirm == true): ?>
                <?php if ($licenseStatus == 4): ?>
                    <div class="row m-0 mt-3 p-4 border-left-red div-request-license mb-5">
                        <div class="col-2 text-center mt-4">
                            <img src="<?= url('themes/assets/img/map.png') ?>">
                        </div>
                        <div class="col-10">
                            <h4 class="black-title-section">Situação</h4>

                            <p class="subtitle-section-p">Aceite ou recuse o cadastro do Ambulante em sua empresa.</p>
                            <button id="accept" class="btn-3 primary" data-ajax-click data-method="POST"
                                    data-action="<?= $router->route('web.licenseStatus') ?>"
                                    data-form-data="id=<?= $licenseValidate->id ?>&status=0">Aceitar
                            </button>
                            <button class="btn-3 secondary-color" data-ajax-click data-method="POST"
                                    data-action="<?= $router->route('web.licenseStatus') ?>"
                                    data-form-data="id=<?= $licenseValidate->id ?>&status=3">Recusar
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="col-md-12 license-info display-none">
            <div class="float-right">
                <button class="btn secondary-color btn-menu" onclick="backMenu('info')">
                    <span class="icon-arrow_back"></span>
                </button>
                <button class="btn primary-color btn-menu" onclick="window.print()">
                    <span class="icon-print2"></span>
                </button>
            </div>
            <hr>
            <div class="div-gray-bg border-top-green p-5">
                <div class="header-title">
                    <h4 class="black-title-section">Informações da licença</h4>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-3 subtitle-section-p">
                        Status:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $status ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Cpf:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $user->cpf ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Nome:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $user->nome ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Email:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $user->email ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Telefone:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= $user->telefone ?>
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
                        <?= date('d / m / Y', strtotime($licenseValidate->data_inicio)) ?>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-3 subtitle-section-p">
                        Fim da licença:
                    </div>
                    <div class="col-9 subtitle-section-p text-right">
                        <?= date('d / m / Y', strtotime($licenseValidate->data_fim)) ?>
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

                <div class="row mt-5">
                    <div class="col-md-6">
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
                    </div>
                    <div class="col-md-6">
                        <h4 class="black-title-section">Horário de trabalho</h4>
                        <hr>
                        <p class="subtitle-section-p"><?= $license->atendimento_hora_inicio ?>
                            - <?= $license->atendimento_hora_fim ?></p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5 d-none license-url-link">
                <img class="mb-5 w-25" src="<?= url('themes/assets/img/nav-logo.png') ?>">
                <h5 class="black-title-section">Disponível ao acesso em:
                    <?= url('licenseinfo/1/'. md5($user->id)) ?></h5>
            </div>
        </div>
        <div class="col-md-12 license-payment display-none">
            <div class="float-right">
                <button class="btn secondary-color btn-menu" onclick="backMenu('payment')">
                    <span class="icon-arrow_back"></span>
                </button>
            </div>
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
                                               href="<?= BOLETOS . $payment->cod_referencia ?>"
                                               target="_blank">Pagar</a>
                                        <?php elseif ($payment->status == 0 || $payment->status == 3): ?>
                                            <a class="btn-3 tertiary"
                                               href="<?= BOLETOS . $payment->cod_referencia ?>"
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
        <?php if ($_SESSION['user']['role'] == 2 || $_SESSION['user']['role'] == 3 ||  $_SESSION['user']['role'] == 4): ?>
            <div class="col-md-12 license-block display-none">
                <div class="float-right">
                    <button class="btn secondary-color btn-menu" onclick="backMenu('block')">
                        <span class="icon-arrow_back"></span>
                    </button>
                </div>
                <hr>
                <div class="row m-0">
                    <?php if ($_SESSION['user']['role'] == 3): ?>
                        <div class="col-6">
                            <div class="div-gray-bg border-top-green p-5">
                                <div class="header-title">
                                    <h4 class="black-title-section">Multar ou bloquear licença</h4>
                                    <hr>
                                </div>
                                <form id="pay-form" method="POST" action="<?= $router->route('web.licenseBlock') ?>">
                                    <fieldset>
                                        <input type="hidden" name="licenseId" value="<?= md5($license->id) ?>">
                                        <div class="form-group">
                                            <label>Título:</label>
                                            <input type="text" class="form-input" placeholder="Insira um título para a ação" name="punishmentTitle">
                                            <div class="invalidate-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Decrição:</label>
                                            <input type="text" class="form-input" placeholder="Descreva o motivo" name="punishmentDesciption">
                                            <div class="invalidate-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Valor:</label>
                                            <input type="number" class="form-input" placeholder="Digite o valor da multa" name="punishmentValue">
                                            <div class="invalidate-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Situação:</label>
                                            <label class="control control--radio">Permitida
                                                <input type="radio" name="punishmentStatus" value="0" checked="checked"/>
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio">Bloqueada
                                                <input type="radio" value="1" name="punishmentStatus"/>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="float-right">
                                            <button class="btn-3 primary c-white">Confirmar</button>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-6">
                        <div class="div-gray-bg border-top-green p-5">
                            <div class="header-title">
                                <h4 class="black-title-section">Cadastrar nova notificação</h4>
                                <hr>
                            </div>
                            <form id="form-create-notification" action="<?= $router->route("web.createNotification"); ?>"
                                  method="POST">
                                <fieldset>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Título:</label>
                                                <input type="text" class="form-input" id="title" name="title"
                                                       title="Insira um título para a notificação"
                                                       placeholder="Ex.: Local irregular">
                                                <div class="invalidate-feedback"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Data da notificação:</label>
                                                <input type="date" class="form-input" id="date" name="date"
                                                       title="Data da notificação">
                                                <div class="invalidate-feedback"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Hora da notificação:</label>
                                                <input type="time" class="form-input" id="time" name="time"
                                                       title="Hora da notificação">
                                                <div class="invalidate-feedback"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Fiscal:</label>
                                                <select id="agentSelect" class="form-input" name="agentSelect">
                                                    <option style="display: none;" class="opt0" value="0">Selecione o fiscal
                                                        responsável pela notificação
                                                    </option>
                                                    <?php foreach ($agents as $agent): ?>
                                                        <option value="<?= $agent->id ?>"
                                                                selected><?= $agent->nome ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Descrição:</label>
                                                <input type="text" class="form-input" id="noticationDescription"
                                                       name="noticationDescription"
                                                       placeholder="Ex.: Indivíduo notificado por estar atuando em local diferente do cadastrado no sistema">
                                                <div class="invalidate-feedback"></div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="licenseId" value="<?= md5($license->id); ?>">
                                        <input type="hidden" name="userId" value="<?= md5($user->id); ?>">

                                        <div class="col-xl-12 text-right mt-4">
                                            <button type="submit" class="btn-3 primary">
                                                Cadastrar
                                            </button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row m-0 mt-5 div-gray-bg border-top-green p-5">
                            <?php if ($notifications): ?>
                                <div class="col-12">
                                    <h4 class="black-title-section">Histórico de notificações</h4>
                                    <hr>
                                </div>
                                <?php foreach ($notifications as $notification): ?>
                                    <div class="col-md-6 mb-5">
                                        <div class="container-white border-left-red p-5">
                                            <h4 class="black-title-section"><?= $notification->titulo ?></h4>
                                            <p class="subtitle-section-p"><?= $notification->descricao ?></p>
                                            <p class="subtitle-section-p">
                                                Realizado em <?= date("d/m/y", strtotime($notification->data_notificacao)); ?>
                                                as <?= $notification->hora_notificacao ?>
                                            </p>
                                            <?php if ($notification->id_boleto): ?>
                                                <a href="<?= BOLETOS . $notification->cod_referencia ?>" target="blank"
                                                   class="text-red">
                                                    Visualizar boleto
                                                </a>
                                            <?php endif; ?>
                                            <div class="text-right">
                                                <label class="black-title-section"><?= $notification->agentName ?></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; else: ?>
                                <div class="col-12 text-center p-5">
                                    <img class="w-25" src="<?= url('themes/assets/img/empty-list.svg') ?>">
                                    <p class="subtitle-section-p mt-3">Não há notificações cadastradas para esse ambulante.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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

        <?php if($licenseStatus == 0 || $licenseStatus == 3): ?>
        L.marker(['<?= $license->latitude; ?>', '<?= $license->longitude; ?>'], {icon: pending}).bindPopup('Local cadastrado').addTo(map);
        <?php elseif ($license->situacao == 1): ?>
        L.marker(['<?= $license->latitude; ?>', '<?= $license->longitude; ?>'], {icon: paid}).bindPopup('Local cadastrado').addTo(map);
        <?php else: ?>
        L.marker(['<?= $license->latitude; ?>', '<?= $license->longitude; ?>'], {icon: expired}).bindPopup('Local cadastrado').addTo(map);
        <?php endif; ?>
    });

    $('[data-ajax-click]').on('click', function (e) {
        e.preventDefault();
        $("#loader-div").show();

        const method = $(this).data('method');
        const url = $(this).data('action');
        let data = $(this).data('form-data');

        const formData = new FormData();

        if (data) {
            let decodeFormData = decodeURI(data);

            if (decodeFormData.indexOf('&') == -1) {
                let [name, value] = decodeFormData.split('=');
                console.log(name, value);
                formData.append(name, value);
            } else {
                data = decodeFormData.split('&');

                for (let item of data) {
                    let [name, value] = item.split('=');
                    console.log(name, value);
                    formData.append(name, value);
                }
            }
        }

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false
        }).done(function (returnData) {
            console.log("Sucesso");
            location.reload();
        }).fail(function () {
            console.log("Fail");
        }).always(function () {
            console.log("Sempre");
            $("#loader-div").hide();
        });
    });

    $('#form-create-notification').on('submit', function (e) {
        e.preventDefault();
        $("#loader-div").show();

        const _thisForm = $(this);
        const data = new FormData(this);
        const fieldsetDisable = _thisForm.find('fieldset');
        fieldsetDisable.attr('disabled', true);

        if (formSubmit(this) === true) {
            $.ajax({
                type: _thisForm.attr('method'),
                url: _thisForm.attr('action'),
                data: data,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function (returnData) {
                if (returnData == 1) {
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
                fieldsetDisable.removeAttr("disabled");
            });
        }
    });

    function debugMap() {
        setTimeout(function () {
            map.invalidateSize();
        }, 500);

        openModal(4);
    }

    function openOrder() {
        window.open('<?= url('order') ?>/1/<?= md5($license->id) ?>', '_blank')
    }

    function changeWindow(value) {
        $('.license-menu').hide();
        $('.license-'+ value).show();
    }

    function backMenu(value) {
        $('.license-'+ value).hide();
        $('.license-menu').show();
    }

    function licenseCancel(value) {
        swal({
            title: 'Tem certeza que deseja cancelar sua licença?',
            text: 'Após o cancelamento, você não terá mais a permissão para a realização do comércio ambulante.',
            icon: 'warning',
            buttons: ['Voltar', 'Continuar'],
            dangerMode: true,
        }).then((btn) => {
            if (btn) {
                swal({
                    text: 'Para confirmar o cancelamento da licença, digite o seu cpf:',
                    content: 'input',
                    buttons: [
                        "Voltar",
                        "Confirmar"
                    ]
                }).then(input => {
                    if (input) {
                        const identity = '<?= $user->cpf ?>';
                        if (input == identity) {
                            $("#loader-div").show();
                            const data = {'id': '<?= md5($license->id) ?>', 'identity': '<?= $user->cpf ?>'};
                            $.ajax({
                                type: 'POST',
                                url: '<?= $router->route('web.licenseCancel') ?>',
                                data: data
                            }).done(function (returnData) {
                                returnData = JSON.parse(returnData);
                                if (returnData.hasOwnProperty('payments')) {
                                    swal({
                                        icon: 'warning',
                                        title: 'Ops..!',
                                        text: 'Você precisa pagar os seus boletos antes de solicitar o cancelamento' +
                                            'da licença.'
                                    });
                                } else if (returnData.hasOwnProperty('blocked')) {
                                    swal({
                                        icon: 'warning',
                                        title: 'Ops..!',
                                        text: 'Você não pode solicitar o cancelamento pois sua licença está bloqueada.'
                                    });
                                } else {
                                    swal({
                                        icon: 'success',
                                        title: 'Sucesso!',
                                        text: 'Sua licença foi cancelada.'
                                    }).then((result) => {
                                        window.location.reload();
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
                        } else {
                            swal({
                                icon: 'warning',
                                title: 'Ops..!',
                                text: 'O cpf digitado está incorreto. Verifique se o campo foi preenchido corretamente.'
                            });
                        }
                    }
                });
            }
        });
    }

    <?php if ($_SESSION['user']['role'] == 3 ||  $_SESSION['user']['role'] == 4): ?>
    $('#pay-form').on('submit', function (e) {
        e.preventDefault();
        $("#loader-div").show();

        const _thisForm = $(this);
        const data = new FormData(this);
        const fieldsetDisable = _thisForm.find('fieldset');
        fieldsetDisable.attr('disabled', true);

        if (formSubmit(this) === true) {
            $.ajax({
                type: _thisForm.attr('method'),
                url: _thisForm.attr('action'),
                data: data,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function (returnData) {
                const response = JSON.parse(returnData);
                if (response.success) {
                    swal({
                        icon: "success",
                        title: "Sucesso!",
                        text: "A licença foi atualizada.",
                    }).then((value) => {
                        window.location.reload();
                    });
                } else {
                    swal({
                        icon: "warning",
                        title: "Ops..!",
                        text: "Não foi possível atualizar a licença,tente novamente mais tarde.",
                    });
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
                fieldsetDisable.removeAttr("disabled");
            });
        }
    });
    <?php endif; ?>
</script>
<?php $v->end(); ?>
