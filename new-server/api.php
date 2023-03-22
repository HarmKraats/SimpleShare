<?php
require_once 'models/database.php';
// Set the content type header to JSON
header('Content-Type: application/json');


// Check the request method to determine what action to take
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['action'])) {
        echo json_encode(['error' => 'No action specified']);
        exit;
    }
    // This is for getting all the files from the database
    if ($_GET['action'] === 'getFiles') {
        echo json_encode(getFromDB('*', 'files', '1'));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_GET['action'])) {
        echo json_encode(['error' => 'No action specified']);
        exit;
    }

    if(empty($_FILES)){
        echo json_encode(['error' => 'No files specified']);
        exit;
    }

    $savedModels = [];


    // Loop through each uploaded file
    foreach ($_FILES as $file) {
        // Get the file name
        $fileName = $file['name'];

        // Create a new File object and set the name property
        $fileModel = new stdClass();
        $fileModel->name = $fileName;

        // Save the File object to the database
        $savedFileModel = saveFileModel($fileModel);

        // If the File was saved successfully, add it to the savedModels array
        if ($savedFileModel) {
            $savedFileModel->encodedName = md5($savedFileModel->_id);
            $savedModels[] = $savedFileModel;
        } else {
            return 'Error creating new file';
        }

        // Move the file to the uploads folder
        move_uploaded_file($file['tmp_name'], 'uploads/' . $savedFileModel->name);

    }

    // If there were no errors, return the savedModels array as the response
    echo json_encode($savedModels);
}
