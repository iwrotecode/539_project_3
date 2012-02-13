<?php
session_start();
unset($_SESSION);
session_destroy();

// destroy the cookie

header("Location: login.php");
?>