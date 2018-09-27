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

			

			

			if(isset($_POST['username']) && isset($_POST['password'])){

				$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
				$password = $_POST['password'];

				$stmt = $dbh->query("SELECT * FROM login WHERE username = :username");
				$stmt->bindParam(':username', $username);
				$stmt->execute();
				
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				if($username == $row['username'] && $password == password_verify($password, $row['password'])){
					echo "<h1>Rätt login</h1>";
					echo "<form action='' method='POST'><input type='submit' name='delete' id='delete' value='Ta bort användare'></form>";

				}else{
					echo "<h1>Fel</h1>";
				}
			}


		}else if (isset($_POST['updatePass'])) {
			$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			
			$sql = "UPDATE login SET password='$password' WHERE username='$username'";
			
			$stmt = $dbh->prepare($sql);
			$stmt->execute();
			echo $stmt->rowCount() . " update succ";
		} else if(isset($_POST['newUser'])){

			$usernamePost = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
			$passwordPost = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$emailPost = $_POST['email'];


			$stmt = $dbh->prepare("INSERT INTO login (username, password, email) VALUES (:username, :password, :email)");			

			$stmt->bindParam(':username', $usernamePost);
			$stmt->bindParam(':password', $passwordPost);
			$stmt->bindParam(':email', $emailPost);

			$stmt->execute();

		}else if(isset($_POST['delete'])){
			$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
			$stmt = $dbh->prepare("SELECT id FROM login WHERE username = :username");
			$stmt->bindParam(':username', $username);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			deleteAccount($row['id'], $dbh);
		}else{
			echo "<h1>Nu har du gjort fel!</h1>";
		}

		function deleteAccount($id, PDO $dbh) {
            $stmt = $dbh->prepare("DELETE FROM login WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
    	}
	?>	

</body>
</html>