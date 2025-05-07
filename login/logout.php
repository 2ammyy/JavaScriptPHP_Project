<?php
session_start();
session_destroy();
header('Location: ../Acceuil/Acceuil.php');
exit;
?>