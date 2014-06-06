<?php
try {
  if(array_key_exists('user', $config)) {
    $db = new PDO($config['connect-string'], $config['user'], $config['password']);
  } else {
    $db = new PDO($config['connect-string']);
  }
} catch(PDOException $e) {
  $error = "Could not connect to database: ".$config['connect-string']." - ".$e->getMessage();
  $db = null;
}

function install_database_tables($db) {
  $create_schema_migrations = false;
  try {
    $st = $db->query("select version from schema_migrations");
    if($st) {
      $versions = $st->fetchAll(PDO::FETCH_COLUMN, 0);
    } else {
      $create_schema_migrations = true;
    }
  } catch (PDOException $e) {
    $create_schema_migrations = true;
  }

  try {
    if($create_schema_migrations) {
      # The schema_migrations table doesn't exist, so create it
      $db->exec("create table schema_migrations ( version varchar(128) )");
      $versions = array();
    }

    $dir = dirname(__FILE__)."/../db/migrate";
    $migrations = scandir($dir);
    foreach($migrations as $migration) {
      # does the filename end with .SQL?
      if(preg_match("/.sql$/i", $migration) == 1) {
	if(in_array($migration, $versions) == false) {
	  # we need to install this migration
	  $sql = file_get_contents($dir."/".$migration);

	  foreach(explode(";", $sql) as $statement) {
	    $statement = trim($statement);
	    if($statement != "") {
#	      echo "<code><pre>$statement</pre></code><br/>";
	      $db->exec($statement);
	    }
	  }

	  $db->exec("insert into schema_migrations(version) values ('$migration')");
	}
      }
    }
  } catch(PDOException $e) {
    return false;
  }

  return true;
}

function create_user($db, $config, $username, $password, $admin) {
  if($admin) {
    $flag = 'T';
  } else {
    $flag = 'F';
  }
  $st = $db->prepare("insert into users (username, password, is_admin) values (?, ?, ?)");
  $st->execute(array($username, sha1($password.'-'.$config['password-salt']), $flag));
}

function attempt_login($db, $config, $username, $password) {
  $st = $db->prepare("select * from users where username=? and password=?");
  $st->execute(array($username, sha1($password.'-'.$config['password-salt'])));
  $user = $st->fetch(PDO::FETCH_ASSOC);

  return $user;
}

function rabbit_online_count($db) {
  $st = $db->prepare("select count(*) c from rabbits where ?-last_seen < 600");
  $st->execute(array(time()));
  $row = $st->fetch(PDO::FETCH_ASSOC);

  if($row) {
    return $row['c'];
  }

  return 0;
}

function rabbits($db) {
  $sql = "select * from rabbits where owner_id=?";
  if(is_admin()) {
    $sql .= " or owner_id is null";
  }

  $st = $db->prepare($sql." order by name");
  $st->execute(array(current_user_id()));

  return $st->fetchAll(PDO::FETCH_ASSOC);
}

?>