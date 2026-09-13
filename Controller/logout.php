<?php

session_start();


session_destroy();


if (isset($_COOKIE["remember_email"])) {

    setcookie(
        "remember_email",
        "",
        time() - 3600,
        "/"
    );

}


echo "<h2>Logout Successful</h2>";

echo "<p>You have been logged out successfully.</p>";

echo "<p><a href='../View/common/login.html'>Go to Login</a></p>";

?>