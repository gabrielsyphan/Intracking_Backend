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
        
        if($input_array == 39158){
            $result = mysqli_query($connection, "SELECT * FROM ambulantes");    
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
                foreach ($data as &$value) {
                    $result2 = mysqli_query($connection, "SELECT * FROM licencas WHERE id = '". $value['id_licenca'] . "'");
                    while($row2 = mysqli_fetch_assoc($result2)){
                        $value = array_merge($value,$row2);
                    }
                    $result2 = mysqli_query($connection, "SELECT * FROM usuarios WHERE id = '". $value['id_usuario'] . "'");
                    while($row2 = mysqli_fetch_assoc($result2)){
                        $value = array_merge($value,$row2);
                    }
                    $result2 = mysqli_query($connection, "SELECT * FROM anexos WHERE tipo_usuario = '0' && id_usuario =" . $value['id_usuario']);
                    while($row2 = mysqli_fetch_assoc($result2)){
                        $dataAnexos[] = $row2;
                        foreach($dataAnexos as $value2){
                        $name = explode('.', $value2['nome'])[0];
                        $dataRes[$name] = $value2['nome'];
                    }
                    }
                    $value = array_merge($value, $dataRes);
                    
                    $result2 = mysqli_query($connection, "SELECT * FROM anexos WHERE tipo_usuario = '1' && id_usuario =" . $value['id_licenca']);
                    while($row2 = mysqli_fetch_assoc($result2)){
                        $dataAnexos[] = $row2;
                        foreach($dataAnexos as $value2){
                        $name = explode('.', $value2['nome'])[0];
                        $dataRes[$name] = $value2['nome'];
                    }
                    }
                    $value = array_merge($value, $dataRes);
                }
            }
            echo json_encode($data);
        }else{
            echo 0;   
        }
    }else{
        echo 0;
    }