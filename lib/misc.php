<?php

function logged_in() {
  return isset($_SESSION) and array_key_exists('user', $_SESSION);
}

function is_admin() {
  return logged_in() && $_SESSION['is-admin'] == 'T';
}

function current_user_id() {
  if(logged_in()) {
    return $_SESSION['user'];
  }

  return null;
}

function rabbit_name($rabbit) {
  return $rabbit['name'] ? $rabbit['name'] : $rabbit['mac_id'];
}

function rabbit_is_online($rabbit) {
  return $rabbit['last_seen'] > time() - 600;
}

function rabbit_status($rabbit) {
  if(rabbit_is_online($rabbit)) {
    return 'Online';
  }

  return 'Offline';
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