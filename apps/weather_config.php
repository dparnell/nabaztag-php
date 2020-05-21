<input type="hidden" name="next_update" value="now"/>
<input type="hidden" name="interval" value="1"/>

<fieldset data-role="controlgroup">
   <legend>Weather App Setup:</legend>
   <div data-role="fieldcontain">
     <label for="city"><a href="https://developer.weatherunlocked.com/" title="Free Weather API" target="_blank">Weather Unlocked Application ID</a></label>
     <input type="text" name="id" id="id" value="<?php echo app_value('id'); ?>"/>
   </div>

   <div data-role="fieldcontain">
     <label for="city"><a href="https://developer.weatherunlocked.com/" title="Free Weather API" target="_blank">Weather Unlocked API Key</a></label>
	   <input type="text" name="key" id="key" value="<?php echo app_value('key'); ?>"/>
   </div>
   <div data-role="fieldcontain">
     <label for="city">Location (country code.postcode or lat,long)</label>
     <input type="text" name="city" id="city" value="<?php echo app_value('city'); ?>"/>
   </div>
</fieldset>

<fieldset data-role="controlgroup">
  <legend>Temperature scale:</legend>
<?php $scale = app_value('scale', 'c'); ?>
  <input type="radio" name="scale" value="c" id="use-c" checked="checked"/><label for="use-c">Use Centergrade</label>
  <input type="radio" name="scale" value="f" id="use-f"/><label for="use-f">Use Fahrenheit</label>
</fieldset>
