<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/Utils.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

if (empty($_SESSION['id']))
{
    echo json_encode(array('msg' => 'failure', 'error' => 'Not authenticated'));
    die();
}

if ($_FILES['file']['error'] || $_FILES['file']['type'] != "image/png")
{
    echo json_encode(array('msg' => 'failure', 'error' => 'Upload failed or incorrect format (supported format : png)'));
    die();
}

$data = base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));

$image = imagecreatefromstring(base64_decode($data));
if ($image == false)
{
    echo json_encode(array('msg' => 'failure', 'error' => 'Image creation failed'));
    die();
}
$filter = imagecreatefrompng("../img/filters/" . $_REQUEST['filter'] . ".png");
$scaled_filter = imagescale($filter, (imagesx($filter) + $_REQUEST['width'] > 0) ? (imagesx($filter) + $_REQUEST['width']) : 1);
$name = generateRandomString() . ".png";
$path = "../img/photos/" . $name ;

imageAlphaBlending($filter, true);
imageSaveAlpha($filter, true);

if ($_REQUEST['filter'] == 'mustache')
    imagecopy($image, $scaled_filter, 140 + $_REQUEST['left'], 140 + $_REQUEST['top'], 0, 0, imagesx($scaled_filter), imagesy($scaled_filter));
else if ($_REQUEST['filter'] == 'hair')
    imagecopy($image, $scaled_filter, 100 + $_REQUEST['left'], 0 + $_REQUEST['top'], 0, 0, imagesx($scaled_filter), imagesy($scaled_filter));
else
    imagecopy($image, $scaled_filter, 0 + $_REQUEST['left'], 0 + $_REQUEST['top'], 0, 0, imagesx($scaled_filter), imagesy($scaled_filter));

imagepng($image, $path);

ob_start();

    imagepng($image);
    $ret = ob_get_contents();

ob_end_clean();

imagedestroy($image);
imagedestroy($filter);
imagedestroy($scaled_filter);

try {
    $count = $DB->exec("INSERT INTO photos (img_path, user_id) VALUES ('" . $name . "', " . $_SESSION['id'] . ")");
    $DB = null;
} catch (Exception $e)
{
    echo json_encode(array('msg' => 'failure', 'error' => $e->getMessage())) ;
    die();
}

echo json_encode(array('msg' => 'success', 'data' => "data:image/png;base64," . base64_encode($ret))) ;

?>
