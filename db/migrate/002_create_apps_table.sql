create table apps (
  id        integer primary key AUTOINCREMENT NOT NULL,
  rabbit_id integer,
  next_update integer,
  reschedule_interval integer,
  application varchar(64),
  data text
)