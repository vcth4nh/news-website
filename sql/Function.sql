-- Get news by story
CREATE OR REPLACE FUNCTION get_news_by_story_name(story_name TEXT)
    RETURNS TABLE
            (
                id      INTEGER,
                title   VARCHAR(250),
                content TEXT,
                date    TIMESTAMP WITH TIME ZONE
            )
AS
$$
BEGIN
    RETURN QUERY
        SELECT n.id, n.title, n.content, n.date
        FROM news n
                 JOIN news_story ns ON n.id = ns.news_id
                 JOIN story s ON ns.story_id = s.id
        WHERE s.title ILIKE ('%' || story_name || '%');
END;
$$ LANGUAGE plpgsql;


-- Get news by category
CREATE OR REPLACE FUNCTION get_news_by_category(category_name TEXT)
    RETURNS TABLE
            (
                id      INTEGER,
                title   VARCHAR(250),
                content TEXT,
                date    TIMESTAMP WITH TIME ZONE
            )
AS
$$
BEGIN
    RETURN QUERY
        SELECT n.id, n.title, n.content, n.date
        FROM news n
                 JOIN category c ON n.category_id = c.id
        WHERE c.name = category_name;
END;
$$ LANGUAGE plpgsql;

-- Get news by author
CREATE OR REPLACE FUNCTION get_news_by_author(author_name VARCHAR(255))
    RETURNS TABLE
            (
                id      INTEGER,
                title   VARCHAR(250),
                content TEXT,
                date    TIMESTAMP WITH TIME ZONE
            )
AS
$$
BEGIN
    RETURN QUERY SELECT n.id, n.title, n.content, n.date
                 FROM news n
                          JOIN author a ON n.author_id = a.id
                 WHERE a.name ILIKE '%' || author_name || '%';
END;
$$ LANGUAGE plpgsql;

-- Get news by keyword
CREATE OR REPLACE FUNCTION get_news_by_keyword(keyword TEXT)
    RETURNS TABLE
            (
                id      INTEGER,
                title   VARCHAR(250),
                content TEXT,
                date    TIMESTAMP WITH TIME ZONE
            )
AS
$$
BEGIN
    RETURN QUERY SELECT n.id, n.title, n.content, n.date
                 FROM news n
                          JOIN news_keyword nk ON n.id = nk.news_id
                          JOIN keyword k ON nk.keyword_id = k.id
                 WHERE k.keyword ILIKE ('%' || keyword || '%');
END;
$$ LANGUAGE plpgsql;

-- Get news by date
CREATE OR REPLACE FUNCTION get_news_by_date(start_date DATE, end_date DATE)
    RETURNS TABLE
            (
                id      INTEGER,
                title   VARCHAR(250),
                content TEXT,
                date    TIMESTAMP WITH TIME ZONE
            )
AS
$$
BEGIN
    RETURN QUERY SELECT id, title, content, date
                 FROM news
                 WHERE date >= start_date
                   AND date <= end_date;
END;
$$ LANGUAGE plpgsql;

-- Update news
CREATE OR REPLACE FUNCTION update_news(
    news_id INTEGER,
    new_title VARCHAR(250),
    new_content TEXT,
    new_author_id INTEGER,
    new_category_id INTEGER
) RETURNS VOID AS
$$
BEGIN
    UPDATE news
    SET title       = COALESCE(new_title, title),
        content     = COALESCE(new_content, content),
        author_id   = COALESCE(new_author_id, author_id),
        category_id = COALESCE(new_category_id, category_id)
    WHERE id = news_id;
END;
$$ LANGUAGE plpgsql;
