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
$str_pag=ceil($total/$kol);

$stmt = $pdo->query("select p.*, u.login as login,
(select count(*) from comments where post_id=p.id) as count_com,
(select count(*) from likes where post_id=p.id) as count_likes
from posts p inner join users u on p.user_id=u.id order by timestamp desc limit $art,$kol");
$posts = $stmt->fetchAll();
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
						<h1><a href="#">Blog</a></h1>
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
                                        <p><?=$_SESSION['login']?></p>
                                        <a href="logout.php"></a>
                                    </li>
                                    <?php;}else{?>
									<li><h3>Login</h3></li>
									<li>
										<form action="auth.php" method="post">
											<input type="text" name="login" placeholder="Login"><br>
											<input type="password" name="password" placeholder="Password"><br>
											<input type="submit" class="button big fit" value="Log In">
										</form>
									</li>
                                    <?php }?>
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
										<a href="#" class="author"><span class="name"><?=$post['login']?></span><img src="images/sana.jpg.jpg" alt="" /></a>
									</div>
								</header>
								<a href="#" class="image featured"><img src="<?=$post['img']?>" alt="" /></a>
								<p><?=$post['resume']?></p>
								<footer>
									<ul class="actions">
										<li><a href="single.php?id=<?=$post['id']?>" class="button big">Continue Reading</a></li>
									</ul>
									<ul class="stats">
                                        <li><a href="add_like.php?id=<?= $post['id'] ?>" class="icon fa-heart"><?=$post['count_likes']?></a></li>
                                        <li><a href="#" class="icon fa-comment"><?=$post['count_com']?></a></li>
									</ul>
								</footer>
								<?php endforeach;?>
							</article>


						<!-- Pagination -->
							<ul class="actions pagination">
                                <?php if ($page>1){?>
								    <li><a href="index.php?page<?=page-1?>" class="button big previous">Previous Page</a></li>
                                <?php } else {?>
                                    <li><a href="#" class="disabled button big previous">Previous Page</a></li>
                                <?php } ?>
                                <?php if ($page<$str_pag) {?>
								<li><a href="index.php?page<?=page+1?>" class="button big next">Next Page</a></li>
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
										<article class="mini-post">
											<header>
												<h3><a href="#">Vitae sed condimentum</a></h3>
												<time class="published" datetime="2015-10-20">1 Ноября 2015</time>
												<a href="#" class="author"><img src="images/avatar.jpg" alt="" /></a>
											</header>
											<a href="#" class="image"><img src="images/pic04.jpg" alt="" /></a>
										</article>

									<!-- Mini Post -->
										<article class="mini-post">
											<header>
												<h3><a href="#">Rutrum neque accumsan</a></h3>
												<time class="published" datetime="2015-10-19">1 Ноября 2015</time>
												<a href="#" class="author"><img src="images/avatar.jpg" alt="" /></a>
											</header>
											<a href="#" class="image"><img src="images/pic05.jpg" alt="" /></a>
										</article>

									<!-- Mini Post -->
										<article class="mini-post">
											<header>
												<h3><a href="#">Odio congue mattis</a></h3>
												<time class="published" datetime="2015-10-18">1 Ноября 2015</time>
												<a href="#" class="author"><img src="images/avatar.jpg" alt="" /></a>
											</header>
											<a href="#" class="image"><img src="images/pic06.jpg" alt="" /></a>
										</article>

									<!-- Mini Post -->
										<article class="mini-post">
											<header>
												<h3><a href="#">Enim nisl veroeros</a></h3>
												<time class="published" datetime="2015-10-17">1 Ноября 2015</time>
												<a href="#" class="author"><img src="images/avatar.jpg" alt="" /></a>
											</header>
											<a href="#" class="image"><img src="images/pic07.jpg" alt="" /></a>
										</article>

								</div>
							</section>

						<!-- Posts List -->
							<section>

								<h3>Rating bloggers</h3>

								<ul class="posts">
									<li>
										<article>
											<header>
												<h3><a href="#">Lorem ipsum fermentum ut nisl vitae</a></h3>
												<span class="published">30 likes in 10 posts</span>
											</header>
											<a href="#" class="image"><img src="images/pic08.jpg" alt="" /></a>
										</article>
									</li>
									<li>
										<article>
											<header>
												<h3><a href="#">Convallis maximus nisl mattis nunc id lorem</a></h3>
												<span class="published">30 likes in 10 posts</span>
											</header>
											<a href="#" class="image"><img src="images/pic09.jpg" alt="" /></a>
										</article>
									</li>
									<li>
										<article>
											<header>
												<h3><a href="#">Euismod amet placerat vivamus porttitor</a></h3>
												<span class="published">20 likes in 5 posts</span>
											</header>
											<a href="#" class="image"><img src="images/pic10.jpg" alt="" /></a>
										</article>
									</li>
									<li>
										<article>
											<header>
												<h3><a href="#">Magna enim accumsan tortor cursus ultricies</a></h3>
												<span class="published">10 likes in 15 posts</span>
											</header>
											<a href="#" class="image"><img src="images/pic11.jpg" alt="" /></a>
										</article>
									</li>
									<li>
										<article>
											<header>
												<h3><a href="#">Congue ullam corper lorem ipsum dolor</a></h3>
												<span class="published">1 likes in 1 post</span>
											</header>
											<a href="#" class="image"><img src="images/pic12.jpg" alt="" /></a>
										</article>
									</li>
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