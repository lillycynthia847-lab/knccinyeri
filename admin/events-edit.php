<?php
require_once 'includes/config.php';
if (!isset($_GET['id'])) { header("Location: events.php"); exit; }
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?"); $stmt->execute([$id]); $event = $stmt->fetch();
if (!$event) { header("Location: events.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cover_image = $event['cover_image'];
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/events/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES['cover_image']['name']);
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $fileName)) {
            if ($cover_image && file_exists('../' . $cover_image)) unlink('../' . $cover_image);
            $cover_image = 'uploads/events/' . $fileName;
        }
    }
    $stmt = $pdo->prepare("UPDATE events SET title=?, event_type=?, event_date=?, event_time_start=?, event_time_end=?, location=?, description=?, cover_image=?, registration_link=?, status=? WHERE id=?");
    $stmt->execute([$_POST['title'], $_POST['event_type'], $_POST['event_date'], $_POST['event_time_start'] ?: null, $_POST['event_time_end'] ?: null, $_POST['location'], $_POST['description'], $cover_image, $_POST['registration_link'], $_POST['status'], $id]);
    header("Location: events.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Event | Admin Panel</title>
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
        <h1 class="page-title">Edit Event</h1>
      </div>
    </div>
    <div class="form-wrapper">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group"><label>Event Title</label><input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required></div>
        <div class="form-row">
          <div class="form-group"><label>Event Type</label>
            <select name="event_type" class="form-control">
              <?php foreach(['Annual General Meeting','Workshop','Networking','Seminar','Conference','Training','General'] as $t): ?>
              <option <?php echo $event['event_type']==$t?'selected':''; ?>><?php echo $t; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group"><label>Status</label>
            <select name="status" class="form-control">
              <?php foreach(['Upcoming','Completed','Cancelled'] as $s): ?>
              <option <?php echo $event['status']==$s?'selected':''; ?>><?php echo $s; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-row-3" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px;">
          <div class="form-group"><label>Event Date</label><input type="date" name="event_date" class="form-control" value="<?php echo htmlspecialchars($event['event_date'] ?? ''); ?>" required></div>
          <div class="form-group"><label>Start Time</label><input type="time" name="event_time_start" class="form-control" value="<?php echo htmlspecialchars($event['event_time_start'] ?? ''); ?>"></div>
          <div class="form-group"><label>End Time</label><input type="time" name="event_time_end" class="form-control" value="<?php echo htmlspecialchars($event['event_time_end'] ?? ''); ?>"></div>
        </div>
        <div class="form-group"><label>Location</label><input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($event['location'] ?? ''); ?>"></div>
        <div class="form-group"><label>Registration Link</label><input type="url" name="registration_link" class="form-control" value="<?php echo htmlspecialchars($event['registration_link'] ?? ''); ?>"></div>
        <div class="form-group">
          <label>Cover Image Upload</label>
          <div class="custom-file-upload">
            <div class="cfu-icon">📁</div>
            <div class="cfu-text">Click or drag new image to replace current</div>
            <div class="cfu-hint">PNG, JPG or GIF (Recommended 1200x800px)</div>
            <input type="file" id="cover_image" name="cover_image" accept="image/*" onchange="previewImage(this, 'imagePreviewEdit')">
            <?php if($event['cover_image']): ?>
              <img id="imagePreviewEdit" class="cfu-preview" src="../<?php echo $event['cover_image']; ?>" alt="Preview" style="display:block;" />
            <?php else: ?>
              <img id="imagePreviewEdit" class="cfu-preview" src="#" alt="Preview" />
            <?php endif; ?>
          </div>
        </div>
        <div class="form-group">
          <label>Description</label>
          <div id="quill-editor"><?php echo $event['description']; ?></div>
          <textarea id="hidden-content" name="description" style="display:none;"></textarea>
        </div>
        <div style="margin-top:32px; border-top:1px solid var(--admin-border); padding-top:24px;">
          <a href="events.php" class="btn-cancel">Cancel</a>
          <button type="submit" class="btn-admin">Update Event</button>
        </div>
      </form>
    </div>
  </main>
  <script>
    // Initialize Quill Editor
    var quill = new Quill('#quill-editor', {
      theme: 'snow',
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
