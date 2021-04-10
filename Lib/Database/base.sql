/* Database name: techone
   Postgres 12 
 */

/* Sequences */
create sequence extensions_id_seq;
create sequence queues_id_seq;

/* Database tables */

/* Extensions */
create table extensions (
    id integer not null primary key,
    exten integer not null,
    username varchar(100) not null,
    secret varchar(100) not null,
    context varchar(50) not null,
    tech char(3) not null,
    recording boolean not null
);

/* Enum for queue strategy */
CREATE TYPE queue_strategy AS ENUM ('ringall', 'linear', 'random');

/* Queues */
create table queues (
    id integer not null primary key,
    description varchar(50) not null,
    number integer not null,
    strategy queue_strategy
);

/* Extensions x Queue */
create table extensions_queues (
    id serial primary key,
    id_exten integer not null references extensions (id),
    id_queue integer not null references queues (id)
);

/* Configs */
create table settings (
    id serial primary key,
    name varchar(100) unique not null,
    value varchar(100),
    description varchar(100)
);

