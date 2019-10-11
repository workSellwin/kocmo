create table if not exists manao_cdekCities (
	ID int(5) NOT NULL auto_increment,
	BITRIX_ID int(5),
	CDEK_ID int(5) NOT NULL,
	NAME varchar(20) NOT NULL,
	REGION varchar(20),
	primary key (ID)
);