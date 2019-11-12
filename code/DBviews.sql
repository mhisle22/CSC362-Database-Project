/*
	CSC 362 Database Project
	
	Team Guns 'n Roses, the best team
	
	Script to create views for the DB.
*/


DROP VIEW IF EXISTS reservations_view;
DROP VIEW IF EXISTS inactive_instructors;
DROP VIEW IF EXISTS inactive_students;
DROP VIEW IF EXISTS students_view;
DROP VIEW IF EXISTS proctors_view;
DROP VIEW IF EXISTS instructors_view;
DROP VIEW IF EXISTS students_tests;



CREATE VIEW students_views
AS
SELECT student_id, 
       student_first_name || ' ' || student_last_name AS student_name,
       student_email, student_extra_time
  FROM students
WHERE student_is_active = TRUE;


CREATE VIEW instructors_views
AS
SELECT instructor_id, instructor_first_name || ' ' || 
       instructor_last_name AS instructor_name, instructor_email, 
       instructor_office_phone, instructor_office_number
  FROM instructors
WHERE instructor_is_active = TRUE;


CREATE VIEW proctors_views
AS
SELECT proctor_id, 
       proctor_first_name || ' ' || proctor_last_name AS proctor_name,
       proctor_email, proctor_shift 
  FROM proctors;


CREATE VIEW students_tests
AS
SELECT student_id, 
       student_first_name || ' ' || student_last_name AS student_name,
       string_agg(test_course, ', ')
  FROM students
	NATURAL JOIN reservations
	NATURAL JOIN tests
GROUP BY student_id, student_first_name, student_last_name;

 
CREATE VIEW inactive_students
AS
SELECT student_id, 
       student_first_name || ' ' || student_last_name AS student_name,
       student_email, student_extra_time
  FROM students
WHERE student_is_active = FALSE;


CREATE VIEW inactive_instructors
AS
SELECT instructor_id, instructor_first_name || ' ' || 
       instructor_last_name AS instructor_name, instructor_email, 
       instructor_office_phone, instructor_office_number
  FROM instructors
WHERE instructor_is_active = FALSE;


CREATE VIEW reservations_views
AS
SELECT test_time_stamp, test_id, test_course
  FROM reservations
	NATURAL JOIN tests;

