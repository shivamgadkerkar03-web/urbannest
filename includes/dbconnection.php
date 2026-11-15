<?php
mysqli_report(MYSQLI_REPORT_OFF);
$con = mysqli_connect("localhost", "root", "", "urbanest");
if (mysqli_connect_errno()) {
  error_log('Database connection failed: ' . mysqli_connect_error());
  http_response_code(500);
  exit('Service temporarily unavailable. Please try again later.');
}
mysqli_set_charset($con, 'utf8mb4');
