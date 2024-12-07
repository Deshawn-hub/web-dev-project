-- Step 1: Create the dolphin_crm database
CREATE DATABASE dolphin_crm;

-- Step 2: Use the database
USE dolphin_crm;

-- Step 3: Define the tables

-- Create the 'users' table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the 'contacts' table
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(10) NOT NULL,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(15),
    company VARCHAR(100),
    type VARCHAR(50) NOT NULL,
    assigned_to INT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Create the 'notes' table
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contact_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Step 4: Insert initial data

-- Insert an admin user
-- Replace 'adminpasswordhash' with the hashed password generated using a password hashing function
INSERT INTO users (firstname, lastname, email, password, role)
VALUES ('Admin', 'User', 'admin@project2.com', '$2y$10$examplehashadmin...', 'Admin');

-- Insert a member user
-- Replace 'memberpasswordhash' with the hashed password generated using a password hashing function
INSERT INTO users (firstname, lastname, email, password, role)
VALUES ('John', 'Doe', 'john.doe@example.com', '$2y$10$examplehashmember...', 'Member');

-- Insert sample contacts
INSERT INTO contacts (title, firstname, lastname, email, telephone, company, type, created_by)
VALUES 
('Mr', 'Jane', 'Smith', 'jane@example.com', '123-456-7890', 'TechCorp', 'Sales Lead', 1),
('Ms', 'John', 'Appleseed', 'john@example.com', '987-654-3210', 'BizSolutions', 'Support', 2);

-- Insert sample notes
INSERT INTO notes (contact_id, comment, created_by)
VALUES 
(1, 'Followed up on project proposal.', 1),
(2, 'Sent an email regarding new opportunities.', 2);
