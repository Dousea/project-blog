<div class="nav-scroller py-1 mb-2">
  <nav class="nav d-flex justify-content-between">
    <?php
      require_once 'include/link.php';

      $query = 'SELECT * FROM tags';
      
      if ($result = mysqli_query($link, $query))
        while ($row = mysqli_fetch_array($result))
          echo '<a class="p-2 text-muted" href="#">' . $row['title'] . '</a>';
    ?>
  </nav>
</div>
