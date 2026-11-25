<?php
session_start();
session_unset();
session_destroy();
header("Location: /PROJETO-A3/auth/login.php");
exit;