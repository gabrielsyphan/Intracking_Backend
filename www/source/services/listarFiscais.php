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
            $result = mysqli_query($connection, "SELECT id, nome, matricula, email, situacao FROM fiscais");    
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
            echo json_encode($data);
        }else{
            echo 0;   
        }
    }else{
        echo 0;
    }