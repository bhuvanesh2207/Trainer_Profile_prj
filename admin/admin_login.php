<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require '../api/db.php';

/* If already logged in, go to dashboard */
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            header("Location: /trainer_profile/admin/dashboard");
            exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = "All fields are required";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {

            session_regenerate_id(true);
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_username'] = $admin['username'];

            header("Location: /trainer_profile/admin/dashboard");
            exit;
        } else {
            $error = "Invalid username or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Trainer Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        resume: { primary: '#ec1d25' }
                    }
                }
            }
        }
    </script>
    <style>.login-card {
  box-shadow: 
    0 0 0 1.5px rgba(244, 248, 2, 1),
    0 20px 40px rgba(0, 0, 0, 0.15),
    0 15px 60px rgba(234, 219, 8, 0.58);
}
</style>
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4">
<div class="login-card p-8 rounded-xl w-full max-w-md bg-white
            border-2 border-[#ec1d25]">


        <div class="flex flex-col items-center mb-6">
           <div class="w-12 h-12 bg-resume-primary rounded-full flex items-center justify-center text-red-500 mb-4">
            <span class="material-icons text-white text-3xl">person</span>
        </div>

            <h1 class="text-2xl font-bold text-[#ec1d25]">Admin Login</h1>
            <p class="text-balck font-bold text-sm">Enter your credentials to access the dashboard</p>
        </div>

        <!-- Display PHP error if any -->
        <?php if($error): ?>
            <div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-md border border-red-100">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            
            <div>
                <label class="block text-base  font-medium text-black mb-1">Username</label>
                <input type="text" name="username" id="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:black outline-none" required>
            </div>
    
            <div>
                <label class="block text-base  font-medium text-black mb-1">Password</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:black outline-none" required>
            </div>

            <button class="w-full py-2 bg-[#ec1d25] text-white rounded-md font-medium hover:bg-opacity-90 transition-colors">
    Sign In
</button>


        </form>
        <div class="text-center mt-4">
            <a href="../form" class="text-black hover:text-[#ec1d25] text-sm font-medium no-underline">
                Back to Form / 
            </a>
            <a href="reset_password.php?send_otp=1" class="text-black hover:text-[#ec1d25] text-sm font-medium no-underline">
                Reset password
            </a>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
    // Focus the username input automatically
    const usernameInput = document.getElementById('username');
    if (usernameInput) {
      usernameInput.focus();
    }
  });

</script>


</body>
</html>
