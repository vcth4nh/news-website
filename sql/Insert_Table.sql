INSERT INTO author (name, email, password)
VALUES ('John Smith', 'johnsmith@example.com', 'password123'),
       ('Jane Doe', 'janedoe@example.com', 'password456'),
       ('Mark Johnson', 'markjohnson@example.com', 'password789'),
       ('Sarah Lee', 'sarahlee@example.com', 'passwordabc'),
       ('David Kim', 'davidkim@example.com', 'passworddef');

INSERT INTO category (name, description)
VALUES ('Technology', 'Latest news in the tech industry.'),
       ('Sports', 'News and updates on sports around the world.'),
       ('Politics', 'News and opinions on politics and government.'),
       ('Entertainment', 'Breaking news on movies, TV shows, and celebrities.'),
       ('Business', 'News and insights on the world of business and finance.');


INSERT INTO story (title, date_start, date_end)
VALUES ('New Product Launch', '2022-01-01', '2022-01-31'),
       ('Upcoming Sports Event', '2022-02-01', '2022-02-28'),
       ('Election Results', '2022-03-01', '2022-03-31'),
       ('Celebrity Gossip', '2022-04-01', '2022-04-30'),
       ('Quarterly Earnings Report', '2022-05-01', '2022-05-31');


INSERT INTO news (content, date, author_id)
VALUES ('This is the content of news 1 which has more than 10 words and should pass the check.', NOW(), 1),
       ('Here is the content of news 2 which also has more than 10 words and will pass the check.', NOW(), 2),
       ('News 3 has a long content with more than 10 words and is inserted into the database.', NOW(), 3),
       ('The fourth news has a lengthy content that is longer than 10 words and should be accepted.', NOW(), 4),
       ('This is news 5 and its content is more than 10 words so it should be inserted into the table.', NOW(), 5);


INSERT INTO keyword (keyword)
VALUES ('wormhole'),
       ('sun'),
       ('Genshin Impact'),
       ('global warming'),
       ('climate change'),
       ('space'),
       ('technology'),
       ('business'),
       ('politics'),
       ('entertainment');


INSERT INTO news_keyword (news_id, keyword_id)
VALUES (1, 3),
       (2, 1),
       (3, 2),
       (4, 4),
       (5, 5),
       (1, 6),
       (2, 7),
       (3, 8),
       (4, 9),
       (5, 10),
       (1, 1),
       (2, 2),
       (3, 3),
       (4, 4),
       (5, 5);

INSERT INTO news_category (news_id, category_id)
VALUES (1, 1),
       (2, 2),
       (3, 3),
       (4, 4),
       (5, 5);

INSERT INTO news_story (news_id, story_id)
VALUES (1, 1),
       (1, 2),
       (2, 3),
       (3, 3),
       (4, 4);


INSERT INTO comment (username, content, date, news_id)
VALUES ('John Doe', 'Great article, thanks for sharing!', '2022-01-01', 1),
       ('Jane Smith', 'I found this really informative, thanks!', '2022-02-01', 2),
       ('Mark Lee', 'This is very interesting, keep up the good work!', '2022-03-01', 3),
       ('Sarah Johnson', 'I loved this article, thanks for writing it!', '2022-04-01', 4),
       ('David Williams', 'This was a great read, thanks for sharing!', '2022-05-01', 5);

