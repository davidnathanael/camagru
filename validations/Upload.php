<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/Utils.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

$data = base64_decode($_POST["data"]);

$image = imagecreatefromstring($data);
$filter = imagecreatefrompng("../img/filters/" . $_POST["filter"] . ".png");
$scaled_filter = imagescale($filter, (imagesx($filter) + $_POST['width'] > 0) ? (imagesx($filter) + $_POST['width']) : 1);
$name = generateRandomString() . ".png";
$path = "../img/photos/" . $name ;

// imageAlphaBlending($filter, true);
// imageSaveAlpha($filter, true);

if ($_POST['filter'] == 'mustache')
    imagecopy($image, $scaled_filter, 140 + $_POST["left"], 140 + $_POST["top"], 0, 0, imagesx($scaled_filter) , imagesy($scaled_filter));
else if ($_POST['filter'] == 'hair')
    imagecopy($image, $scaled_filter, 100 + $_POST["left"], 0 + $_POST["top"], 0, 0, imagesx($scaled_filter), imagesy($scaled_filter));
else
    imagecopy($image, $scaled_filter, 0 + $_POST["left"], 0 + $_POST["top"], 0, 0, imagesx($scaled_filter), imagesy($scaled_filter));

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
    echo json_encode(array('msg' => 'failure', 'error' => $e->getMessage())) ;
    die();
}

imagedestroy($image);
imagedestroy($filter);
imagedestroy($scaled_filter);

echo json_encode(array('msg' => 'success', 'top' => $_POST['top'], 'data' => "data:image/png;base64," . base64_encode($ret))) ;

?>
