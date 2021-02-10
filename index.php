<?php
	include( 'config.php' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/style.css" type="text/css"/>
	<title>Live User Dashboard</title>
</head>
<body>
	<header class='header-wrapper'>
		<div class="inner-header">

		</div>
	</header>
	<main role="main">
		<?php
			if( ! helper()->is_user_login() ){
				include( VIEWPATH . 'login.php' );
			}
		?>
	</main>
	<footer class="footer-wrapper" role="contentinfo">
		<span>Thanks, Niv Noiman</span>
	</footer>
</body>
	<script type="module" src="assets/js/script.js"></script>
</html>
