drop database if exists bancoapirest;
create database bancoapirest;
use bancoapirest;
create table users(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) not null,
    name_user VARCHAR(100) not null,
    password_user VARCHAR(100) not null,
    drink_counter int,
    drink_ml int

);