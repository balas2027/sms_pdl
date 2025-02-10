<?php
include('session_manager.php');
checkRole('student');
echo "<h1>Welcome Student</h1>";
?>
<a href="logout.php">Logout</a>
