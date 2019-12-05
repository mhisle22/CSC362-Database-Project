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
DROP VIEW IF EXISTS upcoming_instructors;
DROP VIEW IF EXISTS work_schedule;
DROP VIEW IF EXISTS today_schedule;



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

CREATE VIEW upcoming_instructors
AS
SELECT student_first_name || ' ' || student_last_name AS student_name, test_date, test_start_time, test_length, test_status, instructor_id
FROM reservations
NATURAL JOIN students
NATURAL JOIN tests
GROUP BY tests.instructor_id, students.student_first_name, students.student_last_name, tests.test_date, tests.test_start_time, tests.test_length, tests.test_status;


CREATE VIEW today_schedule AS
SELECT proctor_id, test_date,student_first_name || ' ' || student_last_name AS student_name, test_id, seat_id, test_start_time, test_start_time+test_length AS test_end_time
FROM proctors
NATURAL JOIN reservations
NATURAL JOIN tests
NATURAL JOIN students
NATURAL JOIN seats
ORDER BY test_start_time;



CREATE VIEW work_schedule AS
SELECT proctor_id, test_id,test_date,test_start_time || '-' ||(test_start_time+test_length) AS time_slot
FROM proctors
NATURAL JOIN reservations
NATURAL JOIN tests
ORDER BY test_date;
