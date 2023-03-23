<?php
// Path: new-server\models\database.php
// Database connection function with PDO
function getDB()
{

    $host = 'localhost';
    $db = 'simpleshare';
    $user = 'root';
    $pass = '';


    try {
        $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Connected successfully
    } catch (PDOException $e) {
        // Not conencteed successfully
        echo 'Database connectie error: ' . $e->getMessage();
    }

    return $pdo;
}

// Data uit de database halen met deze handige functie. 
function getFromDB($what = "*", $table = "users", $where = "1", $debug = false)
{
    try {
        $db = getDB();

        // If the $what is an array, we need to implode it with a comma.
        if (is_array($what)) {
            $what = implode(', ', $what);
        }

        // query
        $sql = "SELECT $what FROM $table WHERE $where";
        if ($debug) {
            echo $sql;
        }

        // prepare and execute query. Then fetch all the results and retype them as an array
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo 'Database gegevens ophalen error: ' . $e->getMessage();
    }
}

function saveFileModel($fileModel, $shareListId)
{
    $pdo = getDB();

    $fileName = $fileModel->name;

    try {
        $dateTime = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("INSERT INTO files (name, share_list_id, uploaded) VALUES (:name, :share_list_id, :uploaded)");
        $stmt->bindParam(':name', $fileName);
        $stmt->bindParam(':share_list_id', $shareListId);
        $stmt->bindParam(':uploaded', $dateTime);
        $stmt->execute();

        $id = $pdo->lastInsertId();

        $savedFileModel = new stdClass();
        $savedFileModel->_id = $id;
        $savedFileModel->name = $fileName;

        // insert the encodedName into the database, it is encoded with btoa
        $encodedName = md5($id);
        $stmt = $pdo->prepare("UPDATE files SET encodedName = :encodedName WHERE id = :id");
        $stmt->bindParam(':encodedName', $encodedName);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $savedFileModel;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteFile($file_id)
{
    // Get file record from the database
    $file = getFromDB('*', 'files', 'id = ' . $file_id);

    // Check if file exists
    if (!$file) {
        return false;
    }

    $pdo = getDB();
    $stmt = $pdo->prepare('DELETE FROM files WHERE id = :id');
    $stmt->bindParam(':id', $file_id);
    $stmt->execute();


    // Check if there are other records using this file
    $stmt = $pdo->prepare('SELECT COUNT(*) AS count FROM files WHERE name = :name AND id != :id');
    $stmt->bindParam(':name', $file['name']);
    $stmt->bindParam(':id', $file['id']);
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Delete file record from the database if no other records are using this file
    if ($count === 0) {
        // Delete file from the filesystem
        $fileLocation = __DIR__ . '/../uploads/' . $file['name'];
        if (file_exists($fileLocation)) {
            unlink($fileLocation);
        }
    }

    return true;
}

function generateUniqueUrl($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $url = '';
    do {
        // Generate a random URL
        for ($i = 0; $i < $length; $i++) {
            $url .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Check if URL is already used in the database
        $existingUrls = getFromDB('url', 'shareList', "url = '$url'");
        $urlExists = !empty($existingUrls);

        // If URL is already used, generate a new one and try again
        if ($urlExists) {
            $url = '';
        }
    } while ($urlExists);

    return $url;
}



function newShareList()
{

    // generate a random string
    $url = generateUniqueUrl();

    // insert the string into the database
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO shareList (url) VALUES (:url)");
    $stmt->bindParam(':url', $url);
    $stmt->execute();

    // get the id of the inserted record
    $id = $pdo->lastInsertId();

    // return the id and the url
    $shareList = new stdClass();
    $shareList->_id = $id;
    $shareList->url = $url;

    return $shareList;
}
