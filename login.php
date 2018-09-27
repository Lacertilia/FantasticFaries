<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
</head>
<body>
	<?php 
		include 'db.php';
		if(isset($_POST['submit'])){

			

			

			if(isset($_POST['username']) && isset($_POST['password'])) {

				$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
				$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

				$statement = $pdo->query("SELECT * FROM login");
				$row = $statement->fetch(PDO::FETCH_ASSOC);

				echo "<pre>" . print_r($row, 1) . "</pre>";
				
				echo $password;

				if($username == $row['username'] && $password == password_verify($password, $row['password'])){
					echo "<h1>Rätt login</h1>";

				} else {
					echo "<h1>Fel</h1>";

				}
			}

			echo "<pre>" . print_r($_POST, 1) . "</pre>";
		} else if (isset($_POST['updatePass'])) {
			$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			
			$sql = "UPDATE login SET password='$password' WHERE username='$username'";
			
			$stmt = $dbh->prepare($sql);

			$stmt->execute();

			echo $stmt->rowCount() . " update succ";
		} 
		
		else if (isset($_POST['newUser'])) {
			$stmt = $dbh->prepare(INSERT INTO `login` (`id`, `username`, `password`, `email`) VALUES (NULL, $_POST['username'], $_POST['password'], $_POST['email']));

			$usernamePost = $_POST['username'];
			$passwordPost = $_POST['password'];
			$emailPost = $_POST['email'];


			$stmt = $dbh->prepare("INSERT INTO login (username, password, email) VALUES (:username, :password, :email)");			

			$stmt->bindParam(':username', $usernamePost);
			$stmt->bindParam(':password', $passwordPost);
			$stmt->bindParam(':email', $emailPost);

			$stmt->execute();
				
		}else{
			echo "<h1>Nu har du gjort fel!</h1>";
		}
	?>	

</body>
</html>