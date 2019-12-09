<?php
  define('DB_HOST', 'localhost');
  define('DB_USERNAME', 'root');
  define('DB_PASSWORD', '');
  define('DB_NAME', 'blog');

  $link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

  if (!$link)
    die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
?>