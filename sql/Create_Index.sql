-- Author
CREATE INDEX idx_author_name_password ON author (name, password);

-- Category
CREATE INDEX idx_category_name ON category (name);

-- Story
CREATE INDEX idx_story_title ON story (title);

-- News
CREATE INDEX idx_news_date ON news (date);

-- Search text in content
CREATE INDEX idx_news_content_fts ON news USING gin(to_tsvector('english', content));

-- Comment
CREATE INDEX idx_comment_news_id ON comment(news_id);

CREATE INDEX idx_comment_news_id_date ON comment(news_id, date);





