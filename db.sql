CREATE DATABASE forum;
USE forum;

CREATE TABLE users (
user_id int(8) PRIMARY KEY AUTO_INCREMENT,
user_mail varchar(64) UNIQUE,
user_nickname varchar(32) UNIQUE,
user_real_name varchar(32) ,
user_birthdate varchar(32) ,
user_password varchar(64) 
);

CREATE TABLE forums(
forum_id int(8) PRIMARY KEY AUTO_INCREMENT,
forum_name varchar(32) 
);

CREATE TABLE threads(
thread_id int(8) PRIMARY KEY AUTO_INCREMENT,
thread_forum_id int(8),
thread_user_id int(8),
thread_name varchar(32) ,
thread_desc varchar(32),
FOREIGN KEY (thread_forum_id) REFERENCES forums(forum_id),
FOREIGN KEY (thread_user_id) REFERENCES users(user_id)
);

CREATE TABLE posts(
post_id int(8) PRIMARY KEY AUTO_INCREMENT,
post_thread_id int(8),
post_user_id int(8),
post_content text(500), 
FOREIGN KEY (post_thread_id) REFERENCES threads(thread_id),
FOREIGN KEY (post_user_id) REFERENCES users(user_id)
);

INSERT INTO forums (forum_name) VALUES ("Forum 1");
INSERT INTO forums (forum_name) VALUES ("Forum 2");
INSERT INTO forums (forum_name) VALUES ("Forum 3");
INSERT INTO forums (forum_name) VALUES ("Forum 4");
