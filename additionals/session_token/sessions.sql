DROP TABLE IF EXISTS sessions ;
CREATE TABLE sessions(
	id INT(3) NOT NULL,
	timestamp int(11) NOT NULL,
	token VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
)ENGINE=InnoDB;