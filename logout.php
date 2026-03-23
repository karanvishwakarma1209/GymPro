<?php
/**
 * GymPro - Logout
 */
require_once 'includes/functions.php';
logoutUser();
header('Location: ' . SITE_URL . '/login.php');
exit;
