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
        $data = array();

        
        if($input_array['token'] == 39158){
            $result = mysqli_query($connection, "SELECT * FROM usuarios WHERE id = '". $input_array['id'] . "'");    
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
            $result2 = mysqli_query($connection, "SELECT * FROM anexos WHERE tipo_usuario = '0' && id_usuario = '". $input_array['id'] . "'");
            while($row2 = mysqli_fetch_assoc($result2)){
                $dataAnexos[] = $row2;
                foreach($dataAnexos as $value2){
                $name = explode('.', $value2['nome'])[0];
                $dataRes[$name] = $value2['nome'];
                }
            }
            $data2 = array_merge($data[0], $dataRes);
            echo json_encode($data2);
        }else{
            echo 0;   
        }
    }else{
        echo 0;
    }