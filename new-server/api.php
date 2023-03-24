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

    if ($_GET['action'] === 'getFilesList') {
        $id = $_GET['id'];
        $result = getFromDB('*', 'files f INNER JOIN sharelist s ON f.share_list_id = s.id', "f.id = $id");

        echo json_encode($result);
        exit;
    }

    // This is for downloading a file
    if ($_GET['action'] === 'downloadFile') {
        if (!isset($_GET['encodedName'])) {
            echo json_encode(['error' => 'No encodedName specified']);
            exit;
        }

        $encodedName = $_GET['encodedName'];
        $file = getFromDB('*', 'files', 'encodedName = "' . $encodedName . '"')[0];
        $id = $file['id'];

        if (!$file) {
            echo json_encode(['error' => 'File not found']);
            exit;
        }
        $fileFolder = getFromDB('*', 'files f INNER JOIN shareList s on f.share_list_id = s.id', "f.id = '$id'");
        $fileFolder = $fileFolder[0]['url'];

        $filePath = "uploads/$fileFolder/" . $file['name'];

        if (!file_exists($filePath)) {
            echo json_encode(['error' => 'File not found']);
            exit;
        }

        header('Content-Type: ' . mime_content_type($filePath));
        header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);

        exit;
    }

    if ($_GET['action'] === 'downloadSelected') {
        if (!isset($_GET['url'])) {
            echo json_encode(['error' => 'No url specified']);
            exit;
        }

        
        $url = $_GET['url'];
        $files = getFromDB('*', 'files f INNER JOIN shareList s on f.share_list_id = s.id', "s.url = '$url'",true);
        $fileFolder = $files[0]['url'];


        $zip = new ZipArchive();
        $zipName = "uploads/$fileFolder/$fileFolder.zip";

        if ($zip->open($zipName, ZipArchive::CREATE) !== TRUE) {
            exit("cannot open <$zipName>\n");
        }

        foreach ($files as $file) {
            $filePath = "uploads/$fileFolder/" . $file['name'];
            $zip->addFile($filePath, substr($filePath, strlen("uploads/$fileFolder/")));
        }

        $zip->close();

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($zipName) . '"');
        header('Content-Length: ' . filesize($zipName));

        readfile($zipName);
        exit;
    }
}



// Upload file
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
    $newShareList = newShareList();
    mkdir('uploads/' . $newShareList->url, 0777, true);



    // Loop through each uploaded file
    foreach ($_FILES as $file) {
        // Get the file name
        $fileName = $file['name'];

        // Create a new File object and set the name property
        $fileModel = new stdClass();
        $fileModel->name = $fileName;

        // Save the File object to the database
        // sharelist id
        $shareListId = $newShareList->_id;
        $savedFileModel = saveFileModel($fileModel, $shareListId);

        // If the File was saved successfully, add it to the savedModels array
        if ($savedFileModel) {
            $savedFileModel->encodedName = md5($savedFileModel->_id);
            $savedModels[] = $savedFileModel;
        } else {
            return 'Error creating new file';
        }

        // Move the file to the uploads folder
        move_uploaded_file($file['tmp_name'], "uploads/$newShareList->url/" . $savedFileModel->name);
    }

    // If there were no errors, return the savedModels array as the response
    echo json_encode($savedModels);
}


// Delete file
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

        $fileFolder = getFromDB('*', 'files f INNER JOIN shareList s on f.share_list_id = s.id', "f.id = '$id'");
        $fileFolder = $fileFolder[0]['url'];


        // $pdo = getDB();
        // $count = getFromDB('COUNT(*) AS count', 'files', 'name = "' . $file['name'] . '" AND id != ' . $file['id'])[0]['count'];

        // if ($count == '0') {
        $fileToDelete = "uploads/$fileFolder/" . $file['name'];

        echo json_encode(['file path' => $fileToDelete]);

        if (unlink($fileToDelete)) {
            echo json_encode(['succes' => 'Deleted file']);
        } else {
            echo json_encode(['error' => 'Error deleting file ' . $file['name']]);
        }
        echo 'Deleted file ' . $file['name'];
        // }

        // Delete the file
        $pdo = getDB();
        $stmt = $pdo->prepare('DELETE FROM files WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();


        // check if there are any files left in the folder
        $files = scandir("uploads/$fileFolder");
        // if there are no files left in the folder, delete the folder
        if (count($files) == 2) {
            rmdir("uploads/$fileFolder");
            echo json_encode(['success' => 'Deleted main folder']);
        }

        exit;
    }
}
