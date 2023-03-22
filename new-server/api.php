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
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($_GET['action'])) {
        echo json_encode(['error' => 'No action specified']);
        exit;
    }
    // This is for deleting a file from the database
    if ($_GET['action'] === 'deleteFile') {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'No id specified']);
            exit;
        }
        $id = $_GET['id'];
        $file = getFromDB('*', 'files', 'id = ' . $id)[0];

        if (!$file) {
            echo json_encode(['error' => 'File not found']);
            exit;
        }


        $pdo = getDB();
        $count = getFromDB('COUNT(*) AS count', 'files', 'name = "' . $file['name'] . '" AND id != ' . $file['id'])[0]['count'];

        if ($count == '0') {
            $fileToDelete = 'uploads/' . $file['name'];

            echo json_encode(['file path' => $fileToDelete]);

            if (unlink($fileToDelete)) {
                echo json_encode(['succes' => 'Deleted file']);
            } else {
                echo json_encode(['error' => 'Error deleting file ' . $file['name']]);
            }
            echo 'Deleted file ' . $file['name'];
        }

        // Delete the file
        $stmt = $pdo->prepare('DELETE FROM files WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo json_encode(['success' => 'File deleted']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_GET['action'])) {
        echo json_encode(['error' => 'No action specified']);
        exit;
    }

    if (empty($_FILES)) {
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
