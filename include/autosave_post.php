<?php
  include 'check_session.php';
  require_once 'link.php';

  $title = &$_POST['title'];
  $content = &$_POST['content'];
  $author_id = &$_SESSION['id'];

  if ($result = mysqli_query($link, 'SELECT * FROM posts WHERE author_id=$author_id')) {
    if (mysqli_num_rows($result) > 0) {
      if (isset($title)) {      
        if ($stmt = mysqli_prepare($link, 'UPDATE posts
                                           SET title="$title", content="$content"
                                           WHERE author_id=$author_id')) {
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
        }
      } else {
        if ($stmt = mysqli_prepare($link, 'SELECT * FROM posts WHERE author_id=$author_id')) {
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $row_title, $row_content);
          
          while (mysqli_stmt_fetch($stmt)) {
            echo json_encode(array('title' => $row_title, 'content' => $row_content));
          }

          mysqli_stmt_close($stmt);
        }
      }
    } else {
      if ($stmt = mysqli_prepare($link, 'INSERT INTO posts (author_id, title, date, content, status)
                                         VALUES (?, ?, ?, ?, ?)')) {
        mysqli_stmt_bind_param($stmt,
          'issss',
          $param_author_id,
          $param_title,
          $param_date,
          $param_content,
          $param_status);
        
        $param_author_id = $author_id;
        $param_title = $title;
        $param_date = date('Y-m-d');
        $param_content = $content;
        $param_status = 'draft';

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
    }
  }
?>