<?php
session_start();
require_once "db.php";
$stmt = $pdo->prepare("SELECT * FROM users where login=?");
$stmt->execute([
   $_SESSION['login']
]);
$login = $stmt->fetch();
$user_id = $login['id'];
$stmt1 = $pdo->prepare("SELECT * FROM likes where user_id=? and post_id=?");
$stmt1->execute([
   $user_id,
   $_GET['id']
]);
$likes = $stmt1->fetch();
if ($likes) {
    $stmt3 = $pdo->prepare("delete from likes where post_id=? and user_id=?");
    $stmt3->execute([
       $_GET['id'],
        $user_id
    ]);
}
else {
    $stmt2 = $pdo->prepare("insert into likes(post_id, user_id) values(?,?)");
    $stmt2->execute([
       $_GET['id'],
       $user_id
    ]);
}
header("Location: index.php");