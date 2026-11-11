<?php
session_start();
session_destroy();
header("Location: LoginGP.php");
exit();
?>