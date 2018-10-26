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

    DROP TABLE IF EXISTS roles_access ;
    CREATE TABLE roles_access(
       id_r INT(3) NOT NULL,
       id_am INT(3) NOT NULL,
       PRIMARY KEY (id_r,id_am)
    )ENGINE=InnoDB;

    DROP TABLE IF EXISTS users ;
    CREATE TABLE users (
       id_user INT(12) NOT NULL,
       login VARCHAR(250) NOT NULL UNIQUE ,
       pwd VARCHAR(250) NOT NULL ,     
       PRIMARY KEY (id_user)
    ) ENGINE=InnoDB;
	
	 DROP TABLE IF EXISTS users ;
    CREATE TABLE users (
       id_user INT(12) NOT NULL,
       login VARCHAR(250) NOT NULL UNIQUE ,
       pwd VARCHAR(250) NOT NULL ,
       name VARCHAR(250) NOT NULL,
       active INT(1) NOT NULL,     
       PRIMARY KEY (id_user)
    ) ENGINE=InnoDB;


    ALTER TABLE actions_on_modules ADD CONSTRAINT FK_1  FOREIGN KEY (id_m) REFERENCES modules(id_m);
    ALTER TABLE actions_on_modules ADD CONSTRAINT FK_2  FOREIGN KEY (id_a) REFERENCES actions(id_a);
    ALTER TABLE roles_access ADD CONSTRAINT FK_3  FOREIGN KEY (id_am) REFERENCES actions_on_modules(id_am);
    ALTER TABLE roles_access ADD CONSTRAINT FK_4  FOREIGN KEY (id_r) REFERENCES roles(id_r);
    ALTER TABLE users_roles ADD CONSTRAINT FK_5  FOREIGN KEY (id_r) REFERENCES roles(id_r);
    ALTER TABLE users_roles ADD CONSTRAINT FK_6  FOREIGN KEY (id_user) REFERENCES users(id_user);
    

    -- insert rows in  actions
    INSERT INTO actions(id_a,name_a) VALUES
      (1,'Create'),
      (2,'Read'),
      (3,'Update'),
      (4,'Delete'),
      (5,'Print');

  -- default user and role

  insert into modules (id_m,name_m) values (1, "Users");
insert into modules (id_m,name_m) values (2, "Roles");

insert into actions_on_modules(id_am,id_m,id_a) values 
(1,1,1),(2,1,2),(3,1,3),(4,1,4),
(5,2,1),(6,2,2),(7,2,3),(8,2,4);

insert into roles(id_r,name_r) values
 (1,"Admin"),(2,"Enquêteur"),(3,"Validateur");

insert into roles_access(id_r,id_am) values
  (1,1),(1,2),(1,3),(1,4),
  (1,5),(1,6),(1,7),(1,8);

insert into users(id_user,login,pwd,name,active) values (1,"root",sha1("pwdroot"),"admin",1);

insert into users_roles(id_user,id_r) values (1,1),(1,2),(1,3);