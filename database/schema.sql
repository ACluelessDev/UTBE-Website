-- Meant to be a copy or backup here. It doesn't actually run lolol. It's just in case the schema needs to be
-- recreated on another device.

CREATE DATABASE IF NOT EXISTS utbe_community
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE utbe_community;

CREATE TABLE users (
                       id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(20) NOT NULL UNIQUE,
                       password_hash VARCHAR(255) NOT NULL,
                       role ENUM('user', 'developer') NOT NULL DEFAULT 'user',
                       created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
                       id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                       user_id INT UNSIGNED NOT NULL,
                       title VARCHAR(100) NOT NULL,
                       caption VARCHAR(500) NOT NULL,
                       image_path VARCHAR(255) NULL,
                       is_developer_post TINYINT(1) NOT NULL DEFAULT 0,
                       created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                       INDEX idx_posts_created (created_at),
                       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE USER 'quantacat_app'@'localhost' IDENTIFIED BY 'YOUR_PASSWORD_HERE';

GRANT SELECT, INSERT, UPDATE, DELETE ON utbe_community.* TO 'quantacat_app'@'localhost';