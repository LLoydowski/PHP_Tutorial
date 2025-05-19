# Querries

-   CREATE DATABASE myApp
-   CREATE TABLE users(id int PRIMARY KEY AUTO_INCREMENT, name varchar(64) unique, password varchar(64))
-   CREATE TABLE posts(id int PRIMARY KEY AUTO_INCREMENT, title varchar(64), imgSrc varchar(512), author varchar(64), date TIMESTAMP default NOW())
-   CREATE USER 'postsApp'@'%' IDENTIFIED BY '1234';
