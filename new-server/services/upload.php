<?php
require_once 'models/database.php';

function file_upload($user_id = null)
{
    // Plaatje uploaden
    $dbh = getDB();
    $date = date("Y-m-d");


    $query = "INSERT INTO files (name, path, user_id, date) VALUES (:name, :path, :user_id, :date)";
    $statement = $dbh->prepare($query);



    $target_file = 'uploads/' . basename($_FILES['files']['name']);


    // if ($file_size < 5242879) {

        if (!file_exists($target_file)) {
            if (move_uploaded_file($_FILES['files']['tmp_name'], $target_file)) {
                try {
                    $statement->bindParam(':name', $filename);
                    $statement->bindParam(':user_id', $user_id ?? null);
                    $statement->bindParam(':date', $date);
                    $statement->execute();   
                    // flash('success', 'Het bestand is geupload.', 'success');
                } catch (PDOException $e) {
                    echo "File upload error: " . $e->getMessage();
                }
            }
        }
    // }
    return;
}
?>
