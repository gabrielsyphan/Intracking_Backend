<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header('Accept: application/json');

    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        require "connection.php";
        
        $input_json = file_get_contents("php://input");
        $input_array = json_decode($input_json, true);
        
        
        $result = mysqli_query($connection, "
            INSERT INTO denuncias(
                `foto`, 
                `data_denuncia`, 
                `hora_denuncia`, 
                `descricao`, 
                `latitude`, 
                `longitude`, 
                `local`,
                `multa`, 
                `regiao`)
            values(
                '". $input_array['foto'] ."', 
                '". $input_array['data_denuncia'] ."', 
                '". $input_array['hora_denuncia'] ."', 
                '". $input_array['descricao'] ."',
                '". $input_array['latitude'] ."', 
                '". $input_array['longitude'] ."', 
                '". $input_array['local']."',
                '". $input_array['multa']."', 
                '". $input_array['regiao'] ."'
            )");
            
            $data['retorno'] = 1;
            echo json_encode($data);
    }else{
        $data['retorno'] = 0;
        echo json_encode($data);
    }

