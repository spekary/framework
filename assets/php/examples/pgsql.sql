-- ========================================================================== -- 
--   Tables                                                                   -- 
-- ========================================================================== -- 

-- SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS person cascade;
DROP TABLE IF EXISTS login cascade;
DROP TABLE IF EXISTS project cascade;
DROP TABLE IF EXISTS project_status_type cascade;
DROP TABLE IF EXISTS team_member_project_assn cascade;
DROP TABLE IF EXISTS person_with_lock cascade;
DROP TABLE IF EXISTS related_project_assn cascade;
DROP TABLE IF EXISTS address cascade;
DROP TABLE IF EXISTS milestone cascade;
DROP TABLE IF EXISTS two_key cascade;
DROP TABLE IF EXISTS person_type cascade;
DROP TABLE IF EXISTS person_persontype_assn cascade;
DROP TABLE IF EXISTS qc_watchers cascade;
DROP TABLE IF EXISTS type_test;

CREATE TABLE person (
    id SERIAL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    CONSTRAINT PK_person PRIMARY KEY (id)
);
CREATE INDEX IDX_person_1 ON person (last_name);

CREATE TABLE login (
    id SERIAL,
    person_id BIGINT,
    username VARCHAR(20) NOT NULL,
    password VARCHAR(20),
    is_enabled BOOLEAN NOT NULL,
    CONSTRAINT PK_login PRIMARY KEY (id),
    UNIQUE (person_id),
    UNIQUE (username)
);
--CREATE UNIQUE INDEX IDX_login_1 ON login (person_id);
--CREATE UNIQUE INDEX IDX_login_2 ON login (username);

CREATE TABLE project (
    id SERIAL,
    project_status_type_id BIGINT NOT NULL,
    manager_person_id BIGINT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    budget DECIMAL,
    spent DECIMAL,
    CONSTRAINT PK_project PRIMARY KEY (id)
);
CREATE INDEX IDX_project_1 ON project (project_status_type_id);
CREATE INDEX IDX_project_2 ON project (manager_person_id);

CREATE TABLE team_member_project_assn (
    person_id BIGINT NOT NULL,
    project_id BIGINT NOT NULL,
    CONSTRAINT PK_team_member_project_assn PRIMARY KEY (person_id, project_id)
);
CREATE INDEX IDX_teammemberprojectassn_2 ON team_member_project_assn (project_id);

CREATE TABLE project_status_type (
    id SERIAL,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    guidelines TEXT,
    CONSTRAINT PK_project_status_type PRIMARY KEY (id),
    UNIQUE (name)
);
--CREATE UNIQUE INDEX IDX_projectstatustype_1 ON project_status_type (name);

CREATE TABLE person_with_lock (
    id SERIAL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    sys_timestamp TIMESTAMP,
    CONSTRAINT PK_person_with_lock PRIMARY KEY (id)
);
COMMENT ON COLUMN person_with_lock.sys_timestamp is '{"Timestamp": 1, "AutoUpdate": 1}';

CREATE TABLE related_project_assn (
  project_id BIGINT NOT NULL,
  child_project_id BIGINT NOT NULL,
    CONSTRAINT PK_related_project_assn PRIMARY KEY (project_id, child_project_id)
);
CREATE INDEX IDX_relatedprojectassn_2 ON related_project_assn (child_project_id);

CREATE TABLE milestone (
  id SERIAL,
  project_id BIGINT NOT NULL,
  name VARCHAR(50) NOT NULL,
    CONSTRAINT PK_milestone PRIMARY KEY (id)
);
CREATE INDEX IDX_milestoneproj_1 ON milestone (project_id);

CREATE TABLE address (
    id SERIAL,
    person_id BIGINT,
    street VARCHAR(100) NOT NULL,
    city VARCHAR(100),
    CONSTRAINT PK_address PRIMARY KEY (id)
);
CREATE INDEX IDX_address_1 ON address (person_id);

CREATE TABLE two_key (
  "server" varchar(50) NOT NULL,
  "directory" varchar(50) NOT NULL,
  file_name varchar(50) NOT NULL,
  person_id BIGINT NOT NULL,
  project_id BIGINT,
  CONSTRAINT PK_two_key PRIMARY KEY ("server", "directory")
);
CREATE INDEX IDX_two_key_person_id ON two_key (person_id);
CREATE INDEX IDX_two_key_project_id ON two_key (project_id);


CREATE TABLE person_type (
  id SERIAL,
  name VARCHAR(50) NOT NULL,
  CONSTRAINT PK_person_type PRIMARY KEY (id),
  UNIQUE (name)
);

CREATE TABLE person_persontype_assn (
  person_id BIGINT NOT NULL,
  person_type_id BIGINT NOT NULL,
    CONSTRAINT PK_person_persontype_assn PRIMARY KEY (person_id, person_type_id)
);
CREATE INDEX IDX_persontypeassn_2 ON person_persontype_assn (person_type_id);

CREATE TABLE qc_watchers (
  table_key VARCHAR(200) NOT NULL,
  ts varchar(40) NOT NULL,
  CONSTRAINT PK_qc_watchers PRIMARY KEY (table_key)
);

CREATE TABLE type_test (
  id SERIAL,
  date date,
  time time,
  date_time timestamp,
  test_int BIGINT,
  test_float float,
  test_text text,
  test_bit boolean,
  test_varchar VARCHAR(10),
  CONSTRAINT PK_type_test PRIMARY KEY (id)
);


-- ========================================================================== -- 
--   Foreign Keys                                                             -- 
-- ========================================================================== -- 

ALTER TABLE login ADD CONSTRAINT person_login FOREIGN KEY (person_id) REFERENCES person (id);
ALTER TABLE project ADD CONSTRAINT person_project FOREIGN KEY (manager_person_id) REFERENCES person (id);
ALTER TABLE project ADD CONSTRAINT project_status_type_project FOREIGN KEY (project_status_type_id) REFERENCES project_status_type (id);
ALTER TABLE address ADD CONSTRAINT person_address FOREIGN KEY (person_id) REFERENCES person (id);
ALTER TABLE team_member_project_assn ADD CONSTRAINT person_team_member_project_assn FOREIGN KEY (person_id) REFERENCES person (id);
ALTER TABLE team_member_project_assn ADD CONSTRAINT project_team_member_project_assn FOREIGN KEY (project_id) REFERENCES project (id);
ALTER TABLE milestone ADD CONSTRAINT project_milestone FOREIGN KEY (project_id) REFERENCES project (id);

ALTER TABLE related_project_assn ADD CONSTRAINT related_project_assn_1 FOREIGN KEY (project_id) REFERENCES project (id);
ALTER TABLE related_project_assn ADD CONSTRAINT related_project_assn_2 FOREIGN KEY (child_project_id) REFERENCES project (id);
ALTER TABLE two_key ADD CONSTRAINT two_key_project FOREIGN KEY (project_id) REFERENCES project (id);
ALTER TABLE two_key ADD CONSTRAINT two_key_person FOREIGN KEY (person_id) REFERENCES person (id);

ALTER TABLE person_persontype_assn ADD CONSTRAINT person_persontype_assn_1 FOREIGN KEY (person_id) REFERENCES person (id);
ALTER TABLE person_persontype_assn ADD CONSTRAINT person_persontype_assn_2 FOREIGN KEY (person_type_id) REFERENCES person_type (id);

-- ========================================================================== -- 
--   Type Data                                                                -- 
-- ========================================================================== -- 

INSERT INTO project_status_type (name, description, guidelines) VALUES ('Open', 'The project is currently active', 'All projects that we are working on should be in this state');
INSERT INTO project_status_type (name, description, guidelines) VALUES ('Cancelled', 'The project has been canned', null);
INSERT INTO project_status_type (name, description, guidelines) VALUES ('Completed', 'The project has been completed successfully', 'Celebrate successes!');

INSERT INTO person_type (name) VALUES ('Contractor');
INSERT INTO person_type (name) VALUES ('Manager');
INSERT INTO person_type (name) VALUES ('Inactive');
INSERT INTO person_type (name) VALUES ('Company Car');
INSERT INTO person_type (name) VALUES ('Works From Home');

-- ========================================================================== -- 
--   Example Data                                                             -- 
-- ========================================================================== -- 

INSERT INTO person(first_name, last_name) VALUES ('John', 'Doe');
INSERT INTO person(first_name, last_name) VALUES ('Kendall', 'Public');
INSERT INTO person(first_name, last_name) VALUES ('Ben', 'Robinson');
INSERT INTO person(first_name, last_name) VALUES ('Mike', 'Ho');
INSERT INTO person(first_name, last_name) VALUES ('Alex', 'Smith'); 
INSERT INTO person(first_name, last_name) VALUES ('Wendy', 'Smith');
INSERT INTO person(first_name, last_name) VALUES ('Karen', 'Wolfe');
INSERT INTO person(first_name, last_name) VALUES ('Samantha', 'Jones');
INSERT INTO person(first_name, last_name) VALUES ('Linda', 'Brady');
INSERT INTO person(first_name, last_name) VALUES ('Jennifer', 'Smith');
INSERT INTO person(first_name, last_name) VALUES ('Brett', 'Carlisle');
INSERT INTO person(first_name, last_name) VALUES ('Jacob', 'Pratt');

INSERT INTO person_with_lock(first_name, last_name) VALUES ('John', 'Doe');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Kendall', 'Public');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Ben', 'Robinson');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Mike', 'Ho');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Alfred', 'Newman');  
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Wendy', 'Johnson');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Karen', 'Wolfe');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Samantha', 'Jones');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Linda', 'Brady');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Jennifer', 'Smith');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Brett', 'Carlisle');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Jacob', 'Pratt');

INSERT INTO login(person_id, username, password, is_enabled) VALUES (1, 'jdoe', 'p@$$.w0rd', false);
INSERT INTO login(person_id, username, password, is_enabled) VALUES (3, 'brobinson', 'p@$$.w0rd', true);
INSERT INTO login(person_id, username, password, is_enabled) VALUES (4, 'mho', 'p@$$.w0rd', true);
INSERT INTO login(person_id, username, password, is_enabled) VALUES (7, 'kwolfe', 'p@$$.w0rd', false);
INSERT INTO login(person_id, username, password, is_enabled) VALUES (NULL, 'system', 'p@$$.w0rd', true);

INSERT INTO project(project_status_type_id, manager_person_id, name, description, start_date, end_date, budget, spent) VALUES
  (3, 7, 'ACME Website Redesign', 'The redesign of the main website for ACME Incorporated', '2004-03-01', '2004-07-01', '9560.25', '10250.75');
INSERT INTO project(project_status_type_id, manager_person_id, name, description, start_date, end_date, budget, spent) VALUES
  (1, 4, 'State College HR System', 'Implementation of a back-office Human Resources system for State College', '2006-02-15', NULL, '80500.00', '73200.00');
INSERT INTO project(project_status_type_id, manager_person_id, name, description, start_date, end_date, budget, spent) VALUES
  (1, 1, 'Blueman Industrial Site Architecture', 'Main website architecture for the Blueman Industrial Group', '2006-03-01', '2006-04-15', '2500.00', '4200.50');
INSERT INTO project(project_status_type_id, manager_person_id, name, description, start_date, end_date, budget, spent) VALUES
  (2, 7, 'ACME Payment System', 'Accounts Payable payment system for ACME Incorporated', '2005-08-15', '2005-10-20', '5124.67', '5175.30');

INSERT INTO team_member_project_assn (person_id, project_id) VALUES (2, 1);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (5, 1);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (6, 1);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (7, 1);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (8, 1);

INSERT INTO team_member_project_assn (person_id, project_id) VALUES (2, 2);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (4, 2);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (5, 2);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (7, 2);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (9, 2);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (10, 2);

INSERT INTO team_member_project_assn (person_id, project_id) VALUES (1, 3);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (4, 3);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (6, 3);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (8, 3);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (10, 3);

INSERT INTO team_member_project_assn (person_id, project_id) VALUES (1, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (2, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (3, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (5, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (8, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (11, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (12, 4);

INSERT INTO related_project_assn (project_id, child_project_id) VALUES(1, 3);
INSERT INTO related_project_assn (project_id, child_project_id) VALUES(1, 4);

INSERT INTO related_project_assn (project_id, child_project_id) VALUES(4, 1);

INSERT INTO address (person_id, street, city) VALUES(1, '1 Love Drive', 'Phoenix');
INSERT INTO address (person_id, street, city) VALUES(2, '2 Doves and a Pine Cone Dr.', 'Dallas');
INSERT INTO address (person_id, street, city) VALUES(3, '3 Gold Fish Pl.', 'New York');
INSERT INTO address (person_id, street, city) VALUES(3, '323 W QCubed', 'New York');
INSERT INTO address (person_id, street, city) VALUES(5, '22 Elm St', 'Palo Alto');
INSERT INTO address (person_id, street, city) VALUES(7, '1 Pine St', 'San Jose');
INSERT INTO address (person_id, street, city) VALUES(7, '421 Central Expw', 'Mountain View');

INSERT INTO milestone (project_id, name) VALUES (1, 'Milestone A');
INSERT INTO milestone (project_id, name) VALUES (1, 'Milestone B');
INSERT INTO milestone (project_id, name) VALUES (1, 'Milestone C');
INSERT INTO milestone (project_id, name) VALUES (2, 'Milestone D');
INSERT INTO milestone (project_id, name) VALUES (2, 'Milestone E');
INSERT INTO milestone (project_id, name) VALUES (3, 'Milestone F');
INSERT INTO milestone (project_id, name) VALUES (4, 'Milestone G');
INSERT INTO milestone (project_id, name) VALUES (4, 'Milestone H');
INSERT INTO milestone (project_id, name) VALUES (4, 'Milestone I');
INSERT INTO milestone (project_id, name) VALUES (4, 'Milestone J');

INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('cnn.com', 'us', 'news', 1, 1);
INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('google.com', 'drive', '', 2, 2);
INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('google.com', 'mail', 'mail.html', 3, 2);
INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('google.com', 'news', 'news.php', 4, 3);
INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('mail.google.com', 'mail', 'inbox', 5, NULL);
INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('yahoo.com', '', '', 6, NULL);

INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (3, 1);
INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (10, 1);
INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (1, 2);
INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (3, 2);
INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (1, 3);
INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (3, 3);
INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (9, 3);
INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (2, 4);
INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (2, 5);
INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (5, 5);
INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (7, 2);
INSERT INTO person_persontype_assn (person_id, person_type_id) VALUES (7, 4);

-- SET FOREIGN_KEY_CHECKS = 1;