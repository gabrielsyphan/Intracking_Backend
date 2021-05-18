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

    $data['code'] = "
            INSERT INTO notificacoes( 
            `data_notificacao`, 
            `hora_notificacao`,
            `titulo`,
            `descricao`, 
            `id_fiscal`,
            `id_usuario`)
            values(
                '" . $input_array['data_notificacao'] . "', 
                '" . $input_array['hora_notificacao'] . "',
                '" . $input_array['titulo'] . "', 
                '" . $input_array['descricao'] . "', 
                " . $input_array['fiscal_id'] . ",
                " . $input_array['ambulante_id'] . "
            )";
    
    $result = mysqli_query($connection, "
            INSERT INTO notificacoes( 
            `data_notificacao`, 
            `hora_notificacao`,
            `titulo`,
            `descricao`, 
            `id_fiscal`,
            `id_usuario`)
            values(
                '" . $input_array['data_notificacao'] . "', 
                '" . $input_array['hora_notificacao'] . "',
                '" . $input_array['titulo'] . "', 
                '" . $input_array['descricao'] . "', 
                " . $input_array['fiscal_id'] . ",
                " . $input_array['ambulante_id'] . "
            )");
    $data["mysqlamb2"] = mysqli_error($connection);
    if($data["mysqlamb2"]==""){
        $data['retorno'] = 1;
        $data['message'] = "Notificação cadastrada com sucesso!";
    } else{
        $data['retorno'] = 2;
        $data['message'] = "Ocorreu um erro no cadastro da notificação!";
    }
    
    echo json_encode($data);
} else {
    $data['retorno'] = 0;
    echo json_encode($data);
}
