create database treasure;
use database treasure;
CREATE TABLE chest_openings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
    opening_rank INT NOT NULL,
    message TEXT NOT NULL,
);
