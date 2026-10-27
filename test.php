<?php
require 'config.php';
$db = new PDO('mysql:host=localhost;dbname=rajasthan_reads', 'root', '');
$stmt = $db->query("SELECT file_path FROM books WHERE slug = 'ignou'");
print_r($stmt->fetch(PDO::FETCH_ASSOC));
?>
