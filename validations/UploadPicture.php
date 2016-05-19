<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/Utils.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

// print(json_encode(array('res' => $_REQUEST['filter'])));
$data = base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));

$image = imagecreatefromstring(base64_decode($data));
$filter = imagecreatefrompng("../img/filters/" . $_REQUEST['filter'] . ".png");
$name = generateRandomString() . ".png";
$path = "../img/photos/" . $name ;

imageAlphaBlending($filter, true);
imageSaveAlpha($filter, true);

if ($_REQUEST['filter'] == 'mustache')
    imagecopy($image, $filter, 140, 140 , 0, 0, imagesx($filter), imagesy($filter));
else if ($_REQUEST['filter'] == 'hair')
    imagecopy($image, $filter, 100, 0, 0, 0, imagesx($filter), imagesy($filter));
else
    imagecopy($image, $filter, 0, 0 , 0, 0, imagesx($filter), imagesy($filter));

imagepng($image, $path);

ob_start();

    imagepng($image);
    $ret = ob_get_contents();

ob_end_clean();

try {
    $count = $DB->exec("INSERT INTO photos (img_path, user_id) VALUES ('" . $name . "', " . $_SESSION['id'] . ")");
    $DB = null;
} catch (Exception $e)
{
    print("Error QUERY ! ". $e->getMessage() ."<br />");
    die();
}

echo json_encode(array('msg' => 'success', 'data' => "data:image/png;base64," . base64_encode($ret))) ;

?>
