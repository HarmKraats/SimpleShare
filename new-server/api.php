<?php
require_once 'models/database.php';
// Set the content type header to JSON
header('Content-Type: application/json');


// Check the request method to determine what action to take
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // This is for getting all the files from the database
    if ($_GET['action'] === 'getFiles') {
        echo json_encode(getFromDB('*', 'files', '1'));
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // For uploading a file
    if($_POST['action'] === 'upload'){
        
    }

}
