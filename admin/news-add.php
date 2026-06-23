<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $publish_date = $_POST['publish_date'];
    $content = $_POST['content'];
    $status = 'Published'; // Hardcoded for now
    
    // Handle image upload
    $cover_image = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/news/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES['cover_image']['name']);
        $targetFilePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetFilePath)) {
            $cover_image = 'uploads/news/' . $fileName;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO news (title, category, publish_date, cover_image, content, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $category, $publish_date, $cover_image, $content, $status]);

    header("Location: news.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add News Article | Admin Panel</title>
  <link rel="stylesheet" href="css/admin.css">
  <!-- Quill.js CDN -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <style>
    /* Quill Custom Styling */
    .ql-container { font-family: 'Inter', sans-serif; font-size: 1rem; min-height: 250px; background: #fff; border-radius: 0 0 var(--radius-md) var(--radius-md) !important; border-color: var(--admin-border) !important; }
    .ql-toolbar { background: #f8fafc; border-radius: var(--radius-md) var(--radius-md) 0 0 !important; border-color: var(--admin-border) !important; }
    
    /* CUSTOM FILE UPLOAD (Cache-busted) */
    .custom-file-upload {
      position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;
      padding: 32px 24px; border: 2px dashed #cbd5e1; border-radius: 8px; background-color: #f8fafc; cursor: pointer;
      text-align: center; transition: all 0.2s; overflow: hidden;
    }
    .custom-file-upload:hover { border-color: #3b82f6; background-color: #eff6ff; }
    .custom-file-upload input[type="file"] { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10; }
    .cfu-icon { font-size: 2rem; color: #94a3b8; margin-bottom: 8px; }
    .cfu-text { font-size: 0.95rem; font-weight: 500; color: #1e293b; margin-bottom: 4px; }
    .cfu-hint { font-size: 0.8rem; color: #64748b; }
    .cfu-preview { margin-top: 16px; max-width: 100%; max-height: 180px; border-radius: 6px; display: none; object-fit: contain; position: relative; z-index: 5; }
  </style>
</head>
<body>

  <?php include 'includes/sidebar.php'; ?>

  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Create News Article</h1>
        <p class="page-subtitle">Draft a new story to publish on the public website.</p>
      </div>
    </div>

    <div class="form-wrapper">
      <form action="" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
          <label for="title">Article Title</label>
          <input type="text" id="title" name="title" class="form-control" placeholder="Enter a catchy headline..." required>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" class="form-control" required>
              <option value="" disabled selected>Select Category</option>
              <option value="Press Release">Press Release</option>
              <option value="Business">Business Update</option>
              <option value="Event Coverage">Event Coverage</option>
              <option value="Policy">Policy & Advocacy</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="publish_date">Publish Date</label>
            <input type="date" id="publish_date" name="publish_date" class="form-control" required>
          </div>
        </div>
        
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
          <label for="content">Article Content</label>
          <div id="quill-editor"></div>
          <textarea id="hidden-content" name="content" style="display:none;"></textarea>
        </div>
        
        <div style="margin-top: 32px; border-top: 1px solid var(--admin-border); padding-top: 24px;">
          <a href="news.php" class="btn-cancel">Cancel</a>
          <button type="submit" class="btn-admin">Save & Publish</button>
        </div>
        
      </form>
    </div>

  </main>

  <script>
    // Initialize Quill Editor
    var quill = new Quill('#quill-editor', {
      theme: 'snow',
      placeholder: 'Write the main story here...',
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
