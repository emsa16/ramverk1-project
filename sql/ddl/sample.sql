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
    (4, "2018-05-20 12:00:00", NULL, "Seinfeld sucks", "Friends is so much better IMO!"),
    (1, "2018-05-15 12:00:00", NULL, "Admin goes on holiday", "The admin for this site will be going on holiday for a couple of weeks. Please be kind to each other in the meanwhile ;).")
;



INSERT INTO rv1proj_Post_votes(user_id, post_id, vote_value) VALUES
(3,5,1),
(3,1,1),
(3,2,1),
(3,4,0),
(4,1,1),
(4,2,1),
(4,3,1),
(5,1,1),
(5,2,1),
(5,3,1),
(5,4,0),
(6,5,1),
(6,3,1),
(6,1,1),
(2,1,1),
(2,2,1),
(2,3,1),
(2,4,0)
;



INSERT INTO rv1proj_Tag(title) VALUES
    ("debate"),
    ("fantheory"),
    ("announcements")
;



INSERT INTO rv1proj_Posts_tags(tag_id, post_id) VALUES
    (2, 3),
    (1, 1),
    (3, 5)
;



INSERT INTO rv1proj_Comment(post_id, parent_id, user, created, edited, content) VALUES
(2,0,2,'2018-08-15 14:51:05',NULL,'\"No soup for you!\"'),
(3,0,2,'2018-08-15 14:51:55',NULL,'Maybe it is just because he is not a very nice person and people don\'t want to work with him.'),
(5,0,2,'2018-08-15 14:52:05',NULL,'Enjoy!!'),
(4,0,2,'2018-08-15 14:52:25',NULL,'Well then go to a Friends forum instead.'),
(1,0,3,'2018-08-15 14:53:15',NULL,'Absolutely George, because Jason Alexander is the best actor of the bunch.'),
(2,1,3,'2018-08-15 14:53:58',NULL,'From the episode \"The soup nazi\". Please read the description :('),
(3,2,3,'2018-08-15 14:55:46',NULL,'I would like to be his friend though.'),
(3,0,3,'2018-08-15 14:56:11',NULL,'Does he even have an education?'),
(4,0,3,'2018-08-15 14:56:26',NULL,'BAN!'),
(1,0,4,'2018-08-15 14:58:21',NULL,'Yeah, Kramer has the craziest stuff, I start laughing even before he comes in :D'),
(2,0,4,'2018-08-15 14:59:30','2018-08-15 14:59:46','[The marine biologist]\r\n\r\nWhen George pulls up the golf ball. It is the perfect climax and everyone\'s reactions are hilarious.'),
(3,0,4,'2018-08-15 15:00:42',NULL,'Same things with his relationshiops.'),
(1,0,5,'2018-08-15 15:01:54',NULL,'I think Elaine is fun, and a great contrast to the rest.'),
(1,5,5,'2018-08-15 15:02:55',NULL,'Agree, Alexander does a great George. I wonder what it would have looked like if the role model for George, Larry David, would also have played that character.'),
(2,1,5,'2018-08-15 15:03:12',NULL,'I love this one!'),
(2,11,5,'2018-08-15 15:04:10',NULL,'George with his dreams, always reaching for the stars. Either he wants to be that, or an architect, or something else.'),
(3,7,5,'2018-08-15 15:04:36',NULL,'Really? Knowing how selfish he is?'),
(3,8,5,'2018-08-15 15:04:51',NULL,'I doubt it.'),
(5,0,5,'2018-08-15 15:05:07',NULL,'We\'ll try to...'),
(1,14,6,'2018-08-15 15:22:41',NULL,'Have you watched Curb your enthusiasm? He is great in that one!'),
(1,0,6,'2018-08-15 15:23:46',NULL,'No one mentions Jerry? He is still the center of the show, and I think it would not work without him balancing out the other ones, because he is not as whacky.'),
(2,0,6,'2018-08-15 15:26:52',NULL,'[The fusilli Jerry]: When Kramer gets his new plates and they say ASSMAN, but he decides to own it and then parks at the hospital. Gold!'),
(2,6,2,'2018-08-15 15:31:28',NULL,'You\'re a posting nazi...')
;



INSERT INTO rv1proj_Comment_votes(user_id, comment_id, vote_value) VALUES
    (2,5,1),
    (2,20,1),
    (2,10,1),
    (2,21,0),
    (2,6,0),
    (2,1,1),
    (2,11,1),
    (2,16,0),
    (2,22,1),
    (2,17,1),
    (2,12,0),
    (2,2,1),
    (2,19,1),
    (2,4,1),
    (2,9,1),
    (3,14,1),
    (3,5,1),
    (3,20,1),
    (3,13,1),
    (3,21,0),
    (3,23,0),
    (3,1,0),
    (3,22,1),
    (3,11,1),
    (3,9,1),
    (3,4,1),
    (4,9,0),
    (4,4,0),
    (4,12,1),
    (4,2,1),
    (4,11,1),
    (4,1,1),
    (4,15,1),
    (4,23,1),
    (4,22,1),
    (4,5,1),
    (4,20,1),
    (4,14,1),
    (5,19,1),
    (5,2,1),
    (5,8,1),
    (5,12,1),
    (5,11,1),
    (5,1,1),
    (5,15,1),
    (5,23,1),
    (5,22,1),
    (5,5,1),
    (5,20,1),
    (5,13,1),
    (5,21,0),
    (6,9,0),
    (6,4,0),
    (6,19,1),
    (6,2,1),
    (6,17,1),
    (6,18,0),
    (6,12,0),
    (6,11,1),
    (6,1,1),
    (6,10,1),
    (6,5,1),
    (6,13,0),
    (6,14,1)
;



INSERT INTO rv1proj_Comment_rewards(user_id, comment_id) VALUES
    (2,5),
    (3,11),
    (3,22),
    (5,2)
;
