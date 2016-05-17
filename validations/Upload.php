<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/Utils.php';

$data = base64_decode($_POST["data"]);

$image = imagecreatefromstring($data);
$filter = imagecreatefrompng("../img/filters/" . $_POST["filter"] . ".png");
$name = generateRandomString() . ".png";
$path = "../img/photos/" . $name ;

imageAlphaBlending($filter, true);
imageSaveAlpha($filter, true);

imagecopy($image, $filter, 0, 0 , 0, 0, imagesx($filter), imagesy($filter));

ob_start();

    imagepng($image);
    $ret = ob_get_contents();

ob_end_clean();

echo "data:image/png;base64," . base64_encode($ret);

?>
