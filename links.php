<ul class="nav">
  <?php if(isset($_SESSION) and array_key_exists('user', $_SESSION)) { ?>
  <li><a href="logout.php">Logout</a></li>
  <?php } else { ?>
  <li><a href="login.php">Login</a></li>
  <?php } ?>
</ul>
