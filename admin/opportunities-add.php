<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $document = null;
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/docs/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES['document']['name']);
        if (move_uploaded_file($_FILES['document']['tmp_name'], $uploadDir . $fileName)) {
            $document = 'uploads/docs/' . $fileName;
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO opportunities (title, opp_type, deadline, description, application_link, document, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['title'],
        $_POST['opp_type'],
        $_POST['deadline'] ?: null,
        $_POST['description'],
        $_POST['application_link'] ?: null,
        $document,
        $_POST['status']
    ]);
    header("Location: opportunities.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Opportunity | Admin Panel</title>
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
        <h1 class="page-title">Create Opportunity</h1>
        <p class="page-subtitle">Post a new job, tender, or grant.</p>
      </div>
    </div>
    <div class="form-wrapper">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group"><label>Opportunity Title</label><input type="text" name="title" class="form-control" placeholder="e.g. Tender for Supply of Office Stationery" required></div>
        <div class="form-row">
          <div class="form-group"><label>Opportunity Type</label>
            <select name="opp_type" class="form-control" required>
              <option value="Vendor">Vendor Opportunity</option>
              <option value="Government Tender">Government Tender</option>
              <option value="Job">Job Opportunity</option>
              <option value="Grant">Grant</option>
            </select>
          </div>
          <div class="form-group"><label>Status</label>
            <select name="status" class="form-control"><option>Open</option><option>Closed</option></select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Deadline</label><input type="date" name="deadline" class="form-control"></div>
          <div class="form-group"><label>Application Link (optional)</label><input type="url" name="application_link" class="form-control" placeholder="https://..."></div>
        </div>
        <div class="form-group">
          <label>Upload Document / PDF (optional)</label>
          <div class="custom-file-upload">
            <div class="cfu-icon">📄</div>
            <div class="cfu-text">Click or drag document to upload</div>
            <div class="cfu-hint">PDF, DOC, DOCX</div>
            <input type="file" id="document_upload" name="document" accept=".pdf,.doc,.docx" onchange="previewDocument(this, 'docPreviewAdd')">
            <div id="docPreviewAdd" class="cfu-doc-preview" style="display:none; font-weight:600; color:var(--admin-primary); margin-top:12px; z-index: 2; position: relative;"></div>
          </div>
        </div>
        <div class="form-group">
          <label>Description / Details</label>
          <div id="quill-editor"></div>
          <textarea id="hidden-content" name="description" style="display:none;"></textarea>
        </div>
        <div style="margin-top:32px; border-top:1px solid var(--admin-border); padding-top:24px;">
          <a href="opportunities.php" class="btn-cancel">Cancel</a>
          <button type="submit" class="btn-admin">Save Opportunity</button>
        </div>
      </form>
    </div>
  </main>
  <script>
    // Initialize Quill Editor
    var quill = new Quill('#quill-editor', {
      theme: 'snow',
      placeholder: 'Write opportunity details here...',
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

    // Document Preview Function
    function previewDocument(input, previewId) {
      const preview = document.getElementById(previewId);
      if (input.files && input.files[0]) {
        preview.textContent = "Selected: " + input.files[0].name;
        preview.style.display = 'block';
      } else {
        preview.textContent = '';
        preview.style.display = 'none';
      }
    }
  </script>
</body>
</html>
