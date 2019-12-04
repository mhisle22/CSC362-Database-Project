DROP VIEW IF EXISTS today_schedule;

CREATE VIEW today_schedule AS
SELECT proctor_id, student_first_name || ' ' || student_last_name AS student_name, test_id, test_start_time, test_start_time+test_length AS test_end_time
FROM proctors
NATURAL JOIN reservations
NATURAL JOIN tests
NATURAL JOIN students;

/*SELECT *
FROM today_schedule;
*/
