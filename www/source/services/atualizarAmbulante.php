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
                `identidade` = '" . $input_array['identidade'] . "',
                `endereco` = '" . $input_array['endereco'] . "',
                `numero` = '" . $input_array['numero'] . "', 
                `bairro` = '" . $input_array['bairro'] . "', 
                `cidade` = '" . $input_array['cidade'] . "', 
                `cep` = '" . $input_array['cep'] . "', 
                `fone` = '" . $input_array['fone'] . "',
                `email` = '" . $input_array['email'] . "',
                `nome` = '" . $input_array['nome'] . "',
                `rg` = '" . $input_array['rg'] . "',
                `nome_materno` = '" . $input_array['nome_materno'] . "',
                `end_local` = '" . $input_array['end_local'] . "',
                `produto` = '" . $input_array['produto'] . "', 
                `atendimento_dias` = '" . $input_array['atendimento_dias'] . "',
                `atendimento_inicio` = '" . $input_array['atendimento_inicio'] . "', 
                `atendimento_fim` = '" . $input_array['atendimento_fim'] . "', 
                `foto` = '" . $input_array['foto'] . "',
                `foto_cpf` = '" . $input_array['foto_cpf'] . "',
                `foto_rg` = '" . $input_array['foto_rg'] . "',
                `foto_equipamento` = '" . $input_array['foto_equipamento'] . "',
                `area_equipamento` = '" . $input_array['area_equipamento'] . "',
                `tipo_equipamento` = '" . $input_array['tipo_equipamento'] . "' 
            WHERE `id` = '" . $input_array['id'] . "'"
    );

    $areaText = $input_array['area_equipamento'];
    $area2 = explode( " x ", $areaText);
    $area = $area2[0]*$area2[1];

    $values = array();

    foreach (str_split($input_array['produto']) as $product) {
        if ($product == 0 || $product == 1) {
            if ($area <= 1.50) {
                $values[] = 40.00;
            } else {
                $values[] = 72.00;
            }
        } else if ($product == 2 || $product == 3 || $product == 4 || $product == 5) {
            if ($area <= 1.50) {
                $values[] = 80.00;
            } else {
                $values[] = 144.00;
            }
        } else if ($product == 6 || $product == 7) {
            if ($area <= 1.50) {
                $values[] = 72.00;
            } else {
                $values[] = 80.00;
            }
        }
    }

    rsort($values);

    if ($input_array['id_empresa'] == "0") {
        $ambulante_id = mysqli_insert_id($connection);
        $result2 = mysqli_query($connection, "UPDATE boletos SET valor= $values[0] WHERE id_ambulante=" . $input_array['id'] . "");
    } else {
        $result3 = mysqli_query($connection, "UPDATE empresas SET contador_ambulantes='$contador_ambulante' WHERE id=$id_empresa");
    }

    $data['retorno'] = 1;
    echo json_encode($data);
} else {
    $data['retorno'] = 0;
    echo json_encode($data);
}
