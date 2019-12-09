<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<header class="blog-header py-3">
  <div class="row flex-nowrap justify-content-between align-items-center">
    <div class="col-4 pt-1">
      <a class="text-muted" href="#">Subscribe</a>
    </div>
    <div class="col-4 text-center">
      <a class="blog-header-logo text-dark" href=".">Gakkari Posts</a>
    </div>
    <div class="col-4 d-flex justify-content-end align-items-center">
      <a class="text-muted" href="#" aria-label="Search">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24" focusable="false"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"></circle><path d="M21 21l-5.2-5.2"></path></svg>
      </a>
      <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
          echo '<div class="dropdown">
                  <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="assets/images/avatar.svg" width="32" height="32" />
                  </a>
                
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="new_post.php">New post</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item disabled" href="#">Settings</a>
                    <a class="dropdown-item" href="logout.php">Sign out</a>
                  </div>
                </div>';
        } else {
          echo '<a class="btn btn-sm btn-outline-primary mr-1" href="login.php">Sign in</a>
                <a class="btn btn-sm btn-outline-secondary" href="register.php">Sign up</a>';
        }
      ?>
    </div>
  </div>
</header>
<?php include "_navbar.php" ?>