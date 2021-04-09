<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Accept: application/json');

//use SimpleXMLElement;

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    require "connection.php";

    $input_json = file_get_contents("php://input");
    $input_array = json_decode($input_json, true);

    $dataTeste = array();

    $result = mysqli_query($connection, "SELECT cmc, id FROM usuarios WHERE cpf = '". $input_array['cpf'] ."'");
    while ($row = mysqli_fetch_assoc($result)) {
        $dataTeste[] = $row;
    }

    if (isset($dataTeste[0])) {
        $cmc = $dataTeste[0]['cmc'];
        $data['code'] = "
                INSERT INTO licencas(
                    `cmc`,
                    `tipo`,
                    `data_inicio`,
                    `data_fim`,
                    `status`,
                    `id_usuario`
                    )
                values(
                    '". $cmc . "',
                    '1',
                    '" . date('Y-m-d', strtotime("+3 days")) . "',
                    '" . date('Y-m-d', strtotime("+90 days")) . "',
                    0,
                    " . $dataTeste[0]['id']. "
                )";
        $result = mysqli_query($connection, "
                INSERT INTO licencas(
                    `cmc`,
                    `tipo`,
                    `data_inicio`,
                    `data_fim`,
                    `status`,
                    `id_usuario`
                    )
                values(
                    '". $cmc . "',
                    '1',
                    '" . date('Y-m-d', strtotime("+3 days")) . "',
                    '" . date('Y-m-d', strtotime("+90 days")) . "',
                    0,
                    " . $dataTeste[0]['id']. "
                )");

        $data["mysqlamb"] = mysqli_error($connection);

        $licenca_id = mysqli_insert_id($connection);

        $data['code2'] = "
            INSERT INTO empresas(
                `id_licenca`,
                `endereco`,
                `numero`,
                `bairro`,
                `cidade`,
                `cep`,
                `produto`,
                `relato_atividade`,
                `cnpj`,
                `cmc`,
                `nome_fantasia`
                )
            values(
                " . $licenca_id . ",
                '" . $input_array['local_endereco'] . "',
                '" . $input_array['numero'] . "',
                '" . $input_array['bairro'] . "',
                '" . $input_array['cidade'] . "',
                '" . $input_array['cep'] . "',
                '" . $input_array['produto'] . "',
                '" . $input_array['relato_atividade'] . "',
                '" . $input_array['cnpj'] .  "',
                '" . $input_array['cmc'] . "',
                '" . $input_array['nome_fantasia'] . "'
            )";
        $result2 = mysqli_query($connection, "
            INSERT INTO empresas(
                `id_licenca`,
                `endereco`,
                `numero`,
                `bairro`,
                `cidade`,
                `cep`,
                `produto`,
                `relato_atividade`,
                `cnpj`,
                `cmc`,
                `nome_fantasia`
                )
            values(
                " . $licenca_id . ",
                '" . $input_array['local_endereco'] . "',
                '" . $input_array['numero'] . "',
                '" . $input_array['bairro'] . "',
                '" . $input_array['cidade'] . "',
                '" . $input_array['cep'] . "',
                '" . $input_array['produto'] . "',
                '" . $input_array['relato_atividade'] . "',
                '" . $input_array['cnpj'] .  "',
                '" . $input_array['cmc'] . "',
                '" . $input_array['nome_fantasia'] . "'
            )");

        $data["mysqlamb2"] = mysqli_error($connection);

        if($data["mysqlamb2"] == "" && $data["mysqlamb"]==""){
            $data['retorno'] = 1;
            $data['message'] = "Cadastro realizado com sucesso!";
        } else {
            $data['retorno'] = 2;
            $data['message'] = "Ocorreu algum erro no cadastro!";
        }

        $data['retorno'] = 1;
        echo json_encode($data);
    }
}
