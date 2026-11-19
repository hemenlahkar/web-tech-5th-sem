<?php
/*Create a registration form with fields for username, email, and password.
Implement server-side validation to check for duplicate usernames or invalid
email formats. Store the user registration data in a MySQL database. Provide
feedback to the user upon successful registration or display appropriate
error messages.*/

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "password";
$DB_NAME = "webtech";

$errors  = [];
$success = "";
$username = $email = "";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $email    = trim($_POST["email"]    ?? "");
    $password = $_POST["password"]      ?? "";

    if ($username === "") {
        $errors[] = "Username is required.";
    } elseif (!preg_match("/^[A-Za-z0-9_]{3,20}$/", $username)) {
        $errors[] = "Username must be 3–20 characters (letters, digits, underscore only).";
    }

    if ($email === "") {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "SELECT id FROM users WHERE username = ? OR email = ?"
        );
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email is already registered.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $username, $email, $hash);

        if ($stmt->execute()) {
            $success  = "Registration successful! Welcome, "
                . htmlspecialchars($username) . ".";
            $username = $email = "";          // clear sticky values
        } elseif ($stmt->errno === 1062) {    // duplicate key from UNIQUE
            $errors[] = "Username or email already exists.";
        } else {
            $errors[] = "Database error. Please try again later.";
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 420px;
            margin: 40px auto;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-top: 4px;
        }

        button {
            margin-top: 16px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .error {
            color: #b00020;
        }

        .success {
            color: #0a7d28;
            font-weight: bold;
        }

        ul.error {
            padding-left: 20px;
        }
    </style>
</head>

<body>

    <h2>Create an Account</h2>

    <?php if ($success !== ""): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <ul class="error">
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="" method="post" novalidate>
        <label for="username">Username</label>
        <input type="text" id="username" name="username"
            value="<?= htmlspecialchars($username) ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email"
            value="<?= htmlspecialchars($email) ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password"
            minlength="6" required>

        <button type="submit">Register</button>
    </form>
</body>
</html>