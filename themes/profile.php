<?php $v->layout("_theme.php") ?>

<div class="container-fluid mt-5 p-0">
    <div class="web-div-box">
        <div class="box-div-info">
            <div class="container">
                <div class="row mt-5">
                    <div class="col-xl-12">
                        <h2 class="black-title-section">Informações pessoais</h2>
                        <p class="subtitle-section-p">Dados informados durante seu cadastro.</p>
                        <div class="row">
                            <div class="col-xl-8">
                                <hr>
                                <fieldset disabled>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>CPF:</label>
                                                <input type="text" class="form-input disabled-input" value="<?= $user->cpf ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>RG:</label>
                                                <input type="text" class="form-input disabled-input" value="<?= $user->rg ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Nome:</label>
                                                <input type="text" class="form-input disabled-input" value="<?= $user->nome ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Mãe:</label>
                                                <input type="text" class="form-input disabled-input" value="<?= $user->nome_mae ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Email:</label>
                                                <input type="text" class="form-input disabled-input" value="<?= $user->email ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Telefone:</label>
                                                <input type="text" class="form-input disabled-input" value="<?= $user->telefone ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>Endereço:</label>
                                                <input type="text" class="form-input disabled-input" value="<?= $user->endereco ?>">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-xl-4 mt-5 pt-5 text-center">
                                <img class="mb-4 mt-4" style="width: 200px; border-radius: 50%;" src="<?= $userImage ?>">

                                <?php switch ($user->situacao):
                                    case 0: ?>
                                        <p><span class="icon-info-circle"></span> Pendente</p>
                                    <?php break; case 1: ?>
                                        <p>Ativo</p>
                                    <?php break; case 2: ?>
                                        <p>Inadimplente</p>
                                    <?php break; case 3: ?>
                                        <p>Pendente</p>
                                    <?php break; endswitch; ?>
                            </div>
                            <div class="col-xl-12 mt-4 mb-5">
                                <h3 class="black-title-section">Ações</h3>
                                <p class="subtitle-section-p">Informações recorrentes</p>
                                <hr>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="row m-0 mt-3 p-4 div-request-license">
                                            <div class="col-xl-2 text-center mt-4">
                                                <img src="<?= url('themes/assets/img/files.png') ?>">
                                            </div>
                                            <div class="col-xl-10">
                                                <h4>Anexos enviados</h4>
                                                <p class="subtitle-section-p">Para vendedores que atuam em um local fixo.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="row m-0 mt-3 p-4 div-request-license">
                                            <div class="col-xl-2 text-center mt-4">
                                                <img src="<?= url('themes/assets/img/cash-payment.png') ?>">
                                            </div>
                                            <div class="col-xl-10">
                                                <h4>Meus pagamentos</h4>
                                                <p class="subtitle-section-p">Para vendedores que atuam em um local fixo.</p>
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
