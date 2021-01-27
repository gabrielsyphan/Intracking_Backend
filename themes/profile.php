<?php $v->layout("_theme.php") ?>

<div id="modal-1" class="div-modal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-xl-10 p-5 container-white">
                <h3 class="black-title-section">Meus anexos</h3>
                <p class="subtitle-section-p">Arquivos enviados por você durante seu cadastro.</p>
                <hr>
                <div class="div-box-span-icon mt-5">
                    <span class="icon-close" onclick="closeModal(1)"></span>
                </div>
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
            <div class="col-xl-10 p-5 container-white">
                <h3 class="black-title-section">Meus pagamentos</h3>
                <p class="subtitle-section-p">Todos os pagamentos referente às suas licenças.</p>
                <hr>
                <div class="div-box-span-icon mt-5">
                    <span class="icon-close" onclick="closeModal(2)"></span>
                </div>
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
                        <?php if ($payments !== NULL):
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


<div class="container-fluid container-white mt-5">
    <div class="p-5">
        <div class="row">
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
                                        <input type="text" class="form-input disabled-input"
                                               value="<?= $user->nome_mae ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input type="text" class="form-input disabled-input"
                                               value="<?= $user->email ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>Telefone:</label>
                                        <input type="text" class="form-input disabled-input"
                                               value="<?= $user->telefone ?>">
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>Endereço:</label>
                                        <input type="text" class="form-input disabled-input"
                                               value="<?= $user->endereco ?>">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 mt-5 pt-5 text-center">
                        <img class="mb-4 mt-4" style="width: 200px; border-radius: 50%;" src="<?= $userImage ?>">

                        <p><span class="icon-info-circle"></span> Usuário</p>
                    </div>
                    <div class="col-xl-12 mt-4 mb-5">
                        <h3 class="black-title-section">Ações</h3>
                        <p class="subtitle-section-p">Informações recorrentes</p>
                        <hr>
                        <div class="row">
                            <div class="col-xl-6" onclick="openModal(1)">
                                <div class="row m-0 mt-3 p-4 div-request-license border-left-yellow">
                                    <div class="col-xl-2 text-center mt-4">
                                        <img src="<?= url('themes/assets/img/files.png') ?>">
                                    </div>
                                    <div class="col-xl-10">
                                        <h4>Meus anexos</h4>
                                        <p class="subtitle-section-p">Arquivos enviados por você durante seu
                                            cadastro.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6" onclick="openModal(2)">
                                <div class="row m-0 mt-3 p-4 div-request-license border-left-green">
                                    <div class="col-xl-2 text-center mt-4">
                                        <img src="<?= url('themes/assets/img/cash-payment.png') ?>">
                                    </div>
                                    <div class="col-xl-10">
                                        <h4>Meus boletos</h4>
                                        <p class="subtitle-section-p">Todos os pagamentos referente às suas
                                            licenças.</p>
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
