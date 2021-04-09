<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Accept: application/json');

//use SimpleXMLElement;

function base64_to_jpeg($base64_string, $imagePlace, $imageName, $id)
{
    $output_file = $imagePlace . $imageName;
    // open the output file for writing
    $ifp = fopen($output_file, 'wb');

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode(',', $base64_string);

    // we could add validation here with ensuring count( $data ) > 1
    fwrite($ifp, base64_decode($data[1]));

    // clean up the file resource
    fclose($ifp);
    return $output_file;
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    require "connection.php";

    $input_json = file_get_contents("php://input");
    $input_array = json_decode($input_json, true);
    
    $imagePlace = __DIR__ . $input_array['link'];

    base64_to_jpeg($input_array['foto'], $imagePlace, "userImage.jpg", $input_array['id']);

    $data['retorno'] = '1';
    echo json_encode($data);

}