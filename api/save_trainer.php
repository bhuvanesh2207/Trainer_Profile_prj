<?php
header('Content-Type: application/json');
require 'db.php';

try {

    // ---------- IMAGE UPLOAD ----------
    $photoName = null;

    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array(strtolower($ext), $allowed)) {
            throw new Exception('Invalid image type');
        }

        $photoName = uniqid('trainer_', true) . '.' . $ext;
        $uploadPath = __DIR__ . '/../uploads/' . $photoName;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            throw new Exception('Image upload failed');
        }
    }

    // ---------- SQL ----------
    $sql = "INSERT INTO trainers
    (first_name, last_name, title, email, phone, location, summary,
     experience, education, skills, languages, achievements,
     template, font, photo_shape, photo)
    VALUES
    (:first_name, :last_name, :title, :email, :phone, :location, :summary,
     :experience, :education, :skills, :languages, :achievements,
     :template, :font, :photo_shape, :photo)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':first_name'   => $_POST['firstName'] ?? '',
        ':last_name'    => $_POST['lastName'] ?? '',
        ':title'        => $_POST['title'] ?? '',
        ':email'        => $_POST['email'] ?? '',
        ':phone'        => $_POST['phone'] ?? '',
        ':location'     => $_POST['location'] ?? '',
        ':summary'      => $_POST['summary'] ?? '',

        ':experience'   => $_POST['experience'] ?? '[]',
        ':education'    => $_POST['education'] ?? '[]',
        ':skills'       => $_POST['skills'] ?? '[]',
        ':languages'    => $_POST['languages'] ?? '[]',
        ':achievements' => $_POST['achievements'] ?? '[]',

        ':template'     => $_POST['template'] ?? '1',
        ':font'         => $_POST['font'] ?? '',
        ':photo_shape'  => $_POST['photoShape'] ?? 'circle',
        ':photo'        => $photoName
    ]);

    echo json_encode([
    'status' => 'success',
    'id' => $pdo->lastInsertId()
]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
