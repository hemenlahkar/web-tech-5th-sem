<!-- Write a PHP script to connect to a MySQL database and fetch data from a table. -->
<?php
$server_name = "localhost";
$username = "root";
$password = "password";
$dbname = "mydb";

$conn = mysqli_connect($server_name, $username, $password, $dbname);

if (!$conn) {
    die("Connecion failed: " . mysqli_connect_error());
}

$sql = "SELECT firstname, lastname FROM users";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect to MySQL</title>
    <style>
        table, td, th {
            border: 1px solid black;
            border-collapse: collapse;
            margin: 3px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h2>List of users</h2>
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
        </tr>
        <?php
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr> <td>" . $row["firstname"] . "</td> <td>" . $row["lastname"] . "</td> </tr>";
                }
            }
        ?>
    </table>
    
</body>
</html>