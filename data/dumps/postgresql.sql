/**
 * knowledgeroot2 dump for postgresql
 */

BEGIN;

/* sequences */
CREATE SEQUENCE seq_acl;
CREATE SEQUENCE seq_page;
CREATE SEQUENCE seq_page_history;
CREATE SEQUENCE seq_content;
CREATE SEQUENCE seq_content_history;
CREATE SEQUENCE seq_file;
CREATE SEQUENCE seq_file_history;
CREATE SEQUENCE seq_user;
CREATE SEQUENCE seq_group;
CREATE SEQUENCE seq_group_member;
CREATE SEQUENCE seq_tag;
CREATE SEQUENCE seq_tag_content;

/* types */
CREATE TYPE acl_action as ENUM ('new','edit','delete','show','new_content','permission','print','export');
CREATE TYPE acl_right as ENUM ('allow', 'deny');
CREATE TYPE content_position as ENUM ('start', 'end');
CREATE TYPE group_member_type as ENUM ('user', 'group');

/* tables */

/* table: user */
CREATE TABLE "user" (
  id integer PRIMARY KEY DEFAULT nextval('seq_user') NOT NULL,
  first_name varchar(255) DEFAULT '' NOT NULL,
  last_name varchar(255) DEFAULT '' NOT NULL,
  login varchar(255) DEFAULT '' NOT NULL,
  email varchar(255) DEFAULT '' NOT NULL,
  password varchar(255) DEFAULT '' NOT NULL,
  language varchar(10) DEFAULT '' NOT NULL,
  timezone varchar(50) DEFAULT 'UTC' NOT NULL,
  time_start timestamp without time zone NULL,
  time_end timestamp without time zone NULL,
  active boolean DEFAULT false NOT NULL,
  created_by integer NOT NULL,
  create_date timestamp without time zone NOT NULL,
  changed_by integer NOT NULL,
  change_date timestamp without time zone NOT NULL,
  deleted boolean DEFAULT false NOT NULL
);

/* table: group */
CREATE TABLE "group" (
  id integer PRIMARY KEY DEFAULT nextval('seq_group') NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  description text DEFAULT '' NOT NULL,
  time_start timestamp without time zone NULL,
  time_end timestamp without time zone NULL,
  active boolean DEFAULT false NOT NULL,
  created_by integer NOT NULL,
  create_date timestamp without time zone NOT NULL,
  changed_by integer NOT NULL,
  change_date timestamp without time zone NOT NULL,
  deleted boolean DEFAULT false NOT NULL
);

/* table: user_group */
CREATE TABLE group_member (
  id integer PRIMARY KEY DEFAULT nextval('seq_group_member') NOT NULL,
  group_id integer NOT NULL,
  member_id integer NOT NULL,
  member_type group_member_type NOT NULL
);

/* table: acl */
CREATE TABLE acl (
  id integer PRIMARY KEY DEFAULT nextval('seq_acl') NOT NULL,
  role_id varchar(255) NOT NULL,
  resource varchar(255) NOT NULL,
  action acl_action NOT NULL,
  "right" acl_right NOT NULL
);

/* table: page */
CREATE TABLE page (
  id integer PRIMARY KEY DEFAULT nextval('seq_page') NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  subtitle varchar(255) DEFAULT '' NOT NULL,
  description text DEFAULT '' NOT NULL,
  tooltip varchar(255) DEFAULT '' NOT NULL,
  icon varchar(255) DEFAULT '' NOT NULL,
  alias varchar(255) DEFAULT '' NOT NULL,
  content_collapse boolean DEFAULT TRUE NOT NULL,
  content_position content_position DEFAULT 'end' NOT NULL,
  show_content_description boolean DEFAULT FALSE NOT NULL,
  show_table_of_content boolean DEFAULT FALSE NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start timestamp without time zone NULL,
  time_end timestamp without time zone NULL,
  created_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  create_date timestamp without time zone NOT NULL,
  changed_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  change_date timestamp without time zone NOT NULL,
  active boolean DEFAULT false NOT NULL,
  deleted boolean DEFAULT false NOT NULL
);

/* table: page */
CREATE TABLE page_history (
  id integer PRIMARY KEY DEFAULT nextval('seq_page_history') NOT NULL,
  page_id integer DEFAULT 0 NOT NULL REFERENCES page (id) ON DELETE CASCADE,
  version integer DEFAULT 0 NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  subtitle varchar(255) DEFAULT '' NOT NULL,
  description text DEFAULT '' NOT NULL,
  tooltip varchar(255) DEFAULT '' NOT NULL,
  icon varchar(255) DEFAULT '' NOT NULL,
  alias varchar(255) DEFAULT '' NOT NULL,
  content_collapse boolean DEFAULT TRUE NOT NULL,
  content_position content_position DEFAULT 'end' NOT NULL,
  show_content_description boolean DEFAULT FALSE NOT NULL,
  show_table_of_content boolean DEFAULT FALSE NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start timestamp without time zone NULL,
  time_end timestamp without time zone NULL,
  created_by integer NOT NULL,
  create_date timestamp without time zone NOT NULL,
  changed_by integer NOT NULL,
  change_date timestamp without time zone NOT NULL,
  active boolean DEFAULT false NOT NULL,
  deleted boolean DEFAULT false NOT NULL
);

/* table: content */
CREATE TABLE content (
  id integer PRIMARY KEY DEFAULT nextval('seq_content') NOT NULL,
  parent integer DEFAULT 0 NOT NULL REFERENCES page (id) ON DELETE CASCADE,
  name varchar(255) DEFAULT '' NOT NULL,
  content text DEFAULT '' NOT NULL,
  type varchar(255) DEFAULT 'text' NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start timestamp without time zone NULL,
  time_end timestamp without time zone NULL,
  created_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  create_date timestamp without time zone NOT NULL,
  changed_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  change_date timestamp without time zone NOT NULL,
  active boolean DEFAULT false NOT NULL,
  deleted boolean DEFAULT false NOT NULL
);

/* table: content */
CREATE TABLE content_history (
  id integer PRIMARY KEY DEFAULT nextval('seq_content_history') NOT NULL,
  content_id integer DEFAULT 0 NOT NULL REFERENCES content (id) ON DELETE CASCADE,
  version integer DEFAULT 0 NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  content text DEFAULT '' NOT NULL,
  type varchar(255) DEFAULT 'text' NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start timestamp without time zone NULL,
  time_end timestamp without time zone NULL,
  created_by integer NOT NULL,
  create_date timestamp without time zone NOT NULL,
  changed_by integer NOT NULL,
  change_date timestamp without time zone NOT NULL,
  active boolean DEFAULT false NOT NULL,
  deleted boolean DEFAULT false NOT NULL
);

/* table: file */
CREATE TABLE file (
  id integer PRIMARY KEY DEFAULT nextval('seq_file') NOT NULL,
  parent integer DEFAULT 0 NOT NULL REFERENCES content (id) ON DELETE CASCADE,
  hash varchar(32) DEFAULT '' NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  size integer DEFAULT 0 NOT NULL,
  type varchar(255) DEFAULT 'application/octet-stream',
  downloads integer DEFAULT 0 NOT NULL,
  created_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  create_date timestamp without time zone NOT NULL,
  changed_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  change_date timestamp without time zone NOT NULL,
  deleted boolean DEFAULT false NOT NULL
);

/* table: file_history */
CREATE TABLE file_history (
  id integer PRIMARY KEY DEFAULT nextval('seq_file') NOT NULL,
  file_id integer DEFAULT 0 NOT NULL REFERENCES file (id) ON DELETE CASCADE,
  version integer DEFAULT 0 NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  hash varchar(32) DEFAULT '' NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  size integer DEFAULT 0 NOT NULL,
  type varchar(255) DEFAULT 'application/octet-stream',
  downloads integer DEFAULT 0 NOT NULL,
  created_by integer NOT NULL,
  create_date timestamp without time zone NOT NULL,
  changed_by integer NOT NULL,
  change_date timestamp without time zone NOT NULL,
  deleted boolean DEFAULT false NOT NULL
);

/* tags */

/* table: tag */
CREATE TABLE tag (
  id integer PRIMARY KEY DEFAULT nextval('seq_tag') NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL
);

/* table: tag_content */
CREATE TABLE tag_content (
  id integer PRIMARY KEY DEFAULT nextval('seq_tag_content') NOT NULL,
  tag_id integer NOT NULL REFERENCES tag (id) ON DELETE CASCADE,
  content_id integer NOT NULL REFERENCES content (id) ON DELETE CASCADE
);

/* content ratings */

/* functions */
/* trigger function for content table */
CREATE OR REPLACE FUNCTION contentHistory()
RETURNS TRIGGER AS
$$
    DECLARE contentVersion integer;
BEGIN

    CASE TG_OP
	WHEN 'INSERT' THEN
	    contentVersion = 1;
	WHEN 'UPDATE' THEN
	    contentVersion = (SELECT max(version)+1 FROM content_history WHERE content_id = NEW.id);
    END CASE;

    INSERT INTO content_history (content_id, version, parent, name, content, type, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
    VALUES (NEW.id, contentVersion, NEW.parent, NEW.name, NEW.content, NEW.type, NEW.sorting, NEW.time_start, NEW.time_end, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.active, NEW.deleted);

    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

/* trigger function for page table */
CREATE OR REPLACE FUNCTION pageHistory()
RETURNS TRIGGER AS
$$
    DECLARE pageVersion integer;
BEGIN

    CASE TG_OP
	WHEN 'INSERT' THEN
	    pageVersion = 1;
	WHEN 'UPDATE' THEN
	    pageVersion = (SELECT max(version)+1 FROM page_history WHERE page_id = NEW.id);
    END CASE;

    INSERT INTO page_history (page_id, version, parent, name, subtitle, description, tooltip, icon, alias, content_collapse, content_position, show_content_description, show_table_of_content, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
    VALUES (NEW.id, pageVersion, NEW.parent, NEW.name, NEW.subtitle, NEW.description, NEW.tooltip, NEW.icon, NEW.alias, NEW.content_collapse, NEW.content_position, NEW.show_content_description, NEW.show_table_of_content, NEW.sorting, NEW.time_start, NEW.time_end, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.active, NEW.deleted);

    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

/* trigger function for file table */
CREATE OR REPLACE FUNCTION fileHistory()
RETURNS TRIGGER AS
$$
    DECLARE fileVersion integer;
BEGIN

    CASE TG_OP
	WHEN 'INSERT' THEN
	    fileVersion = 1;
	WHEN 'UPDATE' THEN
	    fileVersion = (SELECT max(version)+1 FROM file_history WHERE file_id = NEW.id);
    END CASE;

    INSERT INTO file_history (file_id, version, parent, hash, name, size, type, downloads, created_by, create_date, changed_by, change_date, deleted)
    VALUES (NEW.id, fileVersion, NEW.parent, NEW.hash, NEW.name, NEW.size, NEW.type, NEW.downloads, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.deleted);

    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

/* trigger */
CREATE TRIGGER contentHistory_trigger AFTER INSERT OR UPDATE ON content FOR EACH ROW EXECUTE PROCEDURE contentHistory();
CREATE TRIGGER pageHistory_trigger AFTER INSERT OR UPDATE ON page FOR EACH ROW EXECUTE PROCEDURE pageHistory();
CREATE TRIGGER fileHistory_trigger AFTER INSERT OR UPDATE ON file FOR EACH ROW EXECUTE PROCEDURE fileHistory();

/* indexes */

/* data */

/* guest user */
INSERT INTO "user" (id, first_name, last_name, login, email, password, language, timezone, active, created_by, create_date, changed_by, change_date, deleted)
VALUES (0, 'knowledgeroot', 'guest', 'guest', 'guest@localhost', 'XXX', 'en_US', 'Europe/Berlin', true, 0, '20121001 22:00:00', 0, '20121001 22:00:00', false);

COMMIT;