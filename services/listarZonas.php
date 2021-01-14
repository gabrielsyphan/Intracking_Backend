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
            $result = mysqli_query($connection, "SELECT ST_AsText(coordenadas) as poligono, ST_AsText(ST_Centroid(coordenadas)) as centroide, detalhes, foto, nome, limite_ambulantes, quantidade_ambulantes, id FROM zonas");    
            while($row = mysqli_fetch_assoc($result)){
                $centroide = explode("POINT(", $row["centroide"]);
                $centroide = explode(")", $centroide[1]);
                $centroide = explode(" ", $centroide[0]);
                
                $poligono = explode("POLYGON((", $row["poligono"]);
                $poligono = explode("))", $poligono[1]);
                $poligono = explode(",", $poligono[0]);
                
                $aux = array();
                foreach($poligono as $polig){
                    $polig = explode(" ", $polig);
                    $aux[] = $polig;
                }
                
                $poligono = $aux;
                
                $row['centroide'] = $centroide;
                $row['poligono'] = $poligono;
                $data[] = $row;
            }
            echo json_encode($data);
        }else{
            echo 0;   
        }
    }else{
        echo 0;
    }