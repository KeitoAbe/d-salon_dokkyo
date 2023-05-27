<?php
session_start();

//ログイン
require("login_check.php");

unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_number']);
$user_number = 0;
$user_name = "";
$user_id = "no_login";
setcookie('d_token', '', time() - 1800);
header('Location: ./index.php');
