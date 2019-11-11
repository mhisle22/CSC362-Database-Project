DROP TABLE IF EXISTS seats;
DROP TABLE IF EXISTS tests;
DROP TABLE IF EXISTS instructors;
DROP TABLE IF EXISTS proctors;
DROP TABLE IF EXISTS students;



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
       student_extra_time	TIME, -- I'm not sure what kind of data type we gonna use for this... In FS, we said it's NUMERIC but the input mask is XX:XX.
       student_is_active 	BOOLEAN NOT NULL DEFAULT TRUE
);

/*--deletion rule for students
CREATE RULE students_for_life AS
       ON DELETE TO students DO INSTEAD
       UPDATE students
       SET student_is_active = FALSE
       WHERE student_id = OLD.student_id;
*/

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
       instrcutor_course_taught VARCHAR(25) NOT NULL,
       instrcutor_office_number	VARCHAR(4)  NOT NULL,
       instructor_is_active     BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE tests(
       PRIMARY KEY(test_id),
       test_id			SERIAL,
       instructor_id		INT NOT NULL
       				REFERENCES instructors(instructor_id)
				ON DELETE RESTRICT,
       test_date		DATE NOT NULL,				
       test_length		INTERVAL NOT NULL,
       test_version		VARCHAR(20),--Length is not defined in the FS...

       test_file_blob		VARCHAR(20) NOT NULL,-----the BLOB type doesn't exist. I'm confused with the FS for this field.

       test_start_time		TIME NOT NULL,
       test_status		VARCHAR(15) NOT NULL--incomplete's length is 10, but I put 15 here in case of the length of the other situation is more than 10.
);

CREATE TABLE seats(
       PRIMARY KEY(seat_id,room_id),
       seat_id			SERIAL,--but is from 01-22. I don't know how to impletement this so I use serial instead. Maybe we just make the type numeric(2) and enter the seat data with seat id from 1-22
       room_id 			NUMERIC(3) NOT NULL,
       if_computer		BOOLEAN NOT NULL
);
