<?php
session_start();

$f = fopen($root_dir . 'logs/logout.txt', 'a');
fwrite($f, $_SESSION['user_id'] . " | " . $_SESSION['username'] . " | " . date('Y-m-d H:i:s') . "\n");
fclose($f);

$_SESSION = array();
session_destroy();

header("Location: /student025/shop/backend/forms/form_login.php");
exit;
?>