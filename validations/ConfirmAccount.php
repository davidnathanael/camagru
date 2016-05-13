<?php

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

if (!isset($_GET['hash']))
header("Location: ../index.php");

$hash = $_GET['hash'];

try {
    $sql = "SELECT login, confirmed, confirmation_hash FROM users WHERE confirmation_hash = '" . $hash ."'";

    $res = $DB->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    if (empty($res))
    header("Location: ../index.php");
    if (!$res[0]['confirmed']) {
        try {
            $sql = "UPDATE users SET confirmed = 1 WHERE confirmation_hash='". $hash ."'";

            $DB->exec($sql);
        } catch(Exception $e) {
            print("Error QUERY ! ". $e->getMessage() ."<br />");
            die();
        }
    }
	header("Location: ../auth.php?action=login&login=" . $res[0]['login'] . "&msg=" . urlencode("Your account has been confirmed."));



} catch(Exception $e) {
    print("Error QUERY ! ". $e->getMessage() ."<br />");
    die();
}

?>
