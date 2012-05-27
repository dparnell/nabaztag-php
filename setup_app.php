<?php
require_once('config.php');
require_once('lib/misc.php');

if(!logged_in()) {
  require_once('index.php');
  exit();
}

require('header.php');
require_once('lib/db.php');
require('lib/rabbit.php');
$rabbit = find_rabbit($db, $_REQUEST['rabbit']);
$app = app_for_rabbit($db, $rabbit, $_REQUEST['app']);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  // we need to save the data!
  $app_data = array();
  foreach($_POST as $key => $value) {
    if($key == 'submit') {
      // do nothing
    } else if($key == 'next_update') {
      $app['next_update'] = strtotime($value);
    } else if($key == 'interval') {
      $app['reschedule_interval'] = $value;
    } else {
      $app_data[$key] = $value;
    }
  }

  $app['data'] = serialize($app_data);

  save_rabbit_app($db, $rabbit, $app);
} else {
  if($app['data']) {
    $app_data = unserialize($app['data']);
  } else {
    $app_data = array();
  }
}

function app_value($key, $default = null) {
  global $app_data;

  if(array_key_exists($key, $app_data)) {
    return $app_data[$key];
  }

  return $default;
}


?>
<header class="jumbotron masthead">
  <div class="inner">
    <h1>Nabaztag Server</h1>
    <p><?php echo rabbit_name($rabbit); ?></p>
  </div>
</header>
<hr class="soften">

<div class="marketing">
  <h1>Configure <?php echo $_REQUEST['app']; ?></h1>
  <form method="post" action="setup_app.php?rabbit=<?php echo $_REQUEST['rabbit']; ?>&app=<?php echo $_REQUEST['app']; ?>" id="app-setup-form" data-ajax="false" class="ui-body ui-body-b ui-corner-all">
  
    <?php require('apps/'.$_REQUEST['app'].'_config.php'); ?>

    <fieldset data-role="controlgroup">
      <input type="submit" name="submit" value="Save" id="submit" data-role="none" class="btn button"/>
    </fieldset>
  </form>
</div>
<?php require('footer.php'); ?>

