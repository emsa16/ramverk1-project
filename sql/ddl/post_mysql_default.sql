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
    `deleted` DATETIME DEFAULT NULL,

    FOREIGN KEY (`user`) REFERENCES `rv1proj_User` (`id`) ON DELETE SET NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;



--
-- Table Post_votes
--
DROP TABLE IF EXISTS rv1proj_Post_votes;
CREATE TABLE rv1proj_Post_votes (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user_id` INTEGER DEFAULT NULL,
    `post_id` INTEGER DEFAULT NULL,
    `vote_value` TINYINT(1) NOT NULL,

    FOREIGN KEY (`user_id`) REFERENCES `rv1proj_User` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`post_id`) REFERENCES `rv1proj_Post` (`id`) ON DELETE SET NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;



--
-- Table Tag
--
DROP TABLE IF EXISTS rv1proj_Tag;
CREATE TABLE rv1proj_Tag (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `title` VARCHAR(80) NOT NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;



--
-- Table Posts_tags
--
DROP TABLE IF EXISTS rv1proj_Posts_tags;
CREATE TABLE rv1proj_Posts_tags (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `tag_id` INTEGER DEFAULT NULL,
    `post_id` INTEGER DEFAULT NULL,

    FOREIGN KEY (`tag_id`) REFERENCES `rv1proj_Tag` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`post_id`) REFERENCES `rv1proj_Post` (`id`) ON DELETE SET NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;
