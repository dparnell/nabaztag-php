<fieldset data-role="controlgroup">
   <legend>Weather App Config:</legend>
   <div data-role="fieldcontain">
     <label for="app-weather-api-key"><a href="http://www.worldweatheronline.com/register.aspx">worldweatheronline.com</a> API Key:</label>
     <input type="text" name="app-weather-api-key" id="app-weather-api-key" value="<?php echo config_value('app-weather-api-key'); ?>"/>
   </div>
</fieldset>
