<?php
  // if the id is empty, the session will immediately end
  require 'config.php';
  if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM  tb_user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    $curruser = $row["id"];
    // these set of variables hold essential data for the session
    // the if-statement is used to allow access if the the user has logged in
    
    if(isset($_POST["accountDelete"])){
      if($curruser == $id){
      // the if-statement above checks if the current user has
      // the same id as the one in the row selected in the database
        function secure($conn, $var){
          $var = stripcslashes($var);
          $var = mysqli_real_escape_string($conn, $var);
          return $var;
        }
        // the function above is used to secure input and prevent sql injection
        $confirm = secure($conn, $_POST["confirmDelete"]);
        // the input is passed to a function as an argument and clean it before using it in mqsli functions
        if(password_verify($confirm, $row["password"])){
          // password verification through comparing the input and the hashed value stored in the database
          // if it doesn't match it'll return to the settings
          $findpost = mysqli_query($conn, "SELECT * FROM tb_post");
          // this while loop will find and delete likes and comments made by other users from the database
          while($relatedpost = mysqli_fetch_assoc($findpost)){
            if($relatedpost["userpostid"] == $curruser){
              $found = $relatedpost["postid"];
              $delrelatedlike = "DELETE FROM tb_like WHERE likedpost = '$found'";
              $delrelatedcomment = "DELETE FROM tb_comment WHERE postid = '$found'";
              mysqli_query($conn, $delrelatedlike);
              mysqli_query($conn, $delrelatedcomment);
            }
          }
          $findpostimages = mysqli_query($conn, "SELECT * FROM tb_post WHERE userpostid = '$curruser'");
          $finduserimages = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = '$curruser'");
          $opres = mysqli_fetch_assoc($finduserimages);
          
          if($opres["profilepic"] != 'default.png'){
            // if it's not the default profile picture, the image will be deleted from the database and uploads folder
            $imgdel = __DIR__ . "/uploads/profile_pictures/" . $opres["profilepic"];
            unlink($imgdel);
          }
          while($fpirow = mysqli_fetch_assoc($findpostimages)){
            // deletes all images uploaded by the user from the database and uploads folder
            $imgdel = __DIR__ . "/uploads/post_images/" . $fpirow["image"];
            unlink($imgdel);
          }
          $likedel = "DELETE FROM tb_like WHERE likedby = '$curruser'";
          $commdel = "DELETE FROM tb_comment WHERE usercommenterid = '$curruser'";
          $postdel = "DELETE FROM tb_post WHERE userpostid = '$curruser'";
          $userdel = "DELETE FROM tb_user WHERE id = '$curruser'";
          // removes all content made by this user
          mysqli_query($conn, $likedel);
          mysqli_query($conn, $commdel);
          mysqli_query($conn, $postdel);
          mysqli_query($conn, $userdel);
          $_SESSION = [];
          session_unset();
          session_destroy();
          // ends the session after account deletion
          header("Location: account_delete.php");
        }
        else{
          header('Location: settings.php');
        }
      }
    }
    header("location: settings.php");
    die();
  }
  else{
    header("location: login.php");
  }
?>