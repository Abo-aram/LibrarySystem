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
      <form action="/register.php" method="post" class="register-form">
        <div class="headings-container">
          <div class="logo-container">
            <h1 class="logo">KurdLit</h1>
          </div>
          <h1>Welcome</h1>
          <h3>read yourslef out this is yours to explore</h3>
        </div>

        <div>
          <label class="hide" for="username">Username:</label>
          <input
            placeholder="Username"
            class="textFiled"
            type="text"
            id="username"
            name="username"
            required />
        </div>
        <div>
          <label class="hide" for="email">Email:</label>
          <input
            placeholder="Email"
            class="textFiled"
            type="text"
            id="email"
            name="email"
            required />
        </div>
        <div>
          <label class="hide" for="password">Password:</label>
          <input
            placeholder="Passowrd"
            class="textFiled"
            type="password"
            id="password"
            name="password"
            required />
        </div>
        <div>
          <button class="submitButton" type="submit">Register</button>
        </div>

        <p style="font-size: small; opacity: 0.5">
          have an account?
          <a href="/login.php" style="text-decoration: none">Login here</a>
        </p>
      </form>
    </div>

    <div name="fillup-side" class="fillup-side hide">
      <div class="overlay">
        <h1>Explore. Learn. Grow.</h1>
        <p>
        <p>
          Create your free account today and become part of a growing network
          of readers, learners, and creators. Register to access exclusive
          content, save your favorite books, and enjoy a personalized
          experience every time you visit. It only takes a minute to sign up
          and start exploring!
        </p>
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
include 'DbConnection.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $errors = [];
  $username = htmlspecialchars($_POST['username']);
  $email = htmlspecialchars($_POST['email']);
  $password = $_POST['password'];


  if (empty($username) || empty($email) || empty($password)) {
    array_push($errors, 'All inputs are rquired');
  }

  if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($errors, 'Enter a Correct Email Address');
  }

  if (!empty($password) && strlen($password) < 8) {
    array_push($errors, 'The Password must be 8 Character');
  }


  if (empty($errors)) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      array_push($errors, "This User Exists");
      $stmt->close();
    } else {
      $stmt->close();

      $insert = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");

      $parts = preg_split('/\s+/', trim($username));
      $first_name = $parts[0] ?? '';
      $last_name = $parts[1] ?? '';

      $password_hash = password_hash($password, PASSWORD_DEFAULT);
      $role = "user";
      $insert->bind_param("sssss", $first_name, $last_name, $email, $password_hash, $role);

      if ($insert->execute()) {
        header("Location: login.php");
      } else {
        array_push($errors, "An Error occured");
      }
      $insert->close();
    }

    $conn->close();
  }
  foreach ($errors as $error) {
    echo "$error" . "<br/>";
  }
}
?>