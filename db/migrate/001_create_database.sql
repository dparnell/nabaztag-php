create table users (
  id       integer  primary key AUTOINCREMENT NOT NULL,
  username varchar(256),
  password varchar(256),
  is_admin varchar(1),
  timezone_offset integer
);

create table rabbits (
  id        integer primary key AUTOINCREMENT NOT NULL,
  mac_id    varchar(32),
  name      varchar(128),
  owner_id  integer,
  last_seen datetime
);
