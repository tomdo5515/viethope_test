<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Database Error</title>
<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background-color: #1941b1;
	margin: 40px;
	font: 13px/20px normal Arial, sans-serif;
	color: #fff;
}

#container {
    margin: 0 auto;
    max-width: 87.5em;
    padding-top: 5%;
    text-align: center;
}

#container img {
    max-width: 105em;
}

#container h1 {
    font-size: 2rem;
    line-height: 1.53333;
    font-family: "Gotham SSm A","Gotham SSm B",sans-serif;
    margin-bottom: 40px;
    margin-top: 20px;
    text-transform: uppercase;
    color: #fff;
}
#container a {
    border: 2px solid #fff;
    color: #fff;
    display: inline-block;
    font-family: "Gotham SSm A","Gotham SSm B",sans-serif;
    padding: 0.5em 1em;
    text-transform: uppercase;
    font-size: 1rem;
    line-height: 1.53333;
    margin-top: 1.5em;
    text-decoration: none;
}
#container p{
	color: #fff;
	margin: 0 0 10px;
}
</style>
</head>
<body>
    <div id="container">
        <img alt="Space invaders" src="/system/images/error.jpg">
        <h1><?php echo $heading; ?></h1>
        <?php echo $message; ?>
        <script>
            document.write('<a href="' + document.referrer + '">Get Me Out!</a>');
        </script>
    </div>
</body>
</html>