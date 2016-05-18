<?php

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

try {
    $stmt = $DB->prepare("INSERT INTO comments (user_id, photo_id, content) VALUES (:user_id, :photo_id, :content)");

    $stmt->bindParam(':user_id', $_SESSION['id'] , PDO::PARAM_INT);
    $stmt->bindParam(':photo_id', $_POST['id'] , PDO::PARAM_INT);
    $stmt->bindParam(':content', $_POST['comment'] , PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount())
        echo json_encode(array('msg' => 'success'));
    else
        echo json_encode(array('msg' => 'failure', 'error' => 'Not inserted'));

} catch (Exception $e) {
    echo json_encode(array('msg' => 'failure', 'error' => $e->getMessage()));
}
