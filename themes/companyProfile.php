<?php $v->layout("_theme.php") ?>

<div class="modal fade" id="companyPayments" tabindex="-1" role="dialog" aria-labelledby="companyPaymentsLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Empresa / Pagamentos</h5>
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
                            <th scope="col">Ambulante</th>
                            <th scope="col">Validade</th>
                            <th scope="col">Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($payments !== NULL):
                            foreach ($payments as $payment):
                                if($payment->status == 3 || $payment->status == 0):
                                    $status = "Pendente";
                                elseif ($payment->status == 1):
                                    $status = "Pago";
                                else:
                                    $status = "Vencido";
                                endif; ?>
                                <tr>
                                    <?php if ($payment->status == 2): ?>
                                        <td class="statusExpired"><?= $status ?></td>
                                    <?php elseif($payment->status == 0 || $payment->status == 3): ?>
                                        <td class="statusPendent"><?= $status ?></td>
                                    <?php else: ?>
                                        <td class="statusPaid"><?= $status ?></td>
                                    <?php endif;?>
                                    <td>R$ <?= $payment->valor  ?>,00</td>
                                    <td><?= $payment->name ?></td>
                                    <td><?= date('d-m-Y', strtotime($payment->pagar_em)); ?></td>
                                    <td>
                                        <?php if ($payment->status == 2): ?>
                                            <a class="btn btn-style-4" href="http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?<?= $payment->cod_referencia ?>" target="_blank">Pagar boleto</a>
                                        <?php elseif($payment->status == 0 || $payment->status == 3): ?>
                                            <a class="btn btn-style-3" href="http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?<?= $payment->cod_referencia ?>" target="_blank">Pagar boleto</a>
                                        <?php else: ?>
                                            Não há ações
                                        <?php endif;?>
                                    </td>
                                </tr>
                            <?php endforeach; endif;?>
                        </tbody>
                    </table>
                </div>
                <div class="divInfo">
                    <p class="pInfo">Os status dos pagamentos serão atualizados todos os dias as 18:00.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="companySalesmans" tabindex="-1" role="dialog" aria-labelledby="companySalesmansLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Empresa / Ambulantes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-div-info-overflow-x background-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">CPF</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Localização</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($salesmans !== NULL):
                            $aux = 1;
                            foreach ($salesmans as $salesman): ?>
                                <tr onclick="openPage('<?= $salesman->id ?>')">
                                    <td style="font-weight: bold"><?= $aux ?></td>
                                    <td><?= $salesman->identidade ?></td>
                                    <td><?= $salesman->nome ?></td>
                                    <td><?= $salesman->end_local ?></td>
                                </tr>
                                <?php $aux++; endforeach; endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="companyAttachs" tabindex="-1" role="dialog" aria-labelledby="companyAttachsLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Empresa / Anexos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body background-body">
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

<div class="container-fluid mt-5" style="background-color: #fff;">
    <div class="container pt-5">
        <div class="row">
            <div class="col-sm-3">
                <div class="text-center">
                    <img src="<?= $userImage; ?>" class="avatar img-circle img-thumbnail" alt="avatar">
                </div>
                <br>
                <ul class="list-group box-div-info-no-padding mb-5">
                    <li class="list-group-item panel-heading">
                        <span class="icon-shopping_cart mr-2"></span>
                        Produtos e/ou serviços
                    </li>
                    <?php foreach (str_split($company->produto) as $product):
                        if($product == 0): ?>
                            <li class="list-group-item text-left text-left-action"><span class="pull-left">Gêneros e produtos alimentícios em geral</span></li>
                        <?php elseif ($product == 1): ?>
                            <li class="list-group-item text-left text-left-action"><span class="pull-left">Bebidas alcoólicas</span></li>
                        <?php elseif ($product == 2): ?>
                            <li class="list-group-item text-left text-left-action"><span class="pull-left">Bebidas não alcoólicas</span></li>
                        <?php elseif ($product == 3): ?>
                            <li class="list-group-item text-left text-left-action"><span class="pull-left">Brinquedos e artigos ornamentais</span></li>
                        <?php elseif ($product == 4): ?>
                            <li class="list-group-item text-left text-left-action"><span class="pull-left">Confecções, calçados e artigos de uso pessoal</span></li>
                        <?php elseif ($product == 5): ?>
                            <li class="list-group-item text-left text-left-action"><span class="pull-left">Louças, ferragens, artefatos de plástico,borracha, couro e utensílios domésticos</span></li>
                        <?php elseif ($product == 6): ?>
                            <li class="list-group-item text-left text-left-action"><span class="pull-left">Artesanato, antiguidades e artigos de arte em geral</span></li>
                        <?php elseif ($product == 7): ?>
                            <li class="list-group-item text-left text-left-action"><span class="pull-left"><?= $company->relato_atividade ?></span></li>
                        <?php endif; endforeach; ?>
                </ul>

                <ul class="list-group">
                    <ul class="list-group box-div-info-no-padding mb-5">
                        <li class="list-group-item panel-heading">
                            <span class="icon-info mr-2"></span>
                            Atividade
                        </li>
                    <li class="list-group-item text-right text-left-action"><span class="pull-left">Ambulantes</span> <?= $salesmansCount; ?></li>
                    <li class="list-group-item text-right text-left-action"><span class="pull-left">Equipamentos</span> <?= $company->quantidade_equipamentos; ?></li>
                </ul>
            </div>

            <div class="col-sm-9 mb-5">
                <div class="tab-content">
                    <div class="tab-pane active" id="profile">
                        <div class="web-div-box">
                            <div class="box-div-info-no-padding">
                                <div class="table-title">
                                    <span class="icon-building mr-2"></span>
                                    Informações da empresa
                                </div>
                                <div class="padding-header background-body">
                                    <div class="row box-div-info-overflow-x">
                                        <div class="col-xl-12">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-users mr-2"></span>
                                                    Nome de fantasia: <?= $company->nome_fantasia; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-verified_user mr-2"></span>
                                                    CNPJ: <?= $company->cnpj; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-streetview mr-2"></span>
                                                    CMC: <?= $company->cmc; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-user-secret mr-2"></span>
                                                    RG: <?= $company->rg; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-user-secret mr-2"></span>
                                                    CPF: <?= $company->cpf; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-user mr-2"></span>
                                                    Cadastrante: <?= $company->nome; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-pregnant_woman mr-2"></span>
                                                    Nome da mãe: <?= $company->nome_materno; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-phone mr-2"></span>
                                                    Fone: <?= $company->fone; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-markunread_mailbox mr-2"></span>
                                                    Email: <?= $company->email; ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-street-view mr-2"></span>
                                                    Endereço da sede: <?= $company->endereco; ?>
                                                </h5>
                                            </div>
                                        </div>

                                        <div class="col-xl-12">
                                            <div class="profile-div-data">
                                                <h5>
                                                    <span class="icon-input mr-2"></span>
                                                    Relato da atividade: <?= $company->relato_atividade; ?>
                                                </h5>
                                            </div>
                                        </div>

                                        <div class="col-xl-12">
                                            <div class="profile-div-data">
                                                <button class="btn btn-style-1" data-toggle="modal"
                                                        data-target="#companyAttachs">
                                                    <span class="icon-file mr-2"></span>
                                                    Anexos
                                                </button>

                                                <button class="btn btn-style-4" data-toggle="modal"
                                                        data-target="#companyPayments">
                                                    <span class="icon-monetization_on mr-2"></span>
                                                    Pagamentos
                                                </button>

                                                <button class="btn btn-style-3" data-toggle="modal"
                                                        data-target="#companySalesmans">
                                                    <span class="icon-list mr-2"></span>
                                                    Ambulantes cadastrados
                                                </button>

                                                <button class="btn btn-style-2" data-toggle="modal"
                                                        data-target="#companyPayments">
                                                    <span class="icon-user-plus mr-2"></span>
                                                    Novo ambulante
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script>
    let lat = 0;
    let lng = 0;

    $(function() {
        let identity = $("#identity");
        let cnpj = $("#cnpj");
        let rg = $("#rg");
        let cmc = $("#cmc");
        let phone = $("#phone");
        let width = $("#width");
        let length = $("#length");
        let postcode = $("#postcode");

        width.mask('00.00', {reverse: true});
        length.mask('00.00', {reverse: true});
        identity.mask('000.000.000-00', {reverse: true});
        cnpj.mask("99.999.999/9999-99");
        rg.mask('0000000-0', {reverse: true});
        cmc.mask('0000000-0', {reverse: true});
        phone.mask('0000-0000', {reverse: true});
        postcode.mask('00000000', {reverse: true});

        $('#workedDays').multiselect();
        var theMarker = {};
        var map = null;
        var map_tiles = {};
        var ctr_tiles = {};
        var ctr_layers = {};

        map_tiles['Mapa Jawg'] = L.tileLayer('https://{s}.tile.jawg.io/jawg-light/{z}/{x}/{y}{r}.png?access-token=C1vu4LOmp14JjyXqidSlK8rjeSlLK1W59o1GAfoHVOpuc6YB8FSNyOyHdoz7QIk6', {
            maxNativeZoom: 19,
            maxZoom: 18,
            minZoom: 10
        });
        ctr_tiles["Mapa Jawg"] = map_tiles["Mapa Jawg"];

        map_tiles['Satelite'] = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxNativeZoom: 19,
            maxZoom: 20,
            minZoom: 10,
            subdomains:['mt0','mt1','mt2','mt3']
        });
        ctr_tiles["Satelite"] = map_tiles["Satelite"];

        map = L.map('mapCreateAccount', {
            center: [-9.6435441, -35.7257695],
            layers: [map_tiles["Mapa Jawg"]],
            zoomControl: true,
            maxZoom: 18,
            minZoom: 10,
            zoom: 14
        });

        L.control.layers(ctr_tiles, ctr_layers).addTo(map);

        let pending = L.icon({
            iconUrl:"<?= url("themes/assets/img/marker-0.png"); ?>",
            shadowUrl:"<?= url("themes/assets/img/marker-shadow.png"); ?>",

            iconSize:[31, 40],
            shadowSize:[41, 41],
            iconAnchor:[15, 41],
            shadowAnchor:[13, 41],
            popupAnchor:[0, -41]
        });

        let paid = L.icon({
            iconUrl:"<?= url("themes/assets/img/marker-1.png"); ?>",
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

        L.polygon(aux, {color: '<?= $color ?>', fillColor: '<?= $color ?>'}).addTo(map);
        aux = [];

        <?php endforeach;
        endif;?>


        map.on('click', (e) => {
            lat = e.latlng.lat;
            lng = e.latlng.lng;

            if (theMarker !== undefined) {
                map.removeLayer(theMarker);
            }

            let data = {'latitude': lat, 'longitude': lng};
            $.post("<?= $router->route("web.checkZone"); ?>", data, function (returnData) {
                if(returnData == 1){
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng],{icon:paid}).bindPopup('Local selecionado').addTo(map);
                }else if(returnData == 2){
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng],{icon:pending}).bindPopup('Local selecionado').addTo(map);
                }else{
                    theMarker = L.marker([e.latlng.lat, e.latlng.lng],{icon:expired}).bindPopup('Local selecionado').addTo(map);
                    alert("Essa área já está com seu limite máximo de ambulante.");
                }
            }, "html").fail(function () {
                alert("Erro ao processar requisição!");
            });
        });
    });

    function nextPage(value) {
        if(value === 2){
            $("#modalPage1").animate({opacity: 0}, "fast", function(){
                document.getElementById('modalPage1').style.display = 'none';
                $("#modalPage2").animate({opacity: 1}, "fast", function(){
                    document.getElementById('modalPage2').style.display = 'block';
                });
            });
        }else if(value === 3){
            $("#modalPage2").animate({opacity: 0}, "fast", function(){
                document.getElementById('modalPage2').style.display = 'none';
                $("#modalPage3").animate({opacity: 1}, "fast", function(){
                    document.getElementById('modalPage3').style.display = 'block';
                });
            });
        }
    }

    function lastPage(value) {
        if(value === 2){
            $("#modalPage3").animate({opacity: 0}, "fast", function(){
                document.getElementById('modalPage3').style.display = 'none';
                $("#modalPage2").animate({opacity: 1}, "fast", function(){
                    document.getElementById('modalPage2').style.display = 'block';
                });
            });
        }else if(value === 1){
            $("#modalPage2").animate({opacity: 0}, "fast", function(){
                document.getElementById('modalPage2').style.display = 'none';
                $("#modalPage1").animate({opacity: 1}, "fast", function(){
                    document.getElementById('modalPage1').style.display = 'block';
                });
            });
        }
    }

    $('#teamForm').on('submit',(function(e) {
        e.preventDefault();
        $("#loader-div").show();

        let inputHidden = "<input type='hidden' name='latitude' value='"+ lat+ "'> <input type='hidden' name='longitude' value='"+ lng+ "'>";
        $("#inputHidden").empty();
        $("#inputHidden").append(inputHidden);
        let data = new FormData(this);

        $.ajax({
            type:'POST',
            url: "<?= $router->route("web.validateAccount"); ?>",
            data:data,
            cache:false,
            contentType: false,
            processData: false,
            success:function(returnData){
                $("#loader-div").hide();
                if(returnData == 0){
                    alert("Insira uma descrição sobre o serviço ou produto vendido.");
                }else if(returnData == 1){
                    alert("Já existe alguém cadastrado com esses dados.");
                }else if(returnData == 2){
                    alert("Não há vagas disponívels no local selecionado. Por favor, selecione outra zona.");
                }else if(returnData == 3){
                    alert("Não será possível realizar o cadastro. Por favor, dirija-se a secretaria de economia e realize seu cadastro mercantil de pessoa física ou jurídica para então dar prosseguimento ao seu cadastro no Orditi.");
                }else if(returnData == 4) {
                    alert("CPF inválido. Por favor, insira um CPF válido.");
                }else if(returnData.length > 6){
                    alert("Já existe alguém no local cadastrado.");
                }else{
                    alert("Conta cadastrada com sucesso! Sua senha no ORDITI será: "+ returnData);
                    window.location.href = "<?= url('profile'); ?>";
                }
                console.log(returnData);
            },
            error: function(returnData){
                $("#loader-div").hide();
                console.log("error");
                console.log(returnData);
            }
        });
    }));

    function openPage(data) {
        window.open("<?= url('salesman'); ?>/"+ data, '_blank');
    }

    $("#identity").focusout(function (){
        let cpf = $(this).val();
        if(cpf){
            $("#loader-div").show();
            cpf = cpf.split('-');
            cpf = cpf[0]+''+cpf[1];
            cpf = cpf.split('.');
            cpf = cpf[0]+''+cpf[1]+''+cpf[2];

            if(checkCpf(cpf) === false){
                $("#loader-div").hide();
                alert('O CPF digitado não é válido. Por favor, insira um CPF válido e tente novamente.');
            }else{
                let data = {'cpf': cpf};
                $.post("<?= $router->route("web.checkAccount"); ?>", data, function (e) {
                    $("#loader-div").hide();
                    if(e == 0){
                        alert('Não será possível realizar o cadastro. Por favor, dirija esse funcionário a secretaria de economia para que possa realizar o cadastro mercantil de pessoa física ou jurídica para então dar prosseguimento ao cadastro no Orditi.');
                    }
                }, "html").fail(function () {
                    $("#loader-div").hide();
                    alert("Erro ao processar requisição.");
                });
            }
        }
    });

    function openFile(url) {
        window.open('<?= url() ?>/themes/assets/uploads/'+ url, '_blank');
    }
</script>
<?php $v->end(); ?>
