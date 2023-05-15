<?php
  session_start();
  $conn = mysqli_connect("localhost", "root", "", "anitalk_db");
  // this is the main configuration file
  // where the session begins and required by 90% of
  // all the php files of this project
?>