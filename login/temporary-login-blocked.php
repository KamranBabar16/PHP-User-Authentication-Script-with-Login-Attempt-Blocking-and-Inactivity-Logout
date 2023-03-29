<?php
session_start();

if(isset($_SESSION['blocked_until']) && $_SESSION['blocked_until'] > time()) {
  $time_left = $_SESSION['blocked_until'] - time();
  $hours = floor($time_left / 3600);
  $minutes = floor(($time_left % 3600) / 60);
  $seconds = $time_left % 60;
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Blocked</title>
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
    }

    #container {
      width: 400px;
      margin: 0 auto;
      margin-top: 50px;
      background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
    }

    h1 {
      font-size: 24px;
      margin-bottom: 20px;
    }

    p {
      font-size: 18px;
      margin-bottom: 10px;
    }

    .timer {
      font-size: 36px;
      margin-bottom: 20px;
    }

    button {
      background-color: #4CAF50;
      border: none;
      color: white;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>
  <div id="container">
    <h1>Login Blocked</h1>
    <p>You have exceeded the maximum number of login attempts. You are blocked for:</p>
    <div class="timer"><?php printf("%02d:%02d:%02d", $hours, $minutes, $seconds); ?></div>
    <p>Please try again later.</p>
    <button onclick="window.history.back()">Go Back</button>
  </div>
  
  <script>
    function updateTimer() {
      var timerElement = document.querySelector('.timer');
      var timer = timerElement.textContent.split(':');
      var hours = parseInt(timer[0], 10);
      var minutes = parseInt(timer[1], 10);
      var seconds = parseInt(timer[2], 10);

      if (seconds > 0) {
        seconds--;
      } else {
        minutes--;
        seconds = 59;
      }

      if (minutes < 0) {
        hours--;
        minutes = 59;
      }

      if (hours < 0) {
        clearInterval(intervalId);
        window.location.href = "http://homesvr.net/home";
      }

      timerElement.textContent = hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
    }

    var intervalId = setInterval(updateTimer, 1000);
  </script>
</body>
</html>

<?php
  exit();
}
?>
