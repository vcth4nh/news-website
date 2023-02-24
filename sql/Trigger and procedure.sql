-- Check the new password on author is different from old password 

CREATE OR REPLACE FUNCTION check_new_password() RETURNS TRIGGER AS $$
BEGIN
  IF NEW.password = OLD.password THEN
    RAISE EXCEPTION 'New password must be different from old password.';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER check_new_password_trigger
BEFORE UPDATE ON author
FOR EACH ROW
EXECUTE FUNCTION check_new_password();

-- Limit the number of categories associated with a news

CREATE OR REPLACE FUNCTION check_news_categories() RETURNS TRIGGER AS $$
DECLARE
  num_categories INTEGER;
BEGIN
  SELECT COUNT(*) INTO num_categories FROM news_category WHERE news_id = NEW.news_id;
  IF num_categories > 5 THEN
    RAISE EXCEPTION 'News articles can only be associated with up to 5 categories.';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER check_news_categories_trigger
BEFORE INSERT OR UPDATE ON news_category
FOR EACH ROW
EXECUTE FUNCTION check_news_categories();

-- Updates the category table with a new category if it doesn't already exist,
-- and inserts a new record into the news_category table with the news_id and category_id

CREATE OR REPLACE FUNCTION insert_news_category() RETURNS TRIGGER AS $$
BEGIN
  INSERT INTO category (name)
  SELECT NEW.category_name
  WHERE NOT EXISTS (
    SELECT 1 FROM category WHERE name = NEW.category_name
  );
  
  INSERT INTO news_category (news_id, category_id)
  SELECT NEW.id, id
  FROM category
  WHERE name = NEW.category_name;
  
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_insert_news
AFTER INSERT ON news
FOR EACH ROW
EXECUTE FUNCTION insert_news_category();

-- Check duplicate keyword asscoiated with same news_id

CREATE OR REPLACE FUNCTION check_keyword_update() RETURNS TRIGGER AS $$
BEGIN
  IF EXISTS (
    SELECT 1 FROM news_keyword
    WHERE news_id = NEW.news_id
      AND keyword_id <> NEW.id
      AND EXISTS (
        SELECT 1 FROM keyword WHERE id = NEW.id AND keyword = keyword.keyword
      )
  ) THEN
    RAISE EXCEPTION 'The updated keyword already exists for this news.';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER check_keyword_update_trigger
BEFORE UPDATE ON keyword
FOR EACH ROW
EXECUTE FUNCTION check_keyword_update();






