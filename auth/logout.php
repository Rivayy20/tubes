<?php
session_start();

session_unset();

session_destroy();

header("Location: /tubes/auth/login.php");
exit();
?>
