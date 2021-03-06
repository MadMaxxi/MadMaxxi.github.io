<?php
session_start();
session_id();
setcookie('user_id', session_id());
require "db.php";

if (isset($_GET['page'])){
    $page = $_GET['page'];
}else $page = 1;
$kol = 3;
$art = ($page * $kol) - $kol;
$res = $pdo->query("select count(*) as count from posts");
$row = $res->fetch();
$total = $row['count'];

$str_pag=ceil($total / $kol);

$stmt1 = $pdo->query("select p.title as title, p.timestamp as timestamp, p.img as img, 
count(c.post_id) as pop_post from posts p join likes c on p.id=c.post_id group by p.title, p.timestamp, p.img order by count(c.post_id) desc limit 3");
$popular = $stmt1->fetchAll();

$stmt = $pdo->query("select p.*, u.login as login,
(select count(*) from comments where post_id=p.id) as count_com,
(select count(*) from likes where post_id=p.id) as count_likes
from posts p inner join users u on p.user_id=u.id order by timestamp desc limit $art,$kol");
$posts = $stmt->fetchAll();



$stmt2 = $pdo->query("SELECT u.login as name ,u.login as login,
(select count(*) from posts where user_id=u.id) as post_count,
(select count(*) from likes where user_id=u.id) as likes_count from posts p inner join users u on p.user_id=u.id group by u.id order by post_count desc limit 3");
$ratings = $stmt2->fetchAll();
?>

<!DOCTYPE HTML>
<!--
	Future Imperfect by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Future Imperfect by HTML5 UP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="shortcut icon" href="images/avatar_bu.jpg" type="image/jpg">
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="index.php">Blog</a></h1>
						<nav class="main">
							<ul>
								<li class="menu">
									<a class="fa-user" href="#menu">Menu</a>
								</li>
							</ul>
						</nav>
					</header>

				<!-- Menu -->
					<section id="menu">

						<!-- Actions -->
							<section>
								<ul class="actions vertical">
                                    <?php if (isset($_SESSION['login'])) {?>
                                    <li>
                                        <p> <a href="user.php"><?=$_SESSION['login']?></a></p>
                                        <p> <a href="logout.php">Log Out</a></p>
                                    </li>
                                    <?php }else{ ?>
									<li><h3>Login</h3></li>
									<li>
										<form action="auth.php" method="post">
											<input type="text" name="login" placeholder="Login"><br>
											<input type="password" name="password" placeholder="Password"><br>
											<input type="submit" class="button big fit" value="Log In">
										</form>
									</li>
                                    <?php } ?>
									<li><h3>Registration</h3></li>
									<li>
										<form action="reg.php" method="post">
											<input type="text" name="login" placeholder="Login"><br>
											<input type="password" name="password" placeholder="Password"><br>
											<input type="file" name="file"><br><br>
											<input type="submit" class="button big fit" value="Sign up">
										</form>
									</li>
								</ul>
							</section>

					</section>

				<!-- Main -->
					<div id="main">

						<!-- Post -->
							<article class="post">
                                <?php foreach ($posts as $key => $post):?>
								<header>
									<div class="title">
										<h2><a href="#"><?=$post['title']?></a></h2>
										<p><?=$post['subtitle']?></p>
									</div>
									<div class="meta">
										<time class="published" datetime="2015-11-01"><?=date('d.m.Y', strtotime($post['timestamp']))?></time>
										<a href="#" class="author"><span class="name"><?=$post['login']?></span><img src="images/avatar.jpg" alt="" /></a>
									</div>
								</header>
								<a href="#" class="image featured"><img src="<?=$post['img']?>" alt="" /></a>
								<p><?=$post['resume']?></p>
								<footer>
									<ul class="actions">
										<li><a href="single.php?id=<?=$post['id']?>" class="button big">Continue Reading</a></li>
									</ul>
									<ul class="stats">
                                        <li><a href="add_like.php?id=<?=$post['id']?>" class="icon fa-heart"><?=$post['count_likes']?></a></li>
                                        <li><a href="single.php?id=<?=$post['id']?>" class="icon fa-comment"><?=$post['count_com']?></a></li>
									</ul>
								</footer>
								<?php endforeach; ?>
							</article>


						<!-- Pagination -->
							<ul class="actions pagination">
                                <?php if ($page>1) {?>
								    <li><a href="index.php?page=<?=$page-1?>" class="button big previous">Previous Page</a></li>
                                <?php } else {?>
                                    <li><a href="#" class="disabled button big previous">Previous Page</a></li>
                                <?php } ?>
                                <?php if ($page<$str_pag) {?>
								<li><a href="index.php?page=<?=$page+1?>" class="button big next">Next Page</a></li>
                                <?php } else {?>
                                    <li><a href="#" class="disabled button big next">Next Page</a></li>
                                <?php } ?>
							</ul>

					</div>

				<!-- Sidebar -->
					<section id="sidebar">

						<!-- Intro -->
							<section id="intro">
								<a href="#" class="logo"><img src="images/logo.jpg" alt="" /></a>
								<header>
									<h2>Blog</h2>
									<p>Be popular with us</p>
								</header>
							</section>

						<!-- Mini Posts -->
							<section>
								<h3>Popular posts</h3>
								<div class="mini-posts">
									<!-- Mini Post -->
                                    <?php foreach ($popular as $key => $popipq): ?>
										<article class="mini-post">

											<header>
												<h3><a href="#"><?=$popipq['title'] ?></a></h3>
												<time class="published" datetime="2015-10-20"><?= date('d.m.Y', strtotime($popipq['timestamp']))?></time>
												<a href="#" class="author"><?=$popipq['user_id'] ?><img src="images/avatar.jpg" alt="" /></a>
											</header>
											<a href="#" class="image"><img src="<?= $popipq['img']?>" alt=""/></a>

										</article>
                                    <?php endforeach; ?>
								</div>
							</section>

						<!-- Posts List -->
							<section>

								<h3>Rating bloggers</h3>

								<ul class="posts">
                                    <?php foreach ($ratings as $key => $rates):?>
									<li>
										<article>
											<header>
												<h3><a href="#"><?= $rates['name'] ?></a></h3>
												<span class="published"><?= $rates['likes_count'] ?> likes in <?= $rates['post_count'] ?> posts</span>
											</header>
											<a href="#" class="image"><img src="images/pic08.jpg" alt="" /></a>
										</article>
									</li>
                                    <?php endforeach; ?>
								</ul>
							</section>

						<!-- Footer -->
							<section id="footer">
								<p class="copyright">&copy; Blog. Design: <a href="http://html5up.net">HTML5 UP</a>.</p>
							</section>

					</section>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>