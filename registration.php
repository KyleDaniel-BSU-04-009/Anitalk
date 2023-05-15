<?php
  // if the id isn't empty, it will redirect the user to their profile page
  require 'config.php';
  if(!empty($_SESSION["id"])){
    header("Location: index.php");
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
    <script src="scripts/image_input.js" type="text/javascript" defer></script>
    <?php
      if(isset($_POST["submit"])){
        function secure($conn, $var){
          $var = stripcslashes($var);
          $var = mysqli_real_escape_string($conn, $var);
          return $var;
        }
        // the function above is used to secure input and prevent sql injection
        $uploaddir = __DIR__ . "/uploads/profile_pictures/";
        $name = secure($conn, $_POST["name"]);
        // the input is passed to a function as an argument and clean it before using it in mqsli functions
        $username = strlen($_POST["username"]) > 14 ? substr($_POST["username"], 0, 14) : $_POST["username"];
        // if the username is somehow more than 14 characters long
        // it'll cut off all characters after the 14th character
        $email = secure($conn, $_POST["email"]);
        $password = secure($conn, $_POST["password"]);
        $confirmpassword = secure($conn, $_POST["confirmpassword"]);
        $duplicate = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username' OR email = '$email'");
        if(!preg_match("/^[a-zA-Z-' ]*$/", $name) || !preg_match("/^[a-zA-Z-' ]*$/", $username)){
          // if the username has characters other than a-z or whitespaces
          // it will not allow it
          echo "<script>alert('Letters A-Z and white space are allowed');</script>";
        }
        else{
          if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            // this is a basic filter for emails and detects invalid emails
            echo "<script>alert('Invalid Email!');</script>";
          }
          else{
            if(mysqli_num_rows($duplicate) > 0){
              // detects if the username or email entered already exists on the database
              echo "<script>alert('Username or Email is already taken');</script>";
            }
            else{
              if($password == $confirmpassword){
                // detects if the password entered and the confirmation have the same value
                $password = password_hash($password, PASSWORD_DEFAULT);
                // input will be hashed in order to secure it inside the database as well
                if(!empty($_FILES["profilepic"]["name"])){
                  // detects if the user added a file for the profile picture field
                  $filename = basename($_FILES["profilepic"]["name"]);
                  $filerand = random_int(1000000, 9999999) . '-' . $filename;
                  // generates a random id before the file name to prevent duplicate files
                  $filepath = $uploaddir . $filerand ;
                  $filetype = pathinfo($filepath, PATHINFO_EXTENSION);
                  $allowed = array('png', 'jpg', 'jpeg', 'gif', 'webp', 'jfif');
                  // this is an array of allowed file extensions
                  if(in_array($filetype, $allowed)){
                    // the if-statement above checks if the uploaded file has one of the allowed extensions
                    move_uploaded_file($_FILES["profilepic"]["tmp_name"], $filepath);
                    $query = "INSERT INTO tb_user VALUES ('', '$name', '$username', '$email', '$password', '$filerand')";
                  }
                }
                else{
                  // if doesn't have any images, it will simply send data to the
                  // database with the default profile picture
                  $defaultpfp = "default.png";
                  $query = "INSERT INTO tb_user VALUES ('', '$name', '$username', '$email', '$password', '$defaultpfp')";
                }
                mysqli_query($conn, $query);
                header('Location: login.php');
              }
              else
              { echo "<script>alert('Passwords don't match');</script>"; }
            }
          }
        }
      }
    ?>
  </head>
  <body>
    <div class="formWrapper">
      <img class="logo" draggable="false" src="images/logo.png">
      <form class="" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="regSep">
          <div>
            <label for="name">Name</label><br>
            <input type="text" name="name" placeholder="Enter Your Name" id="name" required value=""><br>
          </div>
          <div>
            <label for="username">Username</label><br>
            <input maxlength="14" type="text" name="username" placeholder="Enter a Username (14 chars..)" id="username" required value=""><br>
          </div>
        </div>
        <label for="email">Email</label><br>
        <input type="text" name="email" placeholder="Enter Your Email Address" id="email" required value=""><br>
        <div class="regSep">
          <div>
            <label for="password">Password</label><br>
            <input type="password" name="password" placeholder="Enter Your Password" id="password" required value=""><br>
          </div>
          <div>
            <label for="confirmpassword">Confirm Password</label><br>
            <input type="password" name="confirmpassword" placeholder="Confirm Your Password" id="confirmpassword" required value=""><br>
          </div>
        </div>
        <label>Profile Picture</label><br>
        <div class="pfpInput">
          <button type="button" id="rmvBtn" onclick="removeImage();" type="button">Remove image</button><br>
          <label id="inpLabel" for="image">Add an Image ✚</label><br>
          <input type="file" name="profilepic" id="image" accept="image/png, image/jpg, image/jpeg, image/gif, image/webp, image/jfif"><br>
          <div class="pfpPreview">
            <img id="preview" src="">
          </div>
        </div>
        <button type="submit" name="submit">Register</button>
        <p class="prompter">Already have na account? <a href="login.php">Log in</a>.</p>
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