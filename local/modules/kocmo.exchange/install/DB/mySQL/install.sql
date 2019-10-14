create table if not exists kocmo_exchange_data (
	ID int NOT NULL auto_increment,
	UID varchar(36) NOT NULL,
	JSON text NOT NULL,
	primary key (ID)
);