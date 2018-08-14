--
-- Sample content
-- The following tables need to be created before the sample content can be inserted into the database:
-- rv1proj_User, rv1proj_Post, rv1proj_Post_votes, rv1proj_Tag, rv1proj_Posts_tags, rv1proj_Comment, rv1proj_Comment_votes, rv1proj_Comment_rewards
--



INSERT INTO rv1proj_User(username, email, password) VALUES
    ("admin", "admin@example.com", "$2y$10$scfqvrJH59WBi4UPEb..4O1.BGRgaI4fzV.NGATt0LHrV88Pa1J9a"),
    ("doe", "doe@example.com", "$2y$10$mYK7Wc6XQeYscR4gbGFXNu7.Kc3RuDPfivO9cfnflLNw04lB5LYKS"),
    ("test1", "test1@example.com", "$2y$10$yszzNGSOm6cKVkVNMB2uq.sQPa53BJj6u7j5frJ6rzISNI2aHzlPe"),
    ("test2", "test2@example.com", "$2y$10$0zdDxYR3VP65XVTc1.qX1e0.5kztWFf4PlRtJXA6hRYG2e9luJ9k6"),
    ("test3", "test3@example.com", "$2y$10$WaFMEQk6o5RSNdRaB7TbhuxgEn1DlclPg7fLTWZ5Zb24Bx9g6PnSW"),
    ("test4", "test4@example.com", "$2y$10$lbucpMVcxHa7ZLj6MYNJu.lmi282qQy9NE8Cww4xwFJfCbCitz7W2")
;



INSERT INTO rv1proj_Post(user, created, edited, title, content) VALUES
    (2, "2018-03-11 12:00:00", NULL, "Who is your favourite character and why?", "Mine is Kramer, because he is so whacky and clearly the most physically talented of them all."),
    (3, "2018-03-18 12:00:00", NULL, "Tell us your favorite moment from the series?", "Please also reference the episode title :)"),
    (5, "2018-04-02 12:00:00", "2018-04-04 12:00:00", "Why can't George keep a job", "I think it is because he has commitment issues. Thoughts?"),
    (4, "2018-05-20 12:00:00", NULL, "Seinfeld sucks", "Friends is so much better IMO!")
;



INSERT INTO rv1proj_Post_votes(user_id, post_id, vote_value) VALUES
    (3, 1, 1),
    (5, 1, 1),
    (2, 1, 0),
    (6, 2, 0),
    (4, 2, 0)
;



INSERT INTO rv1proj_Tag(title) VALUES
    ("debate"),
    ("parody"),
    ("fantheory"),
    ("news"),
    ("announcements")
;



INSERT INTO rv1proj_Posts_tags(tag_id, post_id) VALUES
    (3, 3),
    (1, 1)
;



INSERT INTO rv1proj_Comment(post_id, parent_id, user, created, edited, content) VALUES
    (1, 0, 3, "2016-07-21 12:00:00", "2017-07-22 13:05:00", "Jag älskar katter"),
    (1, 0, 4, "2015-07-22 12:00:00", NULL, "lol"),
    (2, 0, 3, "2017-07-21 12:00:00", NULL, "Söt hund."),
    (2, 0, 3, "2016-07-21 12:00:00", NULL, "Jag har en tax."),
    (1, 1, 5, "2017-09-10 12:00:00", "2017-09-20 12:00:00", "varför"),
    (2, 4, 6, "2016-07-21 12:00:00", NULL, "Jag älskar katter"),
    (1, 1, 4, "2017-09-01 12:00:00", "2017-09-19 12:00:00", "ja!"),
    (1, 5, 3, "2017-09-16 12:00:00", "2017-09-20 20:00:00", "Usch."),
    (1, 2, 3, "2017-04-21 12:00:00", NULL, "jaså."),
    (1, 2, 5, "2017-08-10 12:00:00", NULL, "jepp")
;



INSERT INTO rv1proj_Comment_votes(user_id, comment_id, vote_value) VALUES
    (3, 1, 1),
    (5, 1, 1),
    (2, 1, 0),
    (6, 2, 0),
    (4, 2, 0)
;



INSERT INTO rv1proj_Comment_rewards(user_id, comment_id) VALUES
    (3, 1),
    (5, 1),
    (2, 1),
    (6, 2),
    (4, 2)
;
