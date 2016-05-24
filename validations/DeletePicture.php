<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

if (empty($_SESSION['id']))
{
    echo json_encode(array('msg' => 'failure', 'error' => 'Not authenticated'));
    die();
}

if (empty($_GET['id']))
{
    echo json_encode(array('msg' => 'failure', 'error' => 'ID not specified'));
    die();
}

try {
    $stmt = $DB->prepare('SELECT * FROM photos WHERE id = :id');

    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $pictures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pictures as $pic) {
            if ($_SESSION['id'] == $pic['user_id'])
            {
                $del = $DB->prepare('DELETE FROM photos WHERE id = ' . $pic['id']);
                $del->execute();

                $count = $del->rowCount();
                if ($count)
                {
                    $DB->query('DELETE FROM likes WHERE photo_id = ' . $pic['id']);
                    $DB->query('DELETE FROM comments WHERE photo_id = ' . $pic['id']);
                    $file_path = "../img/photos/" . $pic['img_path'];
                    if (file_exists($file_path))
                        unlink($file_path);
                    echo json_encode(array('msg' => 'success'));
                }
            }
            else
                echo json_encode(array('msg' => 'failure', 'error' => 'unauthorized'));
        };

    } else {
        echo json_encode(array('msg' => 'failure', 'error' => 'No record'));
    }

} catch (Exception $e)
{
    echo json_encode(array('msg' => 'failure', 'error' => e.getMessage()));
    die();
}

?>
