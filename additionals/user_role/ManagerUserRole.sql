DROP TABLE IF EXISTS modules ;
CREATE TABLE modules(
	id_m INT(3) NOT NULL,
	name_m VARCHAR(250) NOT NULL,
	PRIMARY KEY (id_m)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS actions ;
CREATE TABLE actions(
	id_a INT(3) NOT NULL,
	name_a VARCHAR(250) NOT NULL,
	PRIMARY KEY (id_a)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS actions_on_modules ;
CREATE TABLE actions_on_modules(
	id_am INT(3) NOT NULL,
	id_m INT(3) NOT NULL,
	id_a INT(3) NOT NULL,
	UNIQUE(id_m, id_a),
	PRIMARY KEY (id_am)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS roles ;
CREATE TABLE roles(
	id_r INT(3) NOT NULL,
	name_r VARCHAR(250) NOT NULL,
	PRIMARY KEY (id_r)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS have_roles ;
CREATE TABLE have_roles(
	id_r INT(3) NOT NULL,
	id_am INT(3) NOT NULL,
	PRIMARY KEY (id_r, id_am)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS users ;
CREATE TABLE users (
	id_user INT(12) NOT NULL,
	login VARCHAR(250) NOT NULL UNIQUE ,
	pwd VARCHAR(250) NOT NULL,
	id_r INT(3) NOT NULL,
	PRIMARY KEY (id_user)
) ENGINE=InnoDB;


ALTER TABLE actions_on_modules ADD CONSTRAINT FK_1  FOREIGN KEY (id_m) REFERENCES modules(id_m);
ALTER TABLE actions_on_modules ADD CONSTRAINT FK_2  FOREIGN KEY (id_a) REFERENCES actions(id_a);
ALTER TABLE have_roles ADD CONSTRAINT FK_3  FOREIGN KEY (id_am) REFERENCES actions_on_modules(id_am);
ALTER TABLE have_roles ADD CONSTRAINT FK_4  FOREIGN KEY (id_r) REFERENCES roles(id_r);
ALTER TABLE users ADD CONSTRAINT FK_5  FOREIGN KEY (id_r) REFERENCES have_roles(id_r);

-- insert rows in  actions
INSERT INTO actions(id_a,name_a) VALUES
  (1,'Create'),
  (2,'Read'),
  (3,'Update'),
  (4,'Delete'),
  (5,'Print');