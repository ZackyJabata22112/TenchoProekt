<?php
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT username, email, profile_pic, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<h2>Your Profile</h2>
<div class="profile-box" style="margin-top: 2rem;">
    <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Avatar" class="profile-avatar">
    <div>
        <h3>Username: <?php echo htmlspecialchars($user['username']); ?></h3>
        <p>Email Address: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Registration Date: <?php echo htmlspecialchars($user['created_at']); ?></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>