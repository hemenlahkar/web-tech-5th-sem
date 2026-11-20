<?php
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "webtech";

$errors  = [];
$success = "";
$title   = $content = "";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {

    $id = filter_input(INPUT_POST, "delete_id", FILTER_VALIDATE_INT);

    if ($id && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER["PHP_SELF"] . "?deleted=1");
        exit;
    } else {
        $errors[] = "Invalid notice selected for deletion.";
    }
}

elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {

    $title   = trim($_POST["title"]   ?? "");
    $content = trim($_POST["content"] ?? "");

    if ($title === "") {
        $errors[] = "Notice title is required.";
    } elseif (mb_strlen($title) < 3 || mb_strlen($title) > 100) {
        $errors[] = "Title must be between 3 and 100 characters.";
    }

    if ($content === "") {
        $errors[] = "Notice content is required.";
    } elseif (mb_strlen($content) > 1000) {
        $errors[] = "Content must not exceed 1000 characters.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "INSERT INTO notices (title, content) VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {
            $success = "Notice published successfully.";
            $title = $content = "";
        } else {
            $errors[] = "Database error. Could not save the notice.";
        }
        $stmt->close();
    }
}

$notices = [];
$result = $conn->query(
    "SELECT id, title, content, posted_on
     FROM notices
     ORDER BY posted_on DESC, id DESC"
);
while ($row = $result->fetch_assoc()) {
    $notices[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notice Board</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

  <h1>📌 Notice Board</h1>

  <?php if (isset($_GET["deleted"])): ?>
    <div class="alert alert-success">Notice deleted successfully.</div>
  <?php endif; ?>

  <?php if ($success !== ""): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="form-card">
    <h2>Publish a New Notice</h2>
    <form action="" method="post">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" maxlength="100"
             value="<?= htmlspecialchars($title) ?>" required>

      <label for="content">Content</label>
      <textarea id="content" name="content" maxlength="1000"
                required><?= htmlspecialchars($content) ?></textarea>

      <button type="submit" name="add" value="1" class="btn btn-primary">
        Publish Notice
      </button>
    </form>
  </div>

  <?php if (empty($notices)): ?>
    <div class="empty">No notices yet. Add the first one above.</div>
  <?php else: ?>
    <?php foreach ($notices as $n): ?>
      <div class="notice">
        <div class="notice-header">
          <div>
            <h3><?= htmlspecialchars($n["title"]) ?></h3>
            <span class="date">
              Posted: <?= htmlspecialchars(
                  date("d M Y, h:i A", strtotime($n["posted_on"]))
              ) ?>
            </span>
          </div>

          <form action="" method="post"
                onsubmit="return confirm('Delete this notice?');">
            <input type="hidden" name="delete_id"
                   value="<?= (int) $n["id"] ?>">
            <button type="submit" class="btn btn-delete">Delete</button>
          </form>
        </div>

        <p><?= nl2br(htmlspecialchars($n["content"])) ?></p>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

</div>
</body>
</html>