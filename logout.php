<?php
session_start();
session_unset();
session_destroy();

$headerLocation = "index.php";

// Cookie löschen
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, "/", "", true, true);
}

if (isset($headerLocation)) {
header("Location: $headerLocation ");
exit;
} 
?>