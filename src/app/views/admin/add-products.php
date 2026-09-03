<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền quản trị viên
$isAuthorizedAdmin = isset($_SESSION["user_logged_in"]) 
    && $_SESSION["user_logged_in"] === true 
    && (($_SESSION["user_role"] ?? '') === 'admin');

if (!$isAuthorizedAdmin) {
    header("Location: /index.php?page=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMIÈRE Fine Jewelry - Thêm sản phẩm mới</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/admin/style.css">
    <link rel="icon" type="image/png" href="/favicon.png" />
</head>
<body>

<div class="container-fluid">
    <div class="row min-vh-100">
        
        <!-- Sidebar Navigation -->
        <nav class="col-md-3 col-lg-2 sidebar border-end p-4">
            <!-- Mobile Header with Hamburger Trigger -->
            <div class="d-flex justify-content-between align-items-center d-md-none mb-2">
                <a href="/index.php?page=admin_dashboard" class="text-decoration-none"><h3 class="brand-logo mb-0">LUMIÈRE</h3></a>
                <button class="btn btn-link text-dark p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list fs-2"></i>
                </button>
            </div>
            
            <!-- Collapsible Sidebar Content -->
            <div class="collapse d-md-block" id="sidebarMenu">
                <div class="position-sticky d-flex flex-column h-100 justify-content-between">
                    <div>
                        <div class="brand-zone mb-4">
                            <h3 class="brand-logo mb-1">LUMIÈRE</h3>
                            <small class="text-muted tracking-wider text-uppercase font-xs">Fine Jewelry Admin</small>
                        </div>
                        
                        <ul class="nav flex-column gap-2 mt-4">
                            <li class="nav-item">
                                <a class="nav-link" href="/index.php?page=admin_dashboard"><i class="bi bi-grid-1x2 me-2"></i> Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="/index.php?page=admin_products"><i class="bi bi-gem me-2"></i> Sản Phẩm</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/index.php?page=admin_categories"><i class="bi bi-tags me-2"></i> Danh Mục</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/index.php?page=admin_orders"><i class="bi bi-bag me-2"></i> Đơn Hàng</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/index.php?page=admin_users"><i class="bi bi-people me-2"></i> Người Dùng</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/index.php?page=admin_content"><i class="bi bi-layout-text-window me-2"></i> Nội Dung</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/index.php?page=admin_chat"><i class="bi bi-chat-dots me-2"></i> Tin nhắn</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="/index.php?page=logout"><i class="bi bi-box-arrow-right me-2"></i> Đăng xuất</a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="user-profile d-flex align-items-center gap-3 pt-3 border-top">
                        <div class="avatar bg-gold text-white rounded-circle d-flex align-items-center justify-content-center fw-bold">
                            <?php 
                            $nameSegments = explode(" ", trim($_SESSION["user_name"] ?? "Admin"));
                            $avatarLetters = "";
                            foreach ($nameSegments as $segment) {
                                if (!empty($segment)) {
                                    $avatarLetters .= mb_substr($segment, 0, 1, "UTF-8");
                                }
                            }
                            echo htmlspecialchars(mb_strtoupper(mb_substr($avatarLetters, -2, 2, "UTF-8"), "UTF-8"));
                            ?>
                        </div>
                        <div>
                            <h6 class="mb-0 small fw-bold text-dark"><?= htmlspecialchars($_SESSION["user_name"] ?? "Quản trị viên") ?></h6>
                            <small class="text-muted font-xs"><?= htmlspecialchars(ucfirst($_SESSION["user_role"] ?? "admin")) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-4 main-content">
            
            <form action="/index.php?page=admin_api_add_product" method="POST" id="addProductForm" enctype="multipart/form-data">
                <div class="d-flex justify-content-between align-items-center pt-3 pb-3 mb-4 border-bottom">
                    <div>
                        <h1 class="page-title mb-1">Thêm sản phẩm mới</h1>
                        <p class="text-muted mb-0 small">Mở rộng danh mục sản phẩm trang sức của bạn.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <a href="/index.php?page=admin_products" class="btn btn-outline-secondary border-color text-muted font-xs px-4 py-2 text-decoration-none">Hủy bỏ</a>
                        <button type="submit" class="btn btn-gold font-xs px-4 py-2">Đăng sản phẩm</button>
                    </div>
                </div>

                <?php if (!empty($_SESSION["error_message"])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        <?= htmlspecialchars($_SESSION["error_message"]) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION["error_message"]); ?>
                <?php endif; ?>

                <div class="row g-4 mb-5">
                    
                    <div class="col-lg-8">
                        
                        <div class="card border-0 shadow-sm p-4 mb-4">
                            <h5 class="section-title mb-4">Thông tin cơ bản</h5>
                            
                            <div class="mb-4">
                                <label class="form-label form-label-custom">TÊN SẢN PHẨM</label>
                                <input type="text" name="name" class="form-control form-control-custom" placeholder="Ví dụ: Nhẫn Kim Cương Vàng Trắng Lumière" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label form-label-custom">MÔ TẢ SẢN PHẨM</label>
                                <div class="rich-text-editor border rounded">
                                    <div class="toolbar bg-light border-bottom p-2 d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-light border-0"><i class="bi bi-type-bold"></i></button>
                                        <button type="button" class="btn btn-sm btn-light border-0"><i class="bi bi-type-italic"></i></button>
                                        <button type="button" class="btn btn-sm btn-light border-0"><i class="bi bi-type-underline"></i></button>
                                        <div class="vr mx-1"></div>
                                        <button type="button" class="btn btn-sm btn-light border-0"><i class="bi bi-list-ul"></i></button>
                                        <button type="button" class="btn btn-sm btn-light border-0"><i class="bi bi-list-ol"></i></button>
                                    </div>
                                    <textarea name="description" class="form-control border-0 shadow-none p-3 font-xs text-muted" rows="5" placeholder="Mô tả chi tiết về độ tinh xảo, chất liệu, đá quý và nguồn cảm hứng thiết kế..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm p-4">
                            <h5 class="section-title mb-4">Hình ảnh sản phẩm</h5>
                            
                            <div class="upload-zone border-dashed rounded text-center p-5 bg-light-custom position-relative" id="dropZone">
                                <input type="file" name="main_image" class="position-absolute w-100 h-100 top-0 start-0 opacity-0 cursor-pointer" accept="image/*" required>
                                <div id="uploadContent">
                                    <div class="upload-icon mb-3">
                                        <i class="bi bi-cloud-arrow-up fs-2 text-gold"></i>
                                    </div>
                                    <h6 class="mb-2 font-xs fw-bold">Chọn tệp tin hoặc kéo & thả hình ảnh vào đây</h6>
                                    <p class="text-muted font-xs mb-3">Hỗ trợ ảnh JPG, PNG hoặc WEBP (tối đa 5MB)</p>
                                    <button type="button" class="btn btn-outline-secondary border-color btn-sm font-xs px-3">Chọn tệp tin</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        
                        <div class="card border-0 shadow-sm p-4 mb-4">
                            <h5 class="section-title mb-4">Giá bán & Kho hàng</h5>
                            
                            <div class="mb-4">
                                <label class="form-label form-label-custom">GIÁ BÁN (₫)</label>
                                <div class="input-group">
                                    <input type="number" name="price" min="0" step="1000" class="form-control border-color shadow-none font-xs" placeholder="0" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label form-label-custom">GIÁ KHUYẾN MÃI (₫ - TÙY CHỌN)</label>
                                <div class="input-group">
                                    <input type="number" name="sale_price" min="0" step="1000" class="form-control border-color shadow-none font-xs" placeholder="0">
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label form-label-custom">SỐ LƯỢNG TỒN KHO</label>
                                <input type="number" name="stock" min="0" class="form-control form-control-custom" placeholder="0" required>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm p-4">
                            <h5 class="section-title mb-4">Phân loại hàng hóa</h5>
                            
                            <div class="mb-4">
                                <label class="form-label form-label-custom">DANH MỤC</label>
                                <select name="category_id" class="form-select form-control-custom font-xs text-muted" required>
                                    <option value="" selected disabled>Chọn danh mục</option>
                                    <?php if (!empty($categories) && is_iterable($categories)): ?>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= htmlspecialchars((string)($cat['category_id'] ?? '')) ?>">
                                                <?= htmlspecialchars((string)($cat['category_name'] ?? 'Danh mục')) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="main_image"]');
    const dropZone = document.getElementById('dropZone');
    const uploadContent = document.getElementById('uploadContent');

    if (fileInput && dropZone && uploadContent) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    uploadContent.innerHTML = `
                        <div class="position-relative d-inline-block py-2">
                            <img src="${event.target.result}" class="img-fluid rounded shadow-sm mb-3" style="max-height: 180px; object-fit: contain;">
                            <div class="font-xs fw-bold text-success">
                                <i class="bi bi-check-circle-fill me-1"></i> Đã tải ảnh: ${file.name}
                            </div>
                            <small class="text-muted d-block mt-1 font-xs">(Bấm hoặc kéo thả ảnh khác để đổi)</small>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                uploadContent.innerHTML = `
                    <div class="upload-icon mb-3">
                        <i class="bi bi-file-earmark-check fs-2 text-success"></i>
                    </div>
                    <h6 class="mb-2 font-xs fw-bold text-success">Đã chọn tệp tin thành công!</h6>
                    <p class="text-muted font-xs mb-3">${file.name} (${(file.size / (1024 * 1024)).toFixed(2)} MB)</p>
                    <button type="button" class="btn btn-outline-secondary border-color btn-sm font-xs px-3">Đổi tệp khác</button>
                `;
            }
        });

        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.style.borderColor = '#c5a880';
            dropZone.style.backgroundColor = 'rgba(197, 168, 128, 0.05)';
        });

        const resetDragEffects = function() {
            dropZone.style.borderColor = '';
            dropZone.style.backgroundColor = '';
        };

        dropZone.addEventListener('dragleave', resetDragEffects);
        dropZone.addEventListener('drop', resetDragEffects);
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>