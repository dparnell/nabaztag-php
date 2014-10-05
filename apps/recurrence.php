<div data-role="fieldcontain">
  <label for="next_update">When:</label>
  <input type="text" name="next_update" id="next_update" value="<?php echo app_next_update_time($app); ?>"/>
  <label for="next_update">Recurring:</label>
  <select name="interval">
    <option value="0">Never</option>
    <?php
    $intervals = array("60" => "Every minute", "300" => "Every 5 minutes", "900" => "Every 15 minutes", "1800" => "Every 30 minutes", "3600" => "Every hour", "43200" => "Evert 12 hours", "86400" => "Every day", "604800" => "Every week");
    foreach($intervals as $key => $value) {
      print '<option value="'.$key.'"';
      if($key == $app['reschedule_interval']) {
        print 'selected="selected"';
      }
      print '>'.$value.'</option>';
    }
    ?>
  </select>
</div>
