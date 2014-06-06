<?php
require_once('app.php');
if(!isset($config)) {
  require_once('setup.php');
  exit();
}

require_once('lib/db.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user = attempt_login($db, $config, $_REQUEST['username'], $_REQUEST['password']);

  if($user) {
    $info = "Login successful";
    $_SESSION['user'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['is-admin'] = $user['is_admin'];

    require('index.php');
    exit();
  } else {
    $error = "Username or password incorrect";
  }
}

require_once('header.php');
?>
<header class="jumbotron masthead">
  <div class="inner">
    <h1>Nabaztag Server</h1>
    <p>Welcome to the simple Nabaztag Server</p>

    <p class="download-info">
      <a href="https://github.com/dparnell/nabaztag-php/" class="btn btn-primary btn-large">View project on GitHub</a>
    </p>
  </div>
</header>
<hr class="soften">
<div class="marketing">
  <h1>Please log in to your Nabaztag Server.</h1>

  <form method="post" action="login.php" data-ajax="false" class="ui-body ui-body-b ui-corner-all">
    <fieldset data-role="controlgroup">
      <legend>Login Details</legend>
      <div data-role="fieldcontain">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username"/>
      </div>
      <div data-role="fieldcontain">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password"/>
      </div>
    </fieldset>
    <input type="submit" name="submit" value="Login" id="submit" data-role="none" class="btn button"/>
  </form>
</div>
<?php require_once('footer.php'); ?>