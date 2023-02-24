CREATE TABLE IF NOT EXISTS tblnews (
	news_id INT NOT NULL GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	category_id INT NOT NULL,
	date_posted date NOT NULL,
	news_title varchar(100) NOT NULL,
	news_content text NOT NULL,
	date_updated date NOT NULL,
	news_status BOOLEAN NOT NULL,
	comment_status BOOLEAN NOT NULL,
	author_id INT NOT NULL
);

CREATE TABLE IF NOT EXISTS tblnewscategory (
	category_id INT NOT NULL GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	category_name varchar(30) NOT NULL,
	category_description varchar(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS tblauthor (
	author_id INT NOT NULL GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	author_name varchar(50) NOT NULL,
	author_display_name varchar(30) NOT NULL,
	author_email varchar(30) NOT NULL,
	author_account_status BOOLEAN NOT NULL,
	author_profile bytea NOT NULL,
	username varchar(30) NOT NULL,
	password varchar(30) NOT NULL
);

CREATE TABLE IF NOT EXISTS tblsubscriber (
	subscriber_id INT NOT NULL GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	subscriber_display_name varchar(30) NOT NULL,
	subscriber_name varchar(50) NOT NULL,
	subscriber_email varchar(30) NOT NULL,
	subscriber_profile bytea NOT NULL,
	username varchar(30) NOT NULL,
	password varchar(30) NOT NULL,
	account_status BOOLEAN NOT NULL,
	date_joined date NOT NULL,
	date_approved date NOT NULL
);

CREATE TABLE IF NOT EXISTS tblcomment (
	comment_id INT NOT NULL GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	comment_content varchar(100) NOT NULL,
	subscriber_id INT NOT NULL,
	news_id INT NOT NULL,
	comment_date date NOT NULL,
	comment_status BOOLEAN NOT NULL,
	user_id INT NOT NULL
);

CREATE TABLE IF NOT EXISTS tbluser (
	user_id INT NOT NULL GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	user_display_name varchar(30) NOT NULL,
	user_complete_name varchar(30) NOT NULL,
	username varchar(30) NOT NULL,
	password varchar(30) NOT NULL,
	user_profile bytea NOT NULL,
	user_type INT NOT NULL
);

CREATE TABLE IF NOT EXISTS tblbackup (
	backup_id INT NOT NULL GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	backup_date date NOT NULL,
	backup_file varchar(50) NOT NULL,
	user_id INT NOT NULL
);

ALTER TABLE tblnews
ADD CONSTRAINT fk_tblnews_newscategory FOREIGN KEY (category_id) REFERENCES tblnewscategory (category_id);

ALTER TABLE tblnews 
ADD CONSTRAINT fk_tblnews_author FOREIGN KEY (author_id) REFERENCES tblauthor (author_id);

ALTER TABLE tblcomment
ADD CONSTRAINT fk_tblcomment_news FOREIGN KEY (news_id) REFERENCES tblnews (news_id);

ALTER TABLE tblcomment
ADD CONSTRAINT fk_tblcomment_user FOREIGN KEY (user_id) REFERENCES tbluser (user_id);

ALTER TABLE tblcomment
ADD CONSTRAINT fk_tblcomment_subscriber FOREIGN KEY (subscriber_id) REFERENCES tblsubscriber (subscriber_id);

ALTER TABLE tblbackup
ADD CONSTRAINT fk_tblbackup_user FOREIGN KEY (user_id) REFERENCES tbluser (user_id);





