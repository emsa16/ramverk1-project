--
-- Creating Comment table and inserting example comments.
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
-- Table Comment
--
DROP TABLE IF EXISTS rv1proj_Comment;
CREATE TABLE rv1proj_Comment (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `post_id` INTEGER DEFAULT NULL,
    `parent_id` INTEGER NOT NULL,
    `user` INTEGER DEFAULT NULL,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edited` DATETIME ON UPDATE CURRENT_TIMESTAMP,
    `content` TEXT NOT NULL,
    `upvote` INTEGER NOT NULL,
    `downvote` INTEGER NOT NULL,
    `deleted` TINYINT(1) DEFAULT NULL,

    FOREIGN KEY (`user`) REFERENCES `rv1proj_User` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`post_id`) REFERENCES `rv1proj_Post` (`id`) ON DELETE SET NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO rv1proj_Comment(post_id, parent_id, user, created, edited, content, upvote, downvote) VALUES
    (1, 0, 3, "2016-07-21 12:00:00", "2017-07-22 13:05:00", "Jag älskar katter", 3, 2),
    (1, 0, 4, "2015-07-22 12:00:00", NULL, "lol", 10, 0),
    (2, 0, 3, "2017-07-21 12:00:00", NULL, "Söt hund.", 3, 2),
    (2, 0, 3, "2016-07-21 12:00:00", NULL, "Jag har en tax.", 3, 2),
    (1, 1, 5, "2017-09-10 12:00:00", "2017-09-20 12:00:00", "varför", 0, 5),
    (2, 4, 6, "2016-07-21 12:00:00", NULL, "Jag älskar katter", 0, 0),
    (1, 1, 4, "2017-09-01 12:00:00", "2017-09-19 12:00:00", "ja!", 4, 1),
    (1, 5, 3, "2017-09-16 12:00:00", "2017-09-20 20:00:00", "Usch.", 22, 2),
    (1, 2, 3, "2017-04-21 12:00:00", NULL, "jaså.", 3, 2),
    (1, 2, 5, "2017-08-10 12:00:00", NULL, "jepp", 0, 5)
;
