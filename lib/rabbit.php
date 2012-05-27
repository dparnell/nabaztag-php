<?php
function find_rabbit($db, $mac) {
  $st = $db->prepare("select * from rabbits where mac_id=?");
  $st->execute(array($mac));
  $rabbit = $st->fetch(PDO::FETCH_ASSOC);
  
  if($rabbit) {
    return $rabbit;
  }

  $st->closeCursor();

  $st = $db->prepare("insert into rabbits (mac_id) values (?)");
  $st->execute(array($mac));
  
  $rabbit = array();
  $rabbit['id'] = $db->lastInsertId();
  $rabbit['mac_id'] = $mac;
  $rabbit['name'] = $mac;

  return $rabbit;
}

function finished_rabbit($db, $rabbit) {
  $st = $db->prepare("update rabbits set last_seen=? where id=?");
  $st->execute(array(time(), $rabbit['id']));  
}

function rabbit_mac($rabbit) {
  return $rabbit['mac_id'];
}

function apps_for_rabbit($db, $rabbit) {
  $st = $db->prepare("select * from apps where rabbit_id = ? and (next_update<=? or next_update is null or next_update='')");
  if($st) {
    $st->execute(array($rabbit['id'], time()));
  
    return $st->fetchAll(PDO::FETCH_ASSOC);  
  }

  return array();
}

function app_for_rabbit($db, $rabbit, $app_name) {
  $st = $db->prepare("select * from apps where rabbit_id = ? and application = ?");
  if($st) {
    $st->execute(array($rabbit['id'], $app_name));  
  
    $result = $st->fetch(PDO::FETCH_ASSOC);  
  }

  if($result == null) {
    $result = array();
    $result['rabbit_id'] = $rabbit['id'];
    $result['application'] = $app_name;
    $result['next_update'] = null;
    $result['reshedule_interval'] = null;
    $result['data'] = null;
  }

  return $result;
}

function remove_rabbit_app($db, $app) {
  $st = $db->prepare("delete from apps where id=?");
  $st->execute(array($app['id']));
}

function reschedule_rabbit_app($db, $app) {
  $st = $db->prepare("update apps set next_update=?+reschedule_interval where id=?");
  $st->execute(array(time(), $app['id']));
}

function app_name($app) {
  return $app['application'];
}

function app_next_update_time($app) {
  $next = $app['next_update'];

  if($next && $next > 0) {
    return strftime('%Y-%m-%d %H:%M', $next);
  }

  return 'Now';
}

function save_rabbit_app($db, $rabbit, $app) {
  if(array_key_exists('id', $app)) {
    $st = $db->prepare("update apps set data=? where id=?");
    $st->execute(array($app['data'], $app['id']));
  } else {
    $st = $db->prepare("insert into apps (rabbit_id, application, next_update, reschedule_interval, data) values (?, ?, ?, ?, ?)");
    $st->execute(array($rabbit['id'], $app['application'], $app['next_update'], $app['reschedule_interval'], $app['data']));
  }
}

function app_update_interval($app) {
  $interval = $app['reschedule_interval'];

  if($interval && $interval>0) {
    if($interval < 10) {
      return 'Constant';
    }

    if($interval < 90) {
      return $interval.' seconds';
    }
    if($interval < 90*60) {
      return floor($interval/60).' minutes';
    }

    return floor($interval/(60*60)).' hours';
  }

  return 'Once off';
}

?>