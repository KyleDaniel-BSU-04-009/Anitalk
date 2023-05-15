<?php
  // if the id is empty, the session will immediately end
  require 'config.php';
  if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    // these set of variables hold essential data for the session
    // the if-statement is used to allow access if the the user has logged in
    
    $postresult = mysqli_query($conn, "SELECT * FROM tb_post");
    $postCount = 0;
    $profilepic = $row["profilepic"] == NULL ? '' : 'style="background-image:url(\'uploads/profile_pictures/' . $row["profilepic"] . '\');"';
    // this variable will check for the user's profile picture
    // and if it does exist, it will add the detail

    $comcount = 0;
    $comres = mysqli_query($conn, "SELECT * FROM tb_comment");
    while($comrow = mysqli_fetch_assoc($comres)){
      if($comrow["usercommenterid"] == $row["id"]){
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
    // the comments added to the arrays are based on the id of the user
    while($postrow = mysqli_fetch_assoc($postresult)){
      if($postrow["userpostid"] == $row["id"]){
        $postCount++;
      }
    }
    $likesres = mysqli_query($conn, "SELECT * from tb_like");
    // the while loop above count the number of posts made by the user
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
    <link rel="stylesheet" href="styles/comment_section.css">
    <link rel="stylesheet" href="styles/delete_modal.css">
    <link rel="stylesheet" href="styles/user_post_panel.css">
    <link rel="stylesheet" href="styles/comment_right.css">
    <link rel="shortcut icon" href="images/icon.png" type="image/x-icon">
    <script src="scripts/json/anime_json.js" type="text/javascript" defer></script>
    <script src="scripts/anime_library.js" type="text/javascript" defer></script>
    <script src="scripts/userpost_enter.js" type="text/javascript" defer></script>
    <script src="scripts/searchbar.js" type="text/javascript" defer></script>
    <script src="scripts/nav.js" type="text/javascript" defer></script>
    <script src="scripts/delete.js" type="text/javascript" defer></script>
  </head>
  <body>
    <div class="mainWrapper">
      <div class="leftContent">
        <div class="userContent">
          <div class="userContentDetails">
            <div class="userIcon detailIcon" <?php echo $profilepic; ?>></div>
            <h1 class="detailHeader"><?php echo $row["username"]; ?></h1>
          </div>
          <div>
            <h3><span><?php echo $postCount; ?></span> Posts</h3>
            <h3><span><strong><?php echo $commentscount ?></span> Comments</strong></h3>
            <h3><span><?php echo $likescount ?></span> Likes</h3>
            <!-- displays the user details -->
          </div>
          <div class="userLinks">
            <a href="logout.php">Logout</a>
            <a href="settings.php">Settings</a>
          </div>
        </div>
      </div>
      <div class="centerContent">
        <div class="postsBanner">
          <p>Your Comments</p>
        </div>
        <div class="commentFeed feedCollection">
          <?php
            function retuserinfo($comusercommenterid, $f, $conn, $info){
              $ret = mysqli_query($conn, "SELECT * FROM tb_user WHERE id='$comusercommenterid[$f]'");
              $retrow = mysqli_fetch_assoc($ret);
              if($info == "pfp"){ return "uploads/profile_pictures/" . $retrow["profilepic"]; }
              if($info == "usn"){ return $retrow["username"]; }
            }
            // the funtion above will return the information based
            // on the argument passed from the for loop below
            for($f = 0; $f < $comcount; $f++){
              echo  '<div class="userComment">';
              echo    '<div class="enterComment enterThisPost" id="' . $compostid[$f] . '">➤➤➤</div>';
              echo    '<div class="commentUserDetails">';
              echo      '<div class="detailGroup">';
              echo        '<div class="userIcon" style="background-image:url(\'' . retuserinfo($comusercommenterid, $f, $conn, $info = "pfp") . '\')"></div>';
              echo        '<p>' . retuserinfo($comusercommenterid, $f, $conn, $info = "usn") . '</p>';
              echo        '<div class="rmv" id="' . $comcommentid[$f] . '"><div class="btnWrapper rightBtn"><div class="postIcon deleteBtn"></div></div></div>';
              echo      '</div>';
              echo      '<h3 class="datePosted"><span class="dateDets">' . $comdatedetails[$f] . ' at&nbsp;</span>' . $comdateposted[$f] . '</h3>';
              echo    '</div>';
              echo    '<p class="commentText">' . $comcommenttext[$f] . '</p>';
              echo  '</div>';
            }
            // the for loop above will loop through the total comment count
            // made by the first while loop
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
          <form id="editform" method="post" action="<?php echo htmlspecialchars('edit_complete.php?content=comment'); ?>">
            <textarea id="idofcontent" name="idofcontent" autocomplete="off" required></textarea>
            <textarea id="edittext" name="edit" autocomplete="off" value=""></textarea>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>