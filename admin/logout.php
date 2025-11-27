<?php
session_start();
session_unset();
session_destroy();
// Redirect kembali ke login admin
header("Location: login.php");
exit;
?>