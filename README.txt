ACME Test Proctoring Center Database
Team Guns 'n Roses (Mark Hisle, Tori Daniel, Tianrui Chen)

This project is a PostgreSQL Relational Database made for 
the CSC 362 course at Centre College. The concept of the 
project was to model the process of developing a DB for
a simulated client at a test proctoring center. The process
involved conducting interviews with the client (in the form
of the professor), thoroughly designing the DB (involving
creating tables, field specifications, business rules, ER
diagrams, UI diagrams, etc.), and lastly implementing the
design with PHP.

The SQL code to create tables, views, and add test data
are located in the "code" folder. All design documents
and diagrams are located in the "design" folder. Lastly,
the "html" folder contains all PHP code for the pages
of the UI, which naturally also contain code in HTML
and SQL for queries.

Languages used in this project include PHP, HTML, CSS,
and SQL. PostgreSQL was the RDBMS of choice for the
project, although some initial testing took place
using MySQL. The UI is developed for a LAMP software
stack that is hosted on a Google Cloud Platform
compute engine.


----- USING THE DB -----

You can either register a new user for the DB or use an
existing one. Per request of the "client", newly registered
users must match up with the IDs of existing students,
instructors, or proctors of the DB. Therefore, a randomly chosen
ID will not always have prexisting data associated with it.
However, the users below were used to test the project and
do have appropriate data they can use.

*Note: the passwords used are not realistic and were merely chosen
for ease of use. However, password security was taken into 
account during design and hashing and salting takes place.

User logins (username, password):

Student login (w/ extra time): mark.hisle , Password
Student login (w/out extra time): drew.moo, Password
Instructor login: tori.daniel, Password
Proctor login: tianrui.chen, 123456

*Note: The DB is designed to be time sensitive in how it creates
its schedule and records. Therefore, depending upon when
the data is viewed, all records may appear as old data
and there will be no schedule for the future.


----- CREDITS -----

Mark Hisle (class of '20): login and registration pages, student pages,
CSS, miscelaneous bug fixes and site strucure design

Tori Daniel (class of '20): all instructor pages, various business
rules

Tianrui Chen (class of '21): all proctor pages, CSS

All students took part in the design process.
