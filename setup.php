<?php
require_once('config.php');
require_once('lib/misc.php');

if(isset($config) && !logged_in()) {
  $error = "Your server is already configured";
  require_once('index.php');
  exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(!isset($config)) {
    $config = array();
    $config['password-salt'] = sha1(date('l jS \of F Y h:i:s A').mt_rand().mt_rand());

    if($_POST['db'] == 'sqlite') {
      $dir = dirname(__FILE__)."/db";
      
      $config['connect-string'] = "sqlite:/$dir/nabaztag.sqlite3";
    } else {
      $config['connect-string'] = $_POST['connect-string'];
      $config['db-user'] = $_POST['db-user'];
      $config['db-password'] = $_POST['db-password'];
    }
    
    if($_POST['password'] != $_POST['confirm']) {
      $error = "Password and password confirmation do not match";
    }

  }

  $config['server-timezone'] = $_POST['server-timezone'];


  require_once('lib/db.php');
  

  if(isset($error)) {
    unset($config);
  } else {
    if(install_database_tables($db)) {
      save_config($config);

      if(!logged_in()) {
	create_user($db, $config, $_POST['username'], $_POST['password'], true);
      }
      $info = "Configuration successful";
    }
  }
} else {
  require_once('lib/db.php');
}

$dir = dirname(__FILE__);

require('header.php'); ?>
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
  <?php if(logged_in()) { ?>
  <h1>Reconfigure your Nabaztag Server.</h1>
  <p class="marketing-byline">Time to make some changes!</p>
  <?php } else { ?>
  <h1>Your Nabaztag Server is not yet configured.</h1>
  <p class="marketing-byline">Let's get ready to rock!</p>
  <?php } ?>

  <?php if(is_writable($dir)) { ?>
  <form method="post" action="setup.php" id="setup-form" data-ajax="false" class="ui-body ui-body-b ui-corner-all">
    <?php if(!logged_in()) { ?>
    <fieldset data-role="controlgroup">
      <legend>Where do you want to store your data:</legend>
      <?php if(is_writable($dir."/db")) { ?>
      <input type="radio" name="db" value="sqlite" id="use-sqlite" checked="checked"/><label for="use-sqlite">Use auto-configured SQlite database</label>
      <?php } else { ?>
      <input type="radio" name="db" value="sqlite-not-available" id="use-sqlite" disabled="disabled"/><label for="use-sqlite">Use auto-configured SQlite database - not available as <?php echo $dir."/db" ?> is not writable</label>
      <?php } ?>
      <input type="radio" name="db" value="other" id="use-other"/><label for="use-other">Configure database connection manually</label>
    </fieldset>
    <fieldset data-role="controlgroup" id="pdo-settings" style="display: none">
      <legend>Advanced database details:</legend>
      <label for="connect-string">PDO Connect String (<a href="http://www.electrictoolbox.com/php-pdo-dsn-connection-string/" target="_blank">Help</a>):</label>
      <input type="text" name="connect-string" id="connect-string" />
      <label for="db-user">Database Username:</label>
      <input type="text" name="db-user" id="db-user" />
      <label for="db-password">Database Password:</label>
      <input type="password" name="db-password" id="db-password" />
    </fieldset>
    <fieldset data-role="controlgroup">
      <legend>Administrator User Details:</legend>
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" class="required"/>
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" class="required"/>
      <label for="confirm">Confirm:</label>
      <input type="password" name="confirm" id="confirm" class="required"/>
    </fieldset>
    <?php } ?>
    <fieldset data-role="controlgroup">
      <legend>Server Timezone:</legend>
      <?php timezone_select('server-timezone', isset($config) && array_key_exists('server-timezone', $config) ? $config['server-timezone'] : null); ?>
    </fieldset>
    
    <fieldset data-role="controlgroup">
      <input type="submit" name="submit" value="Save" id="submit" data-role="none" class="btn button"/>
    </fieldset>
  </form>

</div>
<script type="text/javascript">
  $(document).ready(function(){
    $('#setup-form').validate();

    $('#use-other, #use-sqlite').click(function(e) {
      if($('#use-sqlite').prop('checked')) {
        $('#pdo-settings').slideUp();
      } else {
        $('#pdo-settings').slideDown();
      }
    });
  });
</script>
<?php } else { ?>
<div class="alert alert-error">
  <h2>Error</h2>
  <p>Please make sure that <?php echo $dir; ?> is writable by your web server.</p>
  <p>Until this is done it is not possible to configure your simple nabaztag server.</p>
</div>
<?php } ?>
<?php require('footer.php'); ?>
