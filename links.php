<ul class="nav">
  <?php if(logged_in()) { ?>
  <li><a href="index.php">Rabbits</a></li>
  <?php if(is_admin()) { ?>
  <li><a href="setup.php">Setup</a></li>
  <?php } ?>
  <li><a href="logout.php">Logout</a></li>
  <?php } else { ?>
  <li><a href="login.php">Login</a></li>
  <?php } ?>
</ul>
