<?php
session_start();
require 'db.php';

// Check if form data exists
if (!isset($_SESSION['form_data'])) {
    echo "<h3>No form data found. Please fill the form first.</h3>";
    exit;
}

$formData = $_SESSION['form_data'];

// Prepare insert query
$sql = "INSERT INTO submissions 
(first_name, last_name, title, email, phone, location, website, summary, photo, experience, education, skills, languages, interests, strengths, achievements)
VALUES
(:first_name, :last_name, :title, :email, :phone, :location, :website, :summary, :photo, :experience, :education, :skills, :languages, :interests, :strengths, :achievements)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':first_name' => $formData['firstName'] ?? '',
    ':last_name'  => $formData['lastName'] ?? '',
    ':title'      => $formData['title'] ?? '',
    ':email'      => $formData['email'] ?? '',
    ':phone'      => $formData['phone'] ?? '',
    ':location'   => $formData['location'] ?? '',
    ':website'    => $formData['website'] ?? '',
    ':summary'    => $formData['summary'] ?? '',
    ':photo'      => $formData['photo'] ?? '',
    ':experience' => json_encode($formData['experience'] ?? []),
    ':education'  => json_encode($formData['education'] ?? []),
    ':skills'     => json_encode($formData['skills'] ?? []),
    ':languages'  => json_encode($formData['languages'] ?? []),
    ':interests'  => json_encode($formData['interests'] ?? []),
    ':strengths'  => json_encode($formData['strengths'] ?? []),
    ':achievements' => json_encode($formData['achievements'] ?? []),
]);

// Clear session
session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Form Submitted</title>
</head>
<body>
<h2>Form submitted successfully!</h2>
<a href="/trainer_profile/form">Submit another form</a>
<a href="/trainer_profile/admin/login">Admin Login</a>
</body>
</html>
