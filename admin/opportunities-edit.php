<?php
require_once 'includes/config.php';
if (!isset($_GET['id'])) { header("Location: opportunities.php"); exit; }
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM opportunities WHERE id = ?"); $stmt->execute([$id]); $opp = $stmt->fetch();
if (!$opp) { header("Location: opportunities.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $document = $opp['document'];
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/docs/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES['document']['name']);
        if (move_uploaded_file($_FILES['document']['tmp_name'], $uploadDir . $fileName)) {
            if ($document && file_exists('../' . $document)) unlink('../' . $document);
            $document = 'uploads/docs/' . $fileName;
        }
    }
    
    $stmt = $pdo->prepare("UPDATE opportunities SET title=?, opp_type=?, deadline=?, description=?, application_link=?, document=?, status=? WHERE id=?");
    $stmt->execute([
        $_POST['title'],
        $_POST['opp_type'],
        $_POST['deadline'] ?: null,
        $_POST['description'],
        $_POST['application_link'] ?: null,
        $document,
        $_POST['status'],
        $id
    ]);
    header("Location: opportunities.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Opportunity | Admin Panel</title>
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
        <h1 class="page-title">Edit Opportunity</h1>
      </div>
    </div>
    <div class="form-wrapper">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group"><label>Opportunity Title</label><input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($opp['title']); ?>" required></div>
        <div class="form-row">
          <div class="form-group"><label>Opportunity Type</label>
            <select name="opp_type" class="form-control" required>
              <?php foreach(['Vendor','Government Tender','Job','Grant'] as $t): ?>
              <option value="<?php echo $t; ?>" <?php echo $opp['opp_type']==$t?'selected':''; ?>><?php echo $t; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group"><label>Status</label>
            <select name="status" class="form-control">
              <option <?php echo $opp['status']=='Open'?'selected':''; ?>>Open</option>
              <option <?php echo $opp['status']=='Closed'?'selected':''; ?>>Closed</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Deadline</label><input type="date" name="deadline" class="form-control" value="<?php echo htmlspecialchars($opp['deadline'] ?? ''); ?>"></div>
          <div class="form-group"><label>Application Link (optional)</label><input type="url" name="application_link" class="form-control" value="<?php echo htmlspecialchars($opp['application_link'] ?? ''); ?>"></div>
        </div>
        <div class="form-group">
          <label>Upload Document / PDF</label>
          <div class="custom-file-upload">
            <div class="cfu-icon">📄</div>
            <div class="cfu-text">Click or drag new document to replace current</div>
            <div class="cfu-hint">PDF, DOC, DOCX</div>
            <input type="file" id="document_upload" name="document" accept=".pdf,.doc,.docx" onchange="previewDocument(this, 'docPreviewEdit')">
            
            <div id="docPreviewEdit" class="cfu-doc-preview" style="display:none; font-weight:600; color:var(--admin-primary); margin-top:12px; z-index: 2; position: relative;"></div>
            
            <?php if($opp['document']): ?>
              <div style="margin-top:12px; z-index: 2; position: relative;">
                <span style="font-size: 0.85rem; color: var(--muted);">Current File:</span> 
                <a href="../<?php echo $opp['document']; ?>" target="_blank" style="color:var(--admin-primary);font-weight:600;font-size:0.9rem; text-decoration: underline;">View Document</a>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="form-group">
          <label>Description / Details</label>
          <div id="quill-editor"><?php echo $opp['description']; ?></div>
          <textarea id="hidden-content" name="description" style="display:none;"></textarea>
        </div>
        <div style="margin-top:32px; border-top:1px solid var(--admin-border); padding-top:24px;">
          <a href="opportunities.php" class="btn-cancel">Cancel</a>
          <button type="submit" class="btn-admin">Update Opportunity</button>
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
