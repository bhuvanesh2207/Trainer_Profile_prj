<?php
$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid submission");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Profile Submitted</title>

</head>

<body>

<h2>✅ Profile Submitted Successfully!</h2>
<p>Your profile PDF is ready to share.</p>


<hr>

<h3>Share</h3>
<button onclick="shareWhatsApp()">WhatsApp</button>
<button onclick="shareFacebook()">Facebook</button>
<button onclick="shareEmail()">Email</button>
<div id="hiddenPreview" style="display:none;"></div>

<script>
const profileId = "<?= $id ?>";
const pdfUrl = `${location.origin}/trainer_profile/pdfs/profile_${profileId}.pdf`;


// --- Share ---
function shareWhatsApp() {
  window.open(
    `https://wa.me/?text=${encodeURIComponent('Check my profile PDF: ' + pdfUrl)}`,
    '_blank'
  );
}

function shareFacebook() {
  window.open(
    `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pdfUrl)}`,
    '_blank'
  );
}

function shareEmail() {
  window.location.href =
    `mailto:?subject=My Profile&body=${encodeURIComponent('Download my profile PDF: ' + pdfUrl)}`;
}
</script>

</body>
</html>
