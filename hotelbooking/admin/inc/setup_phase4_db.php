<?php
require_once __DIR__ . '/db_config.php';

$sql = "CREATE TABLE IF NOT EXISTS `booking_guests` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `booking_id` INT(11) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `id_card` VARCHAR(50) NOT NULL,
  `dob` DATE DEFAULT NULL,
  `gender` VARCHAR(20) DEFAULT NULL,
  `nationality` VARCHAR(100) DEFAULT 'Việt Nam',
  `phonenum` VARCHAR(20) DEFAULT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `is_leader` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($con, $sql)) {
    echo "SUCCESS: Table `booking_guests` created or already exists.\n";
} else {
    echo "ERROR: " . mysqli_error($con) . "\n";
}
?>
