<?php
  // if the id is empty, the session will immediately end
  require 'config.php';
  if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM  tb_user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    $uploaddir = __DIR__ . "/uploads/post_images/";
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
      $posttext = secure($conn, $_POST["post"]);
      // the input is passed to a function as an argument and clean it before using it in mqsli functions
      $postid = v4uuid();
      // calls a function to generate a unique id for the post
      $animetag = $_SESSION["animetag"];
      $userpostid = $row["id"];
      $posttag = secure($conn, $_POST["posttag"]);
      $has_image = empty($_FILES["image"]["name"]) ? 0 : 1;
      // checks if the post has a an image or not
      date_default_timezone_set('Etc/GMT+8');
      // this is a fixed timezone used to modify the date to send tot he database
      $datedetails = date("h:i:s A");// hours : minutes : seconds : AM/PM
      $dateposted = date("d/m/y");// day / month / year
      
      if(!empty($_FILES["image"]["name"])){
        // checks if the user uploaded any images to the post
        $filename = basename($_FILES["image"]["name"]);
        $filerand = random_int(1000000, 9999999) . '-' . $filename;
        // generates a random id before the file name to prevent duplicate files
        $filepath = $uploaddir . $filerand ;
        $filetype = pathinfo($filepath, PATHINFO_EXTENSION);
        $allowed = array('png', 'jpg', 'jpeg', 'gif', 'webp', 'jfif');
        // this is an array of allowed file extensions
        if(in_array($filetype, $allowed)){
          // the if-statement above checks if the uploaded file has one of the allowed extensions
          move_uploaded_file($_FILES["image"]["tmp_name"], $filepath);
          $query = "INSERT INTO tb_post VALUES ('$postid', '$userpostid', '$has_image', '$animetag', '$filerand', '$posttext', '$posttag', '$datedetails', '$dateposted')";
        }
      }
      else{
        // if doesn't have any images, it will simply send data to the
        // database without a file name
        $query = "INSERT INTO tb_post VALUES ('$postid', '$userpostid', '$has_image', '$animetag', '', '$posttext', '$posttag', '$datedetails', '$dateposted')";
      }
      mysqli_query($conn, $query);
    }
    
    header('Location: user.php');
    die();
  }
  else{
    header("location: login.php");
  }
?>