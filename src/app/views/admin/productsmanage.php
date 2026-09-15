<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAdmin = !empty($_SESSION["user_logged_in"]) 
    && $_SESSION["user_logged_in"] === true 
    && (($_SESSION["user_role"] ?? "") === "admin");

if (!$isAdmin) {
    header("Location: /index.php?page=login");
    exit();
}

// Chuyển đổi dữ liệu sản phẩm sang JSON cho Frontend
$jsProducts = [];
if (!empty($products) && is_iterable($products)) {
    foreach ($products as $p) {
        $qty = (int)($p["stock_quantity"] ?? 0);
        $status = "IN STOCK";
        if ($qty <= 0) {
            $status = "OUT OF STOCK";
        } elseif ($qty <= 5) {
            $status = "LOW STOCK";
        }

        $jsProducts[] = [
            "id"          => $p["product_id"],
            "name"        => $p["product_name"] ?? "",
            "desc"        => $p["description"] ?? "",
            "category"    => $p["category_name"] ?? "Trang sức",
            "category_id" => $p["category_id"] ?? 0,
            "sku"         => "LUM-PROD-" . str_pad((string)$p["product_id"], 3, "0", STR_PAD_LEFT),
            "price"       => (int)($p["price"] ?? 0),
            "stock"       => $qty,
            "status"      => $status,
            "image"       => "/" . ltrim($p["main_image"] ?? "favicon.png", "/"),
            "dateAdded"   => !empty($p["created_at"]) ? date("Y-m-d", strtotime($p["created_at"])) : date("Y-m-d")
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMIÈRE Fine Jewelry - Quản lý sản phẩm</title>
    
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
                            <small class="text-muted tracking-wider font-xs text-uppercase">Fine Jewelry Admin</small>
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
                            $nameTokens = array_filter(explode(" ", trim($_SESSION["user_name"] ?? "Admin")));
                            $initials = "";
                            foreach ($nameTokens as $token) {
                                $initials .= mb_substr($token, 0, 1, "UTF-8");
                            }
                            echo htmlspecialchars(mb_strtoupper(mb_substr($initials, -2, 2, "UTF-8"), "UTF-8"));
                            ?>
                        </div>
                        <div>
                            <h6 class="mb-0 small fw-bold text-dark"><?= htmlspecialchars($_SESSION["user_name"] ?? "Admin") ?></h6>
                            <small class="text-muted font-xs"><?= htmlspecialchars(ucfirst($_SESSION["user_role"] ?? "admin")) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end border-bottom pb-4 mb-5 gap-3">
                <div>
                    <h1 class="page-title display-6 mb-2">Quản lý sản phẩm</h1>
                    <p class="text-muted mb-0">Quản lý các bộ sưu tập trang sức cao cấp, kiểm soát số lượng tồn kho và cập nhật giá bán tại LUMIÈRE.</p>
                </div>
                <div>
                    <a href="/index.php?page=admin_add_product" class="btn btn-gold text-nowrap py-2 px-4 shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i> THÊM SẢN PHẨM MỚI
                    </a>
                </div>
            </div>

            <?php if (!empty($_SESSION["success_message"])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION["success_message"]) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION["success_message"]); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION["error_message"])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION["error_message"]) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION["error_message"]); ?>
            <?php endif; ?>

            <div class="row g-5">
                <!-- Sidebar Lọc Sản Phẩm -->
                <aside class="col-lg-3">
                    <div class="filter-sidebar bg-white p-4 border rounded-1 shadow-sm">
                        <h5 class="section-title mb-1">Lọc theo</h5>
                        <p class="text-muted font-xs mb-4">Thu hẹp phạm vi tìm kiếm theo danh mục</p>
                        
                        <div class="filter-group d-flex flex-column gap-2 mb-4" id="filterContainer">
                            <?php if (!empty($categories) && is_iterable($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <button class="btn filter-item text-start d-flex align-items-center gap-3 py-2 px-3 text-muted" data-category="<?= htmlspecialchars((string)$cat['category_name']) ?>">
                                        <i class="bi bi-gem"></i> <?= htmlspecialchars((string)$cat['category_name']) ?>
                                    </button>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <hr class="my-4 border-color">

                        <div class="d-flex flex-column gap-2">
                            <a href="#" id="clearFilters" class="text-center text-muted font-xs py-2 text-decoration-none">Xóa tất cả bộ lọc</a>
                            <button id="applyFilters" class="btn btn-apply-filter w-100 py-2 fw-medium text-uppercase font-xs tracking-wider shadow-sm">Áp dụng bộ lọc</button>
                        </div>
                    </div>
                </aside>

                <!-- Bảng Danh Sách Sản Phẩm -->
                <section class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4 gap-4 flex-wrap">
                        <div class="position-relative flex-grow-1" style="max-width: 550px;">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" id="searchInput" class="form-control bg-white ps-5 border py-2 font-xs" placeholder="Tìm kiếm trang sức theo tên hoặc mã SKU...">
                        </div>
                        
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <select id="sortSelect" class="form-select border bg-white font-xs text-muted py-2 px-3" style="min-width: 170px;">
                                <option value="newest">Sắp xếp: Mới nhất</option>
                                <option value="price-asc">Giá: Thấp đến Cao</option>
                                <option value="price-desc">Giá: Cao đến Thấp</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive bg-white border rounded-1 shadow-sm">
                        <table class="table align-middle mb-0">
                            <thead class="table-light-bg font-xs tracking-wider text-muted text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3" style="width: 40%;">Sản phẩm</th>
                                    <th class="py-3">Danh mục</th>
                                    <th class="py-3">Mã SKU</th>
                                    <th class="py-3">Giá niêm yết</th>
                                    <th class="py-3">Trạng thái</th>
                                    <th class="pe-4 py-3 text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                                <!-- Đổ dữ liệu tự động bằng productmanage.js -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span id="paginationInfo" class="text-muted font-xs">Hiển thị từ 0 đến 0 trong tổng số 0 kết quả</span>
                        <nav>
                            <ul id="paginationControls" class="pagination pagination-sm mb-0 gap-1">
                                <!-- Tạo nút trang bằng productmanage.js -->
                            </ul>
                        </nav>
                    </div>

                </section> 
            </div> 
            
            <!-- Footer -->
            <footer class="d-flex justify-content-between text-muted font-xs mt-5 pt-4 border-top">
                <span>&copy; <?= date('Y') ?> LUMIÈRE Fine Jewelry. All rights reserved.</span>
                <div class="d-flex gap-3">
                    <a href="#" class="text-muted text-decoration-none">Tài liệu nội bộ</a>
                    <a href="#" class="text-muted text-decoration-none">Hỗ trợ kỹ thuật</a>
                    <a href="#" class="text-muted text-decoration-none">Chính sách bảo mật</a>
                </div>
            </footer>
            
        </main>
    </div>
</div>

<!-- Modal Chỉnh Sửa Chi Tiết Sản Phẩm -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card shadow-sm border-0 p-2">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title page-title fw-bold text-dark tracking-wider fs-5">CHỈNH SỬA CHI TIẾT SẢN PHẨM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" id="editOldSku">
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control-custom w-100" id="editProductName" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Mô tả sản phẩm</label>
                        <textarea class="form-control-custom w-100" id="editProductDesc" rows="3" placeholder="Nhập mô tả chi tiết..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label-custom">Danh mục</label>
                            <select class="form-select form-control-custom" id="editProductCategory" required>
                                <?php if (!empty($categories) && is_iterable($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= htmlspecialchars((string)$cat['category_name']) ?>">
                                            <?= htmlspecialchars((string)$cat['category_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label-custom">Mã SKU (Cố định)</label>
                            <input type="text" class="form-control-custom w-100" id="editProductSku" disabled style="background-color:#f1f5f9;">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label-custom">Giá niêm yết (₫) <span class="text-danger">*</span></label>
                            <input type="number" min="0" step="1000" class="form-control-custom w-100" id="editProductPrice" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label-custom">Số lượng trong kho <span class="text-danger">*</span></label>
                            <input type="number" class="form-control-custom w-100" id="editProductStock" min="0" required>
                        </div>
                    </div>
                    
                    <div class="text-end mt-4 pt-2 border-top border-light">
                        <button type="button" class="btn btn-outline-custom py-2 px-3 me-2" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-gold py-2 px-4 shadow-sm">Cập nhật sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
window.productsData = <?= json_encode($jsProducts, JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/admin/productmanage.js"></script>
</body>
</html>