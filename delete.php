<?php
  // if the id is empty, the session will immediately end
  require 'config.php';
  if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    // these set of variables hold essential data for the session
    // the if-statement is used to allow access if the the user has logged in
    
    if($_GET["content"]){
      // if the url content parameter exists it will continue
      $content = $_GET["content"];
      // if the content exists, it will take the value
      $checkpost = mysqli_query($conn, "SELECT * FROM tb_post WHERE postid = '$content'");
      $checkcomment = mysqli_query($conn, "SELECT * FROM tb_comment WHERE commentid = '$content'");
      if(mysqli_num_rows($checkpost) > 0){
        // the if-statement above checks if the post exists
        $post = $content;
        $prow = mysqli_fetch_assoc($checkpost);
        if(($prow["postid"] == $post) && ($prow["userpostid"] == $row["id"])){
          // the if-statement above checks if the post uploader's id matches with
          // the logged in user's id
          if($prow["has_image"] == 1){
            // if the post has an image, it will delete the file as well
            $imgdel = __DIR__ . "/uploads/post_images/" . $prow["image"];
            unlink($imgdel);
          }
          $del = "DELETE FROM tb_post WHERE postid = '$post'";
          $comm = "DELETE FROM tb_comment WHERE postid = '$post'";
          $like = "DELETE FROM tb_like WHERE likedpost = '$post'";
          mysqli_query($conn, $like);
          mysqli_query($conn, $comm);
          // deletes the post from all related databases
          if(mysqli_query($conn, $del)){
            header('Location: user.php');
            die();
          }
        }
        else{
          echo '<script>alert("Error deleting post")</script>';
        }
      }
      if(mysqli_num_rows($checkcomment) > 0){
        // the if-statement above checks if the comment exists
        $post = $content;
        $prow = mysqli_fetch_assoc($checkcomment);
        if(($prow["commentid"] == $post) && ($prow["usercommenterid"] == $row["id"])){
          // the if-statement above checks if the comment uploader's id matches with
          // the logged in user's id
          $del = "DELETE FROM tb_comment WHERE commentid = '$post'";
          // deletes the comment from all related databases
          if(mysqli_query($conn, $del)){
            header('Location: comments.php');
            die();
          }
        }
        else{
          echo '<script>alert("Error deleting post")</script>';
        }
      }
    }
    header('Location: index.php');
    die();
  }
  else{
    header("location: login.php");
  }
?>