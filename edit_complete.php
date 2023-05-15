<?php
  // if the id is empty, the session will immediately end
  require 'config.php';
  if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM  tb_user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    $content = $_GET["content"];
    // these set of variables hold essential data for the session
    // the if-statement is used to allow access if the the user has logged in
    
    if(isset($_POST["submit"])){
      function secure($conn, $var){
        $var = stripcslashes($var);
        $var = mysqli_real_escape_string($conn, $var);
        return $var;
      }
      // the function above is used to secure input and prevent sql injection
      $idofcontent = secure($conn, $_POST["idofcontent"]);
      // the input is passed to a function as an argument and clean it before using it in mqsli functions
      $edittext = empty($_POST["edit"]) ? null : secure($conn, $_POST["edit"]);
      // checks if the text submitted is empty
      if($edittext != null){
        // checks if the content that's being edited is a post or a comment
        if($content == "post"){
          // check if the user that posted the content is the same one who is currently sign in
          $usercheck = mysqli_query($conn, "SELECT * FROM tb_post WHERE postid = '$idofcontent'");
          $checkresult = mysqli_fetch_assoc($usercheck);
          if($checkresult["postid"] == $idofcontent){
            // check if the post exists
            if($checkresult["userpostid"] == $row["id"]){
              // if all is well, meaning the edit isn't empty and the user is authenticated the database will be updated
              $query = "UPDATE tb_post SET posttext = '$edittext' WHERE postid = '$idofcontent'";
              mysqli_query($conn, $query);
              header('Location: index.php');
              die();
            }
          }
        }
        if($content == "comment"){
          // check if the user that posted the content is the same one who is currently sign in
          $usercheck = mysqli_query($conn, "SELECT * FROM tb_comment WHERE commentid = '$idofcontent'");
          $checkresult = mysqli_fetch_assoc($usercheck);
          if($checkresult["usercommenterid"] == $row["id"]){
            // check if the comment exists
            if($checkresult["commentid"] == $idofcontent){
              // if all is well, meaning the edit isn't empty and the user is authenticated the database will be updated
              $query = "UPDATE tb_comment SET commenttext = '$edittext' WHERE commentid = '$idofcontent'";
              mysqli_query($conn, $query);
              header('Location: comments.php');
              die();
            }
          }
        }
      }
      header('Location: index.php');
    }
    header('Location: index.php');
  }
  else{
    header("location: login.php");
  }
?>