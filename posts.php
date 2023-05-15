<?php
  // if the id is empty, the session will immediately end
  require 'config.php';
  if(!empty($_SESSION["id"])){
    $cid = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = $cid");
    $row = mysqli_fetch_assoc($result);
    $curruser = $row["id"];
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
    
    $postusers = mysqli_query($conn, "SELECT * FROM tb_user");
    $postresult = mysqli_query($conn, "SELECT * FROM tb_post");
    $postCount = 0;
    $pagetag = $_GET["anime"];
    while($postrow = mysqli_fetch_assoc($postresult)){
      if($postrow["animetag"] == $pagetag){
        $postCount++;
        $postid[] = $postrow["postid"];
        $userpostid[] = $postrow["userpostid"];
        $posttag[] = $postrow["posttag"];
        $has_image[] = $postrow["has_image"];
        $image[] = $postrow["image"];
        $posttext[] = $postrow["posttext"];
        $dateposted[] = $postrow["dateposted"];
        $datedetails[] = $postrow["datedetails"];
      }
    }
    // the while loop above will collect all the posts related to the anime
    // topic of the page
    while($userrow = mysqli_fetch_assoc($postusers)){
      $id[] = $userrow["id"];
      $username[] = $userrow["username"];
      $profilepic[] = $userrow["profilepic"];
    }
    // the while loop above will collect the ncecessary details of the
    // users who made posts related to the anime topic
    
    $reviewCount = 0;
    $artCount = 0;
    $criticismCount = 0;
    $rantCount = 0;
    $humorCount = 0;
    // initializing variables for tag counts
    
    if(!empty($posttag)){
      for($d = 0; $d < count($posttag); $d++){
        $posttag[$d] == "review" ? $reviewCount++ : null;
        $posttag[$d] == "art" ? $artCount++ : null;
        $posttag[$d] == "criticism" ? $criticismCount++ : null;
        $posttag[$d] == "rant" ? $rantCount++ : null;
        $posttag[$d] == "humor" ? $humorCount++ : null;
      }
    }
    // if the array for post tags isn't empty, it'll start collecting
    // all the tags of the posts related to the anime topic
    // checking if the array of post tags isn't empty will prevent errors
    
    if(isset($_POST["likedpost"])){
      function secure($conn, $var){
        $var = stripcslashes($var);
        $var = mysqli_real_escape_string($conn, $var);
        return $var;
      }
      // the function above is used to secure input and prevent sql injection
      $likedpost = secure($conn, $_POST["likedpost"]); // data sent by AJAX function handled using jQuery
      // the input is passed to a function as an argument and clean it before using it in mqsli functions
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
    <link rel="stylesheet" href="styles/sidebar_switch.css">
    <link rel="stylesheet" href="styles/post_panel.css">
    <link rel="shortcut icon" href="images/icon.png" type="image/x-icon">
    <script src="scripts/filters.js" type="text/javascript" defer></script>
    <script src="scripts/post_compose.js" type="text/javascript" defer></script>
    <script src="scripts/like.js" type="text/javascript" defer></script>
    <script src="scripts/nav.js" type="text/javascript" defer></script>
    <script src="scripts/userpost_enter.js" type="text/javascript" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  </head>
  <body>
    <div class="sidebarSwitch"><div class="siswicon"></div></div>
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
        <div class="postTagList">
          <h1>Tag List</h1>
          <div class="tagLibrary">
            <div class="tagButtons">
              <div class="postTagGenre reviewPostTag"><label>review</label></div>
              <div class="postTagGenre artPostTag"><label>art</label></div>
              <div class="postTagGenre criticismPostTag"><label>criticism</label></div>
              <div class="postTagGenre rantPostTag"><label>rant</label></div>
              <div class="postTagGenre humorPostTag"><label>humor</label></div>
            </div>
            <div class="tagCount">
              <h2 class="reviewPostTag"><?php echo $reviewCount ?></h2>
              <h2 class="artPostTag"><?php echo $artCount ?></h2>
              <h2 class="criticismPostTag"><?php echo $criticismCount ?></h2>
              <h2 class="rantPostTag"><?php echo $rantCount ?></h2>
              <h2 class="humorPostTag"><?php echo $humorCount ?></h2>
              <!-- displays the tag count from the list of posts -->
            </div>
          </div>
        </div>
        <div class="filtersList">
          <h1>Filters</h1>
          <div>
            <h2>Time</h2>
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
            <h2>Likes &amp; Comments</h2>
            <container>
              <label for="mostLikes">Most likes
                <input type="radio" name="likesFilter" id="mostLikes">
                <span class="radioButton"></span>
              </label>
              <label for="leastLikes">Least Likes
                <input type="radio" name="likesFilter" id="leastLikes">
                <span class="radioButton"></span>
              </label>
            </container>
            <container>
              <label for="mostComments">Most Comments
                <input type="radio" name="commentsFilter" id="mostComments">
                <span class="radioButton"></span>
              </label>
              <label for="leastComments">Least Comments
                <input type="radio" name="commentsFilter" id="leastComments">
                <span class="radioButton"></span>
              </label>
            </container>
            <h2 class="resetFilters">Reset Filters</h2>
          </div>
        </div>
      </div>
      <div class="centerContent">
        <div class="postFeed feedCollection">
          <?php
            function userpfp($id, $i, $userpostid, $profilepic){
              $length = count($id);
              for($j = 0; $j < $length; $j++){
                if($userpostid[$i] == $id[$j])
                { return $profilepic[$j]; }
              }
            }
            function username($id, $i, $userpostid, $username){
              $length = count($id);
              for($j = 0; $j < $length; $j++){
                if($userpostid[$i] == $id[$j])
                { return $username[$j]; }
              }
            }
            // the functions above will return the detail requested by the caller
            
            function likesCount($postid, $i, $conn){
              $likesresult = mysqli_query($conn, "SELECT * FROM tb_like");
              while($likesrow = mysqli_fetch_assoc($likesresult)){
                if($postid[$i] == $likesrow["likedpost"]){
                  $likedpost[] = $likesrow["likedpost"];
                }
              }
              return empty($likedpost) ? 0 : count($likedpost);
            }
            function commentsCount($postid, $i, $conn){
              $commentsresult = mysqli_query($conn, "SELECT * FROM tb_comment");
              while($commentsrow = mysqli_fetch_assoc($commentsresult)){
                if($postid[$i] == $commentsrow["postid"]){
                  $commentpost[] = $commentsrow["postid"];
                }
              }
              return empty($commentpost) ? 0 : count($commentpost);
            }
            // the functions above will count likes and comments
            // each like will be pushed into an array and the array's
            // size will be returned
            // the functions will return 0 if the array is empty
            
            function iscurr($userpostid, $i, $curruser){
              return $userpostid[$i] == $curruser ? ' currUser' : null;
            }
            // the function above will check if the post was made by the same user
            
            function likedba($curruser, $postid, $i, $conn){
              $likedba = mysqli_query($conn, "SELECT * FROM tb_like WHERE likedpost = '$postid[$i]' AND likedby = '$curruser'");
              return mysqli_num_rows($likedba) > 0 ? ' isLiked' : ' notLiked';
            }
            // the function above will check if the user has already liked the post or not
            
            function image($has_image, $image, $i){
              return $has_image[$i] == 0 ? null : "uploads/post_images/" . $image[$i];
            }
            // the function above is used to add an image directory if the post has an image
            
            $findpost = mysqli_query($conn, "SELECT * FROM tb_post WHERE animetag = '$pagetag'");
            if(mysqli_num_rows($findpost) > 0){
              for($i = 0; $i < $postCount; $i++){
                echo '<div data-likecount="' . likesCount($postid, $i, $conn) . '" data-commcount="' . commentsCount($postid, $i, $conn) . '" class="userPost enterThisPost" id="' . $postid[$i] . '"style="background-image:url(\'images/' . $pagetag . '.jpg\');">';
                echo   '<div class="userDetails">';
                echo     '<div class="userDetailsGroup">';
                echo       '<div class="userIcon" style="background-image:url(\'uploads/profile_pictures/' . userpfp($id, $i, $userpostid, $profilepic) . '\')"></div>';
                echo       '<h1 class="userUsername">' . username($id, $i, $userpostid, $username) . '</h1>';
                echo     '</div>';
                echo     '<div class="postTagGenre ' . $posttag[$i] . 'PostTag"><label>' . $posttag[$i] . '</label></div>';
                echo   '</div>';
                echo   '<div class="userPostDetails">';
                echo     '<pre class="postText">' . $posttext[$i] . '</pre>';
                echo     '<img class="postImage" loading="lazy" src="' . image($has_image, $image, $i) . '">';
                echo   '</div>';
                echo   '<div class="postOptions">';
                echo     '<div class="btnWrapper likeTrigger leftBtn' . iscurr($userpostid, $i, $curruser) . '" id="' . $postid[$i] . '"><div class="postIcon likeBtn' . likedba($curruser, $postid, $i, $conn) . '"></div><span class="contentCount">' . likesCount($postid, $i, $conn) . '</span></div>';
                echo     '<div class="btnWrapper commentTrigger leftBtn"><div class="postIcon commentBtn"></div><span class="contentCount">' . commentsCount($postid, $i, $conn) . '</span></div>';
                echo     '<div class="rightBtn dateWrapper"><h3 class="datePosted"><span class="dateDets">' . $datedetails[$i] . ' at&nbsp;</span>' . $dateposted[$i] . '</h3></div>';
                echo   '</div>';
                echo '</div>';
              }
            }
            else{
              echo '<div class="postsBanner">';
              echo   "<p>No content here yet, sorry!</p>";
              echo '</div>';
            }
            // if there are posts inside of the anime topic, the for loop
            // will echo the list of posts
            // if the anime topic doesn't have any, it will provide a message
            // in the header
          ?>
        </div>
      </div>
      <div class="rightContent">
      </div>
      <div class="postPanel">
        <span class="pagePostBtn"></span>
      </div>
    </div>
  </body>
</html>