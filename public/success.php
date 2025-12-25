<?php
$id = intval($_GET['id']);
$profileUrl = "http://localhost/trainer_profile/profile.php?id=$id";
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile Created</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-xl shadow-xl text-center max-w-md">
    <h2 class="text-2xl font-bold mb-4">🎉 Profile Created!</h2>

    <p class="text-gray-600 mb-6">Your public profile is ready</p>

    

   <div class="flex gap-3 justify-center">
    <a
      href="../trainer_profile/form"
      class="px-6 py-2 rounded-md text-white font-medium shadow-md flex items-center gap-2 transition-all hover:opacity-90"
      style="background-color:#5D1F2F;"
    >
      Back
    </a>
  </div>

</div>



</body>
</html>
