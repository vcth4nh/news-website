-- Author
CREATE INDEX idx_author_name_password ON author (name, password);

-- Category
CREATE INDEX idx_category_name ON category (name);

-- Story
CREATE INDEX idx_story_title ON story (title);

CREATE INDEX idx_story_date ON story(date_start, date_end);

-- News
CREATE INDEX idx_news_title ON news (title);

CREATE INDEX idx_news_date ON news (date);

-- Search text in content
CREATE INDEX idx_news_content ON news USING gin(to_tsvector('english', content));

-- Keyword
CREATE INDEX idx_keyword_keyword ON keyword (keyword);

-- Comment
CREATE INDEX idx_comment_news_id ON comment(news_id);

CREATE INDEX idx_comment_news_id_date ON comment(news_id, date);






