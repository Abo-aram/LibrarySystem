<?php
include 'DbConnection.php';

session_start();


if (isset($_SESSION['user_id'])) {
  header('Location: HomePage.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="css/register.css" />
</head>

<body>
  <div name="main-div" class="mainContainer">

    <div name="input-side" class="input-side">
      <form action="/login.php" method="post" class="register-form">
        <div class="headings-container">
          <div class="logo-container">
            <h1 class="logo">KurdLit</h1>
          </div>
          <h1>Welcome Back</h1>
          <h3>Your favorite book is waiting for you</h3>
        </div>

        <div>

          <input
            placeholder="Email"
            class="textFiled"
            type="email"
            id="email"
            name="email"
            required />
        </div>
        <div>

          <input
            placeholder="Password"
            class="textFiled"
            type="password"
            id="password"
            name="password"
            required />
        </div>

        <div>
          <button class="submitButton" type="submit">Login</button>
        </div>

        <p style="font-size: small; opacity: 0.5">
          have an account?
          <a href="/register.php" style="text-decoration: none">Login here</a>
        </p>
      </form>
    </div>

    <div name="fillup-side" class="fillup-side hide">
      <div class="overlay">
        <h1>Welcome Back!</h1>
        <p>
          Log in to reconnect with your world of books,
          learning, and creativity. Access your personalized library, pick up
          where you left off, and continue your journey of discovery. Sign in
          now and make the most of your reading experience!
        </p>

      </div>
      <div style="width: 500px; height: 500px; margin-top: 20px; padding-left:20%;">
        <img
          class="image"
          src="assets/images/book.png"

          alt="" />

      </div>


    </div>
  </div>
</body>

</html>

<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $errors = [];
  $email = htmlspecialchars($_POST['email']);
  $password = $_POST['password'];


  if (empty($email) || empty($password)) {
    array_push($errors, 'All inputs are rquired');
  }

  if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($errors, 'Enter a Correct Email Address');
  }

  if (!empty($password) && strlen($password) < 8) {
    array_push($errors, 'The Password must be 8 Character');
  }


  if (empty($errors)) {
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($user_id, $hashed_password);
      $stmt->fetch();

      if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;
        session_regenerate_id(true);
        header('Location: HomePage.php');
        exit();
      } else {
        array_push($errors, 'Wrong password');
      }
    } else {
      array_push($errors, 'This account dose not exist');
    }
    $stmt->close();

    $conn->close();
  }
  foreach ($errors as $error) {
    echo "$error" . "<br/>";
  }
}
?>