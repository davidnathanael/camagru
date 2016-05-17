<?php

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

try {
    $total = $DB->query('SELECT COUNT(*) FROM photos')->fetchColumn();
    $limit = 10;
    $pages = ceil($total / $limit);
    $page = $_GET['page'];
    // $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
    //     'options' => array(
    //         'default'   => 1,
    //         'min_range' => 1,
    //     ),
    // )));
    $offset = ($page - 1)  * $limit;
    $start = $offset + 1;
    $end = min(($offset + $limit), $total);

    $stmt = $DB->prepare('
        SELECT
            *
        FROM
            photos
        ORDER BY
            createdAt desc
        LIMIT
            :limit
        OFFSET
            :offset
    ');

    // Bind the query params
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Do we have any results?
    if ($stmt->rowCount() > 0) {
        // Define how we want to fetch the results
        $pictures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($pictures);

    } else {
        echo json_encode('No more pictures');
    }

    $DB = null;
} catch (Exception $e)
{
    print("Error QUERY ! ". $e->getMessage() ."<br />");
    die();
}



?>
