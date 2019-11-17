/*
	CSC 362 Database Project
	
	Team Guns 'n Roses, the best team
	
	This is starter code to create the basics
	of the ACME Test Proctoring Center. This
	includes all of its tables, select deletion
	rules, participation rules, and table
	relations.

	See the design documentation for more
	information on the database itself.
*/

DROP DATABASE IF EXISTS gunsnrosesproject;
CREATE DATABASE gunsnrosesproject;


DROP TABLE IF EXISTS reservations CASCADE;
DROP TABLE IF EXISTS seats CASCADE;
DROP TABLE IF EXISTS tests CASCADE;
DROP TABLE IF EXISTS instructors CASCADE;
DROP TABLE IF EXISTS proctors CASCADE;
DROP TABLE IF EXISTS students CASCADE;
DROP TABLE IF EXISTS users CASCADE;


--users of the DB, used in login screen of DB
CREATE TABLE users(
	PRIMARY KEY(user_id),
	user_id			SERIAL,
	username 		VARCHAR(30) NOT NULL UNIQUE,
	password		VARCHAR(255) NOT NULL,
	role			VARCHAR(10) NOT NULL
);


--representation of student users in the database
CREATE TABLE students(
       PRIMARY KEY(student_id),
       student_id		SERIAL, --or NUMERICAL XXXXXX? I'm not that confident with serial for all pk for students,proctors, and instrucors.
       student_first_name	VARCHAR(20) NOT NULL,
       student_last_name   	VARCHAR(20) NOT NULL,
       student_email		VARCHAR(30) NOT NULL UNIQUE,
       student_mobile_number	CHAR(12)    NULL UNIQUE -- pattern: 123-456-7899
       				CONSTRAINT valid_student_phone_number
				CHECK (student_mobile_number SIMILAR TO '[0-9]{3}\-[0-9]{3}\-[0-9]{4}'),
       student_password		VARCHAR(25) NOT NULL, --maybe we need a bussiness rule here? Like to make it unique?
       student_extra_time	BOOLEAN DEFAULT FALSE, -- I'm not sure what kind of data type we gonna use for this... In FS, we said it's NUMERIC but the input mask is XX:XX.
       student_is_active 	BOOLEAN NOT NULL DEFAULT TRUE
);

--deletion rule for students
CREATE RULE students_for_life AS
       ON DELETE TO students DO INSTEAD
       UPDATE students
       SET student_is_active = FALSE
       WHERE student_id = OLD.student_id;



--representation of proctor users in the database
CREATE TABLE proctors(
       PRIMARY KEY(proctor_id),
       proctor_id               SERIAL,
       proctor_first_name       VARCHAR(20) NOT NULL,
       proctor_last_name        VARCHAR(20) NOT NULL,
       proctor_email            VARCHAR(30) NOT NULL UNIQUE,
       proctor_home_phone    	CHAR(12)    NULL UNIQUE -- pattern: 123-456-7899
       				CONSTRAINT valid_proctor_home_phone
				CHECK (proctor_home_phone SIMILAR TO '[0-9]{3}\-[0-9]{3}\-[0-9]{4}'),
       proctor_password         VARCHAR(25) NOT NULL,
       proctor_shift		VARCHAR(20),-- In the FS, The datatype is ALPHANUMERIC but is not green when I type, so I just use NUMERIC instead. 
       proctor_is_active        BOOLEAN NOT NULL DEFAULT TRUE
);


--representation of instructor users in the database
CREATE TABLE instructors(
       PRIMARY KEY(instructor_id),
       instructor_id		SERIAL,
       instructor_first_name    VARCHAR(20) NOT NULL,
       instructor_last_name     VARCHAR(20) NOT NULL,
       instructor_email         VARCHAR(30) NOT NULL UNIQUE,
       instructor_office_phone	CHAR(12)    NULL UNIQUE -- pattern: 123-456-7899
       				CONSTRAINT valid_instructor_office_phone
				CHECK (instructor_office_phone SIMILAR TO '[0-9]{3}\-[0-9]{3}\-[0-9]{4}'),
       instructor_password	VARCHAR(25) NOT	NULL,
       instructor_office_number	VARCHAR(4)  NOT NULL,
       instructor_is_active     BOOLEAN NOT NULL DEFAULT TRUE
);


--deletion rule for students
CREATE RULE instructor_for_life AS
       ON DELETE TO instructors DO INSTEAD
       UPDATE instructors
       SET instructor_is_active = FALSE
       WHERE instructor_id = OLD.instructor_id;


--data for tests
CREATE TABLE tests(
       PRIMARY KEY(test_id),
       test_id			SERIAL,
       instructor_id		INT NOT NULL
       				REFERENCES instructors(instructor_id)
				ON DELETE RESTRICT,
       test_date		DATE NOT NULL,				
       test_length		INTERVAL NOT NULL,
       test_version		VARCHAR(20),--Length is not defined in the FS...
       test_course		VARCHAR(30) NOT NULL,
       test_file_blob		bytea NOT NULL,--bytea is a blob type

       test_start_time		TIME NOT NULL,
       test_status		VARCHAR(15) NOT NULL--incomplete's length is 10, but I put 15 here in case of the length of the other situation is more than 10.
);


--representation of seats in proctoring centers
--used as a pseudo-validation table
CREATE TABLE seats(
       PRIMARY KEY(seat_id,room_id),
       seat_id			NUMERIC(2) NOT NULL UNIQUE,--but is from 01-22
       room_id 			NUMERIC(3) NOT NULL,
       is_computer		BOOLEAN NOT NULL
);


--linking table connecting students, tests, proctors, and seats
CREATE TABLE reservations(
       PRIMARY KEY(student_id, test_id, seat_id, test_time_stamp),
       student_id		SERIAL NOT NULL
				REFERENCES students (student_id)
				ON DELETE RESTRICT,		
       test_id			SERIAL NOT NULL
				REFERENCES tests (test_id)
				ON DELETE RESTRICT,
       seat_id			SERIAL NOT NULL
				REFERENCES seats (seat_id)
				ON DELETE RESTRICT,
       test_time_stamp		TIMESTAMP NOT NULL,
       proctor_id               SERIAL NOT NULL
				REFERENCES proctors (proctor_id)
				ON DELETE RESTRICT
);
