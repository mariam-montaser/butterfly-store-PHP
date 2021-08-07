<?php

require_once '../core/init.php';
unset($_SESSION['BUser']);
header('Location:login.php');
exit();