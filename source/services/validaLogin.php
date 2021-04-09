<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header('Accept: application/json');
    
    function login(): void
    {
        header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
        $data['a']='a';
        echo json_encode($data);
    }

    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        require "connection.php";
        
         $input_json = file_get_contents("php://input");
         $input_array = json_decode($input_json, true);
         $data = array();
        
         if($input_array['token'] == 39158){
             $result = mysqli_query($connection, "SELECT id, matricula, nome, email, cpf, tipo_fiscal FROM fiscais WHERE cpf = '". $input_array['dados']['matricula'] ."' AND senha = '". md5($input_array['dados']['senha']) . "'");  

             $num_rows = mysqli_num_rows($result);
             if($num_rows > 0){
                 while($row = mysqli_fetch_assoc($result)){
                     $data['dados'] = $row;
                     $result2 = mysqli_query($connection, "SELECT * FROM anexos WHERE tipo_usuario = '3' && id_usuario = '". $row['id'] . "'");
                    while($row2 = mysqli_fetch_assoc($result2)){
                        $dataAnexos[] = $row2;
                        foreach($dataAnexos as $value2){
                            $name = explode('.', $value2['nome'])[0];
                            $dataRes[$name] = $value2['nome'];
                        }
                        $data['dados'] = array_merge($data['dados'], $dataRes);
                    }
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