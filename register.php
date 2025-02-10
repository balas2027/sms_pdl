<?php
session_start();
include('db.php');

if(isset($_POST['submit'])){
    $name = trim($_POST['name']);
    $uname = trim($_POST['uname']);
    $phone = trim($_POST['number']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $role = isset($_POST['role']) ? trim($_POST['role']) : ''; 

    if (!$email) {
        echo "<script>alert('Invalid Email Format!'); window.history.back();</script>";
        exit();
    }

    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long, include a number and an uppercase letter!');</script>";
        exit();
    }

    if ($password !== $cpassword) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT email FROM user_validation WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->fetch_assoc()) {
        echo "<script>alert('User Already Exists'); window.history.back();</script>";
        exit();
    }

    // Insert into user_validation
    $stmt = $conn->prepare("INSERT INTO user_validation (name, user_name, phone_number, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $uname, $phone, $email, $hashed_password, $role);

    // Insert into student_details if role is student
    if ($role === 'student') {
        $stmt1 = $conn->prepare("INSERT INTO student_details (name, user_name, phone_number, email, role) VALUES (?, ?, ?, ?, ?)");
        $stmt1->bind_param("sssss", $name, $uname, $phone, $email, $role);
    }

    // Insert into staff_details if role is teacher or admin
    if ($role === 'teacher' || $role === 'admin') {
        $stmt2 = $conn->prepare("INSERT INTO staff_details (name, user_name, phone_number, email, role) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("sssss", $name, $uname, $phone, $email, $role);
    }

    if ($stmt->execute() && 
        ($role !== 'student' || $stmt1->execute()) && 
        ($role === 'student' || $stmt2->execute())) {
        echo "<script>alert('Registration Successful'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Registration Failed. Try again!');</script>";
    }

    $stmt->close();
    if (isset($stmt1)) $stmt1->close();
    if (isset($stmt2)) $stmt2->close();
    $conn->close();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
 
    
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Responsive Registration Form | CodingLab </title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <!-- Title section -->
    <div class="title">Registration</div>
    <div class="content">
      <!-- Registration form -->
      <form action="register.php" method="POST">
        <div class="user-details">
          <!-- Input for Full Name -->
          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" name="name" placeholder="Enter your name" required>
          </div>
          <!-- Input for Username -->
          <div class="input-box">
            <span class="details">Username</span>
            <input type="text" name="uname" placeholder="Enter your username" required>
          </div>
          <!-- Input for Email -->
          <div class="input-box">
            <span class="details">Email</span>
            <input type="text" name="email" placeholder="Enter your email" required>
          </div>
          <!-- Input for Phone Number -->
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="text" name="number" placeholder="Enter your number" required>
          </div>
         <!-- Password -->
<div class="input-box">
  <span class="details">Password</span>
  <input type="password" name="password" placeholder="Enter your password" required>
</div>
<!-- Confirm Password -->
<div class="input-box">
  <span class="details">Confirm Password</span>
  <input type="password" name="cpassword" placeholder="Confirm your password" required>
</div>
<div class="input-box">
    <span class="details">Role</span>
    <select name="role" required>
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
        <option value="admin">Admin</option>
    </select>
</div>

      
        </div>
        <!-- Submit button -->
        <div class="button">
          <input style="color:red; background-color:black; padding:10px; border-radius:10px"  type="submit" name="submit" value="Register">
        </div>
      </form>
    </div>
  </div>
</body>
</html>
    
</body>
</html>