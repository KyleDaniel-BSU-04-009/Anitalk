<?php
  require 'config.php';
  $_SESSION = [];
  session_unset();
  session_destroy();
  header("Location: login.php");
  // this is a script used to end the session and redirect them to the login page
?>