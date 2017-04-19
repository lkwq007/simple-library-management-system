CREATE TABLE book (
  bno      CHAR(32) PRIMARY KEY,
  category VARCHAR(50),
  title    VARCHAR(50),
  press    VARCHAR(50),
  year     INT,
  author   VARCHAR(50),
  price    DECIMAL(8, 2),
  total    INT,
  stock    INT
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
CREATE TABLE card (
  cno        CHAR(12) PRIMARY KEY,
  name       VARCHAR(50),
  department VARCHAR(50),
  type       ENUM ('T', 'S')
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
CREATE TABLE admin (
  id      CHAR(32) PRIMARY KEY,
  pwd     CHAR(32),
  name    VARCHAR(50),
  contact VARCHAR(50)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
CREATE TABLE borrow (
  cno         CHAR(12),
  bno         CHAR(32),
  admin_id    CHAR(32),
  borrow_date DATE,
  return_date DATE,
  uuid        CHAR(36),
  PRIMARY KEY (uuid),
  FOREIGN KEY (cno) REFERENCES card (cno),
  FOREIGN KEY (bno) REFERENCES book (bno),
  FOREIGN KEY (admin_id) REFERENCES admin (id)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TRIGGER borrow_check
AFTER UPDATE ON book
FOR EACH ROW
  BEGIN
    IF NEW.stock < 0
    THEN
      SIGNAL SQLSTATE '45001'
      SET MESSAGE_TEXT = 'Stock is empty!';
    END IF;
  END;

INSERT INTO `admin` (`id`, `pwd`, `name`, `contact`) VALUES
  ('root', '63a9f0ea7bb98050796b649e85481845', 'root', 'i@llonely.com');