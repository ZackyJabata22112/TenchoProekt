<?php
include '../config/db.php';
include '../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_pic']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = uniqid('profile_', true) . '.' . $ext;
            $destination = '../images/profiles/' . $new_filename;

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $destination)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, profile_pic) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$username, $email, $hashed_password, $destination]);
                    header("Location: login.php");
                    exit;
                } catch (PDOException $e) {
                    $error = "Username or email already exists.";
                }
            } else {
                $error = "Error uploading the file.";
            }
        } else {
            $error = "Invalid file format. Allowed formats: JPG, JPEG, PNG, GIF.";
        }
    } else {
        $error = "Please upload a profile picture.";
    }
}
?>

<div class="form-container">
    <h2>User Registration</h2>
    <?php if ($error): ?>
        <div class="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="register.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="profile_pic">Profile Picture</label>
            <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required>
        </div>
        <button type="submit" class="btn">Register</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>