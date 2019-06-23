<?php
include "smartystreets.php";
$json = file_get_contents('php://input');
$data = json_decode($json);

//$auth_id = '1232f705-7bd5-76d3-a0d3-6bcb004bce40';
//$auth_token = 'mU8xyF2a5hD9cIt7Z4M6';



$state = strtoupper($data->state);
$city =  strtoupper($data->city);
echo json_encode($Object->getZipCodes($state,$city));


?>