<?php
require_once "izi/IziClient.php";

$client = new IziClient("CbYzNoNiieJRPENQGdYV", "JKifKSnPRbtBuxJthZIyHVgLQFYdUHJOTiRqmZdg");
$url = "https://api.izi.credit/v3/phoneage";
$data   = array(
    "phone" => $paramArr['phone']
);
$response = $client->request($url, $data);
echo '<pre>';
print($response);

