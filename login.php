<?php
  session_start();

  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: index.php");
    exit;
  }

  require_once 'include/link.php';
  
  // Define variables and initialize with empty values
  $username = $password = '';
  $err = array();
  
  // Processing form data when form is submitted
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    // Check if username is empty
    if (empty(trim($_POST['username']))) {
      $err['username'] = 'Please enter your username.';
    } else {
      $username = trim($_POST['username']);
    }
    
    // Check if password is empty
    if(empty(trim($_POST['password']))){
      $err['password'] = 'Please enter your password.';
    } else{
      $password = trim($_POST['password']);
    }
    
    // Validate credentials
    if (count($err) == 0) {
      // Prepare a select statement
      $query = 'SELECT id, username, password FROM authors WHERE username = ?';
      
      if ($stmt = mysqli_prepare($link, $query)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, 's', $param_username);
        
        // Set parameters
        $param_username = $username;
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
          // Store result
          mysqli_stmt_store_result($stmt);
          
          // Check if username exists, if yes then verify password
          if (mysqli_stmt_num_rows($stmt) == 1) {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

            if (mysqli_stmt_fetch($stmt)) {
              if (password_verify($password, $hashed_password)) {
                // Password is correct, so start a new session
                session_start();
                
                // Store data in session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;              
                
                // Redirect user to welcome page
                header('location: index.php');
              } else {
                // Display an error message if password is not valid
                $err['password'] = 'The password you entered was not valid.';
              }
            }
          } else {
            // Display an error message if username doesn't exist
            $err['username'] = 'No account found with that username.';
          }
        } else {
          echo 'Oops! Something went wrong. Please try again later.';
        }

        // Close statement
        mysqli_stmt_close($stmt);
      }
    }
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <?php include 'include/head.php' ?>
    <title>Sign In - Gakkari Posts</title>
  </head>
  <body>
    <div class="container">
      <?php include '_header.php' ?>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" novalidate>
        <h2>Sign In</h2>
        <p>Please fill in your credentials to login.</p>
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username"
                 class="form-control <?php echo (!empty($err['username'])) ? 'is-invalid' : '' ?>"
                 value="<?php echo $username ?>">
          <div class="invalid-feedback"><?php echo $err['username'] ?></div>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password"
                 class="form-control <?php echo (!empty($err['password'])) ? 'is-invalid' : '' ?>"
                 value="<?php echo $password ?>">
          <div class="invalid-feedback"><?php echo $err['password'] ?></div>
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Sign in">
        </div>
        <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
      </form>
      <?php include '_footer.php' ?>
    </div>
  </body>
</html>