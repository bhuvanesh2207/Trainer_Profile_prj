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

<!-- SHARE MODAL -->
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
 </head>
 <body>
    <div id="share-pdf-modal" class="fixed inset-0 bg-black/60 hidden flex items-center justify-center z-50">
  <div class="bg-white p-6 rounded-xl w-80 text-center">
    <h3 class="font-bold mb-4">Share PDF</h3>

    <div class="grid grid-cols-2 gap-3 text-sm">
      <a id="whatsapp-share" target="_blank" href="#" class="px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">WhatsApp</a>
      <a id="facebook-share" target="_blank" href="#" class="px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">Facebook</a>
      <a id="linkedin-share" target="_blank" href="#" class="px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">LinkedIn</a>
      <a id="email-share" target="_blank" href="#" class="px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">Email</a>
      <button id="copy-link" onclick="copyPdfLink()" class="px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all col-span-2">Copy Link</button>
    </div>

    <button onclick="document.getElementById('share-pdf-modal').classList.add('hidden')" class="mt-4 px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">
        Close
    </button>
  </div>
</div>

<script>
function copyPdfLink() {
    const url = window.generatedPdfUrl;
    if (url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('PDF link copied to clipboard!');
        });
    } else {
        alert('PDF URL not available');
    }
}

function updateShareLinks(url) {
    document.getElementById('whatsapp-share').href = `https://wa.me/?text=${encodeURIComponent('Check out this trainer profile PDF: ' + url)}`;
    document.getElementById('facebook-share').href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
    document.getElementById('linkedin-share').href = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
    document.getElementById('email-share').href = `mailto:?subject=Trainer Profile PDF&body=Check out this trainer profile PDF: ${encodeURIComponent(url)}`;
}
</script>

 </body>
 </html>

</body>
</html>
