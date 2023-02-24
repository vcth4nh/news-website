-- create database NewsPortal

CREATE TABLE IF NOT EXISTS author
(
    id       SERIAL PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    email    VARCHAR(100) NOT NULL CHECK (email LIKE '%@%'),
    password VARCHAR(255) NOT NULL CHECK (length(password) > 8)
);

CREATE TABLE IF NOT EXISTS news
(
    id        SERIAL PRIMARY KEY,
    title     VARCHAR(250) NOT NULL,
    content   TEXT    NOT NULL CHECK (cardinality(regexp_split_to_array(content, '\s+')) >= 10),
    date      TIMESTAMP WITH TIME ZONE DEFAULT now(),
    author_id INTEGER NOT NULL REFERENCES author (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS category
(
    id          SERIAL PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS news_category
(
    news_id     INTEGER NOT NULL REFERENCES news (id) ON DELETE CASCADE,
    category_id INTEGER NOT NULL REFERENCES category (id) ON DELETE CASCADE,
    PRIMARY KEY (news_id, category_id)
);

CREATE TABLE IF NOT EXISTS story
(
    id         SERIAL PRIMARY KEY,
    title      VARCHAR(255) NOT NULL,
    date_start TIMESTAMP WITH TIME ZONE,
    date_end   TIMESTAMP WITH TIME ZONE
);

CREATE TABLE IF NOT EXISTS news_story
(
    news_id  INTEGER NOT NULL REFERENCES news (id) ON DELETE CASCADE,
    story_id INTEGER NOT NULL REFERENCES story (id) ON DELETE CASCADE,
    PRIMARY KEY (news_id, story_id)
);

CREATE TABLE IF NOT EXISTS comment
(
    id       SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    content  TEXT         NOT NULL,
    date     TIMESTAMP WITH TIME ZONE DEFAULT now(),
    news_id  INTEGER      NOT NULL REFERENCES news (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS keyword
(
    id      SERIAL PRIMARY KEY,
    keyword TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS news_keyword
(
    news_id    INTEGER NOT NULL REFERENCES news (id) ON DELETE CASCADE,
    keyword_id INTEGER NOT NULL REFERENCES keyword (id) ON DELETE CASCADE,
    PRIMARY KEY (news_id, keyword_id)
)



