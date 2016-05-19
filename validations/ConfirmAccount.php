<?php

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/validations/DB_Utils.php';

if (!isset($_GET['hash']))
header("Location: ../index.php");

$hash = $_GET['hash'];
if ($hash == "")
    header("Location: ../auth.php?action=confirm&msg=" . urlencode("Please enter confirmation code"));
if ($_SESSION['form_token'] != $_GET['form_token'])
    header("Location: ../auth.php?action=confirm&msg=" . urlencode("Invalid form token"));
try {
    $sql = "SELECT login, confirmed, confirmation_hash FROM users WHERE confirmation_hash = '" . $hash ."'";
    $res = $DB->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    if (empty($res)) {
        header("Location: ../auth.php?action=confirm&msg=" . urlencode("Incorrect confirmation code."));
        die();
    }
    if (!$res[0]['confirmed']) {
        try {
            $sql = "UPDATE users SET confirmed = 1, confirmation_hash = NULL WHERE confirmation_hash='". $hash ."'";

            $DB->exec($sql);
            unset($_SESSION['form_token']);
            header("Location: ../auth.php?action=login&login=" . $res[0]['login'] . "&msg=" . urlencode("Your account has been confirmed."));
        } catch(Exception $e) {
            print("Error QUERY ! ". $e->getMessage() ."<br />");
            die();
        }
    }
} catch(Exception $e) {
    print("Error QUERY ! ". $e->getMessage() ."<br />");
    die();
}

?>
