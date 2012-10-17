-- Dump for Postgresql with demo data

-- user
INSERT INTO "user" (first_name, last_name, login, email, password, language, timezone, active, created_by, create_date, changed_by, change_date, deleted)
VALUES ('knowledgeroot', 'admin', 'admin', 'admin@localhost', '$5$100$c4d60033$3997ea4e9be21d976ae54355c29a27f56a776607e8c3e7ad99a120d14a5ae9d69730e08595d0a0576bfd248d30c52aa6f9ed8e1581d307d469be3fe960325814', 'en_US', 'Europe/Berlin', 1, 0, '20121001 22:00:00', 0, '20121001 22:00:00', 0);

-- group
INSERT INTO "group" (name, description, active, created_by, create_date, changed_by, change_date, deleted)
VALUES ('admin', 'administrators', 1, 0, '20121001 22:00:00', 0, '20121001 22:00:00', 0);

INSERT INTO "group" (name, description, active, created_by, create_date, changed_by, change_date, deleted)
VALUES ('users', 'users', 1, 0, '20121001 22:00:00', 0, '20121001 22:00:00', 0);

INSERT INTO "group" (name, description, active, created_by, create_date, changed_by, change_date, deleted)
VALUES ('others', 'others', 1, 0, '20121001 22:00:00', 0, '20121001 22:00:00', 0);

-- group members
INSERT INTO group_member (group_id, member_id, member_type)
VALUES ((SELECT id FROM "group" where name='admin'), (SELECT id FROM "user" where login='admin'), 'user');

-- pages
INSERT INTO page (parent, name, active, created_by, create_date, changed_by, change_date, deleted)
VALUES (0, 'first', 1, (SELECT id FROM "user" where login='admin'), '20121001 22:00:00', (SELECT id FROM "user" where login='admin'), '20121001 22:00:00', 0);

INSERT INTO page (parent, name, active, created_by, create_date, changed_by, change_date, deleted)
VALUES ((SELECT id FROM page where name='first'), 'second', 1, (SELECT id FROM "user" where login='admin'), '20121001 22:00:00', (SELECT id FROM "user" where login='admin'), '20121001 22:00:00', 0);

INSERT INTO page (parent, name, active, created_by, create_date, changed_by, change_date, deleted)
VALUES ((SELECT id FROM page where name='second'), 'third', 1, (SELECT id FROM "user" where login='admin'), '20121001 22:00:00', (SELECT id FROM "user" where login='admin'), '20121001 22:00:00', 0);

-- content
INSERT INTO content (parent, name, content, active, created_by, create_date, changed_by, change_date, deleted)
VALUES ((SELECT id FROM page where name='first'), 'first content', 'first content', 1, (SELECT id FROM "user" where login='admin'), '20121001 22:00:00', (SELECT id FROM "user" where login='admin'), '20121001 22:00:00', 0);

-- files