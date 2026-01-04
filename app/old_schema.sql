-- === Database ===
DROP DATABASE IF EXISTS `student_database_app_db`;
CREATE DATABASE `student_database_app_db`;
USE `student_database_app_db`;

-- === School Years ===
DROP TABLE IF EXISTS `school_years_tbl`;
CREATE TABLE `school_years_tbl` (
    `id` INT NOT NULL,
    `name` VARCHAR(250) NOT NULL,

    -- timestamps
    `created_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

-- === Grade Levels ===
DROP TABLE IF EXISTS `grade_levels_tbl`;
CREATE TABLE `grade_levels_tbl` (
    `id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,

    -- timestamps
    `created_at` DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

-- === Sections ===
DROP TABLE IF EXISTS `sections_tbl`;
CREATE TABLE `sections_tbl` (
    `id` INT NOT NULL,
    `school_year_id` INT NOT NULL,
    `grade_level_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,

    -- timestamps
    `created_at` DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

-- === Courses (Subjects) ===
DROP TABLE IF EXISTS `courses_tbl`;
CREATE TABLE `courses_tbl` (
    `id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,

    -- timestamps
    `created_at` DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

-- === Activity types ===
DROP TABLE IF EXISTS `activity_types_tbl`;
CREATE TABLE `activity_types_tbl` (
    `id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `weight` INT NOT NULL,
    `course_id` INT NOT NULL,

    -- timestamps
    `created_at` DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

-- === Activities ===
DROP TABLE IF EXISTS `activities_tbl`;
CREATE TABLE `activities_tbl` (
    `id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `maximum_score` INT NOT NULL,
    `course_id` INT NOT NULL,
    `type_id` INT NOT NULL, 

    -- timestamps
    `created_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

-- === Scores ===
DROP TABLE IF EXISTS `scores_tbl`;
CREATE TABLE `scores_tbl` (
    `id` INT NOT NULL,
    `score` INT NOT NULL,
    `activity_id` INT NOT NULL,
    `student_id` INT NOT NULL,

    -- timestamps
    `created_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

-- === Students ===
DROP TABLE IF EXISTS `students_tbl`;
CREATE TABLE `students_tbl` (
    `id` INT NOT NULL,
    `lrn` INT NOT NULL,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(55) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `contact_number` VARCHAR(30) NOT NULL,
    `address` VARCHAR(100) NULL DEFAULT NULL,
    `guardian` VARCHAR(100) NULL DEFAULT NULL,
    `guardian_contact_number` VARCHAR(30) NULL DEFAULT NULL,
    `section_id` INT NOT NULL,

    -- timestamps
    `created_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS `classes_tbl`;
CREATE TABLE `classes_tbl` (
    `id` INT NOT NULL,
    `teacher_id` INT NOT NULL,
    `section_id` INT NOT NULL,
    `course_id` INT NOT NULL,

    -- timestamps
    `created_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

-- === teachers (the actual users) ===
DROP TABLE IF EXISTS `teachers_tbl`;
CREATE TABLE `teachers_tbl` (
    `id` INT NOT NULL,

    -- basic metadata.
    `first_name` VARCHAR(50),
    `last_name` VARCHAR(50),

    `email` VARCHAR(100),
    `contact_number` VARCHAR(20),

    `address` VARCHAR(150) NULL,

    -- authentication flow
    `password_hash` VARCHAR(60),

    -- timestamps
    `created_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL 
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

-- The keys are added later to make table creation
-- order insensitive. foreign keys declared directly
-- will cause sensitive creation ordering, which is hard
-- to deal with with this many tables, so here, we are using
-- the late keys style via `ALTER TABLE`
-- also, AUTO_INCREMENT attribute is also added late since
-- it has to be the primary key to be auto_incremented.
-- `students` is a special case since there can be multiple records
-- of one student with different grade levels and sections,
-- each student record only has a reference to the section id
-- since we cannot store raw objects in mysql like in other real
-- programming languages, we have to give a record a unique identifier
-- so that we can identify the record later, hence, the reference
-- `section_id` in `students`.

-- === Primary Keys ===
ALTER TABLE `school_years_tbl`
    ADD CONSTRAINT `pk_school_years_id`
        PRIMARY KEY (`id`),
    MODIFY `id` INT AUTO_INCREMENT NOT NULL;

ALTER TABLE `grade_levels_tbl`
    ADD CONSTRAINT `pk_grade_levels_id`
        PRIMARY KEY (`id`),
    MODIFY `id` INT AUTO_INCREMENT NOT NULL;

ALTER TABLE `sections_tbl`
    ADD CONSTRAINT `pk_sections_id` 
        PRIMARY KEY (`id`),
    MODIFY `id` INT AUTO_INCREMENT NOT NULL;

ALTER TABLE `courses_tbl`
    ADD CONSTRAINT `pk_courses_id` 
        PRIMARY KEY (`id`),
    MODIFY `id` INT AUTO_INCREMENT NOT NULL;

ALTER TABLE `activity_types_tbl`
    ADD CONSTRAINT `pk_activity_types_id`
        PRIMARY KEY (`id`),
    MODIFY `id` INT AUTO_INCREMENT NOT NULL;

ALTER TABLE `activities_tbl`
    ADD CONSTRAINT `pk_activities_id`
        PRIMARY KEY (`id`),
    MODIFY `id` INT AUTO_INCREMENT NOT NULL;

ALTER TABLE `scores_tbl`
    ADD CONSTRAINT `pk_scores_id`
        PRIMARY KEY (`id`),
    MODIFY `id` INT AUTO_INCREMENT NOT NULL;

ALTER TABLE `students_tbl`
    ADD CONSTRAINT `pk_students_id`
        PRIMARY KEY (`id`),
    MODIFY `id` INT AUTO_INCREMENT NOT NULL;

    ALTER TABLE `classes_tbl`
    ADD CONSTRAINT `pk_classes_id`
        PRIMARY KEY (`id`),
    MODIFY `id` INT AUTO_INCREMENT NOT NULL;


ALTER TABLE `teachers_tbl`
    ADD CONSTRAINT `pk_teachers_id`
        PRIMARY KEY (`id`),
    MODIFY `id` INT AUTO_INCREMENT NOT NULL;

-- === Foreign Keys ===
ALTER TABLE `sections_tbl`
    ADD CONSTRAINT `fk_sections_school_years`
        FOREIGN KEY (`school_year_id`)
            REFERENCES `school_years_tbl` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_sections_grade_levels`
        FOREIGN KEY (`grade_level_id`)
            REFERENCES `grade_levels_tbl` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE;

ALTER TABLE `activity_types_tbl`
    ADD CONSTRAINT `fk_activity_types_courses`
        FOREIGN KEY (`course_id`)
            REFERENCES `courses_tbl` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE;

ALTER TABLE `activities_tbl`
    ADD CONSTRAINT `fk_activities_courses`
        FOREIGN KEY (`course_id`)
            REFERENCES `courses_tbl` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_activities_activity_types`
        FOREIGN KEY (`type_id`)
            REFERENCES `activity_types_tbl` (`id`)
                ON DELETE CASCADE 
                ON UPDATE CASCADE;

ALTER TABLE `scores_tbl`
    ADD CONSTRAINT `fk_scores_activities`
        FOREIGN KEY (`activity_id`)
            REFERENCES `activities_tbl` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_scores_students`
        FOREIGN KEY (`student_id`)
            REFERENCES `students_tbl` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE;

ALTER TABLE `students_tbl`
    ADD CONSTRAINT `fk_students_sections`
        FOREIGN KEY (`section_id`)
            REFERENCES `sections_tbl` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE;

ALTER TABLE `classes_tbl`
    ADD CONSTRAINT `fk_classes_teacher`
        FOREIGN KEY (`teacher_id`)
            REFERENCES `teachers_tbl` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE;

    ADD CONSTRAINT `fk_classes_section`
    FOREIGN KEY (`section_id`)
        REFERENCES `sections_tbl` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE;

-- === Unique Fields ===
ALTER TABLE `students_tbl`
    ADD CONSTRAINT `unique_student_identifiers`
        UNIQUE (`first_name`, `last_name`, `lrn`, `section_id`);

ALTER TABLE `school_years_tbl`
    ADD CONSTRAINT `unique_school_year_name`
        UNIQUE (`name`);

ALTER TABLE `grade_levels_tbl`
    ADD CONSTRAINT `unique_grade_level_name`
        UNIQUE (`name`);

ALTER TABLE `sections_tbl`
    ADD CONSTRAINT `unique_section_per_year_grade`
        UNIQUE (`school_year_id`, `grade_level_id`, `name`);

ALTER TABLE `courses_tbl`
    ADD CONSTRAINT `unique_course_name`
        UNIQUE (`name`);

ALTER TABLE `activity_types_tbl`
    ADD CONSTRAINT `unique_activity_type_per_course`
        UNIQUE (`course_id`, `name`);

ALTER TABLE `activities_tbl`
    ADD CONSTRAINT `unique_activity_per_course_type`
        UNIQUE (`course_id`, `type_id`, `name`);

ALTER TABLE `scores_tbl`
    ADD CONSTRAINT `unique_score_per_student_activity`
        UNIQUE (`activity_id`, `student_id`);

ALTER TABLE `classes_tbl`
    ADD CONSTRAINT `unique_teacher_and_section_per_class`
        UNIQUE (`teacher_id`, `section_id`, `course_id`);

ALTER TABLE `teachers_tbl`
    ADD CONSTRAINT `unique_teacher_email`
        UNIQUE (`email`);

-- === Indexes ===
CREATE INDEX idx_students_section ON students_tbl(section_id);
CREATE INDEX idx_scores_student ON scores_tbl(student_id);
CREATE INDEX idx_scores_activity ON scores_tbl(activity_id);
CREATE INDEX idx_activities_course ON activities_tbl(course_id);
CREATE INDEX idx_classes_teacher ON classes_tbl(teacher_id);
CREATE INDEX idx_classes_section ON classes_tbl(section_id);

-- === Views ===
-- Public representation of what actually gets queried (fetch),
-- organizes it so that it's items are human readable as
-- much as possible, this removes the id, and the password hash from
-- the user, there is a separate view for querying for the password
-- hash, this abstract away all the JOINs required to get the data to
-- application code (php), and lets it focus on higher level
-- concepts such as auth, and other services.
-- these views also replaces ORMs i would normally use.

CREATE OR REPLACE VIEW `student_scores` AS
SELECT
    s.id AS student_id,
    s.first_name,
    s.last_name,
    s.lrn,
    s.email,
    s.contact_number,
    s.guardian,
    s.guardian_contact_number,
    sec.name AS section_name,
    c.name AS course_name,
    a.name AS activity_name,
    sc.score,
    sc.created_at,
    sc.updated_at
FROM scores_tbl AS sc
JOIN students_tbl AS s
    ON sc.student_id = s.id
JOIN activities_tbl AS a
    ON sc.activity_id = a.id
JOIN courses_tbl AS c
    ON a.course_id = c.id
JOIN sections_tbl AS sec
    ON s.section_id = sec.id;


CREATE OR REPLACE VIEW `student_course_averages` AS
SELECT
    s.id AS student_id,
    s.first_name,
    s.last_name,
    s.lrn,
    sec.name AS section_name,
    c.name AS course_name,
    ROUND(AVG(sc.score), 3) AS average_score
FROM scores_tbl AS sc
JOIN students_tbl AS s
    ON sc.student_id = s.id
JOIN activities_tbl AS a
    ON sc.activity_id = a.id
JOIN courses_tbl AS c
    ON a.course_id = c.id
JOIN sections_tbl AS sec
    ON s.section_id = sec.id
GROUP BY s.id, c.id;


CREATE OR REPLACE VIEW `teachers` AS
SELECT
    t.id AS teacher_id,
    t.first_name,
    t.last_name,
    t.email,
    t.contact_number,
    t.address
FROM teachers_tbl AS t;


CREATE OR REPLACE VIEW `sections` AS
SELECT
    sec.id AS section_id,
    sec.name AS section_name,
    sy.name AS school_year,
    gl.name AS grade_level,
    COUNT(s.id) AS student_count
FROM sections_tbl AS sec
JOIN school_years_tbl AS sy
    ON sec.school_year_id = sy.id
JOIN grade_levels_tbl AS gl
    ON sec.grade_level_id = gl.id
LEFT JOIN students_tbl AS s
    ON s.section_id = sec.id
GROUP BY sec.id, sec.name, sy.name, gl.name;


CREATE OR REPLACE VIEW `password_hashes` AS
SELECT
    t.id AS teacher_id,
    t.password_hash
FROM teachers_tbl AS t;

CREATE OR REPLACE VIEW `classes` AS
    SELECT
        c.id as class_id,
        CONCAT(t.first_name, " ", t.last_name) as teacher_name,
        s.name as section_name,
        sy.name as school_year
    FROM `classes_tbl` c
    JOIN `teachers_tbl` t on t.id = c.teacher_id
    JOIN `sections_tbl` s ON s.id = c.section_id
    JOIN `school_years_tbl` sy ON sy.id = s.school_year_id

SHOW WARNINGS;