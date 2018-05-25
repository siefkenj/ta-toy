# Create Testuser
CREATE USER 'myuser'@'localhost' IDENTIFIED BY 'mypassword';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON *.* TO 'myuser'@'localhost';

# Create DB
CREATE DATABASE IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `mydb`;

CREATE TABLE IF NOT EXISTS `mydb`.`courses` (
		`Course` VARCHAR(255) NOT NULL,
		`TA` VARCHAR(255) NOT NULL,
		`Sections` VARCHAR(255) NOT NULL,
		PRIMARY KEY (`Course`,`TA`,`Sections`)
);
insert into mydb.courses values('MAT135H1F','Tom Day','TUT0401');
insert into mydb.courses values('MAT135H1F','Ethan Fletcher','TUT0402');
insert into mydb.courses values('MAT135H1F','David Gray','TUT0403');
insert into mydb.courses values('MAT135H1F','Jake Booth','TUT0404');
insert into mydb.courses values('MAT135H1F','Gabriel Kennedy','TUT0405');
insert into mydb.courses values('MAT135H1F','Louis Short','TUT0406');
insert into mydb.courses values('MAT135H1F','Darian Mccullough','TUT0407');
insert into mydb.courses values('MAT135H1F','Leandro Price','TUT0408');
insert into mydb.courses values('MAT135H1F','Markus Casey','TUT0409');
insert into mydb.courses values('MAT135H1F','Darien Hansen','TUT0410');
insert into mydb.courses values('CSC236H1S','Yasmin Baker','TUT0501');
insert into mydb.courses values('CSC236H1S','Yasmin Baker','TUT1501');
insert into mydb.courses values('CSC236H1S','Yasmin Baker','TUT2501');
insert into mydb.courses values('CSC236H1S','Natasha Lawrence','TUT0502');
insert into mydb.courses values('CSC236H1S','Isabella Roberts','TUT0503');
insert into mydb.courses values('CSC236H1S','Megan Hawkins','TUT0504');
insert into mydb.courses values('CSC236H1S','Micah Meyer','TUT0505');
insert into mydb.courses values('CSC236H1S','Macey Gutierrez','TUT0506');
insert into mydb.courses values('CSC236H1S','Bethany Branch','TUT0507');
insert into mydb.courses values('CSC236H1S','Evalyn Zamora','TUT0508');
insert into mydb.courses values('CSC236H1S','Hanna Rutledge','TUT0509');
