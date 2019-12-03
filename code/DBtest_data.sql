/*
	Script to add dummy data into database and
	test existing tables and relations.

	See DBcode.sql for more information.


*/


INSERT INTO students (student_first_name, student_last_name, student_email, 
		     student_mobile_number,
		     student_extra_time, student_is_active)
VALUES	('Cathy', 'Yan', 'cathy.yan@centre.edu', '445-987-0500', 
	FALSE, TRUE),
	('Mark', 'Hisle', 'mark.hisle@centre.edu', '859-456-6625', 
	TRUE, TRUE),
        ('Tori', 'Daniel', 'tori.daniel@gmail.com', '567-893-1427', 
	FALSE, TRUE),
	('Terry', 'Chen', 'terry.chen@centre.edu', '758-182-0000', 
	FALSE, TRUE),
	('Tianrui', 'Chen', 'tianrui.chen@centre.edu', '388-563-7477', 
	FALSE, FALSE),
	('Allie', 'Riley', 'allie.riley@centre.edu', '606-744-1122', 
	TRUE, TRUE),
        ('Philip', 'Parker', 'philip.parker@centre.edu', '555-719-9966', 
	FALSE, TRUE),
	('Madi', 'Bates', 'madi.bates@centre.edu', '859-808-1665', 
	FALSE, TRUE),
	('Justin', 'Germann', 'justin.germann@centre.edu', '859-808-4444', 
	FALSE, TRUE);

INSERT INTO proctors (proctor_first_name, proctor_last_name, proctor_email,
		     proctor_home_phone, proctor_shift,
	             proctor_is_active)
VALUES	('Casey', 'Thompson', 'casey.boi@yahoo.com', '771-445-1983',
	'-M-W-F-', TRUE),
	('Kaj', 'Den Ouden', 'kaj.den_ouden@aol.com', '655-737-1194',
	'-MTWRF-', TRUE),
	('Alexander', 'Leff', 'mrloaf@bellsouth.net', '885-725-2348',
	'-M-W-FS', FALSE),
	('Ailiyah', 'Alim', 'a.a@yahoo.com', '772-445-1983',
	'SMTWRFS', TRUE);


INSERT INTO instructors (instructor_first_name, instructor_last_name, 
		   instructor_email, instructor_office_phone, 
	           instructor_office_number, instructor_is_active)
VALUES	('Patrick', 'Rick', 'p.leahey@centre.edu', '667-984-6222',
	'B243', FALSE),
	('Bob', 'Allen', 'b.allen@centre.edu', '462-444-1732',
	'Q742', TRUE),
	('Daniel', 'Toth', 'd.toth@centre.edu', '123-456-1234',
	'Y344', TRUE),
	('Mitchell', 'Bradshaw', 'm.bradshaw@centre.edu', '859-444-8516',
	'B245', TRUE);


INSERT INTO tests (instructor_id, test_date, test_length, test_version,
		  test_course , test_file_blob, test_start_time, test_status)
VALUES	(2, '06-23-2019', '02:00:00', '1.0.2', 'Statistical Modeling', 
	'0110101', '08:00:00', 'Good'),
	(3, '10-11-2019', '02:00:00', '1.0.1', 'Intro to Math', '0110101', 
	'08:00:00', 'Good'),
	(4, '11-01-2019', '04:00:00', '1.0.0', 'Outro to Reading', '0110101', 
	'12:00:00', 'Bad'),
	(2, '08-18-2019', '02:00:00', '1.0.0', 'Senior Seminar', '0110101', 
	'12:00:00', 'Idk'),
	(2, '02-23-2016', '02:00:00', '1.0.2', 'Macroeconomics', '0110101', 
	'08:00:00', 'Good'),
	(3, '03-23-2021', '02:00:00', '2.0.2', 'Environmental Studies',
	'0110101', '18:00:00', 'Good'),
	(2, '04-25-2020', '02:00:00', '3.0.1', 'Databases', '0110101', 
	'08:00:00', 'Good'),
	(1, '01-22-2017', '06:00:00', '1.0.2', 'Algorithms', '0110101', 
	'18:00:00', 'Good');


INSERT INTO seats (seat_id, room_id, is_computer)
VALUES	(1, 1, TRUE),
	(2, 1, TRUE),
	(3, 1, TRUE),
	(4, 1, TRUE),
	(5, 1, TRUE),
	(6, 1, TRUE),
	(7, 1, FALSE),
	(8, 1, FALSE),
	(9, 1, FALSE),
	(10, 1, FALSE),
	(11, 1, FALSE),
	(12, 1, FALSE),
	(13, 1, FALSE),
	(14, 1, FALSE),
	(15, 1, FALSE),
	(16, 1, FALSE),
	(17, 1, FALSE),
	(18, 1, FALSE),
	(19, 1, FALSE),
	(20, 1, FALSE),
	(21, 1, FALSE),
	(22, 1, FALSE);


INSERT INTO reservations (student_id, test_id, seat_id, test_time_stamp)
VALUES	(1,1,1, '2019-10-19 10:23:54'),
	(2,2,3, '2019-11-23 11:10:15'),
	(3,1,4, '2019-11-23 11:00:00');
