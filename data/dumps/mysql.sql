/**
 * knowledgeroot2 dump for postgresql
 */

BEGIN;

/* tables */

/* table: user */
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
  active boolean DEFAULT false NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted boolean DEFAULT false NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* table: group */
CREATE TABLE `group` (
  id integer NOT NULL AUTO_INCREMENT,
  name varchar(255) DEFAULT '' NOT NULL,
  description text DEFAULT '' NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  active boolean DEFAULT false NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted boolean DEFAULT false NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* table: user_group */
CREATE TABLE group_member (
  id integer NOT NULL AUTO_INCREMENT,
  group_id integer NOT NULL,
  member_id integer NOT NULL,
  member_type ENUM('user', 'group') NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* table: acl */
CREATE TABLE acl (
  id integer NOT NULL AUTO_INCREMENT,
  role_id varchar(255) NOT NULL,
  resource varchar(255) NOT NULL,
  action ENUM('new','edit','delete','show','print','export') NOT NULL,
  `right` ENUM('allow', 'deny') NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* table: page */
CREATE TABLE page (
  id integer NOT NULL AUTO_INCREMENT,
  parent integer DEFAULT 0 NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  tooltip varchar(255) DEFAULT '' NOT NULL,
  icon varchar(255) DEFAULT '' NOT NULL,
  alias varchar(255) DEFAULT '' NOT NULL,
  content_collapse boolean DEFAULT TRUE NOT NULL,
  content_position ENUM('start', 'end') DEFAULT 'end' NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  active boolean DEFAULT false NOT NULL,
  deleted boolean DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE RESTRICT,
  FOREIGN KEY (changed_by) REFERENCES `user` (id) ON DELETE RESTRICT
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* table: page */
CREATE TABLE page_history (
  id integer NOT NULL AUTO_INCREMENT,
  page_id integer DEFAULT 0 NOT NULL,
  version integer DEFAULT 0 NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  tooltip varchar(255) DEFAULT '' NOT NULL,
  icon varchar(255) DEFAULT '' NOT NULL,
  alias varchar(255) DEFAULT '' NOT NULL,
  content_collapse boolean DEFAULT TRUE NOT NULL,
  content_position ENUM('start', 'end') DEFAULT 'end' NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  active boolean DEFAULT false NOT NULL,
  deleted boolean DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (page_id) REFERENCES page (id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* table: content */
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
  active boolean DEFAULT false NOT NULL,
  deleted boolean DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (parent) REFERENCES page (id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE RESTRICT,
  FOREIGN KEY (changed_by) REFERENCES `user` (id) ON DELETE RESTRICT
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* table: content */
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
  active boolean DEFAULT false NOT NULL,
  deleted boolean DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* table: file */
CREATE TABLE file (
  id integer NOT NULL AUTO_INCREMENT,
  content_id integer DEFAULT 0 NOT NULL,
  file_name varchar(255) DEFAULT '' NOT NULL,
  file_size integer DEFAULT 0 NOT NULL,
  file_type varchar(255) DEFAULT 'application/octet-stream',
  downloads integer DEFAULT 0 NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted boolean DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE RESTRICT,
  FOREIGN KEY (changed_by) REFERENCES `user` (id) ON DELETE RESTRICT
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* table: file_history */
CREATE TABLE file_history (
  id integer NOT NULL AUTO_INCREMENT,
  file_id integer DEFAULT 0 NOT NULL,
  version integer DEFAULT 0 NOT NULL,
  content_id integer DEFAULT 0 NOT NULL,
  file_name varchar(255) DEFAULT '' NOT NULL,
  file_size integer DEFAULT 0 NOT NULL,
  file_type varchar(255) DEFAULT 'application/octet-stream',
  downloads integer DEFAULT 0 NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted boolean DEFAULT false NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (file_id)  REFERENCES file (id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* tags */

/* table: tag */
CREATE TABLE tag (
  id integer NOT NULL AUTO_INCREMENT,
  name varchar(255) DEFAULT '' NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* table: tag_content */
CREATE TABLE tag_content (
  id integer NOT NULL AUTO_INCREMENT,
  tag_id integer NOT NULL,
  content_id integer NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE,
  FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* content ratings */

/* functions */
/* trigger function for content table */

/* trigger function for page table */

/* trigger function for file table */

/* trigger */
CREATE TRIGGER contentHistory_trigger AFTER INSERT OR UPDATE ON content FOR EACH ROW EXECUTE PROCEDURE contentHistory();
CREATE TRIGGER pageHistory_trigger AFTER INSERT OR UPDATE ON page FOR EACH ROW EXECUTE PROCEDURE pageHistory();
CREATE TRIGGER fileHistory_trigger AFTER INSERT OR UPDATE ON file FOR EACH ROW EXECUTE PROCEDURE fileHistory();

/* indexes */

COMMIT;