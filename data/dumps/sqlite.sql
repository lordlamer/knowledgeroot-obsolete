/**
 * knowledgeroot2 dump for sqlite
 */

/* tables */

/* table: user */
CREATE TABLE "user" (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  first_name text DEFAULT '' NOT NULL,
  last_name text DEFAULT '' NOT NULL,
  login text DEFAULT '' NOT NULL,
  email text DEFAULT '' NOT NULL,
  password text DEFAULT '' NOT NULL,
  language text DEFAULT '' NOT NULL,
  timezone text DEFAULT 'UTC' NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  active integer DEFAULT 0 NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted integer DEFAULT 0 NOT NULL
);

/* table: group */
CREATE TABLE "group" (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  name text DEFAULT '' NOT NULL,
  description text DEFAULT '' NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  active integer DEFAULT 0 NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted integer DEFAULT 0 NOT NULL
);

/* table: user_group */
CREATE TABLE group_member (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  group_id integer NOT NULL,
  member_id integer NOT NULL,
  member_type text NOT NULL
);

/* table: acl */
CREATE TABLE acl (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  role_id text NOT NULL,
  resource text NOT NULL,
  action text NOT NULL,
  "right" text NOT NULL
);

/* table: page */
CREATE TABLE page (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  name text DEFAULT '' NOT NULL,
  tooltip text DEFAULT '' NOT NULL,
  icon text DEFAULT '' NOT NULL,
  alias text DEFAULT '' NOT NULL,
  content_collapse integer DEFAULT TRUE NOT NULL,
  content_position text DEFAULT 'end' NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  change_date datetime NOT NULL,
  active integer DEFAULT 0 NOT NULL,
  deleted integer DEFAULT 0 NOT NULL
);

/* table: page */
CREATE TABLE page_history (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  page_id integer DEFAULT 0 NOT NULL REFERENCES page (id) ON DELETE CASCADE,
  version integer DEFAULT 0 NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  name text DEFAULT '' NOT NULL,
  tooltip text DEFAULT '' NOT NULL,
  icon text DEFAULT '' NOT NULL,
  alias text DEFAULT '' NOT NULL,
  content_collapse integer DEFAULT TRUE NOT NULL,
  content_position text DEFAULT 'end' NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  active integer DEFAULT 0 NOT NULL,
  deleted integer DEFAULT 0 NOT NULL
);

/* table: content */
CREATE TABLE content (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  parent integer DEFAULT 0 NOT NULL REFERENCES page (id) ON DELETE CASCADE,
  name text DEFAULT '' NOT NULL,
  content text DEFAULT '' NOT NULL,
  type text DEFAULT 'text' NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  change_date datetime NOT NULL,
  active integer DEFAULT 0 NOT NULL,
  deleted integer DEFAULT 0 NOT NULL
);

/* table: content */
CREATE TABLE content_history (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  content_id integer DEFAULT 0 NOT NULL REFERENCES content (id) ON DELETE CASCADE,
  version integer DEFAULT 0 NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  name text DEFAULT '' NOT NULL,
  content text DEFAULT '' NOT NULL,
  type text DEFAULT 'text' NOT NULL,
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  active integer DEFAULT 0 NOT NULL,
  deleted integer DEFAULT 0 NOT NULL
);

/* table: file */
CREATE TABLE file (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  parent integer DEFAULT 0 NOT NULL REFERENCES content (id) ON DELETE CASCADE,
  hash text DEFAULT '' NOT NULL,
  name text DEFAULT '' NOT NULL,
  size integer DEFAULT 0 NOT NULL,
  type text DEFAULT 'application/octet-stream',
  downloads integer DEFAULT 0 NOT NULL,
  created_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT,
  change_date datetime NOT NULL,
  deleted integer DEFAULT 0 NOT NULL
);

/* table: file_history */
CREATE TABLE file_history (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  file_id integer DEFAULT 0 NOT NULL REFERENCES file (id) ON DELETE CASCADE,
  version integer DEFAULT 0 NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  hash text DEFAULT '' NOT NULL,
  name text DEFAULT '' NOT NULL,
  size integer DEFAULT 0 NOT NULL,
  type text DEFAULT 'application/octet-stream',
  downloads integer DEFAULT 0 NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted integer DEFAULT 0 NOT NULL
);

/* tags */

/* table: tag */
CREATE TABLE tag (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  name text DEFAULT '' NOT NULL
);

/* table: tag_content */
CREATE TABLE tag_content (
  id integer PRIMARY KEY DEFAULT auto_incrementing NOT NULL,
  tag_id integer NOT NULL REFERENCES tag (id) ON DELETE CASCADE,
  content_id integer NOT NULL REFERENCES content (id) ON DELETE CASCADE
);

/* content ratings */

-- functions
-- trigger function for content table
CREATE TRIGGER contentHistory_trigger_insert AFTER INSERT ON content FOR EACH ROW
BEGIN
    INSERT INTO content_history (content_id, `version`, `parent`, `name`, `content`, `type`, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
    VALUES (NEW.id, 1, NEW.parent, NEW.name, NEW.content, NEW.type, NEW.sorting, NEW.time_start, NEW.time_end, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.active, NEW.deleted);
END;

CREATE TRIGGER contentHistory_trigger_update AFTER UPDATE ON content FOR EACH ROW
BEGIN
    INSERT INTO content_history (content_id, `version`, `parent`, `name`, `content`, `type`, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
    VALUES (NEW.id, (SELECT max(x.version)+1 FROM (SELECT * FROM content_history) x WHERE x.content_id = NEW.id), NEW.parent, NEW.name, NEW.content, NEW.type, NEW.sorting, NEW.time_start, NEW.time_end, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.active, NEW.deleted);
END;

-- trigger function for page table
CREATE TRIGGER pageHistory_trigger_insert AFTER INSERT ON page FOR EACH ROW
BEGIN
    INSERT INTO page_history (page_id, version, parent, name, tooltip, icon, alias, content_collapse, content_position, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
    VALUES (NEW.id, 1, NEW.parent, NEW.name, NEW.tooltip, NEW.icon, NEW.alias, NEW.content_collapse, NEW.content_position, NEW.sorting, NEW.time_start, NEW.time_end, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.active, NEW.deleted);
END;

CREATE TRIGGER pageHistory_trigger_update AFTER UPDATE ON page FOR EACH ROW
BEGIN
    INSERT INTO page_history (page_id, version, parent, name, tooltip, icon, alias, content_collapse, content_position, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
    VALUES (NEW.id, (SELECT max(x.version)+1 FROM (SELECT * FROM page_history) x WHERE x.page_id = NEW.id), NEW.parent, NEW.name, NEW.tooltip, NEW.icon, NEW.alias, NEW.content_collapse, NEW.content_position, NEW.sorting, NEW.time_start, NEW.time_end, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.active, NEW.deleted);
END;


-- trigger function for file table
CREATE TRIGGER fileHistory_trigger_insert AFTER INSERT ON file FOR EACH ROW
BEGIN
    INSERT INTO file_history (file_id, version, parent, hash, file_name, file_size, file_type, downloads, created_by, create_date, changed_by, change_date, deleted)
    VALUES (NEW.id, 1, NEW.parent, NEW.hash, NEW.name, NEW.size, NEW.type, NEW.downloads, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.deleted);
END;

CREATE TRIGGER fileHistory_trigger_update AFTER UPDATE ON file FOR EACH ROW
BEGIN
    INSERT INTO file_history (file_id, version, parent, hash, file_name, file_size, file_type, downloads, created_by, create_date, changed_by, change_date, deleted)
    VALUES (NEW.id, (SELECT max(x.version)+1 FROM (SELECT * FROM file_history) x WHERE x.file_id = NEW.id), NEW.parent, NEW.hash, NEW.name, NEW.size, NEW.type, NEW.downloads, NEW.created_by, NEW.create_date, NEW.changed_by, NEW.change_date, NEW.deleted);
END;

/* indexes */

/* data */

/* guest user */
INSERT INTO "user" (id, first_name, last_name, login, email, password, language, timezone, active, created_by, create_date, changed_by, change_date, deleted)
VALUES (0, 'knowledgeroot', 'guest', 'guest', 'guest@localhost', 'XXX', 'en_US', 'Europe/Berlin', 1, 0, '20121001 22:00:00', 0, '20121001 22:00:00', 0);