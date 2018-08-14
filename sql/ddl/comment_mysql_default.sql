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
    `deleted` TINYINT(1) DEFAULT NULL,

    FOREIGN KEY (`user`) REFERENCES `rv1proj_User` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`post_id`) REFERENCES `rv1proj_Post` (`id`) ON DELETE SET NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;



--
-- Table Comment_votes
--
DROP TABLE IF EXISTS rv1proj_Comment_votes;
CREATE TABLE rv1proj_Comment_votes (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user_id` INTEGER DEFAULT NULL,
    `comment_id` INTEGER DEFAULT NULL,
    `vote_value` TINYINT(1) NOT NULL,

    FOREIGN KEY (`user_id`) REFERENCES `rv1proj_User` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`comment_id`) REFERENCES `rv1proj_Comment` (`id`) ON DELETE SET NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;



--
-- Table Comment_rewards
--
DROP TABLE IF EXISTS rv1proj_Comment_rewards;
CREATE TABLE rv1proj_Comment_rewards (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user_id` INTEGER DEFAULT NULL,
    `comment_id` INTEGER DEFAULT NULL,

    FOREIGN KEY (`user_id`) REFERENCES `rv1proj_User` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`comment_id`) REFERENCES `rv1proj_Comment` (`id`) ON DELETE SET NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;
