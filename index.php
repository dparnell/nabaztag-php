<?php
require_once('app.php');
require('header.php');
require('lib/rabbit.php');
?>
<header class="jumbotron masthead">
  <div class="inner">
  <?php if(logged_in()) {
$rabbits = rabbits($db);
  ?>
  <h1>My Rabbits</h1>
  <table class="ui-body ui-body-b ui-corner-all">
    <tr><th class="ui-controlgroup-label">Rabbit</th><th class="ui-controlgroup-label">Status</th></tr>
    <?php foreach($rabbits as $rabbit) { ?>
    <tr>
      <td class="ui-controlgroup-label"><a href="apps.php?rabbit=<?php echo rabbit_mac($rabbit); ?>"><?php echo rabbit_name($rabbit); ?></a></td>
      <td class="ui-controlgroup-label"><?php echo rabbit_status($rabbit); ?></td>
    </tr>
    <?php } ?>
  </table>

  <?php } else { ?>
    <h1>Nabaztag Server</h1>
    <p>Welcome to the simple Nabaztag Server</p>

    <p class="download-info">
      <a href="https://github.com/dparnell/nabaztag-php/" class="btn btn-primary btn-large">View project on GitHub</a>
    </p>
  <?php } ?>
  </div>
</header>

<?php require('footer.php'); ?>
