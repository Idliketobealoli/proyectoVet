DROP DATABASE IF EXISTS clinica;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS clinica DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE clinica;

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
    id int(11) NOT NULL AUTO_INCREMENT,
    username varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
    surname varchar(80) DEFAULT NULL,
    email varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL UNIQUE,
    password varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
    phone varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL UNIQUE,
    userRole int(11) NOT NULL DEFAULT 1,
    cookieCode varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
    cookieCodeExpiryDate timestamp NULL DEFAULT NULL,
    active tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
    ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

TRUNCATE TABLE users;
INSERT INTO users (id, username, surname, email, password, phone, userRole, cookieCode, cookieCodeExpiryDate, active) VALUES (1, 'admin', '1234', 'admin@gmail.com', 'admin1234', '620797979', 0, NULL, NULL, 1);

DROP TABLE IF EXISTS pets;
CREATE TABLE IF NOT EXISTS pets (
    id int(11) NOT NULL AUTO_INCREMENT,
    petname varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
    species varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
    sex tinyint(1) NOT NULL DEFAULT 0,
    active tinyint(1) NOT NULL DEFAULT 0,
    ownerId int(11) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (ownerId) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

TRUNCATE TABLE pets;

DROP TABLE IF EXISTS histories;
CREATE TABLE IF NOT EXISTS histories (
    id int(11) NOT NULL AUTO_INCREMENT,
    history TEXT,
    observations TEXT,
    petId int(11) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (petId) REFERENCES pets(id) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

TRUNCATE TABLE histories;
COMMIT;