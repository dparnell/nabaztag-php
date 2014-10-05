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
    $rabbit['asleep'] = 0;

    return $rabbit;
}

function finished_rabbit($db, $rabbit) {
    $st = $db->prepare("update rabbits set last_seen=?, asleep=? where id=?");
    $st->execute(array(time(), $rabbit['asleep'], $rabbit['id']));
}

function rabbit_mac($rabbit) {
    return $rabbit['mac_id'];
}

function scheduled_apps_for_rabbit($db, $rabbit) {
    $st = $db->prepare("select * from apps where rabbit_id = ? and (next_update<=? or next_update is null or next_update='')");
    if($st) {
        $st->execute(array($rabbit['id'], time()));

        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    return array();
}

function apps_for_rabbit($db, $rabbit) {
    $st = $db->prepare("select * from apps where rabbit_id = ?");
    if($st) {
        $st->execute(array($rabbit['id']));

        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    return array();
}

function app_for_rabbit($db, $rabbit, $app_id) {
    if(intval($app_id)>0) {
        $st = $db->prepare("select * from apps where rabbit_id = ? and id = ?");
        if($st) {
            $st->execute(array($rabbit['id'], $app_id));

            $result = $st->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        $result = array();
        $result['rabbit_id'] = $rabbit['id'];
        $result['application'] = $app_id;
        $result['next_update'] = null;
        $result['reshedule_interval'] = null;
        $result['data'] = null;
    }

    return $result;
}

function app_for_rabbit_by_name($db, $rabbit, $app_name) {
    $st = $db->prepare("select * from apps where rabbit_id = ? and application = ?");
    if($st) {
        $st->execute(array($rabbit['id'], $app_name));

        $result = $st->fetch(PDO::FETCH_ASSOC);
    } else {
        $result = null;
    }

    if($result == null) {
        error_log("WHERE");
        $result = array();
        $result['rabbit_id'] = $rabbit['id'];
        $result['application'] = $app_name;
        $result['next_update'] = null;
        $result['reshedule_interval'] = null;
        $result['on_days'] = null;
        $result['data'] = null;
    }

    return $result;
}


function remove_rabbit_app($db, $app) {
    $st = $db->prepare("delete from apps where id=?");
    $st->execute(array($app['id']));
}

function reschedule_rabbit_app($db, $app) {
    $interval = $app['reschedule_interval'];
    $next_update = time() + $interval;
    $on_days = $app['on_days'];
    if($on_days) {
        $day = getdate($next_update);
        while(((1<<$day['wday']) & $on_days) == 0) {
            $next_update += + $interval;
            $day = getdate($next_update);
        }
    }

    $st = $db->prepare("update apps set next_update=? where id=?");
    $st->execute(array($next_update, $app['id']));
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
    if(!array_key_exists('next_update', $app)) {
        $app['next_update'] = 0;
    }
    if(!array_key_exists('reschedule_interval', $app)) {
        $app['reschedule_interval'] = 0;
    }

    if(array_key_exists('id', $app)) {
        $st = $db->prepare("update apps set data=?, next_update=?, reschedule_interval=?, on_days=? where id=?");
        $st->execute(array($app['data'], $app['next_update'], $app['reschedule_interval'], $app['on_days'], $app['id']));
    } else {
        $st = $db->prepare("insert into apps (rabbit_id, application, next_update, reschedule_interval, on_days, data) values (?, ?, ?, ?, ?, ?)");
        $st->execute(array($rabbit['id'], $app['application'], $app['next_update'], $app['reschedule_interval'], $app['on_days'], $app['data']));
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

        if($interval < 24*60*60) {
            return floor($interval/(60*60)).' hours';
        }

        return floor($interval/(24*60*60)).' days';
    }

    return 'Once off';
}

?>