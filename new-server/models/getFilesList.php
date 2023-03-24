<?php
require_once 'database.php';


$url = basename($_SERVER['PHP_SELF']);

$result = getFromDB('*', 'sharelist s INNER JOIN files f on f.share_list_id = s.id', "s.url = '$url'");

echo json_encode($result);
