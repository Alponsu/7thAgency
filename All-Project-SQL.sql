USE project;

-- 1.) == CREATE TABLES ==

-- Applicant Profile Table
CREATE TABLE applicant_profile (
    applicantID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    fullName VARCHAR(60) NOT NULL,
    address VARCHAR(80) NOT NULL,
    city VARCHAR(30) NOT NULL,
    region VARCHAR(20) NOT NULL,
    zip INT NOT NULL,
    homePhone CHAR(20) NULL,
    cellPhone CHAR(20) NOT NULL,
    emailAddress VARCHAR(60) NOT NULL,
    sssNumber VARCHAR(15) NOT NULL,
    birthDate DATE NOT NULL,
    age INT NOT NULL CHECK (age >= 18 AND age <= 65),
    citizenship VARCHAR(30) NOT NULL,
    sex CHAR(1) NOT NULL CHECK (sex IN ('M', 'F', 'I')), 
    maritalStatus CHAR(2) NOT NULL CHECK (maritalStatus IN ('S', 'M', 'E', 'W', 'SE'))
    );

-- Application Information Table
CREATE TABLE application_information (
    applicationID VARCHAR(10) PRIMARY KEY NOT NULL,
    desiredPosition VARCHAR(50) NOT NULL,
    availWDate DATE NOT NULL,
    desiredSalary VARCHAR(9) NOT NULL,
    desiredEmployment VARCHAR(2) NOT NULL CHECK (desiredEmployment IN ('FT', 'PT', 'S')),
    applicantID INT NOT NULL,
    FOREIGN KEY (applicantID) REFERENCES applicant_profile(applicantID)
);

-- School Details Table
CREATE TABLE school_details (
    schoolID VARCHAR(10) NOT NULL PRIMARY KEY, -- Changed from INT to VARCHAR
    schoolName VARCHAR(100) NOT NULL,
    location VARCHAR(50) NOT NULL,
    UNIQUE KEY unique_school_name_location (schoolName, location)
);

-- Educational Background Table
CREATE TABLE educational_background (
    educalbackID VARCHAR(10) PRIMARY KEY NOT NULL,
    yearsAttended CHAR(10) NOT NULL,
    degree VARCHAR(70) NULL,
    major VARCHAR(40) NULL,
    applicantID INT NOT NULL,
    schoolID VARCHAR(10) NOT NULL,
    FOREIGN KEY (applicantID) REFERENCES applicant_profile(applicantID),
    FOREIGN KEY (schoolID) REFERENCES school_details(schoolID)
);

-- Work Experience Table
CREATE TABLE work_experience (
  employerID VARCHAR(10) PRIMARY KEY NOT NULL,
  employer VARCHAR(60) NOT NULL,
  employerContact CHAR(20) NOT NULL,
  employerAddress VARCHAR(80) NOT NULL,
  dateEmployed DATE NOT NULL,
  employedPosition VARCHAR(40) NOT NULL,
  reasonforLeaving VARCHAR(40) NOT NULL,
  applicantID INT NOT NULL,
  FOREIGN KEY (applicantID) REFERENCES applicant_profile(applicantID)
);

-- Character Reference Table
CREATE TABLE character_reference (
    referenceID VARCHAR(10) PRIMARY KEY NOT NULL,
    refName VARCHAR(60) NOT NULL,
    refTitle VARCHAR(40) NOT NULL,
    refCompany VARCHAR(40) NOT NULL,
    refPhone CHAR(20) NOT NULL,
	applicantID INT NOT NULL,
	FOREIGN KEY (applicantID) REFERENCES applicant_profile(applicantID)
);

-- Login Admin Table
CREATE TABLE login_info(
    email VARCHAR(255) PRIMARY KEY NOT NULL,
    password VARCHAR(255) NOT NULL
);


-- 2.) == CREATE SEQ TABLES ==

-- Application Information
CREATE TABLE app_seq(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY);

-- School Details
CREATE TABLE IF NOT EXISTS sch_seq (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
);

-- Educational Background
CREATE TABLE edu_seq(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY);

-- Work Experience
CREATE TABLE work_seq(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY);

-- Character Reference
CREATE TABLE char_seq(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY);



-- 3.) == CREATE TRIGGERS ==

-- Application Information
DELIMITER //
CREATE TRIGGER tg_applicantID_insert
BEFORE INSERT ON application_information
FOR EACH ROW
BEGIN
    INSERT INTO app_seq VALUES (NULL);
    SET NEW.applicationID = CONCAT(NEW.applicantID, '-Al', LPAD(LAST_INSERT_ID(), 3, '0'));
END;
//
DELIMITER ;

-- School Details
DELIMITER //
CREATE TRIGGER tg_schoolID_insert
BEFORE INSERT ON school_details
FOR EACH ROW
BEGIN
    DECLARE new_schoolID VARCHAR(10);
    INSERT INTO sch_seq VALUES (NULL);
    SET new_schoolID = CONCAT('SCH-', LPAD(LAST_INSERT_ID(), 3, '0'));
    SET NEW.schoolID = new_schoolID;
END;

CREATE PROCEDURE InsertOrUpdateSchool(
    IN p_schoolName VARCHAR(100),
    IN p_location VARCHAR(50),
    IN p_applicantID INT
)
BEGIN
    DECLARE existing_schoolID VARCHAR(10);

    -- Check if a school with the same name and location exists
    SELECT schoolID INTO existing_schoolID
    FROM school_details
    WHERE schoolName = p_schoolName AND location = p_location;

    IF existing_schoolID IS NOT NULL THEN
        -- School already exists, use the existing schoolID
        -- Update the applicant's schoolID in the educational_background table
        UPDATE educational_background
        SET schoolID = existing_schoolID
        WHERE applicantID = p_applicantID;

        SELECT CONCAT('Existing school details found. Using schoolID: ', existing_schoolID) AS message, existing_schoolID AS schoolID;
    ELSE
        -- School does not exist, insert new record and generate new schoolID
        INSERT INTO school_details (schoolName, location)
        VALUES (p_schoolName, p_location);

        SET existing_schoolID = LAST_INSERT_ID(); -- Get the last inserted schoolID

        -- Update the applicant's schoolID in the educational_background table
        UPDATE educational_background
        SET schoolID = CONCAT('SCH-', LPAD(existing_schoolID, 3, '0'))
        WHERE applicantID = p_applicantID;

        SELECT CONCAT('New school details inserted. Using schoolID: ', existing_schoolID) AS message, existing_schoolID AS schoolID;
    END IF;

END 
//
DELIMITER ;

//
DELIMITER ;

-- Educational Background
DELIMITER //
CREATE TRIGGER tg_educalbackID_insert
BEFORE INSERT ON educational_background
FOR EACH ROW
BEGIN
    INSERT INTO edu_seq VALUES (NULL);
    SET NEW.educalbackID = CONCAT(NEW.applicantID, '-ED', LPAD(LAST_INSERT_ID(), 3, '0'));
END;
//
DELIMITER ;

-- Work Experience
DELIMITER //
CREATE TRIGGER tg_employerID_insert
BEFORE INSERT ON work_experience
FOR EACH ROW
BEGIN
    INSERT INTO work_seq VALUES (NULL);
    SET NEW.employerID = CONCAT(NEW.applicantID, '-WE', LPAD(LAST_INSERT_ID(), 3, '0'));
END;
//
DELIMITER ;

-- Character Reference
DELIMITER //
CREATE TRIGGER tg_referenceID_insert
BEFORE INSERT ON character_reference
FOR EACH ROW
BEGIN
    INSERT INTO char_seq VALUES (NULL);
    SET NEW.referenceID = CONCAT(NEW.applicantID, '-R', LPAD(LAST_INSERT_ID(), 3, '0'));
END;
//
DELIMITER ;

-- 4.) == SAMPLE DATA ==

-- Applicant Profile

INSERT INTO applicant_profile (fullName, address, city, region, zip, homePhone, cellPhone, emailAddress, sssNumber, birthDate, age, citizenship, sex, maritalStatus)
VALUES
('Santos, Maria Clara', '456 Greenfield', 'Pasig', 'NCR', 1600, '02-2345678', '9172345678', 'mariaclara@gmail.com', '45-2345678-1', '1992-05-15', 32, 'Filipino', 'F', 'S'),
('Reyes, Pedro Juan', '789 Blue Street', 'Quezon City', 'NCR', 1101, '02-3456789', '9183456789', 'pedro.reyes@gmail.com', '56-3456789-2', '1988-03-10', 36, 'Filipino', 'M', 'M'),
('De Guzman, Ana Lucia', '123 Yellow Road', 'Mandaluyong', 'NCR', 1550, '02-4567890', '9194567890', 'ana.deguzman@gmail.com', '67-4567890-3', '1990-08-25', 33, 'Filipino', 'F', 'W'),
('Garcia, Luis Felipe', '321 Red Avenue', 'Taguig', 'NCR', 1630, '02-5678901', '9205678901', 'luis.garcia@gmail.com', '78-5678901-4', '1985-11-30', 38, 'Filipino', 'M', 'SE'),
('Torres, Sofia Isabel', '654 Purple Lane', 'Parañaque', 'NCR', 1700, '02-6789012', '9216789012', 'sofia.torres@gmail.com', '89-6789012-5', '1993-07-12', 30, 'Filipino', 'F', 'E'),
('Lim, Juan Carlos', '432 Orange Boulevard', 'Cebu City', 'VII', 6000, '032-1234567', '9321234567', 'juanlim@gmail.com', '90-1234567-8', '1991-09-20', 32, 'Filipino', 'M', 'S'),
('Chua, Teresa Marie', '876 Violet Drive', 'Davao City', 'XI', 8000, '082-2345678', '9332345678', 'teresa.chua@gmail.com', '91-2345678-9', '1987-04-05', 37, 'Filipino', 'F', 'M'),
('Tan, Jose Manuel', '210 Pink Street', 'Iloilo City', 'VI', 5000, '033-3456789', '9343456789', 'jose.tan@gmail.com', '92-3456789-0', '1984-12-15', 39, 'Filipino', 'M', 'W'),
('Dela Cruz, Ana Maria', '987 White Avenue', 'Baguio City', 'CAR', 2600, '074-4567890', '9354567890', 'ana.delacruz@gmail.com', '93-4567890-1', '1995-02-28', 29, 'Filipino', 'F', 'SE'),
('Gomez, Carlos Miguel', '543 Brown Lane', 'Cagayan de Oro City', 'X', 9000, '088-5678901', '9365678901', 'carlos.gomez@gmail.com', '94-5678901-2', '1989-06-10', 35, 'Filipino', 'M', 'E'),
('Martinez, Laura Elena', '678 Gray Street', 'Bacolod City', 'VI', 6100, '034-1234567', '9371234567', 'laura.martinez@gmail.com', '95-1234567-3', '1994-04-01', 30, 'Filipino', 'F', 'S'),
('Rivera, Miguel Angel', '123 Peach Avenue', 'San Fernando', 'III', 2000, '045-2345678', '9382345678', 'miguel.rivera@gmail.com', '96-2345678-4', '1983-07-20', 40, 'Filipino', 'M', 'M'),
('Flores, Isabel Cristina', '456 Teal Road', 'Legazpi City', 'V', 4500, '052-3456789', '9393456789', 'isabel.flores@gmail.com', '97-3456789-5', '1991-12-10', 32, 'Filipino', 'F', 'E'),
('Navarro, Juan Miguel', '789 Coral Street', 'General Santos City', 'XII', 9500, '083-4567890', '9404567890', 'juan.navarro@gmail.com', '98-4567890-6', '1990-01-15', 34, 'Filipino', 'M', 'SE'),
('Bautista, Carla Marie', '321 Aqua Lane', 'Zamboanga City', 'IX', 7000, '062-5678901', '9415678901', 'carla.bautista@gmail.com', '99-5678901-7', '1988-05-25', 36, 'Filipino', 'F', 'S'),
('Mendoza, Luis Rafael', '654 Indigo Avenue', 'Iligan City', 'X', 9200, '063-6789012', '9426789012', 'luis.mendoza@gmail.com', '00-6789012-8', '1986-11-11', 37, 'Filipino', 'M', 'M'),
('Reyes, Isabel Maria', '432 Lime Street', 'Batangas City', 'IV-A', 4200, '043-1234567', '9431234567', 'isabel.reyes@gmail.com', '01-1234567-9', '1989-03-03', 35, 'Filipino', 'F', 'W'),
('Santiago, Rafael Jose', '876 Amber Road', 'Antipolo', 'IV-A', 1870, '02-2345678', '9442345678', 'rafael.santiago@gmail.com', '02-2345678-1', '1992-09-09', 31, 'Filipino', 'M', 'E'),
('Aquino, Carla Beatriz', '210 Azure Lane', 'Marikina', 'NCR', 1800, '02-3456789', '9453456789', 'carla.aquino@gmail.com', '03-3456789-2', '1993-11-11', 30, 'Filipino', 'F', 'S'),
('Castro, Miguel Antonio', '987 Emerald Street', 'Makati', 'NCR', 1220, '02-4567890', '9464567890', 'miguel.castro@gmail.com', '04-4567890-3', '1987-06-05', 37, 'Filipino', 'M', 'M'),
('Ramos, Maria Josefina', '543 Garnet Avenue', 'Caloocan', 'NCR', 1400, '02-5678901', '9475678901', 'maria.ramos@gmail.com', '05-5678901-4', '1991-01-22', 33, 'Filipino', 'F', 'E'),
('Gonzales, Juan Carlos', '678 Ruby Road', 'Pasay', 'NCR', 1300, '02-6789012', '9486789012', 'juan.gonzales@gmail.com', '06-6789012-5', '1990-10-15', 33, 'Filipino', 'M', 'SE'),
('Villanueva, Ana Sofia', '123 Sapphire Lane', 'Las Piñas', 'NCR', 1740, '02-7890123', '9497890123', 'ana.villanueva@gmail.com', '07-7890123-6', '1985-04-17', 39, 'Filipino', 'F', 'S'),
('Diaz, Rafael Antonio', '456 Onyx Street', 'Muntinlupa', 'NCR', 1770, '02-8901234', '9508901234', 'rafael.diaz@gmail.com', '08-8901234-7', '1988-08-05', 35, 'Filipino', 'M', 'M'),
('Silva, Clara Elena', '789 Amethyst Avenue', 'Valenzuela', 'NCR', 1440, '02-9012345', '9519012345', 'clara.silva@gmail.com', '09-9012345-8', '1994-02-14', 30, 'Filipino', 'F', 'W'),
('Rodriguez, Jose Antonio', '321 Topaz Street', 'Manila', 'NCR', 1000, '02-0123456', '9520123456', 'jose.rodriguez@gmail.com', '10-0123456-9', '1991-09-30', 32, 'Filipino', 'M', 'SE'),
('Roxas, Maria Teresa', '654 Citrine Lane', 'San Juan', 'NCR', 1500, '02-1234567', '9531234567', 'maria.roxas@gmail.com', '11-1234567-0', '1993-12-25', 30, 'Filipino', 'F', 'E'),
('Villamor, Juan Pablo', '432 Diamond Road', 'Malabon', 'NCR', 1470, '02-2345678', '9542345678', 'juan.villamor@gmail.com', '12-2345678-1', '1984-11-09', 39, 'Filipino', 'M', 'S'),
('Sandoval, Maria Cristina', '876 Crystal Avenue', 'Navotas', 'NCR', 1480, '02-3456789', '9553456789', 'maria.sandoval@gmail.com', '13-3456789-2', '1989-07-04', 34, 'Filipino', 'F', 'M'),
('Mendoza, Rafael Lorenzo', '210 Quartz Street', 'Makati', 'NCR', 1220, '02-4567890', '9564567890', 'rafael.mendoza@gmail.com', '14-4567890-3', '1990-05-20', 34, 'Filipino', 'M', 'W');

-- Application Information
INSERT INTO application_information (
    desiredPosition, availWDate, desiredSalary, desiredEmployment, applicantID
) VALUES
('Software Engineer', '2024-05-10', '85000', 'FT', 1),
('Technical Lead', '2024-06-02', '95000', 'FT', 2),
('Data Analyst', '2024-07-15', '70000', 'PT', 3),
('Project Manager', '2024-06-30', '90000', 'FT', 4),
('Data Scientist', '2024-05-25', '100000', 'S', 5),
('Software Developer', '2024-06-18', '80000', 'FT', 6),
('Network Engineer', '2024-07-03', '75000', 'PT', 7),
('UX/UI Designer', '2024-06-20', '72000', 'FT', 8),
('Systems Analyst', '2024-08-01', '78000', 'S', 9),
('Cyber Security Specialist', '2024-05-30', '85000', 'FT', 10),
('Software Developer', '2024-11-05', '80000', 'FT', 11),
('Software Developer', '2024-12-10', '82000', 'PT', 12),
('Software Developer', '2024-11-20', '85000', 'FT', 13),
('Software Developer', '2024-10-25', '88000', 'S', 14),
('Software Developer', '2024-09-30', '90000', 'FT', 15),
('IT Project Coordinator', '2024-09-01', '70000', 'PT', 16),
('Front-End Developer', '2024-08-15', '75000', 'FT', 17),
('Back-End Developer', '2024-11-20', '78000', 'S', 18),
('Full Stack Developer', '2024-12-01', '85000', 'FT', 19),
('Information Security Analyst', '2024-11-15', '90000', 'FT', 20),
('Software Developer', '2024-09-25', '98000', 'PT', 21),
('Systems Administrator', '2024-10-30', '73000', 'FT', 22),
('Mobile App Developer', '2024-12-20', '82000', 'S', 23),
('Network Administrator', '2024-11-05', '71000', 'FT', 24),
('IT Consultant', '2024-12-10', '92000', 'FT', 25),
('AI Specialist', '2024-11-30', '110000', 'PT', 26),
('Technical Support Engineer', '2024-10-10', '65000', 'FT', 27),
('QA Engineer', '2024-09-20', '72000', 'S', 28),
('Data Engineer', '2024-08-10', '87000', 'FT', 29),
('Blockchain Developer', '2024-10-25', '95000', 'PT', 30);

-- School Details
INSERT INTO school_details (schoolName, location)
VALUES
('Jose Rizal Elementary School', 'Manila'),
('Mabini Elementary School', 'Quezon City'),
('Bonifacio Elementary School', 'Manila'),
('Lapu-Lapu Elementary School', 'Cebu City'),
('Rizal Elementary School', 'Davao City'),
('Quezon City High School', 'Quezon City'),
('Manila Science High School', 'Manila'),
('San Carlos High School', 'Cebu City'),
('Davao City National High School', 'Davao City'),
('Makati High School', 'Makati City'),
('University of the Philippines Diliman', 'Quezon City'),
('Ateneo de Manila University', 'Quezon City'),
('De La Salle University', 'Manila'),
('University of Santo Tomas', 'Manila'),
('Polytechnic University of the Philippines', 'Manila'),
('St. Scholastica College', 'Manila'),
('Xavier University - Ateneo de Cagayan', 'Cagayan de Oro City'),
('University of San Carlos', 'Cebu City'),
('Mapua University', 'Manila'),
('Far Eastern University', 'Manila'),
('University of the East', 'Manila'),
('Adamson University', 'Manila'),
('Pamantasan ng Lungsod ng Maynila', 'Manila'),
('Technological Institute of the Philippines', 'Manila'),
('Holy Angel University', 'Angeles City'),
('Silliman University', 'Dumaguete City'),
('Central Luzon State University', 'Science City of Muñoz'),
('Mindanao State University - Iligan Institute of Technology', 'Iligan City'),
('Western Mindanao State University', 'Zamboanga City'),
('Bicol University', 'Legazpi City');


-- Educational Background
INSERT INTO educational_background (applicantID, schoolID, yearsAttended, degree, major)
VALUES
    -- Applicant 1
    ('1', 'SCH-001', '2000-2006', 'Elementary School Diploma', 'General Education'),
    ('1', 'SCH-007', '2006-2010', 'High School Diploma', 'Science'),
    ('1', 'SCH-011', '2010-2014', 'Bachelor of Science', 'Computer Engineering'),
    
    -- Applicant 2
    ('2', 'SCH-002', '2002-2008', 'Elementary School Diploma', 'Basic Education'),
    ('2', 'SCH-008', '2008-2012', 'High School Diploma', 'Mathematics'),
    ('2', 'SCH-012', '2012-2016', 'Bachelor of Science', 'Information Technology'),
    
    -- Applicant 3
    ('3', 'SCH-003', '2001-2007', 'Elementary School Diploma', 'Primary Education'),
    ('3', 'SCH-009', '2007-2011', 'High School Diploma', 'Literature'),
    ('3', 'SCH-013', '2011-2015', 'Bachelor of Science', 'Computer Science'),
    
    -- Applicant 4
    ('4', 'SCH-004', '2003-2009', 'Elementary School Diploma', 'Fundamental Education'),
    ('4', 'SCH-010', '2009-2013', 'High School Diploma', 'Social Sciences'),
    ('4', 'SCH-014', '2013-2017', 'Bachelor of Science', 'Information Systems'),
    
    -- Applicant 5
    ('5', 'SCH-005', '2004-2010', 'Elementary School Diploma', 'Basic Education'),
    ('5', 'SCH-011', '2010-2014', 'High School Diploma', 'Natural Sciences'),
    ('5', 'SCH-015', '2014-2018', 'Bachelor of Science', 'Computer Science'),
    
    -- Applicant 6
    ('6', 'SCH-001', '2005-2011', 'Elementary School Diploma', 'General Education'),
    ('6', 'SCH-007', '2011-2015', 'High School Diploma', 'Humanities'),
    ('6', 'SCH-012', '2015-2019', 'Bachelor of Science', 'Information Technology'),
    
    -- Applicant 7
    ('7', 'SCH-002', '2006-2012', 'Elementary School Diploma', 'Basic Education'),
    ('7', 'SCH-008', '2012-2016', 'High School Diploma', 'History'),
    ('7', 'SCH-013', '2016-2020', 'Bachelor of Science', 'Computer Engineering'),
    
    -- Applicant 8
    ('8', 'SCH-003', '2007-2013', 'Elementary School Diploma', 'Primary Education'),
    ('8', 'SCH-009', '2013-2017', 'High School Diploma', 'Literature'),
    ('8', 'SCH-014', '2017-2021', 'Bachelor of Science', 'Information Systems'),
    
    -- Applicant 9
    ('9', 'SCH-004', '2008-2014', 'Elementary School Diploma', 'Fundamental Education'),
    ('9', 'SCH-010', '2014-2018', 'High School Diploma', 'Social Sciences'),
    ('9', 'SCH-015', '2018-2022', 'Bachelor of Science', 'Computer Science'),
    
    -- Applicant 10
    ('10', 'SCH-005', '2009-2015', 'Elementary School Diploma', 'Basic Education'),
    ('10', 'SCH-011', '2015-2019', 'High School Diploma', 'Natural Sciences'),
    ('10', 'SCH-010', '2019-2023', 'Bachelor of Science', 'Information Technology'),
    
    -- Applicant 11
    ('11', 'SCH-001', '2000-2006', 'Elementary School Diploma', 'General Education'),
    ('11', 'SCH-007', '2006-2010', 'High School Diploma', 'Science'),
    ('11', 'SCH-011', '2010-2014', 'Bachelor of Science', 'Computer Engineering'),
    
    -- Applicant 12
    ('12', 'SCH-002', '2002-2008', 'Elementary School Diploma', 'Basic Education'),
    ('12', 'SCH-008', '2008-2012', 'High School Diploma', 'Mathematics'),
    ('12', 'SCH-012', '2012-2016', 'Bachelor of Science', 'Information Technology'),
    
    -- Applicant 13
    ('13', 'SCH-003', '2001-2007', 'Elementary School Diploma', 'Primary Education'),
    ('13', 'SCH-009', '2007-2011', 'High School Diploma', 'Literature'),
    ('13', 'SCH-013', '2011-2015', 'Bachelor of Science', 'Computer Science'),
    
    -- Applicant 14
    ('14', 'SCH-004', '2003-2009', 'Elementary School Diploma', 'Fundamental Education'),
    ('14', 'SCH-010', '2009-2013', 'High School Diploma', 'Social Sciences'),
    ('14', 'SCH-014', '2013-2017', 'Bachelor of Science', 'Information Systems'),
    
    -- Applicant 15
    ('15', 'SCH-005', '2004-2010', 'Elementary School Diploma', 'Basic Education'),
    ('15', 'SCH-011', '2010-2014', 'High School Diploma', 'Natural Sciences'),
    ('15', 'SCH-015', '2014-2018', 'Bachelor of Science', 'Computer Science'),
    
    -- Applicant 16
    ('16', 'SCH-001', '2005-2011', 'Elementary School Diploma', 'General Education'),
    ('16', 'SCH-007', '2011-2015', 'High School Diploma', 'Humanities'),
    ('16', 'SCH-012', '2015-2019', 'Bachelor of Science', 'Information Technology'),
    
    -- Applicant 17
    ('17', 'SCH-002', '2006-2012', 'Elementary School Diploma', 'Basic Education'),
    ('17', 'SCH-008', '2012-2016', 'High School Diploma', 'History'),
    ('17', 'SCH-013', '2016-2020', 'Bachelor of Science', 'Computer Engineering'),
    
    -- Applicant 18
    ('18', 'SCH-003', '2007-2013', 'Elementary School Diploma', 'Primary Education'),
    ('18', 'SCH-009', '2013-2017', 'High School Diploma', 'Literature'),
    ('18', 'SCH-014', '2017-2021', 'Bachelor of Science', 'Information Systems'),
    
    -- Applicant 19
    ('19', 'SCH-004', '2008-2014', 'Elementary School Diploma', 'Fundamental Education'),
    ('19', 'SCH-010', '2014-2018', 'High School Diploma', 'Social Sciences'),
    ('19', 'SCH-015', '2018-2022', 'Bachelor of Science', 'Computer Science'),
    
    -- Applicant 20
    ('20', 'SCH-005', '2009-2015', 'Elementary School Diploma', 'Basic Education'),
    ('20', 'SCH-011', '2015-2019', 'High School Diploma', 'Natural Sciences'),
    ('20', 'SCH-010', '2019-2023', 'Bachelor of Science', 'Information Technology'),
    
    -- Applicant 21
    ('21', 'SCH-001', '2000-2006', 'Elementary School Diploma', 'General Education'),
    ('21', 'SCH-007', '2006-2010', 'High School Diploma', 'Science'),
    ('21', 'SCH-011', '2010-2014', 'Bachelor of Science', 'Computer Engineering'),
    
    -- Applicant 22
    ('22', 'SCH-002', '2002-2008', 'Elementary School Diploma', 'Basic Education'),
    ('22', 'SCH-008', '2008-2012', 'High School Diploma', 'Mathematics'),
    ('22', 'SCH-012', '2012-2016', 'Bachelor of Science', 'Information Technology'),
    
    -- Applicant 23
    ('23', 'SCH-003', '2001-2007', 'Elementary School Diploma', 'Primary Education'),
    ('23', 'SCH-009', '2007-2011', 'High School Diploma', 'Literature'),
    ('23', 'SCH-013', '2011-2015', 'Bachelor of Science', 'Computer Science'),
    
    -- Applicant 24
    ('24', 'SCH-004', '2003-2009', 'Elementary School Diploma', 'Fundamental Education'),
    ('24', 'SCH-010', '2009-2013', 'High School Diploma', 'Social Sciences'),
    ('24', 'SCH-014', '2013-2017', 'Bachelor of Science', 'Information Systems'),
    
    -- Applicant 25
    ('25', 'SCH-005', '2004-2010', 'Elementary School Diploma', 'Basic Education'),
    ('25', 'SCH-011', '2010-2014', 'High School Diploma', 'Natural Sciences'),
    ('25', 'SCH-015', '2014-2018', 'Bachelor of Science', 'Computer Science'),
    
    -- Applicant 26
    ('26', 'SCH-001', '2005-2011', 'Elementary School Diploma', 'General Education'),
    ('26', 'SCH-007', '2011-2015', 'High School Diploma', 'Humanities'),
    ('26', 'SCH-012', '2015-2019', 'Bachelor of Science', 'Information Technology'),
    
    -- Applicant 27
    ('27', 'SCH-002', '2006-2012', 'Elementary School Diploma', 'Basic Education'),
    ('27', 'SCH-008', '2012-2016', 'High School Diploma', 'History'),
    ('27', 'SCH-013', '2016-2020', 'Bachelor of Science', 'Computer Engineering'),
    
    -- Applicant 28
    ('28', 'SCH-003', '2007-2013', 'Elementary School Diploma', 'Primary Education'),
    ('28', 'SCH-009', '2013-2017', 'High School Diploma', 'Literature'),
    ('28', 'SCH-014', '2017-2021', 'Bachelor of Science', 'Information Systems'),
    
    -- Applicant 29
    ('29', 'SCH-004', '2008-2014', 'Elementary School Diploma', 'Fundamental Education'),
    ('29', 'SCH-010', '2014-2018', 'High School Diploma', 'Social Sciences'),
    ('29', 'SCH-015', '2018-2022', 'Bachelor of Science', 'Computer Science'),
    
    -- Applicant 30
    ('30', 'SCH-005', '2009-2015', 'Elementary School Diploma', 'Basic Education'),
    ('30', 'SCH-011', '2015-2019', 'High School Diploma', 'Natural Sciences'),
    ('30', 'SCH-010', '2019-2023', 'Bachelor of Science', 'Information Technology');


-- Work Experience
INSERT INTO work_experience (applicantID, employer, employerContact, employerAddress, dateEmployed, employedPosition, reasonforLeaving)
VALUES
    ('1', 'Tech Solutions Inc.', '9650715692', '1234 Acacia Avenue, Barangay San Isidro', '2020-02-19', 'Software Engineer', 'Due to Contract'),
    ('2', 'Web Experts Ltd.', '9876543210', '5678 Elm Street, Barangay Sto. Niño', '2018-05-10', 'Web Developer', 'Career Growth'),
    ('3', 'Data Insights Corp.', '9988776655', '7890 Maple Lane, Barangay Malanday', '2017-08-15', 'Data Analyst', 'Relocation'),
    ('4', 'IT Innovations Ltd.', '9998887770', '4321 Pine Road, Barangay Tandang Sora', '2019-11-30', 'IT Specialist', 'Career Change'),
    ('5', 'Digital Solutions LLC', '9123456789', '2468 Oak Avenue, Barangay Poblacion', '2016-03-25', 'Data Scientist', 'Company Downsizing'),
    ('6', 'Code Masters Inc.', '9234567890', '1357 Cedar, Barangay Bagong Pag-asa', '2018-09-10', 'Software Developer', 'New Opportunity'),
    ('7', 'Network Solutions Ltd.', '9345678901', '8765 Birch Lane, Barangay Kamuning', '2019-06-28', 'Network Engineer', 'Better Compensation'),
    ('8', 'UX/UI Creations', '9456789012', '5432 Redwood Drive, Barangay Don Antonio', '2020-04-15', 'UX/UI Designer', 'Career Advancement'),
    ('9', 'Systems Integration', '9567890123', '9876 Fir Boulevard, Barangay Holy Spirit', '2017-11-05', 'Systems Analyst', 'Company Restructuring'),
    ('10', 'Cyber Defense Solutions', '9678901234', '2109 Walnut, Barangay Loyola Heights', '2018-07-20', 'Cyber Security Specialist', 'Seeking Remote Work'),
    ('11', 'Web Solutions Ltd.', '9650715693', '1235 Acacia Avenue, Barangay San Isidro', '2020-03-19', 'Web Developer', 'Due to Contract'),
    ('12', 'Tech Innovators Inc.', '9876543211', '5679 Elm Street, Barangay Sto. Niño', '2018-06-10', 'Software Engineer', 'Career Growth'),
    ('13', 'Data Analytics Corp.', '9988776656', '7891 Maple Lane, Barangay Malanday', '2017-09-15', 'Data Analyst', 'Relocation'),
    ('14', 'IT Systems Ltd.', '9998887771', '4322 Pine Road, Barangay Tandang Sora', '2019-12-15', 'IT Specialist', 'Career Change'),
    ('15', 'Digital Insights LLC', '9123456790', '2469 Oak Avenue, Barangay Poblacion', '2016-04-25', 'Data Scientist', 'Company Downsizing'),
    ('16', 'Code Gurus Inc.', '9234567891', '1358 Cedar, Barangay Bagong Pag-asa', '2018-10-10', 'Software Developer', 'New Opportunity'),
    ('17', 'Network Technologies Ltd.', '9345678902', '8766 Birch Lane, Barangay Kamuning', '2019-07-28', 'Network Engineer', 'Better Compensation'),
    ('18', 'Creative UX/UI Designs', '9456789013', '5433 Redwood Drive, Barangay Don Antonio', '2020-05-15', 'UX/UI Designer', 'Career Advancement'),
    ('19', 'Integrated Systems', '9567890124', '9877 Fir Boulevard, Barangay Holy Spirit', '2017-12-05', 'Systems Analyst', 'Company Restructuring'),
    ('20', 'Cyber Defense Solutions', '9678901235', '2110 Walnut, Barangay Loyola Heights', '2018-08-20', 'Cyber Security Specialist', 'Seeking Remote Work'),
    ('21', 'Web Solutions Ltd.', '9650715694', '1236 Acacia Avenue, Barangay San Isidro', '2020-04-19', 'Web Developer', 'Due to Contract'),
    ('22', 'Tech Innovators Inc.', '9876543212', '5680 Elm Street, Barangay Sto. Niño', '2018-07-10', 'Software Engineer', 'Career Growth'),
    ('23', 'Data Analytics Corp.', '9988776657', '7892 Maple Lane, Barangay Malanday', '2017-10-15', 'Data Analyst', 'Relocation'),
    ('24', 'IT Systems Ltd.', '9998887772', '4323 Pine Road, Barangay Tandang Sora', '2020-01-30', 'IT Specialist', 'Career Change'),
    ('25', 'Digital Insights LLC', '9123456791', '2470 Oak Avenue, Barangay Poblacion', '2016-05-25', 'Data Scientist', 'Company Downsizing'),
    ('26', 'Code Gurus Inc.', '9234567892', '1359 Cedar, Barangay Bagong Pag-asa', '2018-11-10', 'Software Developer', 'New Opportunity'),
    ('27', 'Network Technologies Ltd.', '9345678903', '8767 Birch Lane, Barangay Kamuning', '2019-08-28', 'Network Engineer', 'Better Compensation'),
    ('28', 'Creative UX/UI Designs', '9456789014', '5434 Redwood Drive, Barangay Don Antonio', '2020-06-15', 'UX/UI Designer', 'Career Advancement'),
    ('29', 'Integrated Systems', '9567890125', '9878 Fir Boulevard, Barangay Holy Spirit', '2017-12-15', 'Systems Analyst', 'Company Restructuring'),
    ('30', 'Cyber Defense Solutions', '9678901236', '2111 Walnut, Barangay Loyola Heights', '2018-09-20', 'Cyber Security Specialist', 'Seeking Remote Work');


-- Character Reference
INSERT INTO character_reference (applicantID, refName, refTitle, refCompany, refPhone)
VALUES
    ('1', 'John Smith', 'Senior Manager', 'ABC Company', '9876543210'),
    ('1', 'Jane Doe', 'HR Director', 'XYZ Corporation', '9876543211'),
    ('1', 'Michael Brown', 'Project Manager', '123 Inc.', '9876543212'),

    ('2', 'Daniela Joaquin', 'Senior Software Engineer', 'AWS', '9774419731'),
    ('2', 'Rachel Martinez', 'Technical Lead', 'Microsoft', '9774419732'),
    ('2', 'Patrick Sullivan', 'Product Manager', 'Google', '9774419733'),

    ('3', 'Michael Johnson', 'Tech Lead', 'Google', '9876543210'),
    ('3', 'Sarah Smith', 'Product Manager', 'Microsoft', '9865432109'),
    ('3', 'Christopher Brown', 'Senior Engineer', 'Apple', '9854321098'),

    ('4', 'Emily Davis', 'Software Developer', 'Apple', '9654321098'),
    ('4', 'Alexandra Garcia', 'Project Manager', 'Facebook', '9654321097'),
    ('4', 'Daniel White', 'Data Analyst', 'Amazon', '9654321096'),

    ('5', 'Thomas Anderson', 'Data Scientist', 'Facebook', '9543210987'),
    ('5', 'Olivia Taylor', 'Software Engineer', 'Twitter', '9543210986'),
    ('5', 'William Martinez', 'Product Manager', 'LinkedIn', '9543210985'),

    ('6', 'Andrew Johnson', 'Software Engineer', 'Uber', '9432109875'),
    ('6', 'Sophie Brown', 'Software Developer', 'Airbnb', '9432109874'),
    ('6', 'Jessica Lee', 'Data Analyst', 'Tesla', '9432109876'),

    ('7', 'Lucas Martinez', 'Software Developer', 'Amazon', '9345678903'),
    ('7', 'Carlos Santos', 'Technical Lead', 'Netflix', '9345678901'),
    ('7', 'Isabella Garcia', 'Software Engineer', 'Hulu', '9345678902'),

    ('8', 'David Johnson', 'Technical Lead', 'Intel', '9456789013'),
    ('8', 'Sophia Brown', 'Software Developer', 'Adobe', '9456789014'),
    ('8', 'Jessica Garcia', 'Software Engineer', 'Salesforce', '9456789012'),

    ('9', 'Emily Rodriguez', 'Software Engineer', 'Oracle', '9567890124'),
    ('9', 'Matthew Taylor', 'Product Manager', 'IBM', '9567890125'),
    ('9', 'David Fernandez', 'Data Scientist', 'Cisco', '9567890123'),

    ('10', 'Sophia Reyes', 'Software Developer', 'VMware', '9678901234'),
    ('10', 'James Smith', 'Engineering Manager', 'HP', '9678901235'),
    ('10', 'Emma Davis', 'Data Analyst', 'Dell', '9678901236'),

    ('11', 'Jessica Moore', 'Software Engineer', 'Uber', '9650715693'),
    ('11', 'Sophie Williams', 'Senior Developer', 'Google', '9876543211'),
    ('11', 'Emily White', 'Technical Lead', 'Apple', '9543210987'),

    ('12', 'James Davis', 'Data Analyst', 'Facebook', '9432109875'),
    ('12', 'Oliver Johnson', 'Software Engineer', 'Netflix', '9456789013'),
    ('12', 'Lucas Garcia', 'Senior Developer', 'Salesforce', '9678901234'),

    ('13', 'Sophia Martinez', 'Product Manager', 'Twitter', '9345678903'),
    ('13', 'Sophia Lee', 'Software Engineer', 'Oracle', '9650715692'),

    ('14', 'David Moore', 'Data Analyst', 'IBM', '9876543210'),
    ('14', 'Jessica Taylor', 'Product Manager', 'Cisco', '9654321097'),

    ('15', 'Michael Brown', 'Software Developer', 'VMware', '9543210986'),
    ('15', 'Sophie Clark', 'Software Engineer', 'HP', '9432109874'),

    ('16', 'Thomas Johnson', 'Senior Developer', 'Dell', '9345678901'),
    ('16', 'Emily Martinez', 'Software Developer', 'Uber', '9456789012'),

    ('17', 'David Davis', 'Data Scientist', 'Google', '9567890123'),
    ('17', 'Jessica Moore', 'Product Manager', 'Amazon', '9678901235'),

    ('18', 'Michael Johnson', 'Software Engineer', 'Microsoft', '9650715694'),
    ('18', 'Jane Smith', 'Data Analyst', 'Apple', '9876543212'),

    ('19', 'Robert Davis', 'Senior Developer', 'Facebook', '9654321096'),
    ('19', 'Emma Johnson', 'Product Manager', 'Twitter', '9543210985'),

    ('20', 'Sophia Brown', 'Software Engineer', 'Uber', '9432109876'),
    ('20', 'John Martinez', 'Senior Manager', 'Google', '9456789011'),

    ('21', 'Sophia Lee', 'Technical Lead', 'Microsoft', '9345678902'),
    ('21', 'David Rodriguez', 'Data Scientist', 'Apple', '9876543211'),

    ('22', 'Emily White', 'Product Manager', 'Facebook', '9654321095'),
    ('22', 'Michael Brown', 'Software Engineer', 'Amazon', '9543210984'),

    ('23', 'Sophie Clark', 'Senior Developer', 'Google', '9432109873'),
    ('23', 'Thomas Johnson', 'Data Analyst', 'Microsoft', '9456789010'),

    ('24', 'Jessica Moore', 'Product Manager', 'Apple', '9567890122'),
    ('24', 'Michael Johnson', 'Software Engineer', 'IBM', '9650715693'),

    ('25', 'David Moore', 'Data Analyst', 'Cisco', '9876543213'),
    ('25', 'Sophia Martinez', 'Product Manager', 'VMware', '9543210983'),

    ('26', 'Rachel Johnson', 'Senior Developer', 'AWS', '9432109872'),
    ('27', 'Patrick Sullivan', 'Product Manager', 'HP', '9654321094'),
    ('28', 'Isabella Garcia', 'Software Engineer', 'Dell', '9432109871'),
    ('29', 'Daniel White', 'Data Analyst', 'Google', '9678901233'),
    ('30', 'Emily Davis', 'Product Manager', 'Amazon', '9456789009');

-- Log in Admin
INSERT INTO login_info (email, password) VALUES 
('renzalfonsoquinto0611@gmail.com', 'admin1'),
('nathanielmagtibay00@gmail.com', 'admin2'),
('avegailtillo@gmailcom', 'admin3'),
('joaquindaniela2018@gmail.com', 'admin4');

-- SQL FILTERS
-- SIMPLE 
-- 1.) Display the profile of the applicants who lives at the NCR Region who's age is 30 years old and above, order by their Applicant ID.
SELECT *
FROM applicant_profile
WHERE region IN ("NCR") AND Age >= 30
ORDER BY applicantID;

-- 2.) Display applicant ID, available working date and desired salary application information of applicants who applied for full time Software Developer position, order by their working date availability.
SELECT applicantID, availWDate, desiredSalary
FROM application_information
WHERE desiredPosition IN ("Software Developer") AND desiredEmployment IN ("FT")
ORDER BY availWDate;

-- 3.) Display the applicant ID, employer, previously employed position, employed date, and the reason for leaving for those applicants who left beacause of career or relocation related reason, sort by the most recent employed date.
SELECT applicantID, employer, employedPosition, dateEmployed, reasonforLeaving
FROM work_experience
WHERE reasonforLeaving LIKE "Career%" OR reasonforLeaving IN ("Relocation")
ORDER BY dateEmployed DESC;

-- 4.) Display the records of applicants who took any computer major in their bachelors degree, order by the applicantID
SELECT *
FROM educational_background
WHERE major LIKE "%Computer%" AND degree LIKE "Bachelor%"
ORDER BY applicantID;

-- MODERATE
-- 1.) Display the average desired salary of applicants if it is greater than 50,000 and they are seeking full-time employment. Group the results by desired position and sort them by average salary.
SELECT desiredPosition, AVG(desiredSalary) AS "averageSalary"
FROM application_information
WHERE desiredEmployment = 'FT'
GROUP BY desiredPosition
HAVING AVG(desiredSalary) > 50000
ORDER BY averageSalary;

-- 2.) Count applicants who are single and aged over 20 years. Group them by age and region, and sort the results by age.
SELECT age, region, COUNT(*) AS "applicantsCount"
FROM applicant_profile
WHERE maritalStatus = 'S' AND age > 20
GROUP BY age, region
ORDER BY age;


-- 3.) Count the number of applicants whose last employment was more than 5 years ago. Group and sort the results by employed position.
SELECT employedPosition, COUNT(*) AS "totalApplicants"
FROM work_experience
WHERE DATEDIFF(CURDATE(), dateEmployed) / 365 > 5
GROUP BY employedPosition
ORDER BY employedPosition;

-- 4.) Count applicants whose last employment was over 5 years ago and whose position includes 'Software'. Group by position, include only positions with at least one applicant, and sort by position.
SELECT employedPosition, COUNT(*) AS "totalApplicants"
FROM work_experience
WHERE DATEDIFF(CURDATE(), dateEmployed) / 365 > 5 AND employedPosition LIKE '%Software%'
GROUP BY employedPosition
HAVING COUNT(*) >= 1
ORDER BY employedPosition;

-- DIFFICULT
-- 1. Display the full names and regions of applicants who aim for part-time or seasonal employment with a desired salary exceeding $30,000. The average days must be more than a week before applicants are available for work, sorted by their names.
SELECT P.fullname, P.region, AVG(DATEDIFF(CURDATE(), I.availWDate)) AS "averageDays"
FROM applicant_profile AS P, application_information AS I 
WHERE P.applicantID = I.applicantID AND I.desiredSalary > 30000 AND I.desiredEmployment IN ('PT', 'S')
GROUP BY P.fullname, P.region
HAVING AVG(DATEDIFF(CURDATE(), I.availWDate)) > 7
ORDER BY P.fullname;

-- 2.) Count the number of references greater than or equal to 2 provided by each male applicant who applies for a full-time job. Display the applicant's ID, fullname, desired position, and their past position, sort by their applicantID descending.
SELECT P.applicantID, P.fullname, I.desiredPosition, W.employedPosition, COUNT(C.applicantID) AS "refCount"
FROM applicant_profile AS P, application_information AS I, work_experience AS W, character_reference AS C
WHERE (P.applicantID = I.applicantID) AND (P.applicantID = W.applicantID) AND (P.applicantID = C.applicantID) AND P.sex = "M" AND I.desiredEmployment = "FT"
GROUP BY P.applicantID, P.fullname, I.desiredPosition, W.employedPosition
HAVING COUNT(C.applicantID) >= 2
ORDER BY P.applicantID DESC; 

-- 3.) Count how many applicants share the same school at the college level (Bachelor's degree), only if more than 1 applicant attended. Group them and sort by the school name.
SELECT E.schoolID, S.schoolName, COUNT(S.schoolID) AS "applicantCount"
FROM educational_background AS E, school_details AS S
WHERE S.schoolID = E.schoolID AND E.degree LIKE "%Bachelor%"
GROUP BY E.schoolID, S.schoolName
HAVING COUNT(S.schoolID) > 1
ORDER BY S.schoolName;






