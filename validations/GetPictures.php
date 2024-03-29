<?php

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

if (empty($_SESSION['id']))
{
    echo json_encode(array('msg' => 'failure', 'error' => 'Not authenticated'));
    die();
}

try {
    $total = $DB->query('SELECT COUNT(*) FROM photos')->fetchColumn();
    $limit = 12;
    $pages = ceil($total / $limit);
    $page = $_GET['page'] ? $_GET['page'] : 1;
    $offset = ($page - 1)  * $limit;
    $start = $offset + 1;
    $end = min(($offset + $limit), $total);

    $stmt = $DB->prepare('SELECT * FROM photos ORDER BY createdAt desc LIMIT :limit OFFSET :offset');

    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $pictures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pictures as $key => $pic) {
            $likes = $DB->query('SELECT * FROM likes WHERE photo_id = ' . $pic['id'])->fetchAll(PDO::FETCH_ASSOC);
            $pictures[$key]['liked'] = false;
            foreach ($likes as $like) {
                if ($like['user_id'] == $_SESSION['id'])
                    $pictures[$key]['liked'] = true;
            }
            $pictures[$key]['likes'] = count($likes);

            $comments = $DB->query('SELECT content, users.login as author FROM comments JOIN users ON comments.user_id=users.id WHERE photo_id = ' . $pic['id'])->fetchAll(PDO::FETCH_ASSOC);
            $pictures[$key]['comments'] = array();
            foreach ($comments as $comment) {
                $pictures[$key]['comments'][] = array('comment' => $comment['content'], 'user' => $comment['author']);
            }
            $pictures[$key]['nb_comments'] = count($comments);
        }
        echo json_encode(array('msg' => 'success', 'last_page' => $pages, 'user_id' => $_SESSION['id'], 'pictures' => $pictures));

    } else {
        echo json_encode(array('msg' => 'error', 'error' => 'No records'));
    }

    $DB = null;
} catch (Exception $e)
{
    echo json_encode(array('msg' => 'error', 'error' => $e->getMessage()));
}



?>
