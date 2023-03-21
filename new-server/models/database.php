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
        if($debug){
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



function saveFileModel($fileModel)
{
    $pdo = getDB();

    $fileName = $fileModel->name;

    try {
        $stmt = $pdo->prepare("INSERT INTO files (name) VALUES (:name)");
        $stmt->bindParam(':name', $fileName);
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