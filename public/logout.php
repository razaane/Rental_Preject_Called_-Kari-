<?php
session_start();
session_destroy();
header("Location: login.php");
exit;
//<a href="logout.php" class="text-red-500 hover:text-red-700 font-bold">Logout</a>
