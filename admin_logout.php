<?php
  session_start();
  session_regenerate_id(true);
  if (isset($_SESSION['login']) === false) {
    header('Location: admin_login.php');
    exit();
  }

  $_SESSION = array();
  if (isset($_COOKIE[session_name()]) == true) {
    setcookie(session_name(),'',time()-42000,'/');
  }
  session_destroy();

  header('Location: admin_login.php');
  exit();
?>