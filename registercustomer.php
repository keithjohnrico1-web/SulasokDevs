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
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$user = $_POST['username'];
$raw_password = $_POST['password'];


$sql = "INSERT INTO users (firstname, middlename, lastname, birthdate, gender, phone, email, address, username, password) 
        VALUES ('$firstname', '$middlename', '$lastname', '$birthdate', '$gender', '$phone', '$email', '$address', '$user', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    echo "Success";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>