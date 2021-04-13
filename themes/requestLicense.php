<?php $v->layout("_theme.php"); ?>


<?php if (!$cmc): ?>
<div class="container-fluid container-white mt-5">
    <div class="p-5">
        <div class="row">
            <div class="col-xl-7">
                <h2 class="black-title-section">Nova licença</h2>
                <p class="subtitle-section-p">Selecione o tipo de licença correspondente a função a qual
                    deseja exercer. Você poderá solicitar quantas licenças quiser posteriormente.</p>

                <?php if((isset($_SESSION['user']['team']) && $_SESSION['user']['team'] == 1) || !isset($_SESSION['user']['team'])): ?>
                <div class="row m-0 mt-5 p-4 div-request-license" onclick="newLicense(1)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/salesman.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>Ambulante</h4>
                        <p class="subtitle-section-p">Para pessoas que exercem a profissão de ambulante.</p>
                    </div>
                </div>
                <div class="row m-0 mt-3 p-4 div-request-license" onclick="newLicense(2)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/building.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>Empresa</h4>
                        <p class="subtitle-section-p">Para empresas com funcionários ambulantes.</p>
                    </div>
                </div>
                <div class="row m-0 mt-3 p-4 div-request-license" onclick="newLicense(3)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/calendar.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>Eventuais</h4>
                        <p class="subtitle-section-p">Para atuar como ambulante em algum evento.</p>
                    </div>
                </div>
                <div class="row m-0 mt-3 p-4 div-request-license" onclick="newLicense(4)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/sale.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>Publicidade</h4>
                        <p class="subtitle-section-p">Para fixação de outdoors na cidade.</p>
                    </div>
                </div>
                <div class="row m-0 mt-3 p-4 div-request-license" onclick="newLicense(5)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/flag.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>Uso de solo</h4>
                        <p class="subtitle-section-p">Para vendedores que atuam em um local fixo.</p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row m-0 mt-3 p-4 div-request-license" onclick="newLicense(6)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/foodTruck.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>FoodTrucks</h4>
                        <p class="subtitle-section-p">Para foodtrucks na cidade.</p>
                    </div>
                </div>

                <?php if((isset($_SESSION['user']['team']) && $_SESSION['user']['team'] == 2) || !isset($_SESSION['user']['team'])): ?>
                <div class="row m-0 mt-3 p-4 div-request-license" onclick="newLicense(7)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/market.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>Mercado público</h4>
                        <p class="subtitle-section-p">Para vendedores que atuam em um mercado.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-xl-5 mt-5 pt-5 text-center image-request-license">
                <img style="width: 80%; margin-top: 150px" src="<?= url('themes/assets/img/license.svg') ?>">
                <div class="p-5">
                    <p class="subtitle-section-p">Após o cadastro, sua licença ficará disponível
                        na área de "Minhas licenças"</p>
                </div>
            </div>
            <div class="col-xl-12">
                <hr>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script>
    function newLicense(license) {
        switch (license) {
            case 1:
                window.location.href = '<?= isset($user) ? url('salesmanLicenseUser/' . md5($user->id)) : url('salesmanLicense') ?>';
                break;
            case 2:
                window.location.href = '<?= isset($user) ? url('companyLicenseUser/' . md5($user->id)) : url('companyLicense') ?>';
                break;
            case 5:
                window.location.href = '<?= isset($user) ? url('occupationLicenseUser/' . md5($user->id)) : url('occupationLicense') ?>';
                break;
            case 6:
                window.location.href = '<?= isset($user) ? url('foodTruckLicenseUser/' . md5($user->id)) : url('foodTruckLicense') ?>';
                break;
            case 7:
                window.location.href = '<?= isset($user) ? url('marketLicenseUser/' . md5($user->id)) : url('marketLicense') ?>';
                break;
            default:
                swal({
                    icon: "warning",
                    title: "Ops...",
                    text: "Esta licença não está disponível no momento.",
                });
                break;
        }
    }

    function validateCpf(e) {
        $("#loader-div").show();
        let cpf = formatedCPF(e);

        if (checkCpf(cpf) == false) {
            $("#loader-div").hide();
            swal({
                icon: "error",
                title: "Erro",
                text: "O CPF digitado não é válido. Por favor, insira um CPF válido e tente novamente.",
            });
        } else {
            let data = {'cpf': cpf};
            $.post("<?= $router->route("web.checkAccount"); ?>", data, function (e) {
                $("#loader-div").hide();
                if (e == 0) {
                    swal({
                        icon: "warning",
                        title: "Atenção",
                        text: "Não será possível realizar o cadastro. Por favor, dirija-se a secretaria de economia e realize seu cadastro mercantil de pessoa física ou jurídica para então dar prosseguimento com o do Orditi.",
                    });
                }
            }, "html").fail(function () {
                $("#loader-div").hide();
                swal({
                    icon: "~warning",
                    title: "Atenção",
                    text: "Erro ao processar requisição.",
                });
            });
        }
    }
</script>
<?php $v->end(); ?>
<?php endif; ?>
