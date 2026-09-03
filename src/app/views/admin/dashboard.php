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

// Giá trị mặc định an toàn nếu controller chưa kịp truyền biến
$totalRevenue   = $totalSales ?? 0;
$ordersCount    = $totalOrders ?? 0;
$customersCount = $activeCustomers ?? 0;
$stockAlertQty  = $lowStock ?? 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMIÈRE Fine Jewelry - Admin Dashboard</title>
    
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
                                <a class="nav-link active" href="/index.php?page=admin_dashboard"><i class="bi bi-grid-1x2 me-2"></i> Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/index.php?page=admin_products"><i class="bi bi-gem me-2"></i> Sản Phẩm</a>
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
                            $nameSegments = array_filter(explode(" ", trim($_SESSION["user_name"] ?? "Admin")));
                            $shortLetters = "";
                            foreach ($nameSegments as $segment) {
                                $shortLetters .= mb_substr($segment, 0, 1, "UTF-8");
                            }
                            echo htmlspecialchars(mb_strtoupper(mb_substr($shortLetters, -2, 2, "UTF-8"), "UTF-8"));
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

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-4 main-content">
            
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="page-title mb-1">Tổng quan Báo cáo</h1>
                    <p class="text-muted mb-0 small">Thống kê chỉ số kinh doanh và phân tích hiệu suất hệ thống</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <button class="btn btn-gold dropdown-toggle shadow-sm" type="button" id="dropdownExportReport" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download me-2"></i>Xuất Báo Cáo
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2" aria-labelledby="dropdownExportReport" style="min-width: 280px; font-size: 13px;">
                            <li><h6 class="dropdown-header text-uppercase tracking-wider font-xs text-muted fw-bold mb-1">Chọn loại báo cáo</h6></li>
                            <li>
                                <a class="dropdown-item rounded py-2" href="#" id="btnExportSales">
                                    <i class="bi bi-graph-up-arrow me-2 text-success"></i>1. Báo cáo Doanh thu & Doanh số
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded py-2" href="#" id="btnExportInventory">
                                    <i class="bi bi-box-seam me-2 text-warning"></i>2. Báo cáo Sản phẩm & Tồn kho
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- KPI Metric Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card p-3 border-0 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-wrapper"><i class="bi bi-wallet2 text-gold"></i></div>
                            <span class="trend-badge positive">+12.5%</span>
                        </div>
                        <small class="text-muted fw-semibold font-xs tracking-wider">TỔNG DOANH THU</small>
                        <h3 class="mt-1 mb-0 font-numeric"><?= number_format((float)$totalRevenue, 0, ',', '.') ?>₫</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 border-0 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-wrapper"><i class="bi bi-cart3 text-gold"></i></div>
                            <span class="trend-badge positive">+8.2%</span>
                        </div>
                        <small class="text-muted fw-semibold font-xs tracking-wider">TỔNG ĐƠN HÀNG</small>
                        <h3 class="mt-1 mb-0 font-numeric"><?= number_format((int)$ordersCount) ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 border-0 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-wrapper"><i class="bi bi-people text-gold"></i></div>
                            <span class="trend-badge neutral">+4</span>
                        </div>
                        <small class="text-muted fw-semibold font-xs tracking-wider">KHÁCH HÀNG MỚI</small>
                        <h3 class="mt-1 mb-0 font-numeric"><?= number_format((int)$customersCount) ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 border-0 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-wrapper"><i class="bi bi-box-seam text-gold"></i></div>
                            <span class="trend-badge text-danger rounded-pill px-2" style="background-color: #fee2e2;">Cần nhập</span>
                        </div>
                        <small class="text-muted fw-semibold font-xs tracking-wider">CẢNH BÁO TỒN KHO</small>
                        <h3 class="mt-1 mb-0 font-numeric"><?= number_format((int)$stockAlertQty) ?></h3>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <!-- Biểu đồ phân tích doanh thu -->
                <div class="col-lg-8">
                    <div class="card p-4 border-0 shadow-sm h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="section-title mb-0">Xu Hướng Doanh Số</h5>
                            <select class="form-select form-select-sm w-auto border-0 bg-light text-muted">
                                <option>6 tháng gần đây</option>
                                <option>3 tháng gần đây</option>
                            </select>
                        </div>
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="salesTrendsChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Danh sách đơn hàng gần đây -->
                <div class="col-lg-4">
                    <div class="card p-4 border-0 shadow-sm h-100 d-flex flex-column">
                        <h5 class="section-title mb-4">Đơn Hàng Mới Nhất</h5>
                        <div class="activity-list d-flex flex-column gap-3 mb-4">
                            <?php if (empty($recentOrders)): ?>
                                <p class="text-muted text-center py-4 small">Chưa có dữ liệu đơn hàng gần đây.</p>
                            <?php else: ?>
                                <?php foreach ($recentOrders as $o): ?>
                                    <div class="activity-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-wrapper bg-light text-gold d-flex align-items-center justify-content-center" style="width:40px; height:40px; border-radius:8px;">
                                                <i class="bi bi-bag"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 small fw-bold">Đơn Hàng #<?= htmlspecialchars((string)($o["order_code"] ?? '---')) ?></h6>
                                                <small class="text-muted font-xs">Khách hàng: <?= htmlspecialchars((string)($o["full_name"] ?? 'Khách vãng lai')) ?></small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="d-block small fw-bold text-gold"><?= number_format((float)($o["final_amount"] ?? 0), 0, ',', '.') ?>₫</span>
                                            <small class="text-muted font-xs">
                                                <?= !empty($o["created_at"]) ? (new DateTime($o["created_at"]))->format("H:i d/m") : "—" ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <a href="/index.php?page=admin_orders" class="btn btn-link text-gold btn-sm mt-auto text-center pt-3 border-top text-decoration-none fw-medium w-100">
                            Xem tất cả đơn hàng <i class="bi bi-arrow-right ms-1"></i>
                        </a>                   
                    </div>
                </div>
            </div>

            <!-- Hiệu suất danh mục & Cảnh báo tồn kho -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card p-4 border-0 shadow-sm">
                        <h5 class="section-title mb-4">Hiệu suất theo Danh mục</h5>
                        <div class="progress-stack d-flex flex-column gap-3">
                            <div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-medium">Bông Tai Cao Cấp</span>
                                    <span class="text-muted fw-bold">45%</span>
                                </div>
                                <div class="progress" style="height: 6px;"><div class="progress-bar bg-gold" style="width: 45%"></div></div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-medium">Dây Chuyền Vàng 18K</span>
                                    <span class="text-muted fw-bold">32%</span>
                                </div>
                                <div class="progress" style="height: 6px;"><div class="progress-bar bg-gold" style="width: 32%"></div></div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-medium">Nhẫn Kim Cương Tự Nhiên</span>
                                    <span class="text-muted fw-bold">23%</span>
                                </div>
                                <div class="progress" style="height: 6px;"><div class="progress-bar bg-gold" style="width: 23%"></div></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card p-4 border-0 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="section-title mb-0">Cảnh Báo Tồn Kho Thấp</h5>
                            <i class="bi bi-exclamation-circle text-danger"></i>
                        </div>
                        <div class="alert-list d-flex flex-column gap-3">
                            <?php if (empty($lowStockProducts)): ?>
                                <p class="text-muted text-center py-4 small">Tất cả sản phẩm đều đảm bảo lượng tồn kho an toàn.</p>
                            <?php else: ?>
                                <?php foreach ($lowStockProducts as $p): ?>
                                    <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="/<?= htmlspecialchars((string)($p["main_image"] ?? '')) ?>" style="width:35px; height:35px; object-fit:cover;" class="rounded" alt="SP">
                                            <div>
                                                <h6 class="mb-0 small fw-bold"><?= htmlspecialchars((string)($p["product_name"] ?? 'Sản phẩm')) ?></h6>
                                                <small class="text-muted font-xs">Mã: #<?= htmlspecialchars((string)($p["product_id"] ?? '')) ?></small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-0 small d-block">Còn <?= htmlspecialchars((string)($p["stock_quantity"] ?? 0)) ?> sp</span>
                                            <small class="text-muted text-uppercase font-xs">Cần nhập kho</small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/assets/admin/dashboard.js"></script>

</body>
</html>