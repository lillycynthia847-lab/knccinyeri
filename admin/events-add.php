<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cover_image = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/events/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES['cover_image']['name']);
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $fileName)) {
            $cover_image = 'uploads/events/' . $fileName;
        }
    }
    $stmt = $pdo->prepare("INSERT INTO events (title, event_type, event_date, event_time_start, event_time_end, location, description, cover_image, registration_link, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['title'], $_POST['event_type'], $_POST['event_date'], $_POST['event_time_start'] ?: null, $_POST['event_time_end'] ?: null, $_POST['location'], $_POST['description'], $cover_image, $_POST['registration_link'], $_POST['status']]);
    header("Location: events.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Event | Admin Panel</title>
  <link rel="stylesheet" href="css/admin.css">
  <!-- Quill.js CDN -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <style>
    /* Quill Custom Styling */
    .ql-container { font-family: 'Inter', sans-serif; font-size: 1rem; min-height: 250px; background: #fff; border-radius: 0 0 var(--radius-md) var(--radius-md) !important; border-color: var(--admin-border) !important; }
    .ql-toolbar { background: #f8fafc; border-radius: var(--radius-md) var(--radius-md) 0 0 !important; border-color: var(--admin-border) !important; }
  </style>
</head>
<body>
  <?php include 'includes/sidebar.php'; ?>
  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Create Event</h1>
        <p class="page-subtitle">Schedule a new upcoming event.</p>
      </div>
    </div>
    <div class="form-wrapper">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group"><label>Event Title</label><input type="text" name="title" class="form-control" required></div>
        <div class="form-row">
          <div class="form-group"><label>Event Type</label>
            <select name="event_type" class="form-control"><option>Annual General Meeting</option><option>Workshop</option><option>Networking</option><option>Seminar</option><option>Conference</option><option>Training</option><option>General</option></select>
          </div>
          <div class="form-group"><label>Status</label>
            <select name="status" class="form-control"><option>Upcoming</option><option>Completed</option><option>Cancelled</option></select>
          </div>
        </div>
        <div class="form-row-3" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px;">
          <div class="form-group"><label>Event Date</label><input type="date" name="event_date" class="form-control" required></div>
          <div class="form-group"><label>Start Time</label><input type="time" name="event_time_start" class="form-control"></div>
          <div class="form-group"><label>End Time</label><input type="time" name="event_time_end" class="form-control"></div>
        </div>
        <div class="form-group"><label>Location / Venue</label><input type="text" name="location" class="form-control" placeholder="e.g. Outspan Hotel, Nyeri"></div>
        <div class="form-group"><label>Registration Link (optional)</label><input type="url" name="registration_link" class="form-control" placeholder="https://..."></div>
        <div class="form-group">
          <label>Cover Image Upload</label>
          <div class="custom-file-upload">
            <div class="cfu-icon">📁</div>
            <div class="cfu-text">Click or drag image to upload</div>
            <div class="cfu-hint">PNG, JPG or GIF (Recommended 1200x800px)</div>
            <input type="file" id="cover_image" name="cover_image" accept="image/*" onchange="previewImage(this, 'imagePreviewAdd')">
            <img id="imagePreviewAdd" class="cfu-preview" src="#" alt="Preview" />
          </div>
        </div>
        <div class="form-group">
          <label>Description</label>
          <div id="quill-editor"></div>
          <textarea id="hidden-content" name="description" style="display:none;"></textarea>
        </div>
        <div style="margin-top:32px; border-top:1px solid var(--admin-border); padding-top:24px;">
          <a href="events.php" class="btn-cancel">Cancel</a>
          <button type="submit" class="btn-admin">Save Event</button>
        </div>
      </form>
    </div>
  </main>
  <script>
    // Initialize Quill Editor
    var quill = new Quill('#quill-editor', {
      theme: 'snow',
      placeholder: 'Write event details here...',
      modules: {
        toolbar: [
          [{ 'header': [1, 2, 3, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          ['link', 'blockquote'],
          ['clean']
        ]
      }
    });

    // Sync Quill content to hidden textarea on submit
    document.querySelector('form').addEventListener('submit', function() {
      document.querySelector('#hidden-content').value = quill.root.innerHTML;
    });

    // Image Preview Function
    function previewImage(input, previewId) {
      const preview = document.getElementById(previewId);
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      } else {
        preview.src = '#';
        preview.style.display = 'none';
      }
    }
  </script>
</body>
</html>
