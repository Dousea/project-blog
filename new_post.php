<?php
  include 'include/check_session.php';
  require_once 'include/link.php';

  $title = $content = '';
  $err = array();
  
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty(trim($_POST['title']))) {
      $err['title'] = 'Please enter post\'s title.';
    } else {
      $title = trim($_POST['title']);
    }
    
    if (empty(trim($_POST['content']))) {
      $err['content'] = 'Please enter post\'s content.';
    } else{
      $content = trim($_POST['content']);
    }
    
    if (count($err) == 0) {
      $query = 'INSERT INTO posts (author_id, title, date, content, status) VALUES (?, ?, ?, ?, ?)';
      
      if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt,
          'issss',
          $param_author_id,
          $param_title,
          $param_date,
          $param_content,
          $param_status);
        
        $param_author_id = $_SESSION['id'];
        $param_title = $title;
        $param_date = date('Y-m-d');
        $param_content = $content;
        $param_status = $_POST['action'] == 'Save' ? 'saved' :
                          $_POST['action'] == 'Publish' ? 'published' :
                            'draft';
        
        if (mysqli_stmt_execute($stmt)) {
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
    <?php include 'include/head.php' ?>
    <title>New Post - Gakkari Posts</title>
    <style>
      #editor-container {
        height: 30em;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <?php include '_header.php' ?>
      <h2>New Post</h2>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" novalidate>
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" name="title"
                 class="form-control <?php echo (!empty($err['title'])) ? 'is-invalid' : '' ?>"
                 value="<?php echo $title ?>">
          <?php echo (!empty($err['title']))
                     ? '<div class="invalid-feedback">' . $err['title'] . '</div>'
                     : '' ?>
        </div>
        <div class="form-group">
          <label for="content">Content</label>
          <input name="content" type="hidden">
          <input type="hidden" name="content"
                 class="<?php echo (!empty($err['content'])) ? 'is-invalid' : '' ?>">
          <div id="editor-container"></div>
          <?php echo (!empty($err['content']))
                     ? '<div class="invalid-feedback">' . $err['content'] . '</div>'
                     : '' ?>
        </div>
        <input name="action" type="submit" class="btn btn-primary" value="Publish">
        <input name="action" type="submit" class="btn btn-secondary" value="Save">
      </form>
      <?php include '_footer.php' ?>
    </div>
    <script>
      var quill = new Quill('#editor-container', {
        modules: {
          toolbar: [
            ['bold', 'italic'],
            ['link', 'blockquote', 'code-block', 'image'],
            [{ list: 'ordered' }, { list: 'bullet' }]
          ]
        },
        theme: 'snow'
      });

      $('form').on('submit', function() {
        $('input[name=content]').val(JSON.stringify(quill.getContents()));
      });
      
      /*
      $(function () {
        $.post("include/autosave_post.php", function(data) {
          $("input[name=title]").val(data.title);
          // TODO: It needs to transform `quill` variable
          $("input[name=content]").val(data.content);
        }, "json");
        
        setInterval(function () {
          $.post("include/autosave_post.php", $('form').serialize());
        }, 2000);
      });
      */
    </script>
  </body>
</html>