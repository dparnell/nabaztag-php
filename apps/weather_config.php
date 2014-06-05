<input type="hidden" name="next_update" value="now"/>
<input type="hidden" name="interval" value="1"/>

<fieldset data-role="controlgroup">
   <legend>Weather App Setup:</legend>
   <div data-role="fieldcontain">
     <label for="city">City</label>
     <input type="text" name="city" id="city" value="<?php echo app_value('city'); ?>"/>
   </div>
</fieldset>

<fieldset data-role="controlgroup">
  <legend>Temperature scale:</legend>
<?php $scale = app_value('scale', 'C'); ?>
  <input type="radio" name="scale" value="C" id="use-c" checked="checked"/><label for="use-c">Use Centergrade</label>
  <input type="radio" name="scale" value="F" id="use-f"/><label for="use-f">Use Fahrenheit</label>
</fieldset>
