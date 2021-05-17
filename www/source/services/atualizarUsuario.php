<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Accept: application/json');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    require "connection.php";

    $input_json = file_get_contents("php://input");
    $input_array = json_decode($input_json, true);

    $result = mysqli_query(
        $connection,
        "
            UPDATE ambulantes SET
                    `nome` = '" . $input_array['nome'] . "',
                    `endereco` = '" . $input_array['rua'] . ', ' . $input_array['cidade'] . ', ' . $input_array['bairro'] . ', ' . $input_array['numero'] .  "',
                    `telefone` = '" . $input_array['fone'] . "',
                    `email` = '" . $input_array['email'] . "',
                    `nome_mae` = '" . $input_array['nome_materno'] . "',
            WHERE `id` = '" . $input_array['id'] . "'"
    );

    $data['retorno'] = 1;
    echo json_encode($data);
} else {
    $data['retorno'] = 0;
    echo json_encode($data);
}
