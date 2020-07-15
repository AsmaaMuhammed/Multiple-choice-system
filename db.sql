CREATE TABLE `roles` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL,
 `description` text NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `classes` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL,
 `description` text NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `permissions` (
 `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `name` varchar(255) NOT NULL UNIQUE KEY,
 `method` varchar(255) NOT NULL UNIQUE KEY,
 `description` text NOT NULL
);

CREATE TABLE `users`(
    `id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `role_id` INT(11) DEFAULT NULL,
    `class_id` INT(11) DEFAULT NULL,
    `username` VARCHAR(255) UNIQUE NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `profile_picture` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `users_ibfk_1` FOREIGN KEY(`role_id`) REFERENCES `roles`(`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
    CONSTRAINT `user_class_ibfk_1` FOREIGN KEY(`class_id`) REFERENCES `classes`(`id`) ON DELETE SET NULL ON UPDATE NO ACTION
);

CREATE TABLE `permission_role` (
 `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
 `role_id` int(11) NOT NULL,
 `permission_id` int(11) NOT NULL,
 KEY `role_id` (`role_id`),
 KEY `permission_id` (`permission_id`),
 CONSTRAINT `permission_role_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `permission_role_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`)
);



CREATE TABLE `tests` (
 `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
 `class_id` INT(11) DEFAULT NULL,
 `name` varchar(255) NOT NULL,
 `description` text NOT NULL,
 `test_grade` int(11) NOT NULL,
 `is_active` int(2) DEFAULT 1,
  CONSTRAINT `class_ibfk_1` FOREIGN KEY(`class_id`) REFERENCES `classes`(`id`) ON DELETE SET NULL ON UPDATE NO ACTION
);
CREATE TABLE `questions` (
 `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
 `test_id` INT(11) DEFAULT NULL,
 `name` text NOT NULL,
 `choice1` varchar(255) NOT NULL,
 `choice2` varchar(255) NOT NULL,
 `choice3` varchar(255) NOT NULL,
 `choice4` varchar(255) NOT NULL,
 `correct_answer` varchar(255) NOT NULL,
 `ques_grade` int(11) NOT NULL,
  CONSTRAINT `test_ibfk_1` FOREIGN KEY(`test_id`) REFERENCES `tests`(`id`) ON DELETE SET NULL ON UPDATE NO ACTION
);

CREATE TABLE `users_answers` (
 `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
 `user_id` INT(11) DEFAULT NULL,
 `test_id` INT(11) DEFAULT NULL,
 `ques_id` INT(11) DEFAULT NULL,
 `user_answer` varchar(255) NOT NULL,
 `user_grade` int NOT NULL,
  CONSTRAINT `test_user_ibfk_1` FOREIGN KEY(`test_id`) REFERENCES `tests`(`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `quest_ibfk_1` FOREIGN KEY(`ques_id`) REFERENCES `questions`(`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `user_ques_ibfk_1` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE NO ACTION
);



--   Samples
INSERT INTO `roles`(`name`, `description`) 
VALUES  ('Admin', 'Has roles to view all the tests, users, questions, and add, edit or delete these items' ), 
		('User', 'Has roles to login, take a test, and answer the questions, review a report of his answers with the correct answers and grade');

INSERT INTO `classes`(`name`, `description`) 
VALUES  ('Class A', 'For En'), 
    ('Class B', 'For Fr'), 
		('Class C', 'For Math'),
    ('Class D', 'For Ar'),
    ('Class E', 'For Geo');

INSERT INTO `users` (`email`, `username`, `password`, `role_id`, `class_id`) VALUES
('john@webw.com', 'John Doe', '123456', 1, 1),
('jane@webw.com', 'Jane Doe', '123456', 2, 2);



INSERT INTO `permissions` (`name`,`method`, `description`) VALUES

('Access users','user_List','Access users'),
('Create and Update users','user_Form','Create and Update users'),
('Delete users','user_List', 'Delete users'),

('Access tests','test_List','Access tests'),
('Create and Update tests','test_Form','Create and Update tests'),
('Delete tests','test_List', 'Delete tests'),

('Access questions','question_List','Access questions'),
('Create and Update questions','question_Form','Create and Update questions'),
('Delete questions','question_List', 'Delete questions'),

  ('Access roles','role_List','Access role'),
  ('Create and Update role','role_Form','Create and Update role'),
  ('Delete role','role_List', 'Delete role'),

('view Test and Answer Questions','question_Answer','View Test and Answer Questions'),
('Review a report of his answers with the correct answers and grade','question_Report','Review a report of his answers with the correct answers and grade');

INSERT INTO `permission_role` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(2, 13),
(2, 14);


