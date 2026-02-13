CREATE DATABASE test_ddb;
USE test_ddb;

---------------------------------------------------------
-- TABLES
---------------------------------------------------------

CREATE TABLE Customer (
    customer_id CHAR(20),
    full_name CHAR(50),
    age INT,
    credit_score INT,
    customer_since DATE,
    phone_number CHAR(11),
    PRIMARY KEY (customer_id)
);

CREATE TABLE Borrowed_Loan (
    loan_id CHAR(20),
    customer_id CHAR(20),
    loan_amount INT,
    loan_type CHAR(50),
    interest_rate INT,
    ldue_date DATE,
    lstart_date DATE,
    PRIMARY KEY (loan_id, customer_id),
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id) ON DELETE CASCADE
);

CREATE TABLE Account (
    account_number CHAR(20),
    account_type CHAR(50),
    balance INT,
    created_at DATE,
    PRIMARY KEY (account_number)
);

CREATE TABLE Maintains (
    customer_id CHAR(20) NOT NULL,
    account_number CHAR(20),
    PRIMARY KEY(customer_id, account_number),
    FOREIGN KEY(customer_id) REFERENCES Customer(customer_id) ON DELETE CASCADE,
    FOREIGN KEY(account_number) REFERENCES Account(account_number) ON DELETE CASCADE
);

CREATE TABLE Paid_Bill (
    bill_id CHAR(20),
    bdue_date DATE,
    account_number CHAR(20) NOT NULL,
    bstart_date DATE,
    bill_type CHAR(50),
    bill_amount INT,
    payment_date DATE,
    PRIMARY KEY (bill_id, account_number),
    FOREIGN KEY (account_number) REFERENCES Account(account_number) ON DELETE CASCADE
);

CREATE TABLE Has_Card (
    card_number INT,
    customer_id CHAR(20) NOT NULL,
    expiration_date DATE,
    card_type CHAR(20),
    cvv INT,
    PRIMARY KEY (card_number, customer_id),
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id) ON DELETE CASCADE
);

CREATE TABLE Customer_Ticket (
    ticket_id CHAR(20),
    customer_id CHAR(20),
    topic CHAR(50),
    message VARCHAR(250),
    PRIMARY KEY (ticket_id),
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id)
);

CREATE TABLE Consultation_Ticket (
    ticket_id CHAR(20),
    advisor VARCHAR(40),
    PRIMARY KEY(ticket_id),
    FOREIGN KEY(ticket_id) REFERENCES Customer_Ticket(ticket_id)
);

CREATE TABLE IT_Ticket (
    ticket_id CHAR(20),
    device VARCHAR(40),
    PRIMARY KEY (ticket_id),
    FOREIGN KEY(ticket_id) REFERENCES Customer_Ticket(ticket_id)
);

---------------------------------------------------------
-- DATA
---------------------------------------------------------

/* customer */
INSERT INTO Customer (customer_id, full_name, age, credit_score, customer_since, phone_number)
VALUES
('C001','Michael Scott',45,720,'2010-04-21','00000000000'),
('C002','Dwight Schrute',42,760,'2012-03-05','11111111111'),
('C003','Jim Halpert',38,740,'2014-09-17','22222222222'),
('C004','Pamela Beesly',36,730,'2015-02-11','33333333333'),
('C005','Stanley Hudson',55,700,'2008-07-30','44444444444'),
('C006','Kevin Malone',40,610,'2013-01-22','55555555555'),
('C007','Angela Martin',41,750,'2011-06-10','66666666666'),
('C008','Oscar Martinez',43,760,'2009-10-14','77777777777'),
('C009','Toby Flenderson',47,680,'2010-12-01','88888888888'),
('C010','Creed Bratton',60,650,'2007-08-25','99999999999');

/* account */
INSERT INTO Account (account_number, account_type, balance, created_at)
VALUES
('AC1001','Personal',10000,'2010-05-01'),
('AC1002','Joint',25000,'2012-07-15'),
('AC1003','Personal',15000,'2014-09-20'),
('AC1004','Personal',20000,'2015-02-25'),
('AC1005','Joint',50000,'2008-07-30'),
('AC1006','Personal',12000,'2013-01-22'),
('AC1007','Joint',30000,'2011-06-10'),
('AC1008','Personal',40000,'2009-10-14'),
('AC1009','Personal',7000,'2010-12-01'),
('AC1010','Joint',9000,'2007-08-25');

/* paid bill */
INSERT INTO Paid_Bill (
    bill_id,
    account_number,
    bill_type,
    bill_amount,
    bstart_date,
    bdue_date,
    payment_date
)
VALUES
('B001','AC1001','Rent',1200,'2025-12-01','2025-12-20', NULL),
('B002','AC1002','Electricity',150,'2025-12-05','2025-12-26', NULL),
('B003','AC1003','Internet',80,'2025-12-10','2025-12-28', NULL),
('B004','AC1004','Water',50,'2025-12-15','2025-12-31', NULL),
('B005','AC1005','Rent',2000,'2025-12-20','2026-01-05', NULL),
('B006','AC1006','Subscription',20,'2025-12-22','2026-01-10', NULL),
('B007','AC1007','Electricity',100,'2025-12-05','2025-12-24', NULL),
('B008','AC1008','Internet',60,'2025-12-12','2025-12-29', NULL),
('B009','AC1009','Water',45,'2025-12-18','2026-01-02', NULL),
('B010','AC1010','Rent',1500,'2025-12-22','2026-01-12', NULL);

UPDATE Paid_Bill
SET bdue_date = '2025-12-20',
    account_number = 'AC1001',
    bstart_date = '2025-12-01',
    bill_type = 'Rent',
    bill_amount = 1200,
    payment_date = '2025-12-19'
WHERE bill_id = 'B001';

UPDATE Paid_Bill
SET bdue_date = '2025-12-26',
    account_number = 'AC1002',
    bstart_date = '2025-12-05',
    bill_type = 'Electricity',
    bill_amount = 150,
    payment_date = '2025-12-26'
WHERE bill_id = 'B002';

UPDATE Paid_Bill
SET bdue_date = '2025-12-28',
    account_number = 'AC1003',
    bstart_date = '2025-12-10',
    bill_type = 'Internet',
    bill_amount = 80,
    payment_date = '2025-12-28'
WHERE bill_id = 'B003';

UPDATE Paid_Bill
SET bdue_date = '2025-12-31',
    account_number = 'AC1004',
    bstart_date = '2025-12-15',
    bill_type = 'Water',
    bill_amount = 50,
    payment_date = '2025-12-28'
WHERE bill_id = 'B004';

UPDATE Paid_Bill
SET bdue_date = '2026-01-05',
    account_number = 'AC1005',
    bstart_date = '2025-12-20',
    bill_type = 'Rent',
    bill_amount = 2000,
    payment_date = NULL
WHERE bill_id = 'B005';

UPDATE Paid_Bill
SET bdue_date = '2026-01-10',
    account_number = 'AC1006',
    bstart_date = '2025-12-22',
    bill_type = 'Subscription',
    bill_amount = 20,
    payment_date = NULL
WHERE bill_id = 'B006';

UPDATE Paid_Bill
SET bdue_date = '2025-12-24',
    account_number = 'AC1007',
    bstart_date = '2025-12-05',
    bill_type = 'Electricity',
    bill_amount = 100,
    payment_date = '2025-12-22'
WHERE bill_id = 'B007';

UPDATE Paid_Bill
SET bdue_date = '2025-12-27',
    account_number = 'AC1008',
    bstart_date = '2025-12-12',
    bill_type = 'Internet',
    bill_amount = 60,
    payment_date = '2025-12-28'
WHERE bill_id = 'B008';

UPDATE Paid_Bill
SET bdue_date = '2026-01-02',
    account_number = 'AC1009',
    bstart_date = '2025-12-18',
    bill_type = 'Water',
    bill_amount = 45,
    payment_date = NULL
WHERE bill_id = 'B009';

UPDATE Paid_Bill
SET bdue_date = '2025-12-26',
    account_number = 'AC1010',
    bstart_date = '2025-12-22',
    bill_type = 'Rent',
    bill_amount = 1500,
    payment_date = NULL
WHERE bill_id = 'B010';

/* has card */
INSERT INTO Has_Card (card_number, customer_id, expiration_date, card_type, cvv)
VALUES
(1001,'C001','2027-04-30','Physical',237),
(1002,'C002','2026-03-15','Virtual',374),
(1003,'C003','2028-09-17','Physical',397),
(1004,'C004','2027-02-11','Virtual',913),
(1005,'C005','2029-07-30','Physical',467),
(1006,'C006','2026-01-22','Virtual',365),
(1007,'C007','2027-06-10','Physical',574),
(1008,'C008','2028-10-14','Virtual',562),
(1009,'C009','2026-12-01','Physical',525),
(1010,'C010','2025-08-25','Virtual',753);

/* maintains */
INSERT INTO Maintains (customer_id, account_number)
VALUES
('C001','AC1001'),
('C002','AC1002'),
('C003','AC1002'),
('C003','AC1003'),
('C004','AC1004'),
('C005','AC1005'),
('C006','AC1005'),
('C006','AC1006'),
('C007','AC1007'),
('C008','AC1007'),
('C008','AC1008'),
('C009','AC1009'),
('C010','AC1010');

/* borrowed loans */
INSERT INTO Borrowed_Loan (loan_id, customer_id, loan_amount, loan_type, interest_rate, lstart_date, ldue_date)
VALUES
('L001','C001',50000,'Personal Loan',8,'2024-01-15','2027-01-15'),
('L002','C002',200000,'Mortgage',5,'2023-06-01','2043-06-01'),
('L003','C003',30000,'Auto Loan',7,'2024-03-10','2029-03-10'),
('L004','C004',15000,'Personal Loan',9,'2024-05-20','2026-05-20'),
('L005','C005',100000,'Home Equity Loan',6,'2023-11-01','2033-11-01'),
('L006','C006',25000,'Personal Loan',10,'2024-02-14','2027-02-14'),
('L007','C007',80000,'Mortgage',5,'2022-08-30','2042-08-30'),
('L008','C008',40000,'Auto Loan',6,'2024-04-05','2029-04-05'),
('L009','C009',20000,'Personal Loan',8,'2024-06-15','2026-06-15'),
('L010','C010',35000,'Personal Loan',11,'2024-07-01','2027-07-01');

/* customer tickets */
INSERT INTO Customer_Ticket (ticket_id, customer_id, topic, message)
VALUES
('T001','C001','Account Access','Can not log in to my online banking account'),
('T002','C002','Card Issue','My card was declined during online shopping'),
('T003','C003','Loan Inquiry','I would like information about refinancing options'),
('T004','C004','Mobile App Error','The app crashes when I try to transfer money'),
('T005','C005','Investment Advice','Looking for investment portfolio recommendations'),
('T006','C006','Website Problem','Can not access my statements on the website'),
('T007','C007','Bill Payment','Need help setting up automatic bill payments'),
('T008','C008','ATM Issue','ATM did not dispense cash but debited my account'),
('T009','C009','Account Statement','I see unauthorized transactions on my statement'),
('T010','C010','Password Reset','Can not reset my password through the app');

/* consultations */
INSERT INTO Consultation_Ticket (ticket_id, advisor)
VALUES
('T003','Sarah Johnson'),
('T005','Ken Park'),
('T007','Emily Davis'),
('T009','David Williams');

/* IT tickets */
INSERT INTO IT_Ticket (ticket_id, device)
VALUES
('T001','Macbook Pro M4'),
('T002','iPhone 14'),
('T004','Samsung Galaxy Tab S11 Ultra'),
('T006','MacBook Pro M3 Pro'),
('T008','iPhone 11'),
('T010','iPhone 12');

---------------------------------------------------------
-- TRIGGERS
---------------------------------------------------------

DELIMITER //

CREATE TRIGGER Decrease_Balance_After_Bill_Paid
AFTER INSERT ON Paid_Bill
FOR EACH ROW
BEGIN
    UPDATE Account
    SET balance = balance - NEW.bill_amount
    WHERE account_number = NEW.account_number;
END //

CREATE TRIGGER Update_Credit_Score_After_Bill_Paid
AFTER INSERT ON Paid_Bill
FOR EACH ROW
BEGIN
    IF NEW.payment_date > NEW.bdue_date THEN
        UPDATE Customer
        SET credit_score = ROUND(credit_score * 0.99)
        WHERE customer_id IN (
            SELECT customer_id
            FROM Maintains
            WHERE account_number = NEW.account_number
        );
    ELSE
        UPDATE Customer
        SET credit_score = ROUND(credit_score * 1.01)
        WHERE customer_id IN (
            SELECT customer_id
            FROM Maintains
            WHERE account_number = NEW.account_number
        );
    END IF;
END //

DELIMITER ;

---------------------------------------------------------
-- STORED PROCEDURES
---------------------------------------------------------

DELIMITER //

CREATE PROCEDURE CreateTicket (
    IN p_ticket_id CHAR(20),
    IN p_customer_id CHAR(20),
    IN p_topic CHAR(50),
    IN p_message VARCHAR(250),
    IN p_type CHAR(20),
    IN p_extra_info VARCHAR(50)
)
BEGIN
    INSERT INTO Customer_Ticket (ticket_id, customer_id, topic, message)
    VALUES (p_ticket_id, p_customer_id, p_topic, p_message);

    IF p_type = 'IT' THEN
        INSERT INTO IT_Ticket(ticket_id, device)
        VALUES (p_ticket_id, p_extra_info);

    ELSEIF p_type = 'Consultation' THEN
        INSERT INTO Consultation_Ticket(ticket_id, advisor)
        VALUES (p_ticket_id, p_extra_info);

    END IF;
END //

CREATE PROCEDURE AddNewCard (
    IN p_card_number INT,
    IN p_customer_id CHAR(20),
    IN p_exp DATE,
    IN p_type CHAR(20),
    IN p_cvv INT
)
BEGIN
    INSERT INTO Has_Card (card_number, customer_id, expiration_date, card_type, cvv)
    VALUES (p_card_number, p_customer_id, p_exp, p_type, p_cvv);
END //

DELIMITER ;