use prism;
-- BEFORE:
-- MariaDB [prism]> describe projects;
-- +--------------+--------------+------+-----+---------+----------------+
-- | Field        | Type         | Null | Key | Default | Extra          |
-- +--------------+--------------+------+-----+---------+----------------+
-- | id           | int(11)      | NO   | PRI | NULL    | auto_increment |
-- | description  | text         | YES  |     | NULL    |                |
-- | posterId     | varchar(255) | YES  |     | NULL    |                |
-- | staff        | int(11)      | YES  |     | NULL    |                |
-- | stack        | text         | YES  |     | NULL    |                |
-- | breakdown    | text         | YES  |     | NULL    |                |
-- | companyId    | text         | YES  |     | NULL    |                |
-- | created_at   | text         | YES  |     | NULL    |                |
-- | updated_at   | text         | YES  |     | NULL    |                |
-- | currency     | text         | YES  |     | NULL    |                |
-- | methodology  | text         | YES  |     | NULL    |                |
-- | stage        | text         | YES  |     | NULL    |                |
-- | name         | text         | YES  |     | NULL    |                |
-- | contractType | text         | YES  |     | NULL    |                |
-- +--------------+--------------+------+-----+---------+----------------+

alter table projects add column logo text;
alter table projects add column projectType text;
alter table projects add column workload text;
alter table projects add column requiredSkills text;
alter table projects add column perks text;

-- AFTER:
-- MariaDB [prism]> describe projects;
-- +----------------+--------------+------+-----+---------+----------------+
-- | Field          | Type         | Null | Key | Default | Extra          |
-- +----------------+--------------+------+-----+---------+----------------+
-- | id             | int(11)      | NO   | PRI | NULL    | auto_increment |
-- | description    | text         | YES  |     | NULL    |                |
-- | posterId       | varchar(255) | YES  |     | NULL    |                |
-- | staff          | int(11)      | YES  |     | NULL    |                |
-- | stack          | text         | YES  |     | NULL    |                |
-- | breakdown      | text         | YES  |     | NULL    |                |
-- | companyId      | text         | YES  |     | NULL    |                |
-- | created_at     | text         | YES  |     | NULL    |                |
-- | updated_at     | text         | YES  |     | NULL    |                |
-- | currency       | text         | YES  |     | NULL    |                |
-- | methodology    | text         | YES  |     | NULL    |                |
-- | stage          | text         | YES  |     | NULL    |                |
-- | name           | text         | YES  |     | NULL    |                |
-- | contractType   | text         | YES  |     | NULL    |                |
-- | logo           | text         | YES  |     | NULL    |                |
-- | projectType    | text         | YES  |     | NULL    |                |
-- | workload       | text         | YES  |     | NULL    |                |
-- | requiredSkills | text         | YES  |     | NULL    |                |
-- | perks          | text         | YES  |     | NULL    |                |
-- +----------------+--------------+------+-----+---------+----------------+
