<?php
session_start();

$_SESSION = array();

session_destroy();

header("Location: /student025/shop/backend/forms/form_login.php");
exit;
?>