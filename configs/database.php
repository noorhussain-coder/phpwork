<?php


$severname='localhost';
$username='root';
$password='';
$database='cybersecurity';


$conn=new mysqli($severname,$username,$password,$database);
if($conn->connect_error){
    die( 'some went wrong'.$conn->connect_error);
}



?>

<!-- CREATE DATABASE cybersecurity;

USE user_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); -->

<!-- CREATE TABLE videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,                  -- uploader id (foreign key to users)
    title VARCHAR(255) NOT NULL,           -- video title
    description TEXT,                      -- optional description
    video_url VARCHAR(500) NOT NULL,       -- Cloudinary video URL
    public_id VARCHAR(255),                -- Cloudinary public ID (for deleting/updating)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); -->