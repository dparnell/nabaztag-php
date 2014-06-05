<input type="hidden" name="next_update" value="now"/>
<input type="hidden" name="interval" value="1"/>

<fieldset data-role="controlgroup">
   <div data-role="fieldcontain">
     <label for="wake-time">Wake up time:</label>
     <input type="text" name="wake_time" id="wake-time" value="<?php echo app_value('wake_time'); ?>"/>
   </div>
   <div data-role="fieldcontain">
     <label for="wake-time">Wake up sound:</label>
     <input type="text" name="wake_sound" id="wake-sound" value="<?php echo app_value('wake_sound'); ?>"/>
   </div>
   <div data-role="fieldcontain">
     <label for="sleep-time">Sleep time:</label>
     <input type="text" name="sleep_time" id="sleep-time" value="<?php echo app_value('sleep_time'); ?>"/>
   </div>
   <div data-role="fieldcontain">
     <label for="sleep-time">Sleep sound:</label>
     <input type="text" name="sleep_sound" id="sleep-sound" value="<?php echo app_value('sleep_sound'); ?>"/>
   </div>
</fieldset>