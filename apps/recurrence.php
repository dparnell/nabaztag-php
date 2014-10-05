<?php
  if(array_key_exists('reschedule_interval', $app)) {
    $interval = $app['reschedule_interval'];
  } else {
    $interval = 0;
  }

  if(array_key_exists('on_days', $app)) {
    $on_days = $app['on_days'];
  } else {
    $on_days = 0;
  }

?><div data-role="fieldcontain">
  <label for="next_update">When:</label>
  <input type="text" name="next_update" id="next_update" value="<?php echo app_next_update_time($app); ?>"/>
  <label for="interval">Recurring:</label>
  <select name="interval">
    <option value="0">Never</option>
    <?php
    $intervals = array("60" => "Every minute", "300" => "Every 5 minutes", "900" => "Every 15 minutes", "1800" => "Every 30 minutes", "3600" => "Every hour", "43200" => "Evert 12 hours", "86400" => "Every day", "604800" => "Every week");
    foreach($intervals as $key => $value) {
      print '<option value="'.$key.'"';
      if($key == $interval) {
        print 'selected="selected"';
      }
      print '>'.$value.'</option>';
    }
    ?>
  </select>
  <input type="hidden" id="on_days" name="on_days" value="<?php print $on_days; ?>"/>
  <label>Days</label>
  <label for="day-0"><input type="checkbox" id="day-0"/> Sunday</label>
  <label></label>
  <label for="day-1"><input type="checkbox" id="day-1"/> Monday</label>
  <label></label>
  <label for="day-2"><input type="checkbox" id="day-2"/> Tuesday</label>
  <label></label>
  <label for="day-3"><input type="checkbox" id="day-3"/> Wednesday</label>
  <label></label>
  <label for="day-4"><input type="checkbox" id="day-4"/> Thursday</label>
  <label></label>
  <label for="day-5"><input type="checkbox" id="day-5"/> Friday</label>
  <label></label>
  <label for="day-6"><input type="checkbox" id="day-6"/> Saturday</label>
</div>
