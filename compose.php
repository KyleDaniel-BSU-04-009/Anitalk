<?php
  // if the id is empty, the session will immediately end
  require 'config.php';
  if(!empty($_SESSION["id"])){
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM  tb_user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    $_SESSION["animetag"] = $_GET["anime"];
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
    <link rel="shortcut icon" href="images/icon.png" type="image/x-icon">
    <script src="scripts/json/anime_json.js" type="text/javascript" defer></script>
    <script src="scripts/compose.js" type="text/javascript" defer></script>
    <script src="scripts/nav.js" type="text/javascript" defer></script>
    <script src="scripts/image_input.js" type="text/javascript" defer></script>
    <script src="scripts/address_check.js" type="text/javascript" defer></script>
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
        <div class="postsBanner">
          <p>Say Something!</p>
        </div>
        <div class="postForm">
          <div class="formWrapper">
            <form class="composeForm" action="<?php echo htmlspecialchars('compose_complete.php'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
              <label for="posttag">Tag ✚
                <div class="tagDiv">
                  <label class="tagOption" for="review">Review</label>
                  <input type="radio" name="posttag" value="review" id="review">
                  <span class="radioButton"></span>
                </div>
                <div class="tagDiv">
                  <label class="tagOption" for="art">Art</label>
                  <input type="radio" name="posttag" value="art" id="art">
                  <span class="radioButton"></span>
                </div>
                <div class="tagDiv">
                  <label class="tagOption" for="criticism">Criticism</label>
                  <input type="radio" name="posttag" value="criticism" id="criticism">
                  <span class="radioButton"></span>
                </div>
                <div class="tagDiv">
                  <label class="tagOption" for="rant">Rant</label>
                  <input type="radio" name="posttag" value="rant" id="rant">
                  <span class="radioButton"></span>
                </div>
                <div class="tagDiv">
                  <label class="tagOption" for="humor">Humor</label>
                  <input type="radio" name="posttag" value="humor" id="humor">
                  <span class="radioButton"></span>
                </div>
              </label>
              <button id="rmvBtn" onclick="removeImage();" type="button">Remove image</button>
              <label id="inpLabel" for="image">image ✚</label>
              <input type="file" name="image" id="image" accept="image/png, image/jpg, image/jpeg, image/gif, image/webp, image/jfif">
              <textarea type="text" name="post" id="post" autocomplete="off" required value=""></textarea>
              <button type="submit" name="submit">post!</button>
            </form>
            <img id="preview" src="">
          </div>
        </div>
      </div>
      <div class="rightContent">
      </div>
    </div>
  </body>
</html>