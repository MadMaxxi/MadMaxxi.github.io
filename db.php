<?php
$pdo = new PDO('mysql:host=localhost;dbname=blog_ir1;charset=utf8', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);