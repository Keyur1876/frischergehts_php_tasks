
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
