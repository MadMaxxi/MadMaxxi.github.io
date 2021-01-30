<?php
session_start();
require_once "db.php";
$stmt = $pdo->prepare("SELECT * FROM users where login=?");
$stmt->execute([
    $_SESSION['login']
]);
$login = $stmt->fetch();
$user_id=$login['id'];

if (!empty($_POST['name']) && !empty($_POST['subtitle']) && !empty($_POST['resume']) & !empty($_POST['content'])) {
    $apppath = dirname(__FILE__);
    $filepath = 'images/' . time() . basename($_FILES['file']['name']);
    $uploadfile = $apppath . '/' . $filepath;
    move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);
    $stmt = $pdo->prepare("insert into posts(title,subtitle,img,resume, message, user_id) values(?,?,?,?,?,?)");
    $stmt->execute([
       $_POST['name'],
       $_POST['subtitle'],
       $filepath,
       $_POST['resume'],
       $_POST['content'],
       $user_id
    ]);
    header("Location: index.php");
}