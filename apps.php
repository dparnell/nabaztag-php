<?php
require_once('config.php');
require_once('lib/misc.php');

if(!logged_in()) {
  require_once('index.php');
  exit();
}

require_once('lib/db.php');
require_once('lib/rabbit.php');
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
    <tr><th class="ui-label">Application</th><th class="ui-label">Next Update</th><th class="ui-label">Interval</th></tr>
  <?php
  $configured_apps = array();
  foreach($apps as $app) {
    array_push($configured_apps, $app['application']);
  ?>
    <tr><td><a href="setup_app.php?rabbit=<?php echo $rabbit['mac_id']; ?>&app=<?php echo $app['id']; ?>"><?php echo app_name($app); ?></a></td><td><?php echo app_next_update_time($app); ?></td><td><?php echo app_update_interval($app); ?></td></tr>
  <?php } ?>
  </table>

  <h2>Available Apps for your rabbit</h2>
  <table class="ui-body ui-body-b ui-corner-all">
    <tr><th class="ui-label">Application</th></tr>
<?php
$apps_dir = dirname(__FILE__)."/apps";
$files = scandir($apps_dir);

foreach($files as $file) {
  if(preg_match("/^(.*)_config\.php$/i", $file, $matches)) {
    $app = $matches[1];
    require_once($apps_dir."/".$app."_app.php");

    if(!in_array($app, $configured_apps) || in_array($app, $multi_instance_apps)) { ?>
    <tr><td><a href="setup_app.php?rabbit=<?php echo $rabbit['mac_id']; ?>&app=<?php echo $app; ?>"><?php echo $app; ?></a></td></tr>
<?php
    }
  }
}

?>
  </table>

  <h1>RFID Tags</h1>
  <table class="ui-body ui-body-b ui-corner-all">
    <tr><th class="ui-label">Tag ID</th><th class="ui-label">Last Seen</th></tr>
<?php
$tags = rfid_tags_for_rabbit($db, $rabbit);
foreach($tags as $tag) {
?>
    <tr><td><a href="setup_rfid_tag.php?rabbit=<?php echo $rabbit['mac_id']; ?>&tag=<?php echo $tag['rfid']; ?>"><?php echo $tag['rfid']; ?></a></td><td><?php echo strftime('%Y-%m-%d %H:%M', $tag['last_seen']); ?></td></tr>
<?php
}
?>

  </table>
</div>
<?php require('footer.php'); ?>
