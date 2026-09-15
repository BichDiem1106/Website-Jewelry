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

// Bảng cấu hình trạng thái đơn hàng
$statusMeta = [
    'pending'          => ['class' => 'status-pending',    'text' => 'Chờ xử lý'],
    'processing'       => ['class' => 'status-processing', 'text' => 'Đang xử lý'],
    'shipping'         => ['class' => 'status-processing', 'text' => 'Đang giao'],
    'delivered'        => ['class' => 'status-shipped',    'text' => 'Đã giao'],
    'cancelled'        => ['class' => 'status-cancelled',  'text' => 'Đã hủy'],
    'return_requested' => ['class' => 'status-pending',    'text' => 'Yêu cầu hoàn trả (Đang thu hồi)'],
    'returned'         => ['class' => 'status-returned',   'text' => 'Đã hoàn trả thành công']
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMIÈRE Fine Jewelry - Quản lý đơn hàng</title>
    
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
                                <a class="nav-link" href="/index.php?page=admin_products"><i class="bi bi-gem me-2"></i> Sản Phẩm</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/index.php?page=admin_categories"><i class="bi bi-tags me-2"></i> Danh Mục</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="/index.php?page=admin_orders"><i class="bi bi-bag me-2"></i> Đơn Hàng</a>
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
                            $nameParts = array_filter(explode(" ", trim($_SESSION["user_name"] ?? "Admin")));
                            $shortInitials = "";
                            foreach ($nameParts as $part) {
                                $shortInitials .= mb_substr($part, 0, 1, "UTF-8");
                            }
                            echo htmlspecialchars(mb_strtoupper(mb_substr($shortInitials, -2, 2, "UTF-8"), "UTF-8"));
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

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
            
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                <div>
                    <h2 class="page-title mb-1">Quản lý đơn hàng</h2>
                    <small class="text-muted">Theo dõi tiến độ, thanh toán và vận chuyển đơn hàng trực tuyến của LUMIÈRE.</small>
                </div>
            </div>

            <!-- Bộ lọc đơn hàng -->
            <div class="card bg-white p-3 mb-4 shadow-sm border-0">
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light-custom border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchOrders" class="form-control form-control-custom bg-light-custom border-start-0" placeholder="Tìm kiếm mã đơn hàng hoặc tên khách hàng...">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <select id="filterPayment" class="form-select font-xs text-muted">
                            <option value="all">Tất cả thanh toán</option>
                            <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                            <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <select id="filterFulfillment" class="form-select font-xs text-muted">
                            <option value="all">Tất cả trạng thái giao</option>
                            <option value="pending">Chờ xử lý</option>
                            <option value="processing">Đang xử lý</option>
                            <option value="shipping">Đang giao hàng</option>
                            <option value="delivered">Đã giao hàng</option>
                            <option value="cancelled">Đã hủy</option>
                            <option value="return_requested">Yêu cầu hoàn trả (Đang thu hồi)</option>
                            <option value="returned">Đã hoàn trả thành công</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <button id="btnApplyFilters" class="btn btn-gold w-100 font-xs text-uppercase tracking-wider py-2 shadow-sm">Áp dụng lọc</button>
                    </div>
                </div>
            </div>

            <!-- Bảng danh sách đơn hàng -->
            <div class="card bg-white border-0 shadow-sm overflow-hidden">
                <div class="table-responsive table-custom-wrapper">
                    <table class="table align-middle mb-0" id="ordersTable">
                        <thead class="table-light-bg text-uppercase font-xs tracking-wider text-muted">
                            <tr>
                                <th scope="col" class="ps-4" style="width: 50px;">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th scope="col">Mã đơn hàng</th>
                                <th scope="col">Khách hàng</th>
                                <th scope="col">Ngày đặt</th>
                                <th scope="col">Tổng tiền</th>
                                <th scope="col">Phương thức</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col" class="text-end pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        Không tìm thấy dữ liệu đơn hàng phù hợp
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $o): ?>
                                    <?php 
                                    $curStatus = $o["status"] ?? 'pending';
                                    $stInfo = $statusMeta[$curStatus] ?? ['class' => 'status-pending', 'text' => $curStatus];
                                    ?>
                                    <tr class="row-hover" 
                                        data-id="<?= htmlspecialchars((string)$o["order_code"]) ?>" 
                                        data-customer="<?= htmlspecialchars((string)$o["full_name"]) ?>" 
                                        data-payment="<?= htmlspecialchars((string)$o["payment_method"]) ?>" 
                                        data-fulfillment="<?= htmlspecialchars((string)$curStatus) ?>"
                                        data-db-id="<?= htmlspecialchars((string)$o["order_id"]) ?>">
                                        <td class="ps-4">
                                            <div class="form-check custom-checkbox">
                                                <input class="form-check-input" type="checkbox">
                                            </div>
                                        </td>
                                        <td><span class="fw-bold text-dark">#<?= htmlspecialchars((string)$o["order_code"]) ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-sm bg-light-custom rounded-circle d-flex align-items-center justify-content-center fw-medium text-secondary font-xs">
                                                    <?= htmlspecialchars(mb_substr((string)$o["full_name"], 0, 2, "UTF-8")) ?>
                                                </div>
                                                <div>
                                                    <div class="fw-medium text-dark small"><?= htmlspecialchars((string)$o["full_name"]) ?></div>
                                                    <div class="text-muted font-xs">SĐT: <?= htmlspecialchars((string)$o["receiver_phone"]) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted font-xs">
                                            <?= !empty($o["created_at"]) ? (new DateTime($o["created_at"]))->format("d/m/Y H:i") : "—" ?>
                                        </td>
                                        <td class="font-numeric text-dark fw-bold"><?= number_format((float)($o["final_amount"] ?? 0), 0, ',', '.') ?>₫</td>
                                        <td>
                                            <span class="status-badge">
                                                <?php if (($o["payment_method"] ?? "") === "cod"): ?>
                                                    <i class="bi bi-cash status-paid me-1"></i> COD
                                                <?php else: ?>
                                                    <i class="bi bi-credit-card status-pending me-1"></i> Chuyển khoản
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge">
                                                <i class="bi bi-circle-fill font-xs <?= $stInfo['class'] ?> me-1"></i> 
                                                <?= $stInfo['text'] ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-outline-custom font-xs btn-update-status">CẬP NHẬT TRẠNG THÁI</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal cập nhật trạng thái -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content card shadow-sm border-0 p-2">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title section-title fw-bold text-dark tracking-wider fs-5" id="modalOrderId">Cập nhật đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="mb-3">
                    <label class="form-label-custom">Phương thức thanh toán</label>
                    <select class="form-select form-control-custom" id="modalSelectPayment">
                        <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                        <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label-custom">Trạng thái đơn hàng</label>
                    <select class="form-select form-control-custom" id="modalSelectFulfillment">
                        <option value="pending">Chờ xử lý</option>
                        <option value="processing">Đang xử lý</option>
                        <option value="shipping">Đang giao hàng</option>
                        <option value="delivered">Đã giao hàng</option>
                        <option value="cancelled">Đã hủy</option>
                        <option value="return_requested">Yêu cầu hoàn trả (Đang thu hồi)</option>
                        <option value="returned">Đã hoàn trả thành công</option>
                    </select>
                </div>
                <div class="text-end mt-4 pt-2 border-top border-light">
                    <button type="button" class="btn btn-outline-custom py-2 px-3 me-2" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" class="btn btn-gold py-2 px-4 shadow-sm" id="btnSaveStatus">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/admin/ordersmanage.js"></script>
</body>
</html>