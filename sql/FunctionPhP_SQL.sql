-- News
-- Get all news
CREATE OR REPLACE FUNCTION list_all_news()
RETURNS TABLE (id integer, title varchar(250), content text, date timestamp with time zone, author_id integer, category_id integer)
AS $$
BEGIN
    RETURN QUERY
    SELECT *
    FROM news;
END;
$$ LANGUAGE plpgsql;

-- Get news by id
CREATE OR REPLACE FUNCTION search_news_by_id(news_id INTEGER)
RETURNS TABLE (id INTEGER, title VARCHAR(250), content TEXT, date TIMESTAMP WITH TIME ZONE, author_id INTEGER, category_id INTEGER) AS $$
BEGIN
  	RETURN QUERY 
  	SELECT *
	FROM news 
	WHERE id = news_id;
END;
$$ LANGUAGE plpgsql;

-- Get news by title
CREATE OR REPLACE FUNCTION search_news_by_title(search_text text)
RETURNS TABLE (id integer, title varchar(250), content text, date timestamp with time zone, author_id integer, category_id integer)
AS $$
BEGIN
    RETURN QUERY
    SELECT *
    FROM news
    WHERE title ILIKE '%' || search_text || '%';
END;
$$ LANGUAGE plpgsql;

-- Get news by content
CREATE OR REPLACE FUNCTION search_news_by_content(search_term text)
RETURNS TABLE (id integer, title varchar(250), content text, date timestamp with time zone, author_id integer, category_id integer)
AS $$
BEGIN
    RETURN QUERY
    SELECT *
    FROM news
    WHERE content LIKE '%' || search_term || '%';
END;
$$ LANGUAGE plpgsql;

-- Get news by date
CREATE OR REPLACE FUNCTION search_news_by_date_range(date_from timestamp with time zone, date_to timestamp with time zone)
RETURNS TABLE (id integer, title varchar(250), content text, date timestamp with time zone, author_id integer, category_id integer)
AS $$
BEGIN
    RETURN QUERY
    SELECT *
    FROM news
    WHERE date >= date_from AND date <= date_to;
END;
$$ LANGUAGE plpgsql;

-- Get news by keyword
CREATE OR REPLACE FUNCTION search_news_by_keyword(keyword varchar(250))
RETURNS TABLE (id integer, title varchar(250), content text, date timestamp with time zone, author_id integer, category_id integer)
AS $$
BEGIN
    RETURN QUERY
    SELECT *
    FROM news
    WHERE keyword ILIKE '%' || keyword || '%';
END;
$$ LANGUAGE plpgsql;

-- Get new by title or content
CREATE FUNCTION search_news(search_term text)
RETURNS TABLE (id integer, title text, content text, date timestamp)
AS $$
BEGIN
  RETURN QUERY
    SELECT *
    FROM news
    WHERE title ILIKE '%' || search_term || '%'
       OR content ILIKE '%' || search_term || '%'
    ORDER BY date DESC;
END;
$$ LANGUAGE plpgsql;


-- Search news by author id 
CREATE OR REPLACE FUNCTION search_news_by_author_id(author_id_str INTEGER)
RETURNS TABLE (
    id INTEGER,
    title VARCHAR(250),
    content TEXT,
    date TIMESTAMP WITH TIME ZONE,
    author_id INTEGER,
    category_id INTEGER
) AS $$
BEGIN
    RETURN QUERY
    SELECT *
    FROM news
    WHERE author_id = author_id_str;
END;
$$ LANGUAGE plpgsql;

-- Create news
CREATE OR REPLACE FUNCTION create_news(n_title TEXT, n_content TEXT, n_category_id INTEGER, n_author_id INTEGER)
RETURNS INTEGER AS $$
DECLARE
  n_date TIMESTAMP;
  n_id INTEGER;
BEGIN
  v_date := NOW();
  	INSERT INTO news(title, content, category_id, author_id, date)
  	VALUES (n_title, n_content, n_category_id, n_author_id, n_date)
    RETURNING id INTO n_id;
  RETURN n_id;
END;
$$ LANGUAGE plpgsql;


-- Delete news by news_id and author_id
CREATE OR REPLACE FUNCTION delete_news_by_author_id(news_id INTEGER, author_id_str INTEGER)
RETURNS VOID AS $$
BEGIN
    DELETE FROM news 
	WHERE id = news_id AND author_id = author_id_str;
END;
$$ LANGUAGE plpgsql;


-- Story
-- Get all story
CREATE OR REPLACE FUNCTION list_all_stories()
RETURNS TABLE (id integer, title VARCHAR(255), date_start timestamp with time zone, date_end timestamp with time zone) AS $$
BEGIN
  	RETURN QUERY 
  	SELECT * 
	FROM story;
END;
$$ LANGUAGE plpgsql;

-- Search story by id
CREATE OR REPLACE FUNCTION search_story_by_id(story_id INTEGER)
RETURNS TABLE (id INTEGER, title VARCHAR(255), date_start TIMESTAMP WITH TIME ZONE, date_end TIMESTAMP WITH TIME ZONE) AS $$
BEGIN
  	RETURN QUERY 
	SELECT *
	FROM story 
	WHERE id = story_id;
END;
$$ LANGUAGE plpgsql;

-- Search story by title
CREATE OR REPLACE FUNCTION search_story_by_title(title_search VARCHAR)
RETURNS TABLE (id INTEGER, title VARCHAR, date_start TIMESTAMP WITH TIME ZONE, date_end TIMESTAMP WITH TIME ZONE)
AS $$
BEGIN
    RETURN QUERY 
	SELECT *
	FROM story
    WHERE title ILIKE '%' || title_search || '%';
END;
$$ LANGUAGE plpgsql;


-- Comment
-- Search comment by news id
CREATE OR REPLACE FUNCTION search_comment_by_news_id(news_id_str INTEGER)
RETURNS TABLE (
    id INTEGER,
    username VARCHAR(255),
    content TEXT,
    date TIMESTAMP WITH TIME ZONE,
    news_id INTEGER
) AS $$
BEGIN
    RETURN QUERY
    SELECT *
    FROM comment
    WHERE news_id = news_id_str;
END;
$$ LANGUAGE plpgsql;

-- Create comment
CREATE OR REPLACE FUNCTION create_comment(news_id INTEGER, username VARCHAR(255), content TEXT)
RETURNS VOID AS $$
BEGIN
    INSERT INTO comment (news_id, username, content, date)
    VALUES (news_id, username, content, now());
END;
$$ LANGUAGE plpgsql;


-- Author
-- Search author by email and password
CREATE OR REPLACE FUNCTION search_author_by_email_password(
    email_in VARCHAR(100),
    password_in VARCHAR(255)
)
RETURNS TABLE (
    id INTEGER,
    name VARCHAR(255),
    email VARCHAR(100),
    password VARCHAR(255)
) AS $$
BEGIN
    RETURN QUERY
    SELECT *
    FROM author
    WHERE email = email_in AND password = password_in;
END;
$$ LANGUAGE plpgsql;

-- Create author
CREATE OR REPLACE FUNCTION create_author(
    name VARCHAR(255),
    email VARCHAR(100),
    password VARCHAR(255)
) RETURNS INTEGER AS $$
DECLARE
    author_id INTEGER;
BEGIN
    INSERT INTO author (name, email, password)
    VALUES (name, email, password)
    RETURNING id INTO author_id;
    
    RETURN author_id;
END;
$$ LANGUAGE plpgsql;

-- Keyword
-- Create keyword
CREATE FUNCTION create_keyword(keyword_text TEXT) RETURNS INTEGER AS $$
DECLARE
    keyword_id INTEGER;
BEGIN
    INSERT INTO keyword (keyword) VALUES (keyword_text) RETURNING id INTO keyword_id;
    RETURN keyword_id;
END;
$$ LANGUAGE plpgsql;

-- Search keyword by news id
CREATE OR REPLACE FUNCTION search_keywords_by_news_id(news_id_str INTEGER)
RETURNS TABLE (
    id INTEGER,
    keyword TEXT
) AS $$
BEGIN
    RETURN QUERY
    SELECT k.id, k.keyword
    FROM keyword k
    JOIN news_keyword nk ON nk.keyword_id = k.id
    WHERE nk.news_id = news_id_str;
END;
$$ LANGUAGE plpgsql;


-- Category
-- List all category
CREATE OR REPLACE FUNCTION list_all_categories()
RETURNS TABLE (
    id INTEGER,
    name VARCHAR(255),
    description TEXT
) AS $$
BEGIN
    RETURN QUERY
    SELECT *
    FROM category;
END;
$$ LANGUAGE plpgsql;

-- Search category by news id
CREATE OR REPLACE FUNCTION search_category_by_news_id(news_id INTEGER)
RETURNS TABLE (
    id INTEGER,
    name VARCHAR(255),
    description TEXT
) AS $$
BEGIN
    RETURN QUERY
    SELECT c.id, c.name, c.description
    FROM category c
    JOIN news n ON n.category_id = c.id
    WHERE n.id = news_id;
END;
$$ LANGUAGE plpgsql;


-- News_Keyword
-- Create map between news and keyword
CREATE OR REPLACE FUNCTION create_news_keyword(news_id INTEGER, keyword_id INTEGER)
RETURNS void AS $$
BEGIN
    INSERT INTO news_keyword (news_id, keyword_id) VALUES (news_id, keyword_id);
END;
$$ LANGUAGE plpgsql;

-- News_Story
-- Create map between news and story
CREATE OR REPLACE FUNCTION create_news_story(story_id INTEGER, news_id INTEGER)
RETURNS VOID AS $$
BEGIN
    INSERT INTO news_story (story_id, news_id) VALUES (story_id, news_id);
END;
$$ LANGUAGE plpgsql;






















