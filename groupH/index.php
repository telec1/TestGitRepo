<html>
	<head>
		<title>BFH-e-Health Login</title>
		<link rel="stylesheet" href="/groupH/assets/css/indexCSS.css" type="text/css" />
		<link rel="stylesheet" href="/groupH/assets/css/styleWindow.css" type="text/css" />
		<link rel="stylesheet" href="/groupH/assets/css/new.css" type="text/css" />
	</head>
	<body id="login">
		<main>
			<div class="container">
				<h1>BFH-e-Health Login</h1>
				<form action="php/login.php" method="POST" class="formcontainer">
					<div class="formtext">Username</div>
					<input type="text" name ="username" class="formfield" required="required" />
					<div class="formtext">Password</div>
					<input type="password" name="password" class="formfield" required="required" />
					<div class="submitcontainer">
						<button class="button button1">Login</button>
					</div>
				</form>
			</div>
		</main>
	</html>