<?php
require_once('config.php');
require_once('lib/misc.php');

if(!logged_in()) {
  require_once('index.php');
  exit();
}

require_once('lib/db.php');
require('lib/rabbit.php');
$rabbit = find_rabbit($db, $_REQUEST['rabbit']);

require('header.php');
?>
<header class="jumbotron masthead">
  <div class="inner">
    <h1>Nabaztag Server</h1>
    <p><?php echo rabbit_name($rabbit); ?></p>

  </div>
</header>
<hr class="soften">

<div class="marketing">
  <h1>Apps for your rabbit</h1>
<?php $apps = apps_for_rabbit($db, $rabbit); ?>
  <table class="ui-body ui-body-b ui-corner-all">
    <tr><th class="ui-controlgroup-label">Application</th><th class="ui-controlgroup-label">Next Update</th><th class="ui-controlgroup-label">Interval</th></tr>
  <?php foreach($apps as $app) { ?>
    <tr><td><a href="setup_app.php?rabbit=<?php echo $rabbit['mac_id']; ?>&app=<?php echo $app['application']; ?>"><?php echo app_name($app); ?></a></td><td><?php echo app_next_update_time($app); ?></td><td><?php echo app_update_interval($app); ?></td></tr>
  <?php } ?>
  </table>
</div>
<?php require('footer.php'); ?>

