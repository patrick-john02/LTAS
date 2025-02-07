-- Users Table (storing user information)
CREATE TABLE `users` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Username` VARCHAR(15) NOT NULL,
  `Password` VARCHAR(255) NOT NULL, -- store hashed passwords
  `FirstName` VARCHAR(15) NOT NULL,
  `LastName` VARCHAR(15) NOT NULL,
  `Position` VARCHAR(100) DEFAULT NULL,
  `Email` VARCHAR(100) DEFAULT NULL,
  `Dept` VARCHAR(100) DEFAULT NULL,
  `u_status` ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Inactive',
  `otp` INT(11) DEFAULT NULL,
  `is_password_reset` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`ID`),
  UNIQUE (`Username`),
  UNIQUE (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Admin Table (refers to users as admins)
CREATE TABLE `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL, -- foreign key referencing users table
  `AccessLevel` VARCHAR(10) NOT NULL, 
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Document Categories Table (normalizing the Category field)
CREATE TABLE `categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Documents Table (normalized with references to users and categories)
CREATE TABLE `documents` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `doc_no` VARCHAR(255) UNIQUE DEFAULT NULL,
  `title` VARCHAR(50) NOT NULL,
  `description` VARCHAR(100) NOT NULL,
  `author` VARCHAR(20) NOT NULL,
  `date_published` DATETIME NOT NULL,
  `category_id` INT(11) NOT NULL,  -- foreign key to categories table
  `file_path` VARCHAR(255) DEFAULT NULL,
  `user_id` INT(11) NOT NULL,  -- foreign key to users table
  `d_status` ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
  `isArchive` INT(11) NOT NULL DEFAULT 0,
  `resolution_no` VARCHAR(255) DEFAULT NULL,
  `ordinance_no` VARCHAR(255) DEFAULT NULL,
  `approval_timestamp` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Document Timeline Table (already normalized with a reference to documents)
CREATE TABLE `document_timeline` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `document_id` INT(11) NOT NULL,  -- foreign key to documents table
  `action` ENUM('Pending', 'First Reading', 'Second Reading', 'Approve', 'Reject', 'In Committee') NOT NULL,
  `changed_column` VARCHAR(50) DEFAULT NULL,
  `old_value` TEXT DEFAULT NULL,
  `new_value` TEXT DEFAULT NULL,
  `performed_by` VARCHAR(50) NOT NULL,
  `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP(),
  `rejection_timestamp` DATETIME DEFAULT NULL,
  `comment` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`document_id`) REFERENCES `documents`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
