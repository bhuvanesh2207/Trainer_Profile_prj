<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    http_response_code(403);
    exit('Forbidden');
}

require 'db.php';

$search = $_GET['search'] ?? '';
$view_id = $_GET['view_id'] ?? '';

if($view_id) {
    // Fetch single trainer for modal view
    $stmt = $pdo->prepare("SELECT * FROM trainers WHERE id = ?");
    $stmt->execute([$view_id]);
    $trainer = $stmt->fetch(PDO::FETCH_ASSOC);
    if($trainer) {
        $trainer['experience'] = json_decode($trainer['experience'], true) ?? [];
        $trainer['education'] = json_decode($trainer['education'], true) ?? [];
        $trainer['skills'] = json_decode($trainer['skills'], true) ?? [];
        $trainer['languages'] = json_decode($trainer['languages'], true) ?? [];
        $trainer['achievements'] = json_decode($trainer['achievements'], true) ?? [];
        echo json_encode($trainer);
    }
    exit;
}

// Search trainers for table
$searchTerm = "%$search%";
$stmt = $pdo->prepare("SELECT id, first_name, last_name, title, email, phone, template, font, photo_shape FROM trainers WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ? ORDER BY created_at DESC");
$stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
$trainers = $stmt->fetchAll();

foreach($trainers as $t) {
    echo '<tr class="hover:bg-gray-50">
        <td class="px-6 py-4 font-medium text-gray-900">'.htmlspecialchars($t['first_name'].' '.$t['last_name']).'</td>
        <td class="px-6 py-4 text-gray-600">'.htmlspecialchars($t['title']).'</td>
        <td class="px-6 py-4 text-gray-600">'.htmlspecialchars($t['email']).'</td>
        <td class="px-6 py-4 text-right">
            <button onclick="viewTrainer('.$t['id'].')" class="text-resume-primary hover:underline text-sm font-medium">View</button>
        </td>
    </tr>';
}
