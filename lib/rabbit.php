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

?>