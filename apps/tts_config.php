<fieldset data-role="controlgroup">
  <legend>Text To Speech:</legend>
  <div data-role="fieldcontain">
    <label for="city">Text:</label>
    <input type="text" name="text" id="text" value="<?php echo app_value('text'); ?>"/>
  </div>

  <?php require('recurrence.php'); ?>
</fieldset>
