<?php 
    include('connect.php');

    session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="login.css"></head>
<body>
    <form action="index.php" method="post">
        username<br>
        <input type="text" name="username"> <br>
        password <br>
        <input type="text" name="password">
        <input type="submit" name ="login" value="login">
    </form>
</body>
</html>
<?php 
   if (isset($_POST['login'])) {

       // Check if both fields are filled
       if (!empty($_POST['username']) && !empty($_POST['password'])) {
           // Sanitize the input
           $username = htmlspecialchars($_POST['username']);
           $password = htmlspecialchars($_POST['password']);

           // Prepare a query to fetch the user's password from the database
           $stmt = $conn->prepare("SELECT password FROM password_db WHERE username = ?");
           $stmt->execute([$username]);

           $result = $stmt->fetch(PDO::FETCH_ASSOC);

           // Check if the user exists and the password matches
           if ($result && password_verify($password, $result['password'])) {
               // Store the username in session and redirect to the main page
               $_SESSION['username'] = $username;
               header('location: dashboard.php');  // Redirect to a protected page
           } else {
               echo "Invalid username or password. <br>";
           }
       } else {
           echo "Missing username/password. <br>";
       }
   }

?>
