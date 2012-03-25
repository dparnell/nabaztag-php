<?php

function logged_in() {
  return isset($_SESSION) and array_key_exists('user', $_SESSION);
}

function is_admin() {
  return logged_in() && $_SESSION['is-admin'] == 'T';
}

function timezone_select($name, $value) {
  $zones = timezone_identifiers_list();

  echo '<select name="'.$name.'" id="'.$name.'">';
  foreach($zones as $zone) {
    echo '<option value="'.$zone.'"';
    if($zone == $value) {
      echo ' selected="selected"';
    }
    echo ">$zone</option>";
  }
  echo '</select>';
}

?>