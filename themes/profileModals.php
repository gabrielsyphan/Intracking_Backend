<div id="attachsModal">
    <div class="container pt-5">
        <div class="row mt-5 p-5 justify-content-center">
            <div class="col-xl-10 p-5 container-white">
                <h3 class="black-title-section">Meus anexos</h3>
                <p class="subtitle-section-p">Arquivos enviados por você durante seu cadastro.</p>
                <hr>
                <div class="div-box-span-icon mt-5">
                    <span class="icon-close" onclick="closeAttachs()"></span>
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
                                        </button>
                                    </form>
                                    <button class="btn" type="submit" onclick="openFile('<?= $upload['groupName'] .'/'.
                                    $upload['userId'] .'/'. $upload['fileName'] ?>')">
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
