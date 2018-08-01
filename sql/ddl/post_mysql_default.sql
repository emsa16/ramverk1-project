--
-- Creating a Post table and inserting example posts.
-- Create a database and a user having access to this database,
-- this must be done by hand, see commented rows on how to do it.
-- Default database SQL, NOTE that all database name and account details should
-- be replaced by actual information
--



--
-- Create a database for test and user
--
-- CREATE DATABASE IF NOT EXISTS anaxdb;
-- GRANT ALL ON anaxdb.* TO anax@localhost IDENTIFIED BY 'anax';
-- USE anaxdb;

-- Ensure UTF8 on the database connection
SET NAMES utf8;



--
-- Table Post
--
DROP TABLE IF EXISTS rv1proj_Post;
CREATE TABLE rv1proj_Post (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user` INTEGER DEFAULT NULL,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edited` DATETIME ON UPDATE CURRENT_TIMESTAMP,
    `title` VARCHAR(120) NOT NULL,
    `content` TEXT,
    `upvote` INTEGER NOT NULL,
    `downvote` INTEGER NOT NULL,
    `deleted` DATETIME DEFAULT NULL,

    FOREIGN KEY (`user`) REFERENCES `rv1proj_User` (`id`) ON DELETE SET NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO rv1proj_Post(user, created, edited, title, content, upvote, downvote) VALUES
    (2, "2018-03-11 12:00:00", NULL, "Who is your favourite character and why?", "Mine is Kramer, because he is so whacky and clearly the most physically talented of them all.", 45, 7),
    (3, "2018-03-18 12:00:00", NULL, "Tell us your favorite moment from the series?", "Please also reference the episode title :)", 18, 8),
    (5, "2018-04-02 12:00:00", "2018-04-04 12:00:00", "Why can't George keep a job [THEORY]", "I think it is because he has commitment issues. Thoughts?", 7, 9),
    (4, "2018-05-20 12:00:00", NULL, "Seinfeld sucks", "Friends is so much better IMO!", 1, 54)
;
