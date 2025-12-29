<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require '../api/db.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* ================= CONFIG ================= */
$email = "bhuvaneshb546@gmail.com"; // HARD-CODED
$OTP_COOLDOWN = 30; // seconds
$OTP_EXPIRY_MINUTES = 10;
/* ========================================== */

$message = '';
$error = '';

/* -------- SEND OTP FUNCTION -------- */
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bhuvaneshb546@gmail.com'; 
        $mail->Password   = 'dlsn lxqa bptz htff'; // APP PASSWORD
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('bhuvaneshb546@gmail.com', 'Trainer Profile Admin');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Admin Password Reset OTP';
        $mail->Body    = "
            <p>Your OTP is:</p>
            <h2>$otp</h2>
            <p>This OTP is valid for $GLOBALS[OTP_EXPIRY_MINUTES] minutes.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/* -------- SEND OTP ON CLICK -------- */
if (isset($_GET['send_otp'])) {
    $pdo->prepare("DELETE FROM otp WHERE email = ?")->execute([$email]);
    $otp = rand(100000, 999999);

    if (sendOTP($email, $otp)) {
        $pdo->prepare("INSERT INTO otp (email, otp) VALUES (?, ?)")->execute([$email, $otp]);
        $_SESSION['otp_sent'] = true;
        $_SESSION['resend_time'] = time();
        $_SESSION['email'] = $email;
        header("Location: reset_password.php");
        exit;
    } else {
        $error = "Failed to send OTP.";
    }
}

/* -------- VERIFY / RESEND / RESET -------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* VERIFY OTP */
    if (isset($_POST['verify_otp'])) {
        $enteredOtp = trim($_POST['otp']);
        $stmt = $pdo->prepare("
            SELECT * FROM otp 
            WHERE email = ? 
              AND otp = ? 
              AND created_at > NOW() - INTERVAL $OTP_EXPIRY_MINUTES MINUTE
            LIMIT 1
        ");
        $stmt->execute([$email, $enteredOtp]);

        if ($stmt->fetch()) {
            $pdo->prepare("DELETE FROM otp WHERE email = ?")->execute([$email]);
            $_SESSION['otp_verified'] = true;
            $message = "OTP verified successfully.";
        } else {
            $error = "Invalid or expired OTP.";
        }
    }

    /* RESEND OTP */
    if (isset($_POST['resend_otp'])) {
        if (isset($_SESSION['resend_time']) && (time() - $_SESSION['resend_time']) < $OTP_COOLDOWN) {
            $error = "Please wait before resending OTP.";
        } else {
            $pdo->prepare("DELETE FROM otp WHERE email = ?")->execute([$email]);

            $otp = rand(100000, 999999);
            if (sendOTP($email, $otp)) {
                $pdo->prepare("INSERT INTO otp (email, otp) VALUES (?, ?)")->execute([$email, $otp]);
                $_SESSION['resend_time'] = time();
                $_SESSION['otp_sent'] = true;
                $message = "New OTP has been sent to your email.";
            } else {
                $error = "Failed to resend OTP.";
            }
        }
    }

    /* RESET PASSWORD */
    if (isset($_POST['reset_password'])) {
        $pass    = trim($_POST['new_password']);
        $confirm = trim($_POST['confirm_password']);

        if ($pass !== $confirm) {
            $error = "Passwords do not match.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE admins SET password = ? LIMIT 1")->execute([$hash]);

            // Redirect to same page with a success flag
            header("Location: reset_password.php?reset_success=1");
            exit;
        }
    }
}

/* -------- RESEND TIMER -------- */
$remaining = 0;
if (isset($_SESSION['resend_time'])) {
    $remaining = max(0, $OTP_COOLDOWN - (time() - $_SESSION['resend_time']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password - Trainer Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    .login-card {
        box-shadow: 
            0 0 0 1.5px rgba(244, 248, 2, 1),
            0 20px 40px rgba(0, 0, 0, 0.15),
            0 15px 60px rgba(234, 219, 8, 0.58);
    }
</style>
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4">
<div class="login-card p-8 rounded-xl w-full max-w-md bg-white border-2 border-[#ec1d25]">
    <div class="flex flex-col items-center mb-6">
        <div class="w-12 h-12 bg-resume-primary rounded-full flex items-center justify-center text-red-500 mb-4">
            <span class="material-icons text-white text-3xl">lock_reset</span>
        </div>
        <h1 class="text-2xl font-bold text-[#ec1d25]">Reset Password</h1>
        <p class="text-black font-bold text-sm">Enter OTP to reset your password</p>
    </div>

    <?php if ($message): ?>
        <div class="mb-4 p-3 bg-green-50 text-green-600 text-sm rounded-md border border-green-100">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-md border border-red-100">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['reset_success'])): ?>
        <div class="text-center mt-4 space-y-4">
            <div class="p-3 bg-green-50 text-green-600 text-sm rounded-md border border-green-100">
                Password Reset Successfully
            </div>
            <a href="admin_login.php" class="text-black hover:text-[#ec1d25] text-sm font-medium no-underline">
                Back to Login
            </a>
        </div>

    <?php elseif (isset($_SESSION['otp_sent']) && !isset($_SESSION['otp_verified'])): ?>

        <!-- OTP Verification Form -->
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-base font-medium text-black mb-1">Enter OTP</label>
                <input name="otp" placeholder="Enter 6-digit OTP" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:black outline-none" required>
            </div>
            <button name="verify_otp" class="w-full py-2 bg-[#ec1d25] text-white rounded-md font-medium hover:bg-opacity-90 transition-colors">
                Verify OTP
            </button>
        </form>

        <!-- Resend OTP Form -->
        <form method="POST" class="mt-4">
            <button name="resend_otp" id="resendBtn" class="w-full py-2 bg-gray-400 text-white rounded-md font-medium hover:bg-opacity-90 transition-colors disabled:opacity-50" <?= $remaining>0?'disabled':'' ?>>
                Resend OTP
            </button>
            <p class="text-center text-sm text-gray-600 mt-2" id="timer"></p>
        </form>

    <?php elseif (isset($_SESSION['otp_verified'])): ?>

        <!-- Reset Password Form -->
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-base font-medium text-black mb-1">New Password</label>
                <input type="password" name="new_password" placeholder="Enter new password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:black outline-none" required>
            </div>
            <div>
                <label class="block text-base font-medium text-black mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm new password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:black outline-none" required>
            </div>
            <button name="reset_password" class="w-full py-2 bg-[#ec1d25] text-white rounded-md font-medium hover:bg-opacity-90 transition-colors">
                Reset Password
            </button>
        </form>

    <?php else: ?>
        <!-- First-time visit: show "Send OTP" link -->
        <div class="text-center mt-4">
            <a href="reset_password.php?send_otp=1" class="text-black hover:text-[#ec1d25] text-sm font-medium no-underline">
                Send OTP to Reset Password
            </a>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="admin_login.php" class="text-black hover:text-[#ec1d25] text-sm font-medium no-underline">
            Back to Login
        </a>
    </div>
</div>

<script>
let remaining = <?= $remaining ?>;
const timer = document.getElementById("timer");
const btn = document.getElementById("resendBtn");

if (remaining > 0) {
    const interval = setInterval(() => {
        timer.innerText = `Resend OTP in ${remaining}s`;
        remaining--;
        if (remaining < 0) {
            clearInterval(interval);
            timer.innerText = "";
            btn.disabled = false;
            btn.classList.remove('bg-gray-400');
            btn.classList.add('bg-[#ec1d25]');
        }
    }, 1000);
}
</script>
</body>
</html>
