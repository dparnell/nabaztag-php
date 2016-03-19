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
$tag = rfid_tag_for_rabbit($db, $rabbit, $_REQUEST['tag']);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  // we need to save the data!
  foreach($_POST as $key => $value) {
    if($key == 'submit') {
      // do nothing
    } else {
      $tag[$key] = $value;
    }
  }

  save_rfid_tag($db, $tag);
  header("Location: apps.php?rabbit=".$rabbit['mac_id']);

  exit();
}
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
    <h1>Configure RFID Tag <?php echo $tag['rfid']; ?></h1>
</div>

<form method="post" action="setup_rfid_tag.php?rabbit=<?php echo $_REQUEST['rabbit']; ?>&tag=<?php echo $_REQUEST['tag']; ?>" id="app-setup-form" data-ajax="false" class="ui-body ui-body-b ui-corner-all">
<fieldset data-role="controlgroup">
   <legend>Command:</legend>
   <div data-role="fieldcontain">
    <textarea name="command" id="command" cols="80" rows="10"><?php echo htmlentities($tag['command']); ?></textarea>
    <div class="alert alert-info">
    <strong>Help:</strong> The following commands are supported....
    </div>
   </div>
</fieldset>

    <fieldset data-role="controlgroup">
      <input type="submit" name="submit" value="Save" id="submit" data-role="none" class="btn button"/>
    </fieldset>
</form>

<?php require('footer.php'); ?>
