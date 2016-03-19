create table rfid_tags (
  id        integer primary key AUTOINCREMENT NOT NULL,
  rfid      varchar(32),
  rabbit_id integer,
  last_seen integer,
  command   text
)
