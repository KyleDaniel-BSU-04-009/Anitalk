<?php
  // if the id is empty, the session will immediately end
  require 'config.php';
  if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    $curruser = $row["id"];
    // these set of variables hold essential data for the session
    // the if-statement is used to allow access if the the user has logged in
    
    $profilepic = $row["profilepic"] == NULL ? '' : 'style="background-image:url(\'uploads/profile_pictures/' . $row["profilepic"] . '\');"';
    // this variable will check for the user's profile picture
    // and if it does exist, it will add the detail
    
    $postresult = mysqli_query($conn, "SELECT * FROM tb_post");
    $likesres = mysqli_query($conn, "SELECT * from tb_like");
    while($likesrow = mysqli_fetch_assoc($likesres)){
      if($likesrow["likedby"] == $row["id"]){
        $listoflikes[] = $likesrow["likedpost"];
      }
    }
    // the while loop above count the number of likes made by the user
    $likescount = empty($listoflikes) ? 0 : count($listoflikes);
    $commentsres = mysqli_query($conn, "SELECT * from tb_comment");
    while($commentsrow = mysqli_fetch_assoc($commentsres)){
      if($commentsrow["usercommenterid"] == $row["id"]){
        $commentscount[] = $commentsrow["usercommenterid"];
      }
    }
    $commentscount = empty($commentscount) ? 0 : count($commentscount);
    // the while loop above count the number of comments made by the user
    $postCount = 0;
    while($postrow = mysqli_fetch_assoc($postresult)){
      if($postrow["userpostid"] == $row["id"]){
        $postCount++;
      }
    }
    // the while loop above count the number of posts made by the user
    if(isset($_POST["likedpost"])){
      $likedpost = $_POST["likedpost"]; // data sent by AJAX function handled using jQuery
      $pres = mysqli_query($conn, "SELECT * FROM tb_post WHERE postid = '$likedpost'");
      $prow = mysqli_fetch_assoc($pres);
      $likedalready = mysqli_query($conn, "SELECT * FROM tb_like WHERE likedpost = '$likedpost' AND likedby = '$curruser'");
      // checking if its the same user liking
      
      if($prow["userpostid"] != $curruser){
        // prevents the user from liking their own posts
        // it's pretty easy to allow them to do that but my social media
        // clone is quirky this way
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
    <link rel="stylesheet" href="styles/edit.css">
    <link rel="stylesheet" href="styles/delete_modal.css">
    <link rel="stylesheet" href="styles/sidebar_switch.css">
    <link rel="stylesheet" href="styles/user_post_panel.css">
    <link rel="shortcut icon" href="images/icon.png" type="image/x-icon">
    <script src="scripts/json/anime_json.js" type="text/javascript" defer></script>
    <script src="scripts/anime_library.js" type="text/javascript" defer></script>
    <script src="scripts/userpost_enter.js" type="text/javascript" defer></script>
    <script src="scripts/searchbar.js" type="text/javascript" defer></script>
    <script src="scripts/like.js" type="text/javascript" defer></script>
    <script src="scripts/filters.js" type="text/javascript" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="scripts/nav.js" type="text/javascript" defer></script>
    <script src="scripts/delete.js" type="text/javascript" defer></script>
  </head>
  <body>
    <div class="sidebarSwitch"><div class="siswicon"></div></div>
    <div class="mainWrapper">
      <div class="leftContent">
        <div class="userContent">
          <div class="userContentDetails">
            <div class="userIcon detailIcon" <?php echo $profilepic; ?>></div>
            <h1 class="detailHeader"><?php echo $row["username"]; ?></h1>
          </div>
          <div>
            <h3><span><?php echo $postCount; ?></span> Posts</h3>
            <h3><span><?php echo $commentscount ?></span> Comments</h3>
            <h3><span><strong><?php echo $likescount ?></span> Likes</strong></h3>
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
            <?php
              $reviewCount = 0;
              $artCount = 0;
              $criticismCount = 0;
              $rantCount = 0;
              $humorCount = 0;
              // initializing variables for tag counts
              
              for($o = 0; $o < $likescount; $o++){
                $tagquery = mysqli_query($conn, "SELECT * FROM tb_post WHERE postid = '$listoflikes[$o]'");
                $tagret = mysqli_fetch_assoc($tagquery);
                if($listoflikes[$o] == $tagret["postid"]){
                  $posttag[] = $tagret["posttag"];
                }
              }
              // the for loop above will collect the post tags of the likes
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
            ?>
            <div class="tagCount">
              <h2 class="reviewPostTag"><?php echo $reviewCount ?></h2>
              <h2 class="artPostTag"><?php echo $artCount ?></h2>
              <h2 class="criticismPostTag"><?php echo $criticismCount ?></h2>
              <h2 class="rantPostTag"><?php echo $rantCount ?></h2>
              <h2 class="humorPostTag"><?php echo $humorCount ?></h2>
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
        <div class="postsBanner">
          <p>Your Likes</p>
        </div>
        <div class="postFeed feedCollection">
          <?php
            function likesCount($postid, $conn){
              $likesresult = mysqli_query($conn, "SELECT * FROM tb_like");
              while($likesrow = mysqli_fetch_assoc($likesresult)){
                if($postid == $likesrow["likedpost"]){
                  $likedpost[] = $likesrow["likedpost"];
                }
              }
              return empty($likedpost) ? 0 : count($likedpost);
            }
            function commentsCount($postid, $conn){
              $commentsresult = mysqli_query($conn, "SELECT * FROM tb_comment");
              while($commentsrow = mysqli_fetch_assoc($commentsresult)){
                if($postid == $commentsrow["postid"]){
                  $commentpost[] = $commentsrow["postid"];
                }
              }
              return empty($commentpost) ? 0 : count($commentpost);
            }
            // the functions above will count likes and comments
            // each like will be pushed into an array and the array's
            // size will be returned
            // the functions will return 0 if the array is empty
            
            // checking for current user
            function iscurr($userpostid, $curruser){
              return $userpostid == $curruser ? ' currUser' : null;
            }
            // checks if user liked it or not
            function likedba($curruser, $postid, $conn){
              $likedba = mysqli_query($conn, "SELECT * FROM tb_like WHERE likedpost = '$postid' AND likedby = '$curruser'");
              return mysqli_num_rows($likedba) > 0 ? ' isLiked' : ' notLiked';
            }
            
            function postdetails($conn, $listoflikes, $i, $detail){
              $postquery = mysqli_query($conn, "SELECT * FROM tb_post WHERE postid = '$listoflikes[$i]'");
              $postret = mysqli_fetch_assoc($postquery);
              $searchid = $postret["userpostid"];
              $userquery = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = '$searchid'");
              $userret = mysqli_fetch_assoc($userquery);
              if($detail == 'userpostid'){ return $searchid; }
              if($detail == 'postid'){ return $postret["postid"]; }
              if($detail == 'animetag'){ return $postret["animetag"]; }
              if($detail == 'posttag'){ return $postret["posttag"]; }
              if($detail == 'posttext'){ return $postret["posttext"]; }
              if($detail == 'dateposted'){ return $postret["dateposted"]; }
              if($detail == 'datedetails'){ return $postret["datedetails"]; }
              if($detail == 'image'){ return $postret["has_image"] == 0 ? null : "uploads/post_images/" . $postret["image"]; }
              if($detail == 'userpfp'){ return $userret["profilepic"]; }
              if($detail == 'username'){ return $userret["username"]; }
            }
            // the function above will return the detail requested by the caller
            
            for($i = 0; $i < $likescount; $i++){
              echo '<div data-likecount="' . likesCount(postdetails($conn, $listoflikes, $i, $detail = "postid"), $conn) . '" data-commcount="' . commentsCount(postdetails($conn, $listoflikes, $i, $detail = "postid"), $conn) . '" class="userPost enterThisPost" id="' . $listoflikes[$i] . '"style="background-image:url(\'images/' . postdetails($conn, $listoflikes, $i, $detail = "animetag") . '.jpg\');">';
              echo   '<div class="userDetails">';
              echo     '<div class="userDetailsGroup">';
              echo       '<div class="userIcon" style="background-image:url(\'uploads/profile_pictures/' . postdetails($conn, $listoflikes, $i, $detail = "userpfp") . '\')"></div>';
              echo       '<h1 class="userUsername">' . postdetails($conn, $listoflikes, $i, $detail = "username") . '</h1>';
              echo     '</div>';
              echo     '<div class="postTagGenre ' . postdetails($conn, $listoflikes, $i, $detail = "posttag") . 'PostTag"><label>' . postdetails($conn, $listoflikes, $i, $detail = "posttag") . '</label></div>';
              echo   '</div>';
              echo   '<div class="userPostDetails">';
              echo     '<pre class="postText">' . postdetails($conn, $listoflikes, $i, $detail = "posttext") . '</pre>';
              echo     '<img class="postImage" loading="lazy" src="' . postdetails($conn, $listoflikes, $i, $detail = "image") . '">';
              echo   '</div>';
              echo   '<div class="postOptions">';
              echo     '<div class="btnWrapper likeTrigger leftBtn' . iscurr(postdetails($conn, $listoflikes, $i, $detail = "userpostid"), $curruser) . '" id="' . postdetails($conn, $listoflikes, $i, $detail = "postid") . '"><div class="postIcon likeBtn' . likedba($curruser, postdetails($conn, $listoflikes, $i, $detail = "postid"), $conn) . '"></div><span class="contentCount">' . likesCount(postdetails($conn, $listoflikes, $i, $detail = "postid"), $conn) . '</span></div>';
              echo     '<div class="btnWrapper commentTrigger leftBtn"><div class="postIcon commentBtn"></div><span class="contentCount">' . commentsCount(postdetails($conn, $listoflikes, $i, $detail = "postid"), $conn) . '</span></div>';
              echo     '<div class="rightBtn dateWrapper"><h3 class="datePosted"><span class="dateDets">' . postdetails($conn, $listoflikes, $i, $detail = "datedetails") . ' at&nbsp;</span>' . postdetails($conn, $listoflikes, $i, $detail = "dateposted") . '</h3></div>';
              echo   '</div>';
              echo '</div>';
            }
            // the for loop above will echo the list of posts liked by the user
          ?>
        </div>
      </div>
      <div class="postPanel">
        <span class="pagePostBtn"></span>
      </div>
      <div class="rightContent">
        <div class="animeContent">
          <input id="animeSearch" type="text" placeholder="Search Anime to talk about">
          <content class="animeLibrary">
          </content>
        </div>
      </div>
      <div class="deleteModal">
        <div class="mainModal">
          <h1>What action do you want for your post?</h1>
          <div class="modalButtons">
            <div class="deleteButtonIto"><button type="button" id="deleteYes">Delete</button></div>
            <div class="editButtonIto"><button type="button" id="edit">Edit</button></div>
            <div class="cancelButtonIto"><button type="button" id="cancel">Cancel</button></div>
          </div>
        </div>
      </div>
      <div class="editModal">
        <div class="mainModal">
          <h1>Edit content</h1>
          <div class="modalButtons">
            <div class="cancelButtonIto"><button type="button" id="cancel">Cancel</button></div>
            <div class="editButtonIto"><button name="submit" type="submit" form="editform" id="confirm">Confirm</button></div>
          </div>
          <form id="editform" method="post" action="<?php echo htmlspecialchars('edit_complete.php?content=post'); ?>">
            <textarea id="idofcontent" name="idofcontent" autocomplete="off" required></textarea>
            <textarea id="edittext" name="edit" autocomplete="off" value=""></textarea>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>