CREATE DATABASE `db_camagru`;

use db_camagru;

CREATE TABLE users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    login VARCHAR(255) UNIQUE NOT NULL,
    mail VARCHAR(255) UNIQUE NOT NULL,
    confirmed BOOLEAN NOT NULL DEFAULT 0,
    confirmation_hash VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    createdAt DATETIME NOT NULL
);

CREATE TABLE photos(
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    img_path VARCHAR(1024),
    createdAt DATETIME NOT NULL
);

CREATE TABLE comments(
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    photo_id INT NOT NULL,
    content TEXT,
    createdAt DATETIME NOT NULL
);

CREATE TABLE likes(
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    photo_id INT NOT NULL,
    createdAt DATETIME NOT NULL
);
