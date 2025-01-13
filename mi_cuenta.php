<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'My Account';
include('header.php');

// Fetch user details
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session
$stmt = $conn->prepare("SELECT username, email, telefono FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<p>Error: User information not found.</p>";
    exit;
}

$stmt->close();

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;
    $telefono = $_POST['telefono'] ?? null;

    $stmt = $conn->prepare("UPDATE users SET email = ?, telefono = ? WHERE id = ?");
    $stmt->bind_param("ssi", $email, $telefono, $user_id);

    if ($stmt->execute()) {
        header('Location: my_account.php?updated=1');
        exit;
    } else {
        echo "<p>Error updating information: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<div class="main" style="text-align: right;">
    <h1 style="text-align: right;">My Account</h1>
    <?php if (isset($_GET['updated'])): ?>
        <p class="success" style="color: green; text-align: right;">Information updated successfully.</p>
    <?php endif; ?>
    
    <form action="my_account.php" method="POST" style="text-align: right;">
        <!-- Username -->
        <div class="form-group" style="margin-bottom: 15px; text-align: right;">
            <label for="username" style="display: block;">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled style="width: 50%; text-align: right;">
        </div>

        <!-- Email -->
        <div class="form-group" style="margin-bottom: 15px; text-align: right;">
            <label for="email" style="display: block;">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" style="width: 50%; text-align: right;">
        </div>

        <!-- Phone -->
        <div class="form-group" style="margin-bottom: 15px; text-align: right;">
            <label for="telefono" style="display: block;">Phone:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>" style="width: 50%; text-align: right;">
        </div>

        <button type="submit" class="btn" style="float: right;">Update Information</button>
    </form>
</div>

<?php include('footer.php'); ?>
