<?php $v->layout("_theme.php") ?>

<div class="container">
    <div class="row">
        <div class="col-xl-12 mt-5 mb-5">
            <div class="web-div-box">
                <div class="box-div-info">
                    <div class="table-title">
<!--                        <img src="--><?//= url('themes/assets/img/icone-dashboard.png') ?><!--"> Dashboard-->
                        <img src="<?= url('themes/assets/img/icone-zona.png') ?>"> Áreas mais ocupadas:
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xl-3 mb-5">
                            <div class="card">
                                <img class="card-img-top" src="<?= url('themes/assets/img/zone.jpg') ?>" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 mb-5">
                            <div class="card">
                                <img class="card-img-top" src="<?= url('themes/assets/img/zone.jpg') ?>" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 mb-5">
                            <div class="card">
                                <img class="card-img-top" src="<?= url('themes/assets/img/zone.jpg') ?>" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 mb-5">
                            <div class="card">
                                <img class="card-img-top" src="<?= url('themes/assets/img/zone.jpg') ?>" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mb-3">
            <div class="web-div-box">
                <div class="box-div-info">
                    <p style="font-weight: bold"><img src="<?= url('themes/assets/img/icone-pagamento.png') ?>"> Pagamentos:</p>
                    <hr class="hr-no-margin">
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                             aria-valuemin="0" aria-valuemax="100" style="width:40%">
                            40% Em dia
                        </div>
                    </div>

                    <div class="progress">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60"
                             aria-valuemin="0" aria-valuemax="100" style="width:60%">
                            60% Pendentes
                        </div>
                    </div>

                    <div class="progress">
                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="70"
                             aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            70% Inadimplentes
                        </div>
                    </div>

                    <div class="progress" style="margin-bottom: 0;">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50"
                             aria-valuemin="0" aria-valuemax="100" style="width:50%">
                            50% Multas
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mt-3 mb-3">
            <div class="row mt-3">
                <div class="col-xl-4 mb-5">
                    <div class="web-div-box">
                        <div class="box-div-info">
                            <p style="font-weight: bold"><img src="<?= url('themes/assets/img/icone-homem.png') ?>"> Ultimos ambulantes cadastrados:</p>
                            <hr class="hr-no-margin">
                            <div class="list-group" id="list-tab" role="tablist" style="margin-bottom: 12px">
                                <a class="list-group-item list-group-item-action active" id="list-profile-1" data-toggle="list" href="#list-home" role="tab" aria-controls="home">Lucas Gabriel</a>
                                <a class="list-group-item list-group-item-action" id="list-profile-2" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Giovanni Oliver</a>
                                <a class="list-group-item list-group-item-action" id="list-profile-3" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Ruan Ramirez</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 mb-4">
                    <div class="web-div-box">
                        <div class="box-div-info">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="col-xl-12 tab-pane active" id="list-profile-1" role="tabpanel" aria-labelledby="list-home-list">
                                    <div class="row">
                                        <div class="col-xl-4 text-center">
                                            <img src="<?= url('themes/assets/img/ambulante.jpg') ?>" class="avatar img-thumbnail dashboard-image" alt="avatar">
                                        </div>
                                        <div class="col-xl-8">
                                            <div class="profile-div-data hr-no-margin">
                                                <img src="<?= url('themes/assets/img/icone-identidade.png') ?>">
                                                CPF: 034.325.347-01
                                            </div>
                                            <div class="profile-div-data">
                                                <img src="<?= url('themes/assets/img/icone-nome.png') ?>">
                                                RG: 3651746-1
                                            </div>
                                            <div class="profile-div-data">
                                                <img src="<?= url('themes/assets/img/icone-email.png') ?>">
                                                Email: lucasgabrielpdoliveira@gmail.com
                                            </div>
                                            <div class="profile-div-data">
                                                <img src="<?= url('themes/assets/img/icone-telefone.png') ?>">
                                                Telefone: 8718-0470
                                            </div>
                                            <div class="profile-div-data">
                                                <img src="<?= url('themes/assets/img/icone-localizacao.png') ?>">
                                                Casa:  Rua doutor batista aciole, 294, Centro, Rio Largo
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 tab-pane" id="list-profile-2" role="tabpanel" aria-labelledby="list-home-list">
                                    <div class="profile-div-data">
                                        <img src="<?= url('themes/assets/img/icone-mulher.png') ?>">
                                        Nome da ssss:
                                    </div>
                                </div>
                                <div class="col-xl-12 tab-pane" id="list-profile-3" role="tabpanel" aria-labelledby="list-home-list">
                                    <div class="profile-div-data">
                                        <img src="<?= url('themes/assets/img/icone-mulher.png') ?>">
                                        Nome da ffff:
                                    </div>
                                </div>
                                <div class="col-xl-12 tab-pane" id="list-settings" role="tabpanel" aria-labelledby="list-home-list">
                                    <div class="profile-div-data">
                                        <img src="<?= url('themes/assets/img/icone-mulher.png') ?>">
                                        Nome da eee:
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mb-5">
            <div class="web-div-box">
                <div class="box-div-info">
                    <p style="font-weight: bold"><img src="<?= url('themes/assets/img/icone-dashboard.png') ?>"> Dashboard</p>
                    <hr class="hr-no-margin">
                    <div class="row">
                        <div class="col-xl-3 col-6 mb-3">
                            <div class="web-div-box box-div-info-yellow">
                                <div class="box-div-info">
                                    <p class="box-div-info-title"><img src="<?= url('themes/assets/img/icone-barraca.png') ?>"> Ambulantes</p>
                                    <hr class="hr-no-margin">
                                    <h3># 43</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-6 mb-3">
                            <div class="web-div-box box-div-info-blue">
                                <div class="box-div-info">
                                    <p class="box-div-info-title"><img src="<?= url('themes/assets/img/icone-pagamento.png') ?>"> Pagamentos</p>
                                    <hr class="hr-no-margin">
                                    <h3># 12</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-6 mb-3">
                            <div class="web-div-box box-div-info-red">
                                <div class="box-div-info">
                                    <p class="box-div-info-title"><img src="<?= url('themes/assets/img/icone-notificacao.png') ?>"> Multas</p>
                                    <hr class="hr-no-margin">
                                    <h3># 80</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-6">
                            <div class="web-div-box box-div-info-green">
                                <div class="box-div-info">
                                    <p class="box-div-info-title"><img src="<?= url('themes/assets/img/icone-zona.png') ?>"> Zonas</p>
                                    <hr class="hr-no-margin">
                                    <h3># 43</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--        <div class="col-xl-6 justify-content-center text-center mt-5 mb-5">-->
<!--            <div class="web-div-box">-->
<!--                <div class="box-div-info box-div-info-overflow-x">-->
<!--                    <div id="barChart"></div>-->
<!--                                   <div id="piechart"></div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="col-xl-6 justify-content-center text-center mt-5 mb-5">-->
<!--            <div class="web-div-box">-->
<!--                <div class="box-div-info">-->
<!--                    <p class="text-left" style="font-weight: bold"><img src="--><?//= url('themes/assets/img/icone-notificacao.png') ?><!--"> Multas recentes:</p>-->
<!--                    <hr class="hr-no-margin">-->
<!--                    <table class="table table-striped">-->
<!--                        <thead>-->
<!--                        <tr>-->
<!--                            <th scope="col">#</th>-->
<!--                            <th scope="col">Nome</th>-->
<!--                            <th scope="col">Capacidade</th>-->
<!--                            <th scope="col">Ambulantes</th>-->
<!--                        </tr>-->
<!--                        </thead>-->
<!--                        <tbody>-->
<!--                        <tr>-->
<!--                            <th scope="row">1</th>-->
<!--                            <td>Mark</td>-->
<!--                            <td>Otto</td>-->
<!--                            <td>@mdo</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <th scope="row">2</th>-->
<!--                            <td>Jacob</td>-->
<!--                            <td>Thornton</td>-->
<!--                            <td>@fat</td>-->
<!--                        </tr>-->
<!--                        </tbody>-->
<!--                    </table>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>

<?php $v->start("scripts"); ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        $('#list-tab a').on('click', function (e) {
            e.preventDefault();
            $('#list-tab a').removeClass('active');
            $(this).addClass('active');
            $(this).tab('show')
        });
    </script>
    <script>
        // Load google charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Cadastros', 'Ambulantes/Empresas'],
                ['Ambulantes', 8],
                ['Empresas', 2],
            ]);

            // Optional; add a title and set the width and height of the chart
            var options = {'title':'Ambulantes / Empresas', 'width':550, 'height':320};

            // Display the chart inside the <div> element with id="piechart"
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawStuff);

        function drawStuff() {
            var data = new google.visualization.arrayToDataTable([
                ['Opening Move', 'Percentage'],
                ["King's pawn (e4)", 44],
                ["Queen's pawn (d4)", 31],
                ["Knight to King 3 (Nf3)", 12],
                ["Queen's bishop pawn (c4)", 10],
                ['Other', 3]
            ]);

            var options = {
                title: 'Zonas mais requisitadas',
                width: 550,
                legend: { position: 'none' },
                chart: { title: 'Zonas mais requisitadas',
                    subtitle: 'Ambulante por zona' },
                bars: 'horizontal', // Required for Material Bar Charts.
                axes: {
                    x: {
                        0: { side: 'top', label: 'Percentage'} // Top x-axis.
                    }
                },
                bar: { groupWidth: "90%" }
            };

            var chart = new google.charts.Bar(document.getElementById('barChart'));
            chart.draw(data, options);
        };
    </script>
<?php $v->end(); ?>
