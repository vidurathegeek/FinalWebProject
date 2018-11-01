<?php
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];

	if(!empty($fname) || !empty($lname) || !empty($email)){
		$host  = "localhost";
		$dbUsername = "root";
		$dbPassword = "";
		$dbname = "finalwebproject";

		$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

		if(mysqli_connect_error()){
			die('Connect Error(' . mysqli_connect_errno().')'. mysqli_connect_error());
		}
		else{
			$SELECT = "SELECT email From volunteers Where email = ? Limit 1";
			$INSERT = "INSERT Into volunteers (fname, lname, email) values(?,?,?)";

			$stmt = $conn->prepare($SELECT);
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->bind_result($email);
			$stmt->store_result();
			$rnum = $stmt->num_rows;

			if($rnum==0){
				$stmt->close();

				$stmt = $conn->prepare($INSERT);
				$stmt->bind_param("sss",$fname, $lname, $email);
				$stmt->execute();
				echo "New Record Inserted Sucessfully";

				/* Email Ending Part*/
				$to = "$email";
				$subject = "Welcome to GreenEarth Volunteer Program";
				$txt = "Hello world!";
				$headers = "From: dhananjayarulz@gmail.com" . "\r\n" .
				"CC: mopallage@gmail.com";

				mail($to,$subject,$txt,$headers);
				/*-------------------------------------------*/
				
				/*
				header('Location : signup.html');
				exit;
				*/
			}
			else{
				echo "Someone Already registered using this email";
			}
			$stmt->close();
			$conn->close();

		}
	}
	else{
		echo "All fields are required";
		die();
	}

?>