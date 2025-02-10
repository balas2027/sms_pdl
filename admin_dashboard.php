<?php
include('session_manager.php');
checkRole('teacher');
echo "<h1>Welcome Admin</h1>";
?>
<a href="logout.php">Logout</a>
