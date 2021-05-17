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
        
        $array_point = array();
        
        // Pega cada geopoint do poligono para adicionar no array de pontos
        foreach ($input_array['coordenadas'] as $point){
            // Adiciona a latitude e longitude da região ao array
            $array_point[] = $point['_lat'] ." ". $point['_long'];
        }
    
        // Criação do poligono e consulta se há alguma denúncia dentro dele
        // $result = mysqli_query($connection, "SELECT id, point FROM maceiogeoreports WHERE ST_CONTAINS(ST_GEOMFROMTEXT('". $polygon ."'), point) AND id = ". $last_id ."");
    
        //  Adiciona uma virgula entre cada casa do array e transforma ele em uma string
        $str = implode(',', $array_point);
        
        // Utiliza a string para criar o nume da função do poligono
        $polygon = 'POLYGON(('. $str .'))';
    
        $result = mysqli_query($connection, "
            INSERT INTO `zonas`(`coordenadas`, `detalhes`, `foto`, `nome`, `limite_ambulantes`, `quantidade_ambulantes`)
                values(
                    PolygonFromText('". $polygon ."'), '". $input_array['detalhes'] ."', '". $input_array['foto'] ."', '". $input_array['nome'] ."',
                    ". $input_array['capacidade'] .", 0
                )");
            
        
        echo json_encode($input_array);
    }