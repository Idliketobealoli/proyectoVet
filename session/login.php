<?php
require_once "../utils/utils.php";
require_once "./session.php";

goToMainPageIfLoggedIn();
?>

<html>
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../stylesheet.css"/>
</head>
<body>
<?php if (isset($_REQUEST["error"])) { ?>
    <p class="right red">Authentication error.</p>
<?php } ?>

<?php if (isset($_REQUEST["closedSession"])) { ?>
    <p class="right blue">Session successfully closed.</p>
<?php } ?>

<h1 class='centered'>Login</h1>
<form action='checkSessionLogin.php' method='post' class='centered'>
    <label for='email'>Email</label><br>
    <input type='email' name='email' placeholder='example@gmail.com' class='centered'><br><br>

    <label for='password'>Password</label><br>
    <input type='password' name='password' placeholder='50 characters max.' class='centered'><br><br>

    <input type='checkbox' name='rememberMe'>Remember me<br><br>

    <input type='submit' value='Login'>
</form>
<br/><p class='centered'>Don't have an account? register <a href='register.php'>here.</a></p>

</body>
</html>