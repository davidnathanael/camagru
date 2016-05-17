<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/Utils.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

$data = base64_decode($_POST["data"]);

$image = imagecreatefromstring($data);
$filter = imagecreatefrompng("../img/filters/" . $_POST["filter"] . ".png");
$name = generateRandomString() . ".png";
$path = "../img/photos/" . $name ;

imageAlphaBlending($filter, true);
imageSaveAlpha($filter, true);

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

echo "data:image/png;base64," . base64_encode($ret);

?>
