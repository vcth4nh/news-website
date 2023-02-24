-- create database NewsPortal

CREATE TABLE IF NOT EXISTS author (
  	id SERIAL PRIMARY KEY,
  	name VARCHAR(255) NOT NULL,
  	email VARCHAR(100) NOT NULL CHECK (email LIKE '%@%'),
  	password VARCHAR(255) NOT NULL CHECK(length(password) > 8)
);

CREATE TABLE IF NOT EXISTS news (
  	id SERIAL PRIMARY KEY,
  	content TEXT NOT NULL CHECK (cardinality(regexp_split_to_array(content, '\s+')) >= 10),
  	date TIMESTAMP WITH TIME ZONE DEFAULT now(),
  	author_id INTEGER NOT NULL REFERENCES author(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS category (
  	id SERIAL PRIMARY KEY,
  	name VARCHAR(255) NOT NULL,
  	description TEXT
);

CREATE TABLE IF NOT EXISTS news_category (
  	news_id INTEGER NOT NULL REFERENCES news(id) ON DELETE CASCADE,
  	category_id INTEGER NOT NULL REFERENCES category(id) ON DELETE CASCADE,
  	PRIMARY KEY (news_id, category_id)
);

CREATE TABLE IF NOT EXISTS story (
  	id SERIAL PRIMARY KEY,
  	title VARCHAR(255) NOT NULL,
  	date_start TIMESTAMP WITH TIME ZONE,
  	date_end TIMESTAMP WITH TIME ZONE
);

CREATE TABLE IF NOT EXISTS news_story (
  	news_id INTEGER NOT NULL REFERENCES news(id) ON DELETE CASCADE,
  	story_id INTEGER NOT NULL REFERENCES story(id) ON DELETE CASCADE,
  	PRIMARY KEY (news_id, story_id)
);

CREATE TABLE IF NOT EXISTS comment (
  	id SERIAL PRIMARY KEY,
  	username VARCHAR(255) NOT NULL,
  	content TEXT NOT NULL,
  	date TIMESTAMP WITH TIME ZONE DEFAULT now(),
  	news_id INTEGER NOT NULL REFERENCES news(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS keyword (
  	id SERIAL PRIMARY KEY,
  	keyword TEXT NOT NULL,
  	news_id INTEGER NOT NULL REFERENCES news(id) ON DELETE CASCADE
);

-- Remove duplicate keyword associated with same news_id

CREATE OR REPLACE FUNCTION remove_duplicate_keywords() RETURNS TRIGGER AS $$
BEGIN
  DELETE FROM keyword
  WHERE id NOT IN (
    SELECT MIN(id)
    FROM keyword
    GROUP BY news_id, keyword
  );
  
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_insert_keyword
AFTER INSERT ON keyword
FOR EACH ROW
EXECUTE FUNCTION remove_duplicate_keywords();
