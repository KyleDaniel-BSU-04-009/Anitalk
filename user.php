<?php
  // if the id is empty, the session will immediately end
  require 'config.php';
  if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    // these set of variables hold essential data for the session
    // the if-statement is used to allow access if the the user has logged in
    
    $profilepic = $row["profilepic"] == NULL ? '' : 'style="background-image:url(\'uploads/profile_pictures/' . $row["profilepic"] . '\');"';
    // this variable will check for the user's profile picture
    // and if it does exist, it will add the detail
    
    $postresult = mysqli_query($conn, "SELECT * FROM tb_post");
    $postCount = 0;
    while($postrow = mysqli_fetch_assoc($postresult)){
      if($postrow["userpostid"] == $row["id"]){
        $postCount++;
        $postid[] = $postrow["postid"];
        $animetag[] = $postrow["animetag"];
        $posttag[] = $postrow["posttag"];
        $has_image[] = $postrow["has_image"];
        $image[] = $postrow["image"];
        $posttext[] = $postrow["posttext"];
        $dateposted[] = $postrow["dateposted"];
        $datedetails[] = $postrow["datedetails"];
      }
    }
    // the while loop above count the number of posts made by the user
    // the while loop above will collect all the posts from the current user
    
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
    // all the tags of the posts made by the user
    // checking if the array of post tags isn't empty will prevent errors
    
    $likesres = mysqli_query($conn, "SELECT * from tb_like");
    while($likesrow = mysqli_fetch_assoc($likesres)){
      if($likesrow["likedby"] == $row["id"]){
        $likescount[] = $likesrow["likedby"];
      }
    }
    $likescount = empty($likescount) ? 0 : count($likescount);
    // the while loop above count the number of likes made by the user
    $commentsres = mysqli_query($conn, "SELECT * from tb_comment");
    while($commentsrow = mysqli_fetch_assoc($commentsres)){
      if($commentsrow["usercommenterid"] == $row["id"]){
        $commentscount[] = $commentsrow["usercommenterid"];
      }
    }
    $commentscount = empty($commentscount) ? 0 : count($commentscount);
    // the while loop above count the number of comments made by the user
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
    <script src="scripts/nav.js" type="text/javascript" defer></script>
    <script src="scripts/delete.js" type="text/javascript" defer></script>
    <script src="scripts/filters.js" type="text/javascript" defer></script>
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
            <h3><span><strong><?php echo $postCount; ?></span> Posts</strong></h3>
            <h3><span><?php echo $commentscount ?></span> Comments</h3>
            <h3><span><?php echo $likescount ?></span> Likes</h3>
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
        <div class="postsBanner">
          <p>Your Posts</p>
        </div>
        <div class="postFeed feedCollection">
          <?php
            //likes and comments counter
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
            
            function image($has_image, $image, $i){
              return $has_image[$i] == 0 ? null : "uploads/post_images/" . $image[$i];
            }
            // the function above is used to add an image directory if the post has an image
            
            for($i = 0; $i < $postCount; $i++){
              echo '<div data-likecount="' . likesCount($postid, $i, $conn) . '" data-commcount="' . commentsCount($postid, $i, $conn) . '" class="userPost enterThisPost" id="' . $postid[$i] . '" style="background-image:url(\'images/' . $animetag[$i] . '.jpg\');">';
              echo   '<div class="userDetails">';
              echo     '<div class="userDetailsGroup">';
              echo       '<div class="userIcon" style="background-image:url(\'uploads/profile_pictures/' . $row["profilepic"] . '\')"></div>';
              echo       '<h1 class="userUsername">' . $row["username"] . '</h1>';
              echo     '</div>';
              echo     '<div class="postTagGenre ' . $posttag[$i] . 'PostTag"><label>' . $posttag[$i] . '</label></div>';
              echo   '</div>';
              echo   '<div class="userPostDetails">';
              echo     '<pre class="postText">' . $posttext[$i] . '</pre>';
              echo     '<img class="postImage" loading="lazy" src="' . image($has_image, $image, $i) . '">';
              echo   '</div>';
              echo   '<div class="postOptions">';
              echo     '<div class="btnWrapper leftBtn"><div class="postIcon likeBtn notLiked"></div><span class="contentCount">' . likesCount($postid, $i, $conn) . '</span></div>';
              echo     '<div class="btnWrapper leftBtn"><div class="postIcon commentBtn"></div><span class="contentCount">' . commentsCount($postid, $i, $conn) . '</span></div>';
              echo     '<div class="rmv" id="' . $postid[$i] . '"><div class="btnWrapper rightBtn"><div class="postIcon deleteBtn"></div></div></div>';
              echo     '<div class="rightBtn dateWrapper"><h3 class="datePosted"><span class="dateDets">' . $datedetails[$i] . ' at&nbsp;</span>' . $dateposted[$i] . '</h3></div>';
              echo   '</div>';
              echo '</div>';
            }
            // the for loop above will echo the list of posts made by the user
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