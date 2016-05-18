<?php

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

try {
    $stmt = $DB->prepare('SELECT * FROM likes WHERE user_id = :user_id AND photo_id = :photo_id');

    $stmt->bindParam(':user_id', $_SESSION['id'] , PDO::PARAM_INT);
    $stmt->bindParam(':photo_id', $_GET['id'] , PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $count = $DB->exec("DELETE FROM likes WHERE user_id = " . $_SESSION['id'] . " AND photo_id = " . $_GET['id']);
        if ($count)
            echo json_encode(array('msg' => 'success', 'confirmation' => 'Deleted liked'));
    } else {
        try {
            $count = $DB->exec("INSERT INTO likes (user_id, photo_id) VALUES (". $_SESSION['id'] . ", ". $_GET['id'] .")");

            if ($count) {
                echo json_encode(array('msg' => 'success'));
            } else {
                echo json_encode(array('msg' => 'failure', 'error' => 'Not inserted'));
            }
        } catch (Exception $e)
        {
            echo json_encode(array('msg' => 'failure', 'error' => $e->getMessage()));
        }
    }
} catch (Exception $e) {
    echo json_encode(array('msg' => 'failure', 'error' => $e->getMessage()));
}

?>
