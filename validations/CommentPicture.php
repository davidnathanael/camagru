<?php

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/Utils.php';

if (empty($_SESSION['id']))
{
    echo json_encode(array('msg' => 'failure', 'error' => 'Not authenticated'));
    die();
}

try {
    try {
        $stmt = $DB->prepare("SELECT *, users.mail as author FROM photos INNER JOIN users ON users.id=photos.user_id WHERE photos.id = :photo_id");

        $stmt->bindParam(':photo_id', $_POST['id'] , PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount())
        {
            $pictures = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mail = $pictures[0]['author'];
        }
        else
        {
            echo json_encode(array('msg' => 'failure', 'error' => "Invalid picture ID"));
            die();
        }
    } catch (Exception $e) {
        echo json_encode(array('msg' => 'failure', 'error' => $e->getMessage()));
        die();
    }
    $stmt = $DB->prepare("INSERT INTO comments (user_id, photo_id, content) VALUES (:user_id, :photo_id, :content)");

    $stmt->bindParam(':user_id', $_SESSION['id'] , PDO::PARAM_INT);
    $stmt->bindParam(':photo_id', $_POST['id'] , PDO::PARAM_INT);
    $stmt->bindParam(':content', htmlspecialchars($_POST['comment']) , PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount())
    {
        sendMail($mail, 'New comment', 'You received a new comment from '. $_SESSION['login'] .' : ' . $_POST['comment']);
        echo json_encode(array('msg' => 'success'));
    }
    else
        echo json_encode(array('msg' => 'failure', 'error' => 'Not inserted'));

} catch (Exception $e) {
    echo json_encode(array('msg' => 'failure', 'error' => $e->getMessage()));
}
