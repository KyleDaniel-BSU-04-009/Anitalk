<?php
  // if the id is empty, the session will immediately end
  require 'config.php';
  if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM  tb_user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    $postid = $_SESSION["postid"];
    // these set of variables hold essential data for the session
    // the if-statement is used to allow access if the the user has logged in
    
    function v4uuid($data = null){
      $data = $data ?? random_bytes(16);
      assert(strlen($data) == 16);// generates 16 bytes of random data
      $data[6] = chr(ord($data[6]) & 0x0f | 0x40);// sets version to 0100
      $data[8] = chr(ord($data[8]) & 0x3f | 0x80);// sets bits 6-7 to 10
      return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));// returns a v4 uuid
    }
    // the function above creates a random v4 uuid for the comment
    
    if(isset($_POST["submit"])){
      function secure($conn, $var){
        $var = stripcslashes($var);
        $var = mysqli_real_escape_string($conn, $var);
        return $var;
      }
      // the function above is used to secure input and prevent sql injection
      $commenttext = secure($conn, $_POST["comment"]);
      // the input is passed to a function as an argument and clean it before using it in mqsli functions
      $usercommenterid = $row["id"];
      date_default_timezone_set('Etc/GMT+8');
      // this is a fixed timezone used to modify the date to send tot he database
      $commentid = v4uuid();
      // calls a function to generate a unique id for the comment
      $datedetails = date("h:i:s A");// hours : minutes : seconds : AM/PM
      $dateposted = date("d/m/y");// day / month / year
      if($commenttext != ''){
        // the if-statement above checks if the comment somehow
        // doesn't have any value as a final safety measure
        $query = "INSERT INTO tb_comment VALUES ('$postid', '$commenttext', '$usercommenterid', '$commentid', '$dateposted', '$datedetails')";
        mysqli_query($conn, $query);
      }
    }
    
    header('Location: userpost.php?post=' . $postid);
    die();
  }
  else{
    header("location: login.php");
  }
?>