<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sunsons_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname = $_POST['lastname'];
$birthdate = $_POST['birthdate'];
$gender = $_POST['gender'];
$department = $_POST['department'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$user = $_POST['username'];
$raw_password = $_POST['password'];

$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);


$sql = "INSERT INTO employees (firstname, middlename, lastname, birthdate, gender, department, phone, email, address, username, password) 
        VALUES ('$firstname', '$middlename', '$lastname', '$birthdate', '$gender', '$department', '$phone', '$email', '$address', '$user', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    echo "Success";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>