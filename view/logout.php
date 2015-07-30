<?php
include_once ('../config.php');
session_name("CRMLoyalty");
session_start();
session_destroy();
header("Location: " . BASE_URL . "view/login.php");
?>