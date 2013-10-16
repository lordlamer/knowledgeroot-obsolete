-- knowledgeroot2 dump for mysql

BEGIN;

-- tables

-- table: user
CREATE TABLE `user` (
  id integer NOT NULL AUTO_INCREMENT,
  first_name varchar(255) DEFAULT '' NOT NULL,
  last_name varchar(255) DEFAULT '' NOT NULL,
  login varchar(255) DEFAULT '' NOT NULL,
  email varchar(255) DEFAULT '' NOT NULL,
  password varchar(255) DEFAULT '' NOT NULL,
  language varchar(10) DEFAULT '' NOT NULL,
  timezone varchar(50) DEFAULT 'UTC' NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  active tinyint(1) DEFAULT false NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted tinyint(1) DEFAULT false NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table: group
CREATE TABLE `group` (
  id integer NOT NULL AUTO_INCREMENT,
  name varchar(255) DEFAULT '' NOT NULL,
  description text DEFAULT '' NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  active tinyint(1) DEFAULT false NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted tinyint(1) DEFAULT false NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table: user_group
CREATE TABLE group_member (
  id integer NOT NULL AUTO_INCREMENT,
  group_id integer NOT NULL,
  member_id integer NOT NULL,
  member_type ENUM('user', 'group') NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table: acl
CREATE TABLE acl (
  id integer NOT NULL AUTO_INCREMENT,
  role_id varchar(255) NOT NULL,
  resource varchar(255) NOT NULL,
  action ENUM('new','edit','delete','show','new_content','permission','print','export') NOT NULL,
  `right` ENUM('allow', 'deny') NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table: page
CREATE TABLE page (
  id integer NOT NULL AUTO_INCREMENT,
  parent integer DEFAULT 0 NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  subtitle varchar(255) DEFAULT '' NOT NULL,
  description text DEFAULT '' NOT NULL,
  tooltip varchar(255) DEFAULT '' NOT NULL,
  icon varchar(255) DEFAULT '' NOT NULL,
  alias varchar(255) DEFAULT '' NOT NULL,
  content_collapse tinyint(1) DEFAULT TRUE NOT NULL,
  content_position ENUM('start', 'end') DEFAULT 'end' NOT NULL,
  show_content_description tinyint(1) DEFAULT FALSE NOT NULL,
  show_table_of_content tinyint(1) DEFAULT FALSE NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  active tinyint(1) DEFAULT false NOT NULL,
  deleted tinyint(1) DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE RESTRICT,
  FOREIGN KEY (changed_by) REFERENCES `user` (id) ON DELETE RESTRICT
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table: page
CREATE TABLE page_history (
  id integer NOT NULL AUTO_INCREMENT,
  page_id integer DEFAULT 0 NOT NULL,
  version integer DEFAULT 0 NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  subtitle varchar(255) DEFAULT '' NOT NULL,
  description text DEFAULT '' NOT NULL,
  tooltip varchar(255) DEFAULT '' NOT NULL,
  icon varchar(255) DEFAULT '' NOT NULL,
  alias varchar(255) DEFAULT '' NOT NULL,
  content_collapse tinyint(1) DEFAULT TRUE NOT NULL,
  content_position ENUM('start', 'end') DEFAULT 'end' NOT NULL,
  show_content_description tinyint(1) DEFAULT FALSE NOT NULL,
  show_table_of_content tinyint(1) DEFAULT FALSE NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  active tinyint(1) DEFAULT false NOT NULL,
  deleted tinyint(1) DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (page_id) REFERENCES page (id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table: content
CREATE TABLE content (
  id integer NOT NULL AUTO_INCREMENT,
  parent integer DEFAULT 0 NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  content text DEFAULT '' NOT NULL,
  type varchar(255) DEFAULT 'text' NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  active tinyint(1) DEFAULT false NOT NULL,
  deleted tinyint(1) DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (parent) REFERENCES page (id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE RESTRICT,
  FOREIGN KEY (changed_by) REFERENCES `user` (id) ON DELETE RESTRICT
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table: content
CREATE TABLE content_history (
  id integer NOT NULL AUTO_INCREMENT,
  content_id integer DEFAULT 0 NOT NULL,
  version integer DEFAULT 0 NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  content text DEFAULT '' NOT NULL,
  type varchar(255) DEFAULT 'text' NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  active tinyint(1) DEFAULT false NOT NULL,
  deleted tinyint(1) DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table: file
CREATE TABLE file (
  id integer NOT NULL AUTO_INCREMENT,
  parent integer DEFAULT 0 NOT NULL,
  hash varchar(32) DEFAULT '' NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  size integer DEFAULT 0 NOT NULL,
  type varchar(255) DEFAULT 'application/octet-stream',
  downloads integer DEFAULT 0 NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted tinyint(1) DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (parent) REFERENCES content (id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE RESTRICT,
  FOREIGN KEY (changed_by) REFERENCES `user` (id) ON DELETE RESTRICT
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table: file_history
CREATE TABLE file_history (
  id integer NOT NULL AUTO_INCREMENT,
  file_id integer DEFAULT 0 NOT NULL,
  version integer DEFAULT 0 NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  hash varchar(32) DEFAULT '' NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  size integer DEFAULT 0 NOT NULL,
  type varchar(255) DEFAULT 'application/octet-stream',
  downloads integer DEFAULT 0 NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted tinyint(1) DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (file_id)  REFERENCES file (id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- tags

-- table: tag
CREATE TABLE tag (
  id integer NOT NULL AUTO_INCREMENT,
  name varchar(255) DEFAULT '' NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table: tag_content
CREATE TABLE tag_content (
  id integer NOT NULL AUTO_INCREMENT,
  tag_id integer NOT NULL,
  content_id integer NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE,
  FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- content ratings

-- functions
-- trigger function for content table
DELIMITER ||
CREATE TRIGGER contentHistory_trigger_insert AFTER INSERT ON content FOR EACH ROW
BEGIN
    INSERT INTO content_history (content_id, `version`, `parent`, `name`, `content`, `type`, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
    VALUES (NEW.id, 1, NEW.parent, NEW.name, NEW.content, NEW.type, NEW.sorting, NEW.time_start, NEW.time_end, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.active, NEW.deleted);
END
||
DELIMITER ;

DELIMITER ||
CREATE TRIGGER contentHistory_trigger_update AFTER UPDATE ON content FOR EACH ROW
BEGIN
    INSERT INTO content_history (content_id, `version`, `parent`, `name`, `content`, `type`, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
    VALUES (NEW.id, (SELECT max(x.version)+1 FROM (SELECT * FROM content_history) x WHERE x.content_id = NEW.id), NEW.parent, NEW.name, NEW.content, NEW.type, NEW.sorting, NEW.time_start, NEW.time_end, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.active, NEW.deleted);
END
||
DELIMITER ;


-- trigger function for page table
DELIMITER ||
CREATE TRIGGER pageHistory_trigger_insert AFTER INSERT ON page FOR EACH ROW
BEGIN
    INSERT INTO page_history (page_id, version, parent, name, subtitle, description, tooltip, icon, alias, content_collapse, content_position, show_content_description, show_table_of_content, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
    VALUES (NEW.id, 1, NEW.parent, NEW.name, NEW.subtitle, NEW.description, NEW.tooltip, NEW.icon, NEW.alias, NEW.content_collapse, NEW.content_position, NEW.show_content_description, NEW.show_table_of_content, NEW.sorting, NEW.time_start, NEW.time_end, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.active, NEW.deleted);
END
||
DELIMITER ;

DELIMITER ||
CREATE TRIGGER pageHistory_trigger_update AFTER UPDATE ON page FOR EACH ROW
BEGIN
    INSERT INTO page_history (page_id, version, parent, name, subtitle, description, tooltip, icon, alias, content_collapse, content_position, show_content_description, show_table_of_content, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
    VALUES (NEW.id, (SELECT max(x.version)+1 FROM (SELECT * FROM page_history) x WHERE x.page_id = NEW.id), NEW.parent, NEW.name, NEW.subtitle, NEW.description, NEW.tooltip, NEW.icon, NEW.alias, NEW.content_collapse, NEW.content_position, NEW.show_content_description, NEW.show_table_of_content, NEW.sorting, NEW.time_start, NEW.time_end, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.active, NEW.deleted);
END
||
DELIMITER ;

-- trigger function for file table
DELIMITER ||
CREATE TRIGGER fileHistory_trigger_insert AFTER INSERT ON file FOR EACH ROW
BEGIN
    INSERT INTO file_history (file_id, version, parent, hash, name, size, type, downloads, created_by, create_date, changed_by, change_date, deleted)
    VALUES (NEW.id, 1, NEW.parent, NEW.hash, NEW.name, NEW.size, NEW.type, NEW.downloads, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.deleted);
END
||
DELIMITER ;

DELIMITER ||
CREATE TRIGGER fileHistory_trigger_update AFTER UPDATE ON file FOR EACH ROW
BEGIN
    INSERT INTO file_history (file_id, version, parent, hash, name, size, type, downloads, created_by, create_date, changed_by, change_date, deleted)
    VALUES (NEW.id, (SELECT max(x.version)+1 FROM (SELECT * FROM file_history) x WHERE x.file_id = NEW.id), NEW.parent, NEW.hash, NEW.name, NEW.size, NEW.type, NEW.downloads, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.deleted);
END
||
DELIMITER ;

-- indexes

-- data

-- guest user
INSERT INTO `user` (id, first_name, last_name, login, email, `password`, language, timezone, active, created_by, create_date, changed_by, change_date, deleted)
VALUES (0, 'knowledgeroot', 'guest', 'guest', 'guest@localhost', 'XXX', 'en_US', 'Europe/Berlin', true, 0, '20121001 22:00:00', 0, '20121001 22:00:00', false);
UPDATE `user` SET id=0 WHERE login='guest';

COMMIT;
