<?php
session_start();

if(isset($_POST['login'])) {
  require('./db/config.php');

  $username_email = $_POST['username_email'];
  $password = $_POST['password'];

  // Check if user entered email or username
  if(filter_var($username_email, FILTER_VALIDATE_EMAIL)) {
    $sql = "SELECT * FROM users WHERE email = ?";
  } else {
    $sql = "SELECT * FROM users WHERE username = ?";
  }

  // Prepare and execute the SQL statement
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $username_email);
  $stmt->execute();

  // Get the result of the SQL query
  $result = $stmt->get_result();

  // Check if there is a user with the entered email or username
  if($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    // Verify the entered password against the hashed password in the database
    if(password_verify($password, $row['password'])) {
      // Password is correct, start a session
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['login_attempts'] = 0;
      $_SESSION['last_activity'] = time();
      header('Location: ../home');
      exit();
    } else {
      // Password is incorrect
      $_SESSION['login_attempts']++;
      $error = 'Incorrect password';
      if($_SESSION['login_attempts'] >= 5) {
        // Max attempts reached, block user for 6 hours
        $_SESSION['blocked_until'] = time() + 6*3600; // 6 hours in seconds
      }
    }
  } else {
    // User with the entered email or username does not exist
    $error = 'User not found';
  }
}

// Check if user is blocked
/*if(isset($_SESSION['blocked_until']) && $_SESSION['blocked_until'] > time()) {
  $time_left = $_SESSION['blocked_until'] - time();
  $hours = floor($time_left / 3600);
  $minutes = floor(($time_left % 3600) / 60);
  die('You are blocked for ' . $hours . ' hours and ' . $minutes . ' minutes');
} */

// Check if user is blocked
if(isset($_SESSION['blocked_until']) && $_SESSION['blocked_until'] > time()) {
  $time_left = $_SESSION['blocked_until'] - time();
  $hours = floor($time_left / 3600);
  $minutes = floor(($time_left % 3600) / 60);
  header('Location: ./temporary-login-blocked.php');
  exit();
}

// Check if user is logged in
if(isset($_SESSION['user_id'])) {
  // Check for inactivity
  if(time() - $_SESSION['last_activity'] > 3*3600) { // 3 hours in seconds
    // User is inactive, log them out
    session_unset();
    session_destroy();
    header('Location: ../login');
    exit();
  } else {
    // Update last activity time
    $_SESSION['last_activity'] = time();
  }
}
?>
<!-- max 5 wrong login attempts and then block for 6 hours, and automatically logout after 3 hours inactivity -->

<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
</head>
<body>
	<h1>Login</h1>
	<?php if(isset($error)): ?>
		<p><?php echo $error; ?></p>
	<?php endif; ?>
	<form method="post" action="./login.php">
		<label for="username_email">Username or Email:</label>
		<input type="text" name="username_email" id="username_email" required>
		<br><br>
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" required>
		<br><br>
		<input type="submit" name="login" value="Login">
	</form>
</body>
</html>
