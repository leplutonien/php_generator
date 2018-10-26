DROP TABLE IF EXISTS sessions ;
  CREATE TABLE sessions(
    id INT(3) NOT NULL,
    id_user INT(3) NOT NULL UNIQUE,
    timestamp int(11) NOT NULL,
    token VARCHAR(250)  UNIQUE,
    access_control_code VARCHAR(250) UNIQUE,
    pwd_reset_token VARCHAR(250) UNIQUE,
    PRIMARY KEY (id)
  );