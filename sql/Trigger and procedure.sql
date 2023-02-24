-- Check the new password on author is different from old password 

CREATE OR REPLACE FUNCTION check_new_password() RETURNS TRIGGER AS $$
BEGIN
  IF NEW.password = OLD.password THEN
    RAISE EXCEPTION 'New password must be different from old password.';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER tg_check_new_password
BEFORE UPDATE OF password ON author
FOR EACH ROW
EXECUTE FUNCTION check_new_password();


-- Updates the category table with a new category if it doesn't already exist,
-- and inserts a new record into the news_category table with the news_id and category_id

CREATE OR REPLACE FUNCTION tg_check_category() RETURNS TRIGGER AS $$
BEGIN
 
  IF EXISTS(
    SELECT 1 FROM category
    WHERE name = NEW.name
  ) THEN RAISE EXCEPTION 'The category has already inserted'
  END IF;
  RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER tg_check_category_duplicate
BEFORE INSERT ON category
FOR EACH ROW
EXECUTE FUNCTION tg_check_category();

-- Check duplicate keyword 

CREATE OR REPLACE FUNCTION check_keyword_update() RETURNS TRIGGER AS $$
BEGIN
  IF EXISTS (
    SELECT 1 FROM keyword
    WHERE NEW.keyword = keyword
      AND NEW.id <> id
  ) THEN
    RAISE EXCEPTION 'The keyword already exists.';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER tg_check_keyword_duplicate
BEFORE UPDATE OR INSERT ON keyword
FOR EACH ROW
EXECUTE FUNCTION check_keyword_update();

-- Check duplicate keyword on a news

CREATE OR REPLACE FUNCTION check_news_keyword_duplicate() RETURNS TRIGGER AS $$
BEGIN
  IF EXISTS (
    SELECT 1 FROM news_keyword
    WHERE NEW.keyword_id = keyword
      AND NEW.news_id = news_id
  ) THEN
    RAISE EXCEPTION 'The keyword already exists on news.';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER tg_check_news_keyword_duplicate
BEFORE UPDATE OR INSERT ON news_keyword
FOR EACH ROW
EXECUTE FUNCTION check_news_keyword_duplicate();






