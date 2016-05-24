<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/Utils.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

if (empty($_SESSION['id']))
{
    echo json_encode(array('msg' => 'failure', 'error' => 'Not authenticated'));
    die();
}

if (!isset($_POST['data']) || !isset($_POST['filter']))
{
    echo json_encode(array('msg' => 'failure', 'error' => 'No data or filter received'));
    die();
}
else if (!in_array($_POST['filter'], array("girls", "hair", "mustache", "rainbow"))) {
    echo json_encode(array('msg' => 'failure', 'error' => 'Invalid filter : ' . $_POST['filter']));
    die();
}

$data = base64_decode($_POST["data"]);

$image = imagecreatefromstring($data);
if ($image == false)
{
    echo json_encode(array('msg' => 'failure', 'error' => 'Image creation failed'));
    die();
}
$filter = imagecreatefrompng("../img/filters/" . $_POST["filter"] . ".png");
$scaled_filter = imagescale($filter, (imagesx($filter) + $_POST['width'] > 0) ? (imagesx($filter) + $_POST['width']) : 1);
$name = generateRandomString() . ".png";
$path = "../img/photos/" . $name ;

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

echo json_encode(array('msg' => 'success', 'data' => "data:image/png;base64," . base64_encode($ret))) ;

?>
