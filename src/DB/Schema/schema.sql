CREATE DATABASE enrollbeauty
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

CREATE TABLE roles (
   id INT AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(50) UNIQUE NOT NULL,
   created_date DATETIME NOT NULL
);

CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(50) NOT NULL,
       surname VARCHAR(50) NOT NULL,
       password VARCHAR(255) NOT NULL,
       email VARCHAR(100) UNIQUE NOT NULL,
       created_date DATETIME NOT NULL,
       referral_parent_id INT,
       last_login_date DATETIME,
       role_id INT NOT NULL,
       FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE admins (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(50) NOT NULL,
       surname VARCHAR(50) NOT NULL,
       password VARCHAR(255) NOT NULL,
       email VARCHAR(100) UNIQUE NOT NULL,
       created_date DATETIME NOT NULL,
       referral_parent_id INT,
       last_login_date DATETIME,
       role_id INT NOT NULL,
       status INT DEFAULT 0 COMMENT '0 - inactive, 1 - active, 2 - banned',
       FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
       FOREIGN KEY (referral_parent_id) REFERENCES admins(id) ON DELETE SET NULL
);

CREATE TABLE admins_setting (
       id INT AUTO_INCREMENT PRIMARY KEY,
       admin_id INT NOT NULL,
       recovery_code VARCHAR(100),
       activation_code VARCHAR(100),
       date_of_sending DATETIME,
       FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
);

CREATE TABLE users_social (
      id INT AUTO_INCREMENT PRIMARY KEY,
      user_id INT NOT NULL,
      Instagram VARCHAR(100),
      TikTok VARCHAR(100),
      Facebook VARCHAR(100),
      YouTube VARCHAR(100),
      Google VARCHAR(100),
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE users_setting (
       id INT AUTO_INCREMENT PRIMARY KEY,
       user_id INT NOT NULL,
       recovery_code VARCHAR(100),
       activation_code VARCHAR(100),
       date_of_sending DATETIME,
       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE users_photo (
     id INT AUTO_INCREMENT PRIMARY KEY,
     user_id INT NOT NULL,
     filename VARCHAR(255),
     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE departments (
     id INT AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(50) NOT NULL
);
CREATE TABLE positions (
   id INT AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(50) NOT NULL,
   department_id INT NOT NULL,
   FOREIGN KEY (department_id) REFERENCES departments(id)
);

CREATE TABLE workers (
     id INT AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(50) NOT NULL,
     surname VARCHAR(50) NOT NULL,
     password VARCHAR(255) NOT NULL,
     email VARCHAR(100) UNIQUE NOT NULL,
     gender ENUM('Male', 'Female', 'Other'),
     age INT NOT NULL,
     years_of_experience DECIMAL(10, 2) NOT NULL,
     position_id INT NOT NULL,
     salary DECIMAL(10, 2),
     role_id INT NOT NULL,
     created_date DATETIME NOT NULL,
     FOREIGN KEY (position_id) REFERENCES positions(id),
     FOREIGN KEY (role_id) REFERENCES roles(id)
);
CREATE TABLE workers_photo (
   id INT AUTO_INCREMENT PRIMARY KEY,
   worker_id INT NOT NULL,
   name VARCHAR(255),
   FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE
);

CREATE TABLE workers_social (
    id INT AUTO_INCREMENT PRIMARY KEY,
    worker_id INT NOT NULL,
    Instagram VARCHAR(100),
    LinkedIn VARCHAR(100),
    Facebook VARCHAR(100),
    Github VARCHAR(100),
    Telegram VARCHAR(100),
    YouTube VARCHAR(100),
    TikTok VARCHAR(100),
    FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE
);
CREATE TABLE workers_setting (
   id INT AUTO_INCREMENT PRIMARY KEY,
   worker_id INT NOT NULL,
   recovery_code VARCHAR(100),
   activation_code VARCHAR(100),
   date_of_sending DATETIME,
   FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE
);

CREATE TABLE documents (
       id INT AUTO_INCREMENT PRIMARY KEY,
       photo_name VARCHAR(255),
       file_pdf_name VARCHAR(255)
);
CREATE TABLE workers_document (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      number VARCHAR(255) NOT NULL,
      worker_id INT NOT NULL,
      issued_by VARCHAR(255) NOT NULL,
      issued_date DATE NOT NULL,
      created_date DATETIME NOT NULL,
      document_id INT,
      FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE,
      FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE SET NULL
);

CREATE TABLE workers_rating (
    id INT AUTO_INCREMENT PRIMARY KEY,
    worker_id INT NOT NULL,
    user_id INT NOT NULL,
    score INT CHECK (score >= 0 AND score <= 5) NOT NULL,
    created_date DATETIME NOT NULL,
    FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE affiliates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    city VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
    address VARCHAR(255) NOT NULL,
    worker_manager_id INT,
    created_date DATETIME NOT NULL,
    FOREIGN KEY (worker_manager_id) REFERENCES workers(id) ON DELETE SET NULL
);
CREATE TABLE services (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL,
      department_id INT NOT NULL,
      FOREIGN KEY (department_id) REFERENCES departments(id)
);
CREATE TABLE workers_affiliate (
    id INT AUTO_INCREMENT PRIMARY KEY,
    worker_id INT NOT NULL,
    affiliate_id INT NOT NULL,
    created_date DATETIME NOT NULL,
    FOREIGN KEY (affiliate_id) REFERENCES affiliates(id) ON DELETE CASCADE,
    FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE
);
CREATE TABLE workers_service_pricing (
      id INT AUTO_INCREMENT PRIMARY KEY,
      worker_id INT NOT NULL,
      service_id INT NOT NULL,
      price DECIMAL(10, 2) NOT NULL,
      currency VARCHAR(5) DEFAULT 'UAH',
      updated_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE,
      FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

CREATE TABLE orders_service (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    schedule_id INT,
    email VARCHAR(100) NOT NULL,
    price_id INT NOT NULL,
    affiliate_id INT NOT NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    completed_datetime DATETIME,
    canceled_datetime DATETIME,
    created_datetime DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES workers_service_schedule(id) ON DELETE CASCADE,
    FOREIGN KEY (price_id) REFERENCES workers_service_pricing(id) ON DELETE CASCADE,
    FOREIGN KEY (affiliate_id) REFERENCES affiliates(id) ON DELETE CASCADE
);
CREATE TABLE workers_service_schedule (
      id INT AUTO_INCREMENT PRIMARY KEY,
      price_id INT NOT NULL,
      affiliate_id INT NOT NULL,
      day DATE NOT NULL,
      start_time TIME NOT NULL,
      end_time TIME NOT NULL,
      order_id INT COMMENT 'If not null, the time window has been taken',
      FOREIGN KEY (price_id) REFERENCES workers_service_pricing(id) ON DELETE CASCADE,
      FOREIGN KEY (affiliate_id) REFERENCES affiliates(id) ON DELETE CASCADE,
      FOREIGN KEY (order_id) REFERENCES orders_service(id) ON DELETE SET NULL
);
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    parent_category_id INT,
    FOREIGN KEY (parent_category_id) REFERENCES categories(id) ON DELETE CASCADE
);
CREATE TABLE products (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL,
      price DECIMAL(10, 2) NOT NULL,
      currency VARCHAR(5) DEFAULT 'UAH',
      category_id INT NOT NULL,
      available_count INT NOT NULL,
      FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
CREATE TABLE orders_product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    email VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    product_id INT NOT NULL,
    count INT NOT NULL,
    worker_id INT,
    affiliate_id INT,
    created_datetime DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE,
    FOREIGN KEY (affiliate_id) REFERENCES affiliates(id) ON DELETE SET NULL
);

CREATE TABLE comments (
      id INT AUTO_INCREMENT PRIMARY KEY,
      user_id INT NOT NULL,
      text TEXT NOT NULL,
      created_date DATETIME NOT NULL,
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


# Statistics
CREATE TABLE periods (
     id INT AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(30) NOT NULL,
     start_date DATE NOT NULL,
     end_date DATE NOT NULL,
     created_date DATETIME NOT NULL,
     updated_date DATETIME
);

#   Statistics: Spends
CREATE TABLE spend_types (
     id INT AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(100) UNIQUE NOT NULL,
     created_date DATETIME NOT NULL
);
CREATE TABLE statistic_spends (
      id INT AUTO_INCREMENT PRIMARY KEY,
      spend_type_id INT NOT NULL,
      sum DECIMAL(10, 2) NOT NULL,
      created_date DATETIME NOT NULL,
      FOREIGN KEY (spend_type_id) REFERENCES spend_types(id) ON DELETE CASCADE
);
CREATE TABLE statistic_spends_summary (
      id INT AUTO_INCREMENT PRIMARY KEY,
      spend_type_id INT NOT NULL,
      sum DECIMAL(10, 2) NOT NULL,
      period_id INT NOT NULL,
      created_date DATETIME NOT NULL,
      updated_date DATETIME,
      FOREIGN KEY (spend_type_id) REFERENCES spend_types(id) ON DELETE CASCADE,
      FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE CASCADE
);

# Statistics: Incomes
CREATE TABLE income_types (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) UNIQUE NOT NULL,
      created_date DATETIME NOT NULL
);
CREATE TABLE statistic_incomes (
       id INT AUTO_INCREMENT PRIMARY KEY,
       income_type_id INT NOT NULL,
       sum DECIMAL(10, 2) NOT NULL,
       created_date DATETIME NOT NULL,
       FOREIGN KEY (income_type_id) REFERENCES income_types(id) ON DELETE CASCADE
);
CREATE TABLE statistic_incomes_summary (
       id INT AUTO_INCREMENT PRIMARY KEY,
       income_type_id INT NOT NULL,
       sum DECIMAL(10, 2) NOT NULL,
       period_id INT NOT NULL,
       created_date DATETIME NOT NULL,
       updated_date DATETIME,
       FOREIGN KEY (income_type_id) REFERENCES income_types(id) ON DELETE CASCADE,
       FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE CASCADE
);

# Statistic: Workers [Income for the salon from worker]
CREATE TABLE statistic_workers_income (
      id INT AUTO_INCREMENT PRIMARY KEY,
      worker_id INT NOT NULL,
      type_id INT NOT NULL,
      created_date DATETIME NOT NULL,
      sum DECIMAL(10, 2) NOT NULL,
      FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE,
      FOREIGN KEY (type_id) REFERENCES income_types(id) ON DELETE CASCADE
);
CREATE TABLE statistic_workers_income_summary (
      id INT AUTO_INCREMENT PRIMARY KEY,
      worker_id INT NOT NULL,
      sum DECIMAL(10, 2) NOT NULL,
      period_id INT NOT NULL,
      created_date DATETIME NOT NULL,
      updated_date DATETIME,
      FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE,
      FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE CASCADE
);
# Statistic: Workers [Earning of the worker]
CREATE TABLE workers_earning_types (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(50) NOT NULL
);
CREATE TABLE statistic_workers_earning (
       id INT AUTO_INCREMENT PRIMARY KEY,
       worker_id INT NOT NULL,
       sum DECIMAL(10, 2) NOT NULL,
       type_id INT NOT NULL,
       created_date DATETIME NOT NULL,
       FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE,
       FOREIGN KEY (type_id) REFERENCES workers_earning_types(id) ON DELETE CASCADE
);
CREATE TABLE statistic_workers_earning_summary (
       id INT AUTO_INCREMENT PRIMARY KEY,
       worker_id INT NOT NULL,
       sum DECIMAL(10, 2) NOT NULL,
       period_id INT NOT NULL,
       created_date DATETIME NOT NULL,
       updated_date DATETIME,
       FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE,
       FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE CASCADE
);

# Statistic: Users
CREATE TABLE statistic_users_service (
     id INT AUTO_INCREMENT PRIMARY KEY,
     user_id INT NOT NULL,
     order_id INT NOT NULL,
     created_date DATETIME NOT NULL,
     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
     FOREIGN KEY (order_id) REFERENCES orders_service(id) ON DELETE CASCADE
);
CREATE TABLE statistic_users_product (
     id INT AUTO_INCREMENT PRIMARY KEY,
     user_id INT NOT NULL,
     order_id INT NOT NULL,
     created_date DATETIME NOT NULL,
     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
     FOREIGN KEY (order_id) REFERENCES orders_product(id) ON DELETE CASCADE
);
CREATE TABLE statistic_users_service_summary (
     id INT AUTO_INCREMENT PRIMARY KEY,
     user_id INT NOT NULL,
     sum DECIMAL(10, 2) NOT NULL,
     period_id INT NOT NULL,
     created_date DATETIME NOT NULL,
     updated_date DATETIME,
     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
     FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE CASCADE
);
CREATE TABLE statistic_users_product_summary (
     id INT AUTO_INCREMENT PRIMARY KEY,
     user_id INT NOT NULL,
     sum DECIMAL(10, 2) NOT NULL,
     period_id INT NOT NULL,
     created_date DATETIME NOT NULL,
     updated_date DATETIME,
     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
     FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE CASCADE
);

# Statistic: Services
CREATE TABLE statistic_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    order_id INT NOT NULL,
    created_date DATETIME NOT NULL,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders_service(id) ON DELETE CASCADE
);
CREATE TABLE statistic_services_summary (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    sum DECIMAL(10, 2) NOT NULL,
    period_id INT NOT NULL,
    created_date DATETIME NOT NULL,
    updated_date DATETIME,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE CASCADE
);

# Statistic: Products
CREATE TABLE statistic_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    order_id INT NOT NULL,
    created_date DATETIME NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders_product(id) ON DELETE CASCADE
);
CREATE TABLE statistic_products_summary (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    sum DECIMAL(10, 2) NOT NULL,
    period_id INT NOT NULL,
    created_date DATETIME NOT NULL,
    updated_date DATETIME,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE CASCADE
);

# Statistic: Categories
CREATE TABLE statistic_categories (
      id INT AUTO_INCREMENT PRIMARY KEY,
      category_id INT NOT NULL,
      order_id INT NOT NULL,
      created_date DATETIME NOT NULL,
      FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
      FOREIGN KEY (order_id) REFERENCES orders_product(id) ON DELETE CASCADE
);
CREATE TABLE statistic_categories_summary (
      id INT AUTO_INCREMENT PRIMARY KEY,
      category_id INT NOT NULL,
      sum DECIMAL(10, 2) NOT NULL,
      period_id INT NOT NULL,
      created_date DATETIME NOT NULL,
      updated_date DATETIME NOT NULL,
      FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
      FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE CASCADE
);
