<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token'); //aspargos
    header('Content-Type: application/json');
    header('Accept: application/json');

    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        require "connection.php";
        
        $input_json = file_get_contents("php://input");
        $input_array = json_decode($input_json, true);
        $data = array();
        $dataRes = array();
        
        if($input_array['token'] == 39158){
            $result = mysqli_query($connection, "SELECT * FROM anexos WHERE tipo_usuario = '" . $input_array['tipo'] . "' && id_usuario =" . $input_array['id']);   
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
            foreach($data as $value){
                $name = explode('.', $value['nome'])[0];
                $dataRes[$name] = $value['nome'];
            }
            echo json_encode($dataRes);
        }else{
            echo 0;   
        }
    }else{
        echo 0;
    }