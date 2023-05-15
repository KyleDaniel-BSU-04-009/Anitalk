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
    
    if(isset($_POST["usernameChange"])){
      function secure($conn, $var){
        $var = stripcslashes($var);
        $var = mysqli_real_escape_string($conn, $var);
        return $var;
      }
      // the function above is used to secure input and prevent sql injection
      $change = secure($conn, $_POST["newUsername"]);
      // the input is passed to a function as an argument and clean it before using it in mqsli functions
      mysqli_query($conn, "UPDATE tb_user SET username = '$change' WHERE id = '$curruser'");
      header("location: settings.php");
      // when all the conditions are met, the username will be updated
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
    <link rel="stylesheet" href="styles/settings.css">
    <link rel="shortcut icon" href="images/icon.png" type="image/x-icon">
    <script src="scripts/settings.js" type="text/javascript" defer></script>
    <script src="scripts/nav.js" type="text/javascript" defer></script>
    <?php
      if(isset($_POST["passwordChange"])){
        if(!empty($_POST["oldPassword"]) || !empty($_POST["newPassword"])){
          // detects if the old passsword and new password are somehow empty
          function secure($conn, $var){
            $var = stripcslashes($var);
            $var = mysqli_real_escape_string($conn, $var);
            return $var;
          }
          // the function above is used to secure input and prevent sql injection
          $change = secure($conn, $_POST["newPassword"]);
          $old = secure($conn, $_POST["oldPassword"]);
          // the input is passed to a function as an argument and clean it before using it in mqsli functions
          if(password_verify($old, $row["password"])){
            // password verification through comparing the input and the hashed value stored in the database
            // if it doesn't match it'll return to the settings
            $change = password_hash($change, PASSWORD_DEFAULT);
            // input will be hashed in order to secure it inside the database as well
            mysqli_query($conn, "UPDATE tb_user SET password = '$change' WHERE id = '$curruser'");
            header("location: settings.php");
            // when all the conditions are met, the password will be updated
          }
          else{
            echo "<script>alert('Old password is wrong!');</script>";
          }
        }
        else{
          echo "<script>alert('Necessary fields must not be empty!');</script>";
        }
      }
      if(isset($_POST["pfpChange"])){
        $uploaddir = __DIR__ . "/uploads/profile_pictures/";
        if(!empty($_FILES["newpfp"]["name"])){
          // finding and replacing the old profile picture
          $oldpfp = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = '$curruser'");
          $opres = mysqli_fetch_assoc($oldpfp);
          if($opres["profilepic"] != 'default.png'){
            // if it's not the default profile picture, the image will be deleted from the database and uploads
            $imgdel = __DIR__ . "/uploads/profile_pictures/" . $opres["profilepic"];
            unlink($imgdel);
          }
          $filename = basename($_FILES["newpfp"]["name"]);
          $filerand = random_int(1000000, 9999999) . '-' . $filename;
          // generates a random id before the file name to prevent duplicate files
          $filepath = $uploaddir . $filerand ;
          $filetype = pathinfo($filepath, PATHINFO_EXTENSION);
          $allowed = array('png', 'jpg', 'jpeg', 'gif', 'webp', 'jfif');
          // this is an array of allowed file extensions
          if(in_array($filetype, $allowed)){
            // the if-statement above checks if the uploaded file has one of the allowed extensions
            move_uploaded_file($_FILES["newpfp"]["tmp_name"], $filepath);
            mysqli_query($conn, "UPDATE tb_user SET profilepic = '$filerand' WHERE id = '$curruser'");
            header("location: settings.php");
            // when all conditions are met, the profile picture will be updated
          }
        }
      }
    ?>
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
            <a href="user.php">Return</a>
          </div>
        </div>
      </div>
    </div>
    <div class="settingsWrapper">
      <div class="mainSettings">
        <div class="accountSettings">
          <h1>Change Username</h1>
          <form method="post" id="usernameForm" action="<?php echo htmlspecialchars('settings.php'); ?>">
            <input required maxlength="14" type="text" name="newUsername" autocomplete="off" placeholder="Enter new username" value="">
            <button type="submit" name="usernameChange" form="usernameForm">Confirm Change</button>
          </form>
        </div>
        <div class="pfpSettings">
          <h1>Change Profile Picture</h1>
          <form method="post" id="pfpForm" action="<?php echo htmlspecialchars('settings.php'); ?>" enctype="multipart/form-data">
            <label id="inpLabel" for="newpfp">Add an Image ✚</label>
            <input required id="newpfp" type="file" name="newpfp" value="" accept="image/png, image/jpg, image/jpeg, image/gif, image/webp, image/jfif">
            <button type="submit" name="pfpChange" form="pfpForm">Confirm Change</button>
          </form>
        </div>
        <div class="passwordSettings">
          <h1>Change Password</h1>
          <button id="passwordReset" type="button">Change Password</button>
          <form method="post" id="passwordForm" action="<?php echo htmlspecialchars('settings.php'); ?>">
            <div class="passwordWrapper">
              <input type="password" name="oldPassword" required autocomplete="off" placeholder="Enter old password" value="">
              <input type="password" name="newPassword" required autocomplete="off" placeholder="Enter new password" value="">
            </div>
            <button type="submit" name="passwordChange" form="passwordForm">Confirm Change</button>
          </form>
        </div>
        <div class="deleteSettings">
          <h1>Delete Account</h1>
          <button id="accDel" type="button">Delete Account</button>
          <form method="post" id="deleteForm" action="<?php echo htmlspecialchars('account_delete.php'); ?>">
            <input type="password" name="confirmDelete" autocomplete="off" placeholder="Enter Password" value="" required>
            <button type="submit" name="accountDelete" form="deleteForm">Confirm Delete</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>