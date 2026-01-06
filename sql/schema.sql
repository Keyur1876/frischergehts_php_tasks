Create DATABASE if not exists delivery_crm
character set utf8mb4  -- supports all modern Unicode chars like umlauts and emojis
collate utf8mb4_unicode_ci; -- how text is compared and stored (MAX==max==Max)


-- core customer table

Create Table if not exists customer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    customer_group VARCHAR(100) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB;


create table if not exists customer_contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    email VARCHAR(50) NULL,
    phone VARCHAR(20) NULL,

    constraint fk_contact_customer
        foreign key (customer_id) references customer(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE if not exists customer_address (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  street VARCHAR(190) NULL,
  zip VARCHAR(20) NULL,
  city VARCHAR(100) NULL,
  constraint fk_address_customer
    FOREIGN KEY (customer_id) REFERENCES customer(id)
    ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS customer_notice (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  notice TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  constraint fk_notice_customer
    FOREIGN KEY (customer_id) REFERENCES customer(id)
    ON DELETE CASCADE
) ENGINE=InnoDB;


-- Demo data

INSERT INTO customer (first_name, last_name, customer_group) VALUES
('Keyur', 'Kachhadiya', 'A'),
('Shyam', 'Patel', 'B');

INSERT INTO customer_contact (customer_id, email, phone) VALUES
(1, 'keyur@example.com', '+49 123 45635166'),
(2, 'shyam@example.com', '+49 222 33335166');

INSERT INTO customer_address (customer_id, street, zip, city) VALUES
(1, 'Gutzkowstraße 1', '01069', 'Dresden'),
(2, 'Nebenweg 2', '20095', 'Hamburg');

INSERT INTO customer_notice (customer_id, notice) VALUES
(1, 'Prefers deliveries after 18:00'),
(2, 'Allergic to peanuts (inform driver)');