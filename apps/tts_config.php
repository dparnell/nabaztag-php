<fieldset data-role="controlgroup">
   <legend>Text To Speech:</legend>
   <div data-role="fieldcontain">
     <label for="city">Text:</label>
     <input type="text" name="text" id="text" value="<?php echo app_value('text'); ?>"/>   
   </div>
   <div data-role="fieldcontain">
     <label for="next_update">When:</label>
     <input type="text" name="next_update" id="next_update" value="<?php echo app_next_update_time($app); ?>"/>
   </div>
</fieldset>