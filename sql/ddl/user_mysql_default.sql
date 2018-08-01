--
-- Creating a User table and inserting example users.
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
-- Table User
--
DROP TABLE IF EXISTS rv1proj_User;
CREATE TABLE rv1proj_User (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(80) UNIQUE NOT NULL,
    `email` VARCHAR(80) UNIQUE NOT NULL,
    `password` VARCHAR(256) NOT NULL,
    `deleted` TINYINT(1) DEFAULT NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO rv1proj_User(username, email, password) VALUES
    ("admin", "admin@example.com", "$2y$10$scfqvrJH59WBi4UPEb..4O1.BGRgaI4fzV.NGATt0LHrV88Pa1J9a"),
    ("doe", "doe@example.com", "$2y$10$mYK7Wc6XQeYscR4gbGFXNu7.Kc3RuDPfivO9cfnflLNw04lB5LYKS"),
    ("test1", "test1@example.com", "$2y$10$yszzNGSOm6cKVkVNMB2uq.sQPa53BJj6u7j5frJ6rzISNI2aHzlPe"),
    ("test2", "test2@example.com", "$2y$10$0zdDxYR3VP65XVTc1.qX1e0.5kztWFf4PlRtJXA6hRYG2e9luJ9k6"),
    ("test3", "test3@example.com", "$2y$10$WaFMEQk6o5RSNdRaB7TbhuxgEn1DlclPg7fLTWZ5Zb24Bx9g6PnSW"),
    ("test4", "test4@example.com", "$2y$10$lbucpMVcxHa7ZLj6MYNJu.lmi282qQy9NE8Cww4xwFJfCbCitz7W2")
;
