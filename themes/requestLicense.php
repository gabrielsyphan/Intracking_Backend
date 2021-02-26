<?php $v->layout("_theme.php"); ?>


<?php if ($cmc): ?>
<div class="container-fluid container-white mt-5">
    <div class="p-5">
        <div class="row">
            <div class="col-xl-7">
                <h2 class="black-title-section">Nova licença</h2>
                <p class="subtitle-section-p">Selecione o tipo de licença correspondente a função a qual
                    deseja exercer. Você poderá solicitar quantas licenças quiser posteriormente.</p>

                <div class="row m-0 mt-5 p-4 div-request-license" onclick="newLicense(0)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/salesman.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>Ambulante</h4>
                        <p class="subtitle-section-p">Para pessoas que exercem a profissão de ambulante.</p>
                    </div>
                </div>
                <div class="row m-0 mt-3 p-4 div-request-license" onclick="newLicense(1)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/building.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>Empresa</h4>
                        <p class="subtitle-section-p">Para empresas com funcionários ambulantes.</p>
                    </div>
                </div>
                <div class="row m-0 mt-3 p-4 div-request-license" onclick="newLicense(2)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/calendar.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>Eventuais</h4>
                        <p class="subtitle-section-p">Para atuar como ambulante em algum evento.</p>
                    </div>
                </div>
                <div class="row m-0 mt-3 p-4 div-request-license" onclick="newLicense(3)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/sale.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>Publicidade</h4>
                        <p class="subtitle-section-p">Para fixação de outdoors na cidade.</p>
                    </div>
                </div>
                <div class="row m-0 mt-3 p-4 div-request-license" onclick="newLicense(4)">
                    <div class="col-2 text-center mt-4">
                        <img src="<?= url('themes/assets/img/flag.png') ?>">
                    </div>
                    <div class="col-10">
                        <h4>Uso de solo</h4>
                        <p class="subtitle-section-p">Para vendedores que atuam em um local fixo.</p>
                    </div>
                </div>
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
<?php else: ?>
    <div class="container pt-5 mt-5">
        <div class="row">
            <div class="col-xl-12 text-center">
                <img src="<?= url('themes/assets/img/missingfile.svg') ?>" style="width: 40%;">
                <h3>Você não pode solicitar uma licença!</h3>
                <p>Solicite um cadastro mercantil junto à Secretaria de Economia de Maceió, para prosseguir com sua licença
                    clique <a href="#">aqui</a>.</p>
                <p>Para mais informações, entre em contato <a href="mailto:suporte@orditi.com">suporte@orditi.com</a></p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $v->start("scripts"); ?>
<script>
    function newLicense(license) {
        switch (license) {
            case 0:
                window.location.href = '<?= $router->route('web.salesmanLicense') ?>';
                break;
            case 1:
                window.location.href = '<?= $router->route('web.companyLicense') ?>';
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
