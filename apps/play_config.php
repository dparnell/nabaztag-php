<fieldset data-role="controlgroup">
  <legend>Play MP3 URL:</legend>
  <div data-role="fieldcontain">
    <label for="url">URL:</label>
    <input type="text" name="url" id="url" value="<?php echo app_value('url'); ?>"/>
  </div>

  <?php require('recurrence.php'); ?>
</fieldset>
