<?php
  // Include config file
  require_once 'link.php';
  
  // Define variables and initialize with empty values
  $fullname = $username = $password = $confirm_password = '';
  $err = array();
  
  // Processing form data when form is submitted
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Validate full name
    if (empty(trim($_POST['fullname']))) {
      $err['fullname'] = 'Please enter your full name.';
    } else {
      $fullname = trim($_POST['fullname']);
    }
  
    // Validate username
    if (empty(trim($_POST['username']))) {
      $err['username'] = 'Please enter a username.';
    } else {
      $username = trim($_POST['username']);

      // Prepare a select statement
      $query = 'SELECT id FROM users WHERE username = ?';
      
      if ($stmt = mysqli_prepare($link, $query)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, 's', $param_username);
        
        // Set parameters
        $param_username = trim($_POST['username']);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
          /* store result */
          mysqli_stmt_store_result($stmt);
          
          if (mysqli_stmt_num_rows($stmt) == 1) {
              $err['username'] = 'This username is already taken.';
          } else {
              $username = trim($_POST['username']);
          }
        } else {
          echo 'Oops! Something went wrong. Please try again later.';
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
      }
    }
    
    // Validate password
    if (empty(trim($_POST['password']))) {
      $err['password'] = 'Please enter a password.';     
    } elseif (strlen(trim($_POST['password'])) < 8) {
      $err['password'] = 'Password must have at least 8 characters.';
    } else {
      $password = trim($_POST['password']);
    }
    
    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
      $err['confirm_password'] = 'Please confirm password.';     
    } else {
      $confirm_password = trim($_POST['confirm_password']);
      
      if(empty($err['password']) && ($password != $confirm_password)){
          $err['confirm_password'] = 'Password did not match.';
      }
    }
    
    // Check input errors before inserting in database
    if (count($err) == 0) {
      // Prepare an insert statement
      $query = 'INSERT INTO authors (name, username, password) VALUES (?, ?, ?)';
      
      if ($stmt = mysqli_prepare($link, $query)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, 'sss', $param_fullname, $param_username, $param_password);
        
        // Set parameters
        $param_fullname = $fullname;
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
          // Redirect to login page
          header('location: index.php');
        } else{
          echo 'Something went wrong. Please try again later.';
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
    <?php include 'head.php' ?>
    <title>Register - Gakkari Posts</title>
  </head>
  <body>
    <div class="container">
      <?php include 'header.php' ?>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="fullname"
                 class="form-control <?php echo (!empty($err['fullname'])) ? 'is-invalid' : ''; ?>"
                 value="<?php echo $fullname; ?>">
          <div class="invalid-feedback"><?php echo $err['fullname']; ?></div>
        </div>
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username"
                 class="form-control <?php echo (!empty($err['username'])) ? 'is-invalid' : ''; ?>"
                 value="<?php echo $username; ?>">
          <div class="invalid-feedback"><?php echo $err['username']; ?></div>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password"
                 class="form-control <?php echo (!empty($err['password'])) ? 'is-invalid' : ''; ?>"
                 value="<?php echo $password; ?>">
          <div class="invalid-feedback"><?php echo $err['password']; ?></div>
        </div>
        <div class="form-group">
          <label>Confirm Password</label>
          <input type="password" name="confirm_password"
                 class="form-control <?php echo (!empty($err['confirm_password'])) ? 'is-invalid' : ''; ?>"
                 value="<?php echo $confirm_password; ?>">
          <div class="invalid-feedback"><?php echo $err['confirm_password']; ?></div>
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Submit">
          <input type="reset" class="btn btn-default" value="Reset">
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
      </form>
      <?php include 'footer.php' ?>
    </div>
  </body>
</html>