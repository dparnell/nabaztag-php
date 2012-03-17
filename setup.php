<?php 
require_once('app.php');
if(isset($config) && !$_SESSION['admin']) {
  $error_message = "You must be an administrator to access this page";
  require_once('failed.php');
}

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
  <h1>Your Nabaztag Server is not yet configured.</h1>
  <p class="marketing-byline">Let's get ready to rock!</p>
  <form method="post">
    <fieldset data-role="controlgroup">
      <legend>Where do you want to store your data:</legend>
      <input type="radio" name="db" value="sqlite" id="use-sqlite"/><label for="use-sqlite">Use SQLite</label>
      <input type="radio" name="db" value="other" id="use-other"/><label for="use-other">Use Other</label>
    </fieldset>
  </form>
</div>
<?php require('footer.php'); ?>
