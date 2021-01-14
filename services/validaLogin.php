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
             $result = mysqli_query($connection, "SELECT id, matricula, nome FROM fiscais WHERE matricula = '". $input_array['dados']['matricula'] ."' AND senha = '". md5($input_array['dados']['senha']) . "'");  
             $data['teste'] = "SELECT id, matricula, nome FROM fiscais WHERE matricula = '". $input_array['dados']['matricula'] ."' AND senha = ". md5($input_array['dados']['senha']);
             $num_rows = mysqli_num_rows($result);
             if($num_rows > 0){
                 while($row = mysqli_fetch_assoc($result)){
                     $data['dados'] = $row;
                 }
                 $data['retorno'] = 1;
                echo json_encode($data);   
             }else{
                $data['retorno'] = 2;
                echo json_encode($data);
             }
         }else{
            $data['retorno'] = 3;
            echo json_encode($data);   
         }
    }else{
        $data['retorno'] = 4;
        echo json_encode($data);   
    }