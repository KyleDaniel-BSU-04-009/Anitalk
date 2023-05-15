<?php
  // if the id isn't empty, it will redirect the user to their profile page
  require 'config.php';
  if(!empty($_SESSION["id"])){
    header("Location: index.php");
  }
  if(isset($_POST["submit"])){
    function secure($conn, $var){
      $var = stripcslashes($var);
      $var = mysqli_real_escape_string($conn, $var);
      return $var;
    }
    // the function above is used to secure input and prevent sql injection
    $usernameemail = secure($conn, $_POST["usernameemail"]);
    $password = secure($conn, $_POST["password"]);
    // the input is passed to a function as an argument and clean it before using it in mqsli functions
    $result = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$usernameemail' OR email = '$usernameemail'");
    $row = mysqli_fetch_assoc($result);
    if(mysqli_num_rows($result) > 0){
      // if the email or username entered doesn't exist in the database
      // the user will receive an error message
      if(password_verify($password, $row["password"])){
        // password verification through comparing the input and the hashed value stored in the database
        // if it doesn't match, the user will receive an error message
        $_SESSION["login"] = true;
        $_SESSION["id"] = $row["id"];
        header("Location: index.php");
        // if all credentials are valid, the user will be
        // redirected to their profile page
      }
      else
      { echo "<script>alert('Wrong password');</script>"; }
    }
    else
    { echo "<script>alert('User not registered');</script>"; }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Anitalk</title>
    <link rel="stylesheet" href="styles/base.css">
    <link rel="stylesheet" href="styles/reglog.css">
    <link rel="stylesheet" href="styles/background.css">
    <link rel="shortcut icon" href="images/icon.png" type="image/x-icon">
  </head>
  <body>
    <div class="formWrapper">
      <img class="logo" draggable="false" src="images/logo.png">
      <form class="" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
        <div class="regSep">
          <div>
            <label for="usernameemail">Username / Email</label><br>
            <input type="text" name="usernameemail" placeholder="Enter your Username or Email" id="usernameemail" required value=""><br>
          </div>
          <div>
            <label for="password">Password</label><br>
            <input type="password" name="password" placeholder="Enter your Password" id="password" required value=""><br>
          </div>
        </div>
        <button type="submit" name="submit">Log in</button>
        <p class="prompter">Don't have an account yet? <a href="registration.php">Sign up</a>.</p>
      </form>
      <p class="miscText">By Kyle Daniel | #415220<br>2023 All Rights Reserved<br>Images in this project are used for academic purposes<br>NOTHING is monetized.</p>
    </div>
    <div class="backgroundWrapper">
      <div class="background">
        <div class="masonryColumn">
          <div class="masonryTile" style="background-image:url('images/d60fb546-f51c-4efa-8fdd-0ecfc34ac634.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/0ad3d4f4-ebc4-4c60-b9bd-a4da0145b56f.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/0001e56a-7acf-41b4-a075-34930012fb5a.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/1bee9708-46a2-4289-a37a-0a68dfa2957e.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/d60fb546-f51c-4efa-8fdd-0ecfc34ac634.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/0ad3d4f4-ebc4-4c60-b9bd-a4da0145b56f.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/0001e56a-7acf-41b4-a075-34930012fb5a.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/1bee9708-46a2-4289-a37a-0a68dfa2957e.jpg');"></div>
        </div>
        <div class="masonryColumn">
          <div class="masonryTile" style="background-image:url('images/6abd77dc-010c-47ed-b0d9-356c72892861.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/7db97e94-d6b9-4fa9-bd2e-0355324a57cf.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/19ac57d4-dfb9-4f4e-90b3-4e9b82f9a3ac.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/54d2cbf0-9e43-424a-8057-2207f58cf629.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/6abd77dc-010c-47ed-b0d9-356c72892861.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/7db97e94-d6b9-4fa9-bd2e-0355324a57cf.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/19ac57d4-dfb9-4f4e-90b3-4e9b82f9a3ac.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/54d2cbf0-9e43-424a-8057-2207f58cf629.jpg');"></div>
        </div>
        <div class="masonryColumn">
          <div class="masonryTile" style="background-image:url('images/84a45076-3faa-4ef0-bdb7-1a41d8df79e6.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/178b291d-831e-438d-be79-a74e9b8b19dd.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/2387f918-6968-4d9c-9b22-1274a5af4933.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/82348c77-0ebb-4cb3-865e-d3adb02717d0.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/84a45076-3faa-4ef0-bdb7-1a41d8df79e6.jpg');"></div>
          <div class="masonryTile" style="background-image:url('images/178b291d-831e-438d-be79-a74e9b8b19dd.jpg');"></div>
        </div>
      </div>
    </div>
  </body>
</html>