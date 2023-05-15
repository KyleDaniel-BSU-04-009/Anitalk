<?php
  // if the id is empty, the session will immediately end
  require 'config.php';
  if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    $postid = $_GET["post"];
    $curruser = $row["id"];
    $_SESSION["postid"] = $postid;
    // these set of variables hold essential data for the session
    // the if-statement is used to allow access if the the user has logged in
    
    $userprofilepic = $row["profilepic"] == NULL ? '' : 'style="background-image:url(\'uploads/profile_pictures/' . $row["profilepic"] . '\');"';
    // this variable will check for the user's profile picture
    // and if it does exist, it will add the detail
    
    $likesres = mysqli_query($conn, "SELECT * from tb_like");
    while($likesrow = mysqli_fetch_assoc($likesres)){
      if($likesrow["likedby"] == $row["id"]){
        $listoflikes[] = $likesrow["likedpost"];
      }
    }
    $userlikescount = empty($listoflikes) ? 0 : count($listoflikes);
    // the while loop above count the number of likes made by the user
    $commentsres = mysqli_query($conn, "SELECT * from tb_comment");
    while($commentsrow = mysqli_fetch_assoc($commentsres)){
      if($commentsrow["usercommenterid"] == $row["id"]){
        $commentscount[] = $commentsrow["usercommenterid"];
      }
    }
    $usercommentscount = empty($commentscount) ? 0 : count($commentscount);
    // the while loop above count the number of comments made by the user
    $userpostsres = mysqli_query($conn, "SELECT * from tb_post");
    while($userpostsrow = mysqli_fetch_assoc($userpostsres)){
      if($userpostsrow["userpostid"] == $row["id"]){
        $userpostscount[] = $userpostsrow["postid"];
      }
    }
    $userpostcount = empty($userpostscount) ? 0 : count($userpostscount);
    // the while loop above count the number of posts made by the user
    
    if(isset($_POST["likedpost"])){
      function secure($conn, $var){
        $var = stripcslashes($var);
        $var = mysqli_real_escape_string($conn, $var);
        return $var;
      }
      $likedpost = secure($conn, $_POST["likedpost"]);
      $pres = mysqli_query($conn, "SELECT * FROM tb_post WHERE postid = '$likedpost'");
      $prow = mysqli_fetch_assoc($pres);
      $likedalready = mysqli_query($conn, "SELECT * FROM tb_like WHERE likedpost = '$likedpost' AND likedby = '$curruser'");
      
      // checking if its the same user liking.
      if($prow["userpostid"] != $curruser){
        if(mysqli_num_rows($likedalready) > 0){
          // if the user hs liked the post already, it will remove the like
          $unlike = "DELETE FROM tb_like WHERE likedpost = '$likedpost' AND likedby = '$curruser'";
          mysqli_query($conn, $unlike);
        }
        else{
          // if the user hasn't liked the post yet, the post will be liked
          $likeQ = "INSERT INTO tb_like VALUES('$likedpost', '$curruser')";
          mysqli_query($conn, $likeQ);
        }
      }
      else
      { echo '<script>alert("cannot like the post")</script>'; }
    }
    
    $comres = mysqli_query($conn, "SELECT * FROM tb_comment");
    $comcount = 0;
    while($comrow = mysqli_fetch_assoc($comres)){
      if($comrow["postid"] == $postid){
        $comcount++;
        $compostid[] = $comrow["postid"];
        $comcommenttext[] = $comrow["commenttext"];
        $comusercommenterid[] = $comrow["usercommenterid"];
        $comcommentid[] = $comrow["commentid"];
        $comdateposted[] = $comrow["dateposted"];
        $comdatedetails[] = $comrow["datedetails"];
      }
    }
    // the while loop above will count the comments and take 
    // the filtered row's data into separate arrays
    // the comments added to the arrays are based on the id of the post
  }
  else{
    header("location: login.php");
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Index</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/base.css">
    <link rel="stylesheet" href="styles/delete_modal.css">
    <link rel="stylesheet" href="styles/comment_section.css">
    <link rel="shortcut icon" href="images/icon.png" type="image/x-icon">
    <script src="scripts/comments_filters.js" type="text/javascript" defer></script>
    <script src="scripts/like.js" type="text/javascript" defer></script>
    <script src="scripts/nav.js" type="text/javascript" defer></script>
    <script src="scripts/comment.js" type="text/javascript" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  </head>
  <body>
    <div class="mainWrapper">
      <div class="leftContent">
        <div class="userContent">
          <div class="userContentDetails">
            <div class="userIcon detailIcon" <?php echo $userprofilepic; ?>></div>
            <h1 class="detailHeader"><?php echo $row["username"]; ?></h1>
          </div>
          <div>
            <h3><span><?php echo $userpostcount; ?></span> Posts</h3>
            <h3><span><?php echo $usercommentscount ?></span> Comments</h3>
            <h3><span><?php echo $userlikescount ?></span> Likes</h3>
            <!-- displays the user details -->
          </div>
          <div class="userLinks">
            <a href="logout.php">Logout</a>
            <a href="settings.php">Settings</a>
          </div>
        </div>
      </div>
      <div class="centerContent">
        <?php
          $findpost = mysqli_query($conn, "SELECT * FROM tb_post WHERE postid = '$postid'");
          if(mysqli_num_rows($findpost) == 0){
            // if there aren't any posts, it will display this message
            // noting the user that there's no such content that exists
            echo '<div class="postsBanner">';
            echo   "<p>This content doesn't exist, sorry!</p>";
            echo '</div>';
          }
        ?>
        <div class="postFeed">
          <?php
            function postdetails($postid, $conn, $postdetails){
              $res = mysqli_query($conn, "SELECT * FROM tb_post WHERE postid = '$postid'");
              $rowres = mysqli_fetch_assoc($res);
              if($postdetails == "animetag"){ return $rowres["animetag"]; }
              if($postdetails == "posttag"){ return $rowres["posttag"]; }
              if($postdetails == "text"){ return $rowres["posttext"]; }
              if($postdetails == "image"){ return $rowres["has_image"] == 0 ? null : "uploads/post_images/" . $rowres["image"]; }
              if($postdetails == "userpostid"){ return $rowres["userpostid"]; }
              if($postdetails == "dateposted"){ return $rowres["dateposted"]; }
              if($postdetails == "datedetails"){ return $rowres["datedetails"]; }
            }
            // the function above will return the detail requested by the caller
            
            function userdetails($postid, $conn, $userdetails){
              $postres = mysqli_query($conn, "SELECT * FROM tb_post WHERE postid = '$postid'");
              $postrow = mysqli_fetch_assoc($postres);
              $postuser = $postrow["userpostid"];
              $userres = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = '$postuser'");
              $userrow = mysqli_fetch_assoc($userres);
              if($userdetails == "pfp"){ return $userrow["profilepic"]; }
              if($userdetails == "username"){ return $userrow["username"]; }
            }
            // the functions above will return values based on the detail requested by the caller
            
            function likesCount($postid, $conn){
              $likesresult = mysqli_query($conn, "SELECT * FROM tb_like");
              while($likesrow = mysqli_fetch_assoc($likesresult)){
                if($postid == $likesrow["likedpost"]){
                  $likedpost[] = $likesrow["likedpost"];
                }
              }
              return empty($likedpost) ? 0 : count($likedpost);
            }
            // the function above will count the likes of the post
            
            function iscurr($userpostid, $curruser){
              return $userpostid == $curruser ? ' currUser' : null;
            }
            // the function above will check if the post was made by the same user
            
            function likedba($curruser, $postid, $conn){
              $likedba = mysqli_query($conn, "SELECT * FROM tb_like WHERE likedpost = '$postid' AND likedby = '$curruser'");
              return mysqli_num_rows($likedba) > 0 ? ' isLiked' : ' notLiked';
            }
            // the function above will check if the user has already liked the post or not
            
            $postdetails = ["animetag", "posttag", "text", "image", "userpostid", "dateposted", "datedetails"];
            $userdetails = ["pfp", "username"];
            
            $findpost = mysqli_query($conn, "SELECT * FROM tb_post WHERE postid = '$postid'");
            if(mysqli_num_rows($findpost) != 0){
              echo '<div class="userPost" id="' . $postid . '" style="background-image:url(\'images/' . postdetails($postid, $conn, $postdetails[0]) . '.jpg\');">';
              echo   '<div class="userDetails">';
              echo     '<div class="userDetailsGroup">';
              echo       '<div class="userIcon" style="background-image:url(\'uploads/profile_pictures/' . userdetails($postid, $conn, $userdetails[0]) . '\')"></div>';
              echo       '<h1 class="userUsername">' . userdetails($postid, $conn, $userdetails[1]) . '</h1>';
              echo     '</div>';
              echo     '<div class="postTagGenre ' . postdetails($postid, $conn, $postdetails[1]) . 'PostTag"><label>' . postdetails($postid, $conn, $postdetails[1]) . '</label></div>';
              echo   '</div>';
              echo   '<div class="userPostDetails">';
              echo     '<pre class="postText">' . postdetails($postid, $conn, $postdetails[2]) . '</pre>';
              echo     '<img class="postImage" src="' . postdetails($postid, $conn, $postdetails[3]) . '">';
              echo   '</div>';
              echo   '<div class="postOptions">';
              echo     '<div class="btnWrapper likeTrigger leftBtn' . iscurr(postdetails($postid, $conn, $postdetails[4]), $curruser) . '" id="' . $postid . '"><div class="postIcon likeBtn' . likedba($curruser, $postid, $conn) . '"></div><span class="contentCount">' . likesCount($postid, $conn) . '</span></div>';
              echo     '<div class="btnWrapper leftBtn"><div class="postIcon commentBtn"></div><span class="contentCount">' . $comcount . '</span></div>';
              echo     '<div class="rightBtn dateWrapper"><h3 class="datePosted"><span class="dateDets">' . postdetails($postid, $conn, $postdetails[6]) . ' at&nbsp;</span>' . postdetails($postid, $conn, $postdetails[5]) . '</h3></div>';
              echo   '</div>';
              echo '</div>';
            }
            // the if-statement above will echo the post along
            // with the details requested from the server
            // the details are requested using a function
          ?>
        </div>
      </div>
      <div class="rightContent">
        <div class="commentSection">
          <form class="commentCompose" action="<?php echo htmlspecialchars('comment_complete.php'); ?>" method="post" autocomplete="off">
            <textarea autocomplete="off" name="comment" placeholder="Write a comment" value=""></textarea>
            <button type="submit" name="submit" id="commentTrigger">Comment!</button>
          </form>
          <hr>
          <div class="commentsFilter">
            <container>
              <label for="latest">Sort by latest
                <input type="radio" name="timeFilter" id="latest">
                <span class="radioButton"></span>
              </label>
              <label for="oldest">Sort by oldest
                <input type="radio" name="timeFilter" id="oldest">
                <span class="radioButton"></span>
              </label>
            </container>
          </div>
          <div class="commentFeed feedCollection">
            <?php
              function retuserinfo($comusercommenterid, $f, $conn, $info){
                $ret = mysqli_query($conn, "SELECT * FROM tb_user WHERE id='$comusercommenterid[$f]'");
                $retrow = mysqli_fetch_assoc($ret);
                if($info == "pfp"){ return $retrow["profilepic"]; }
                if($info == "usn"){ return $retrow["username"]; }
              }
              // the functions above will return values based on the detail requested by the caller
              
              for($f = 0; $f < $comcount; $f++){
                echo  '<div class="userComment">';
                echo    '<div class="commentUserDetails">';
                echo      '<div class="detailGroup">';
                echo        '<div class="userIcon" style="background-image:url(\'uploads/profile_pictures/' . retuserinfo($comusercommenterid, $f, $conn, $info = "pfp") . '\')"></div>';
                echo        '<p>' . retuserinfo($comusercommenterid, $f, $conn, $info = "usn") . '</p>';
                echo      '</div>';
                echo      '<h3 class="datePosted"><span class="dateDets">' . $comdatedetails[$f] . ' at&nbsp;</span>' . $comdateposted[$f] . '</h3>';
                echo    '</div>';
                echo    '<p class="commentText">' . $comcommenttext[$f] . '</p>';
                echo  '</div>';
              }
              // the for loop above will echo all the comments made
              // for this post along with their details requested
              // using a function
            ?>
          </div>
        </div>
        <?php
          $findpost = mysqli_query($conn, "SELECT * FROM tb_post WHERE postid = '$postid'");
          if(mysqli_num_rows($findpost) == 0){
            echo '<script>document.querySelector(".commentSection").remove();</script>';
          }
          // the comment section will be removed when there's
          // no comments found on the post
        ?>
      </div>
    </div>
  </body>
</html>