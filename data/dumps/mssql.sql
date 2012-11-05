-- knowledgeroot2 dump for mssql
GO

BEGIN TRANSACTION;

GO

-- tables

-- table: user
CREATE TABLE [user] (
  id int IDENTITY(1,1) NOT NULL,
  first_name varchar(255) DEFAULT '' NOT NULL,
  last_name varchar(255) DEFAULT '' NOT NULL,
  login varchar(255) DEFAULT '' NOT NULL,
  email varchar(255) DEFAULT '' NOT NULL,
  password varchar(255) DEFAULT '' NOT NULL,
  language varchar(10) DEFAULT '' NOT NULL,
  timezone varchar(50) DEFAULT 'UTC' NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  active bit DEFAULT 0 NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted bit DEFAULT 0 NOT NULL,
  CONSTRAINT [PK_user_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

GO

-- table: group
CREATE TABLE [group] (
  id int IDENTITY(1,1) NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  description text DEFAULT '' NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  active bit DEFAULT 0 NOT NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  deleted bit DEFAULT 0 NOT NULL,
  CONSTRAINT [PK_group_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

GO

-- table: user_group
CREATE TABLE group_member (
  id int IDENTITY(1,1) NOT NULL,
  group_id integer NOT NULL,
  member_id integer NOT NULL,
  member_type varchar(5) NOT NULL CHECK (member_type IN('user', 'group')),
  CONSTRAINT [PK_group_member_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

GO

-- table: acl
CREATE TABLE acl (
  id int IDENTITY(1,1) NOT NULL,
  role_id varchar(255) NOT NULL,
  resource varchar(255) NOT NULL,
  action varchar(6) NOT NULL CHECK (action IN('new','edit','delete','show','print','export')),
  [right] varchar(5) NOT NULL CHECK ([right] IN('allow', 'deny')),
  CONSTRAINT [PK_acl_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

GO

-- table: page
CREATE TABLE page (
  id int IDENTITY(1,1) NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  tooltip varchar(255) DEFAULT '' NOT NULL,
  icon varchar(255) DEFAULT '' NOT NULL,
  alias varchar(255) DEFAULT '' NOT NULL,
  content_collapse bit DEFAULT 1 NOT NULL,
  content_position varchar(5) DEFAULT 'end' NOT NULL CHECK (content_position IN('start', 'end')),
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  active bit DEFAULT 0 NOT NULL,
  deleted bit DEFAULT 0 NOT NULL,
  FOREIGN KEY (created_by) REFERENCES [user] (id) ON DELETE NO ACTION,
  FOREIGN KEY (changed_by) REFERENCES [user] (id) ON DELETE NO ACTION,
  CONSTRAINT [PK_page_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

GO

-- table: page
CREATE TABLE page_history (
  id int IDENTITY(1,1) NOT NULL,
  page_id integer DEFAULT 0 NOT NULL,
  version integer DEFAULT 0 NOT NULL,
  parent integer DEFAULT 0 NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  tooltip varchar(255) DEFAULT '' NOT NULL,
  icon varchar(255) DEFAULT '' NOT NULL,
  alias varchar(255) DEFAULT '' NOT NULL,
  content_collapse bit DEFAULT 1 NOT NULL,
  content_position varchar(5) DEFAULT 'end' NOT NULL CHECK (content_position IN('start', 'end')),
  sorting integer DEFAULT 0 NOT NULL,
  time_start datetime NULL,
  time_end datetime NULL,
  created_by integer NOT NULL,
  create_date datetime NOT NULL,
  changed_by integer NOT NULL,
  change_date datetime NOT NULL,
  active bit DEFAULT 0 NOT NULL,
  deleted bit DEFAULT 0 NOT NULL,
  FOREIGN KEY (page_id) REFERENCES page (id) ON DELETE CASCADE,
  CONSTRAINT [PK_page_history_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

GO

-- table: content
CREATE TABLE content (
  id int IDENTITY(1,1) NOT NULL,
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
  active bit DEFAULT 0 NOT NULL,
  deleted bit DEFAULT 0 NOT NULL,
  FOREIGN KEY (parent) REFERENCES page (id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES [user] (id) ON DELETE NO ACTION,
  FOREIGN KEY (changed_by) REFERENCES [user] (id) ON DELETE NO ACTION,
  CONSTRAINT [PK_content_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

GO

-- table: content
CREATE TABLE content_history (
  id int IDENTITY(1,1) NOT NULL,
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
  active bit DEFAULT 0 NOT NULL,
  deleted bit DEFAULT 0 NOT NULL,
  FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE,
  CONSTRAINT [PK_content_history_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

GO

-- table: file
CREATE TABLE [file] (
  id int IDENTITY(1,1) NOT NULL,
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
  deleted bit DEFAULT 0 NOT NULL,
  FOREIGN KEY (parent) REFERENCES content (id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES [user] (id) ON DELETE NO ACTION,
  FOREIGN KEY (changed_by) REFERENCES [user] (id) ON DELETE NO ACTION,
  CONSTRAINT [PK_file_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

GO

-- table: file_history
CREATE TABLE file_history (
  id int IDENTITY(1,1) NOT NULL,
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
  deleted bit DEFAULT 0 NOT NULL,
  FOREIGN KEY (file_id)  REFERENCES [file] (id) ON DELETE CASCADE,
  CONSTRAINT [PK_file_history_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

GO

-- tags

-- table: tag
CREATE TABLE tag (
  id int IDENTITY(1,1) NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  CONSTRAINT [PK_tag_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

GO

-- table: tag_content
CREATE TABLE tag_content (
  id int IDENTITY(1,1) NOT NULL,
  tag_id integer NOT NULL,
  content_id integer NOT NULL,
  FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE,
  FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE,
  CONSTRAINT [PK_tag_content_id] PRIMARY KEY CLUSTERED (
    id ASC
  )WITH (IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

-- content ratings

GO

-- functions
-- trigger function for content table
CREATE TRIGGER contentHistory_trigger_insert ON content AFTER INSERT AS
BEGIN
    INSERT INTO content_history ([version], content_id, [parent], [name], [content], [type], sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
	SELECT 1 as [version], c.* from inserted i, content c WHERE i.id=c.id
END

GO

CREATE TRIGGER contentHistory_trigger_update ON content AFTER UPDATE AS
	DECLARE @version INT
BEGIN
	SELECT  @version = (max(h.version)+1) FROM inserted i, content_history h WHERE i.id=h.content_id

	INSERT INTO content_history ([version], content_id, [parent], [name], [content], [type], sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
	SELECT @version as [version], c.* from inserted i, content c WHERE i.id=c.id
END

GO

-- trigger function for page table
CREATE TRIGGER pageHistory_trigger_insert ON page AFTER INSERT AS
BEGIN
    INSERT INTO page_history ([version], page_id, parent, name, tooltip, icon, alias, content_collapse, content_position, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
	SELECT 1 as [version], p.* from inserted i, page p WHERE i.id=p.id
END

GO

CREATE TRIGGER pageHistory_trigger_update ON page AFTER UPDATE AS
	DECLARE @version INT
BEGIN
	SELECT  @version = (max(h.version)+1) FROM inserted i, page_history h WHERE i.id=h.page_id

    INSERT INTO page_history (page_id, [version], parent, name, tooltip, icon, alias, content_collapse, content_position, sorting, time_start, time_end, created_by, create_date, changed_by, change_date, active, deleted)
	SELECT @version as [version], p.* from inserted i, page p WHERE i.id=p.id
END

GO

-- trigger function for file table
CREATE TRIGGER fileHistory_trigger_insert ON [file] AFTER INSERT AS
BEGIN
    INSERT INTO file_history ([version], file_id, parent, hash, file_name, file_size, file_type, downloads, created_by, create_date, changed_by, change_date, deleted)
	SELECT 1 as [version], f.* from inserted i, [file] f WHERE i.id=f.id
END

GO

CREATE TRIGGER fileHistory_trigger_update ON [file] AFTER UPDATE AS
	DECLARE @version INT
BEGIN
	SELECT  @version = (max(h.version)+1) FROM inserted i, file_history h WHERE i.id=h.file_id

    INSERT INTO file_history (file_id, [version], parent, hash, file_name, file_size, file_type, downloads, created_by, create_date, changed_by, change_date, deleted)
    SELECT @version as [version], f.* from inserted i, [file] f WHERE i.id=f.id
END

GO

-- indexes

-- data

-- guest user
SET IDENTITY_INSERT [user] ON

GO

INSERT INTO [user] (id, first_name, last_name, login, email, [password], language, timezone, active, created_by, create_date, changed_by, change_date, deleted)
VALUES (0, 'knowledgeroot', 'guest', 'guest', 'guest@localhost', 'XXX', 'en_US', 'Europe/Berlin', 1, 0, '20121001 22:00:00', 0, '20121001 22:00:00', 0);

GO

SET IDENTITY_INSERT [user] OFF

GO
COMMIT;
GO