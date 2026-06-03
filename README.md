Student Management System:-

A web-based Student Management System developed using PHP, MySQL, HTML, CSS, JavaScript, and Bootstrap. This project helps schools or colleges manage students,
teachers, classes, attendance, and related academic activities through a simple and user-friendly interface.

-->  Features:--

Student Registration and Management, 
Teacher Management, 
Class and Subject Management, 
Attendance Tracking, 
User Authentication and Login System, 
Dashboard with Statistics, 
CRUD Operations (Create, Read, Update, Delete), 
Responsive User Interface, 
API Integration Support, 
Database Connectivity with MySQL. 



Technologies Used:--


Frontend: HTML, CSS, JavaScript, Bootstrap


Backend: PHP


Database: MySQL

Server: XAMPP / Apache

Version Control: Git & GitHub

-->  Project Structure:
school_management/
|
│__ admin/
├── api/
├── assets/
├── config/
├── database/
└── README.md

-->  Installation Steps:

1. Clone the Repository
git clone https://github.com/sowmikagamidi/Student-Management.git
2. Move Project to XAMPP htdocs Folder
C:\xampp_new\htdocs\school_management
3. Start Apache and MySQL
Open XAMPP Control Panel and start:
-- Apache
-- MySQL
4. Create Database
Open phpMyAdmin
Create a new database
Import the SQL file from the database folder
5. Configure Database Connection
Update database credentials in your config file:

$host = "localhost";
$user = "root";
$password = "";
$database = "school_management";
6. Run the Project

Open browser and run:
http://localhost/school_management
