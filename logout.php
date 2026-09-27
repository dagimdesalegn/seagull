<?php
require_once __DIR__ . '/includes/functions.php';
unset($_SESSION['customer_id'], $_SESSION['customer_name']);
flash('success','You have been logged out.');
redirect(SITE_URL . '/index.php');
?>
