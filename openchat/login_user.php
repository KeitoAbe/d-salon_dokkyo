<?php
session_start();
//ログイン
require("../login_check.php");

$list = array(0 => $user_name, 1 => $user_number);
echo (json_encode($list));
