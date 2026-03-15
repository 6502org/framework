DROP TABLE IF EXISTS column_types;
CREATE TABLE column_types (
  id            INTEGER PRIMARY KEY AUTOINCREMENT,

  integer_col   integer,
  string_col    varchar(255),
  text_col      text,
  float_col     float,
  decimal_col   decimal,
  datetime_col  datetime,
  date_col      date,
  time_col      time,
  blob_col      blob,
  boolean_col   boolean,
  enum_col      varchar(1),

  integer_col_nn   integer      NOT NULL,
  string_col_nn    varchar(255) NOT NULL,
  text_col_nn      text         NOT NULL,
  float_col_nn     float        NOT NULL,
  decimal_col_nn   decimal      NOT NULL,
  datetime_col_nn  datetime     NOT NULL,
  date_col_nn      date         NOT NULL,
  time_col_nn      time         NOT NULL,
  blob_col_nn      blob         NOT NULL,
  boolean_col_nn   boolean      NOT NULL,
  enum_col_nn      varchar(1)   NOT NULL
);


DROP TABLE IF EXISTS unit_tests;
CREATE TABLE unit_tests (
  id             INTEGER PRIMARY KEY AUTOINCREMENT,
  integer_value  integer default 0,
  string_value   varchar(255) default '',
  text_value     text,
  float_value    float default 0.0,
  decimal_value  decimal default 0.0,
  datetime_value datetime default NULL,
  date_value     date default NULL,
  time_value     time default '00:00:00',
  blob_value     blob,
  boolean_value  boolean default 0,
  enum_value     varchar(1) default 'a',
  email_value    varchar(255) default ''
);
CREATE INDEX index_unit_tests_on_string_value ON unit_tests (string_value);
CREATE UNIQUE INDEX index_unit_tests_on_integer_value ON unit_tests (integer_value);
CREATE INDEX index_unit_tests_on_integer_string ON unit_tests (integer_value, string_value);

DROP TABLE IF EXISTS mixed_case_monkeys;
CREATE TABLE mixed_case_monkeys (
  monkeyID  INTEGER PRIMARY KEY AUTOINCREMENT,
  fleaCount integer
);

DROP TABLE IF EXISTS articles;
CREATE TABLE articles (
  id      INTEGER PRIMARY KEY AUTOINCREMENT,
  title   varchar(255) default '',
  user_id integer      default 0
);
CREATE INDEX index_articles_on_user_id ON articles (user_id);

DROP TABLE IF EXISTS articles_categories;
CREATE TABLE articles_categories (
  id          INTEGER PRIMARY KEY AUTOINCREMENT,
  article_id  integer NOT NULL,
  category_id integer NOT NULL
);
CREATE INDEX index_articles_categories_on_article_id ON articles_categories (article_id);
CREATE INDEX index_articles_categories_on_category_id ON articles_categories (category_id);

DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  id        INTEGER PRIMARY KEY AUTOINCREMENT,
  name      varchar(255) NOT NULL,
  parent_id integer      NOT NULL
);

DROP TABLE IF EXISTS tags;
CREATE TABLE tags (
  id    INTEGER PRIMARY KEY AUTOINCREMENT,
  name  varchar(255) NOT NULL default ''
);

DROP TABLE IF EXISTS taggings;
CREATE TABLE taggings (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  tag_id     integer NOT NULL,
  article_id integer NOT NULL
);

DROP TABLE IF EXISTS comments;
CREATE TABLE comments (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  body       text    NOT NULL,
  article_id integer NOT NULL,
  user_id    integer NOT NULL,
  created_at datetime
);
CREATE INDEX index_comments_on_article_id ON comments (article_id);

DROP TABLE IF EXISTS companies;
CREATE TABLE companies (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  name       varchar(255) NOT NULL
);

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  company_id integer,
  name       varchar(255) default '',
  first_name varchar(40) default '',
  approved   boolean default 1,
  type       varchar(255) default '',
  created_at datetime default NULL,
  created_on date default NULL,
  updated_at datetime default NULL,
  updated_on date default NULL
);

DROP TABLE IF EXISTS avatars;
CREATE TABLE avatars (
  id       INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id  integer NOT NULL,
  filepath varchar(255) NOT NULL
);
CREATE INDEX index_avatars_on_user_id ON avatars (user_id);

-- namespaced
DROP TABLE IF EXISTS fax_jobs;
CREATE TABLE fax_jobs (
  id        INTEGER PRIMARY KEY AUTOINCREMENT,
  page_size varchar(255) NOT NULL
);

DROP TABLE IF EXISTS fax_recipients;
CREATE TABLE fax_recipients (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  name       varchar(255) NOT NULL,
  fax_job_id integer NOT NULL
);

DROP TABLE IF EXISTS fax_attachments;
CREATE TABLE fax_attachments (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  fax_job_id integer NOT NULL default 0,
  article_id integer NOT NULL default 0
);
CREATE UNIQUE INDEX index_fax_attachments_on_fax_job_id_and_article_id ON fax_attachments (fax_job_id, article_id);
