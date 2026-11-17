<!-- Write a PHP script to display the current date and time on a webpage. -->
<?php date_default_timezone_set("asia/kolkata"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Date</title>
</head>
<body>
    <h2>Current Date: <?php echo date("Y-m-d"); ?></h2>
    <h2>Current Time: <?php echo date("h:i:s A"); ?></h2>
</body>
</html>