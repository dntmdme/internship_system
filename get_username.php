<?php
session_start();
if (!isset($_SESSION["username"])) {
    echo "Guest"; // If not logged in, show 'Guest'
} else {
    echo htmlspecialchars($_SESSION["username"]);
}
?>