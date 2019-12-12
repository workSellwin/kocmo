create table if not exists kocmo_exchange_data
(
	ID   int         NOT NULL auto_increment,
	UID  varchar(38) NOT NULL,
	JSON text        NOT NULL,
	ENTRY varchar(36),
	primary key (ID),
	UNIQUE KEY (UID, ENTRY)
);

create table if not exists kocmo_exchange_product_image
(
	ID         int         NOT NULL auto_increment,
	IMG_GUI    varchar(36) NOT NULL,
	PRODUCT_ID int         NOT NULL,
	primary key (ID),
	UNIQUE (PRODUCT_ID)
);