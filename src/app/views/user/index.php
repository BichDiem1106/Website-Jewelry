<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LUMIÈRE JEWELRY - Trang sức tinh tế</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/png" href="/favicon.png">

    <script>
        window.USER_LOGGED_IN = <?php echo (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true) ? 'true' : 'false'; ?>;
    </script>

    <style>
        :root {
            --cream: #f8f5ef;
            --cream-dark: #eee7da;
            --champagne: #c9a66b;
            --champagne-light: #dfc99f;
            --black: #171717;
            --gray: #777;
            --white: #fff;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            color: var(--black);
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }

        a {
            color: inherit;
        }

        .serif {
            font-family: "Times New Roman", Georgia, serif;
        }

        .gold {
            color: var(--champagne);
        }

        /* ================= TOP BAR ================= */

        .top-bar {
            background: var(--black);
            color: #fff;
            text-align: center;
            padding: 8px 15px;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* ================= HEADER ================= */

        .lumiere-header {
            background: rgba(255, 255, 255, 0.97);
            border-bottom: 1px solid #eee7dc;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .main-header {
            min-height: 82px;
            display: flex;
            align-items: center;
        }

        .brand-logo {
            text-decoration: none;
            color: var(--black);
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1;
        }

        .brand-symbol {
            color: var(--champagne);
            font-size: 15px;
            margin-bottom: 5px;
        }

        .brand-name {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 25px;
            letter-spacing: 5px;
        }

        .brand-subtitle {
            font-size: 7px;
            letter-spacing: 4px;
            margin-top: 6px;
            color: #777;
        }

        .main-menu {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 32px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .main-menu a {
            position: relative;
            text-decoration: none;
            color: #222;
            font-size: 12px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 8px 0;
        }

        .main-menu a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 1px;
            background: var(--champagne);
            transition: width .3s ease;
        }

        .main-menu a:hover::after {
            width: 100%;
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-icon {
            color: #222;
            text-decoration: none;
            font-size: 15px;
            transition: .2s ease;
        }

        .header-icon:hover {
            color: var(--champagne);
        }

        .search-box {
            display: flex;
            align-items: center;
        }

        #navbarSearchInput {
            border: none;
            border-bottom: 1px solid var(--champagne);
            outline: none;
            background: transparent;
            width: 150px;
            padding: 5px;
            font-size: 12px;
        }

        /* ================= USER DROPDOWN ================= */

        #userDropdownWrapper {
            position: relative;
        }

        #userDropdownMenu {
            display: none;
            position: absolute;
            top: 35px;
            right: 0;
            width: 225px;
            background: #fff;
            border: 1px solid #eee7dc;
            box-shadow: 0 15px 40px rgba(0,0,0,.12);
            z-index: 9999;
        }

        #userDropdownMenu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 16px;
            text-decoration: none;
            color: #333;
            font-size: 13px;
            border-bottom: 1px solid #f3f0eb;
        }

        #userDropdownMenu a:hover {
            background: var(--cream);
            color: var(--champagne);
        }

        /* ================= HERO ================= */

        .hero-section {
            position: relative;
            overflow: hidden;
            background: var(--cream);
        }

        .hero-slide {
            height: 620px;
            position: relative;
        }

        .hero-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                90deg,
                rgba(0,0,0,.62) 0%,
                rgba(0,0,0,.28) 45%,
                rgba(0,0,0,.05) 100%
            );
        }

        .hero-content {
            position: absolute;
            left: 8%;
            top: 50%;
            transform: translateY(-50%);
            color: #fff;
            max-width: 600px;
        }

        .hero-small {
            font-size: 12px;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-bottom: 20px;
            color: var(--champagne-light);
        }

        .hero-title {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 62px;
            font-weight: 400;
            line-height: 1.05;
            letter-spacing: 3px;
            margin-bottom: 22px;
        }

        .hero-description {
            font-size: 15px;
            line-height: 1.8;
            max-width: 460px;
            color: rgba(255,255,255,.9);
        }

        .gold-button {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-top: 25px;
            padding: 13px 28px;
            border: 1px solid var(--champagne);
            background: var(--champagne);
            color: #fff;
            text-decoration: none;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: .3s ease;
        }

        .gold-button:hover {
            background: transparent;
            color: #fff;
            border-color: #fff;
        }

        .carousel-indicators [data-bs-target] {
            width: 30px;
            height: 2px;
            border: 0;
            border-radius: 0;
        }

        .carousel-indicators .active {
            background-color: var(--champagne);
        }

        /* ================= INTRO ================= */

        .intro-section {
            padding: 90px 20px;
            text-align: center;
            background: #fff;
        }

        .section-label {
            color: var(--champagne);
            font-size: 10px;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .section-title {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 38px;
            font-weight: 400;
            letter-spacing: 2px;
            margin-bottom: 18px;
        }

        .section-line {
            width: 55px;
            height: 1px;
            background: var(--champagne);
            margin: 0 auto 22px;
        }

        .intro-text {
            max-width: 650px;
            margin: auto;
            color: #777;
            font-size: 14px;
            line-height: 1.9;
        }

        /* ================= CATEGORY ================= */

        .category-section {
            padding: 80px 0;
            background: var(--cream);
        }

        .category-card {
            display: block;
            position: relative;
            height: 330px;
            overflow: hidden;
            text-decoration: none;
            background: #ddd;
        }

        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .7s ease;
        }

        .category-card:hover img {
            transform: scale(1.07);
        }

        .category-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(0,0,0,.62),
                rgba(0,0,0,.03)
            );
        }

        .category-content {
            position: absolute;
            bottom: 25px;
            left: 25px;
            color: #fff;
        }

        .category-content h3 {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 25px;
            font-weight: 400;
            letter-spacing: 1px;
            margin: 0 0 5px;
        }

        .category-content span {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* ================= PRODUCTS ================= */

        .products-section {
            padding: 90px 0;
            background: #fff;
        }

        .product-card {
            position: relative;
            background: #fff;
            height: 100%;
        }

        .product-image-wrapper {
            position: relative;
            overflow: hidden;
            background: var(--cream);
        }

        .product-image {
            width: 100%;
            height: 360px;
            object-fit: cover;
            transition: transform .6s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.045);
        }

        .product-heart {
            position: absolute;
            right: 18px;
            top: 18px;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255,255,255,.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
        }

        .product-info {
            padding: 18px 0 10px;
        }

        .product-category {
            font-size: 9px;
            letter-spacing: 2px;
            color: var(--champagne);
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .product-name {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 18px;
            font-weight: 400;
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 14px;
        }

        .old-price {
            color: #aaa;
            text-decoration: line-through;
            font-size: 12px;
            margin-left: 8px;
        }

        .new-badge {
            position: absolute;
            left: 15px;
            top: 15px;
            background: var(--black);
            color: #fff;
            padding: 6px 10px;
            font-size: 8px;
            letter-spacing: 1.5px;
        }

        .view-all-btn {
            display: inline-block;
            margin-top: 45px;
            padding: 13px 35px;
            border: 1px solid #222;
            color: #222;
            text-decoration: none;
            font-size: 10px;
            letter-spacing: 2px;
            transition: .3s;
        }

        .view-all-btn:hover {
            background: #222;
            color: #fff;
        }

        /* ================= CATALOG ================= */

        .catalog-section {
            padding: 85px 0;
            background: var(--cream);
        }

        .filter-box {
            background: #fff;
            padding: 28px;
            border: 1px solid #eee7dc;
            position: sticky;
            top: 105px;
        }

        .filter-title {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 22px;
            margin-bottom: 25px;
        }

        .filter-heading {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
        }

        .category-link {
            text-decoration: none;
            color: #555;
            font-size: 13px;
            transition: .2s;
        }

        .category-link:hover,
        .category-link.active {
            color: var(--champagne);
        }

        .category-link i {
            font-size: 10px;
        }

        .catalog-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .catalog-count {
            color: #777;
            font-size: 13px;
        }

        #sortSelectUser {
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 0 !important;
            font-size: 12px;
            padding: 8px 30px 8px 12px;
        }

        .btn-gold {
            background: var(--champagne);
            border: 1px solid var(--champagne);
            color: #fff;
            border-radius: 0;
            font-size: 10px;
            letter-spacing: 1.5px;
            padding: 12px 20px;
        }

        .btn-gold:hover {
            background: #b18b52;
            color: #fff;
        }

        /* ================= ABOUT ================= */

        .about-section {
            padding: 100px 20px;
            background: #181818;
            color: #fff;
            text-align: center;
        }

        .about-section .section-title {
            color: #fff;
        }

        .about-text {
            max-width: 750px;
            margin: auto;
            color: #cfcfcf;
            font-size: 14px;
            line-height: 2;
        }

        .about-quote {
            font-family: Georgia, "Times New Roman", serif;
            color: var(--champagne-light);
            font-size: 25px;
            font-style: italic;
            margin-bottom: 30px;
        }

        /* ================= CONTACT ================= */

        .contact-section {
            padding: 90px 0;
            background: var(--cream);
        }

        .contact-card {
            background: #fff;
            padding: 35px 20px;
            text-align: center;
            height: 100%;
            border: 1px solid #eee7dc;
            transition: .3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,.08);
        }

        .contact-card i {
            color: var(--champagne);
            font-size: 22px;
            margin-bottom: 18px;
        }

        .contact-card h6 {
            font-size: 11px;
            letter-spacing: 1.5px;
        }

        .contact-card p {
            color: #777;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 0;
        }

        /* ================= FOOTER ================= */

        .footer {
            background: #111;
            color: #fff;
            padding: 70px 0 25px;
        }

        .footer-brand {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 25px;
            letter-spacing: 4px;
        }

        .footer p,
        .footer a {
            color: #999;
            font-size: 12px;
            line-height: 1.8;
        }

        .footer a {
            text-decoration: none;
            transition: .2s;
        }

        .footer a:hover {
            color: var(--champagne-light);
        }

        .footer-title {
            color: #fff;
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .footer-bottom {
            border-top: 1px solid #2d2d2d;
            margin-top: 45px;
            padding-top: 20px;
            color: #666;
            font-size: 10px;
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 991px) {
            .main-header {
                min-height: 70px;
            }

            .main-menu {
                gap: 0;
                padding: 20px 0;
            }

            .main-menu li {
                width: 100%;
                text-align: center;
            }

            .main-menu a {
                display: inline-block;
            }

            .header-icons {
                gap: 14px;
            }

            .hero-slide {
                height: 480px;
            }

            .hero-title {
                font-size: 44px;
            }

            .filter-box {
                position: static;
                margin-bottom: 35px;
            }
        }

        @media (max-width: 575px) {
            .brand-name {
                font-size: 19px;
                letter-spacing: 3px;
            }

            .brand-subtitle {
                font-size: 6px;
                letter-spacing: 2px;
            }

            .header-icons {
                gap: 10px;
            }

            #navbarSearchInput {
                width: 105px;
            }

            .hero-slide {
                height: 390px;
            }

            .hero-content {
                left: 25px;
                right: 25px;
            }

            .hero-title {
                font-size: 35px;
            }

            .hero-description {
                font-size: 12px;
            }

            .section-title {
                font-size: 30px;
            }

            .category-card {
                height: 280px;
            }

            .product-image {
                height: 300px;
            }

            .catalog-header {
                align-items: flex-start;
                gap: 15px;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<!-- ================= TOP BAR ================= -->

<div class="top-bar">
    Miễn phí giao hàng cho đơn hàng từ 2.000.000 ₫
</div>

<!-- ================= HEADER ================= -->

<header class="lumiere-header">
    <div class="container-fluid px-4">
        <div class="main-header">

            <a href="/index.php?page=home" class="brand-logo me-lg-5">
                <span class="brand-symbol">✦</span>
                <span class="brand-name">LUMIÈRE</span>
                <span class="brand-subtitle">FINE JEWELRY</span>
            </a>

            <button class="navbar-toggler ms-auto me-3 d-lg-none"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#lumiereMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="lumiereMenu">

                <ul class="main-menu mx-auto">

                    <li>
                        <a href="/index.php?page=home">Trang chủ</a>
                    </li>

                    <li>
                        <a href="#collection-section">Bộ sưu tập</a>
                    </li>

                    <li>
                        <a href="#products-section">Sản phẩm</a>
                    </li>

                    <li>
                        <a href="#about-us-section">Về LUMIÈRE</a>
                    </li>

                    <li>
                        <a href="#contact-section">Liên hệ</a>
                    </li>

                </ul>

            </div>

            <div class="header-icons ms-lg-4">

                <div class="search-box">

                    <input
                        type="text"
                        id="navbarSearchInput"
                        placeholder="Tìm kiếm..."
                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                        style="<?php echo isset($_GET['search']) && trim($_GET['search']) !== '' ? 'display:block;' : 'display:none;'; ?>"
                    >

                    <a href="#" class="header-icon" id="navbarSearchBtn">
                        <i class="fas fa-search"></i>
                    </a>

                </div>

                <a href="#" class="header-icon">
                    <i class="far fa-heart"></i>
                </a>

                <a href="/index.php?page=gio_hang"
                   class="header-icon position-relative"
                   id="headerCartBtn">

                    <i class="fas fa-shopping-bag"></i>

                    <span id="headerCartBadge"
                          style="
                            display:none;
                            position:absolute;
                            top:-9px;
                            right:-10px;
                            background:#c9a66b;
                            color:#fff;
                            font-size:9px;
                            font-weight:bold;
                            min-width:16px;
                            height:16px;
                            border-radius:50%;
                            align-items:center;
                            justify-content:center;
                          ">
                        0
                    </span>

                </a>

                <div id="userDropdownWrapper">

                    <a href="#"
                       class="header-icon"
                       id="userIconBtn"
                       onclick="toggleUserDropdown(event)">

                        <i class="far fa-user"></i>

                    </a>

                    <div id="userDropdownMenu">

                        <?php if (
                            isset($_SESSION["user_logged_in"]) &&
                            $_SESSION["user_logged_in"] === true
                        ): ?>

                            <div style="padding:15px 16px;background:#f8f5ef;border-bottom:1px solid #eee7dc;">

                                <div style="font-size:11px;color:#888;">
                                    Xin chào
                                </div>

                                <div style="font-weight:bold;margin-top:4px;">
                                    <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
                                </div>

                            </div>

                            <a href="/index.php?page=profile">
                                <i class="far fa-user"></i>
                                Thông tin cá nhân
                            </a>

                            <?php if (
                                isset($_SESSION["user_role"]) &&
                                $_SESSION["user_role"] === "admin"
                            ): ?>

                                <a href="/index.php?page=admin_dashboard">
                                    <i class="fas fa-user-shield"></i>
                                    Trang quản trị
                                </a>

                            <?php endif; ?>

                            <a href="/index.php?page=don_hang">
                                <i class="fas fa-box"></i>
                                Đơn hàng của tôi
                            </a>

                            <a href="/index.php?page=change_password">
                                <i class="fas fa-lock"></i>
                                Đổi mật khẩu
                            </a>

                            <a href="/index.php?page=logout"
                               style="color:#b33a3a;">
                                <i class="fas fa-sign-out-alt"></i>
                                Đăng xuất
                            </a>

                        <?php else: ?>

                            <a href="/index.php?page=login">
                                <i class="fas fa-sign-in-alt"></i>
                                Đăng nhập
                            </a>

                            <a href="/index.php?page=register">
                                <i class="fas fa-user-plus"></i>
                                Tạo tài khoản
                            </a>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>
    </div>
</header>

<!-- ================= HERO ================= -->

<section class="hero-section">

    <?php if (!empty($bannersList)): ?>

        <div id="homepageCarousel"
             class="carousel slide carousel-fade"
             data-bs-ride="carousel"
             data-bs-interval="5000">

            <div class="carousel-indicators">

                <?php foreach ($bannersList as $index => $banner): ?>

                    <button
                        type="button"
                        data-bs-target="#homepageCarousel"
                        data-bs-slide-to="<?php echo $index; ?>"
                        class="<?php echo $index === 0 ? 'active' : ''; ?>">
                    </button>

                <?php endforeach; ?>

            </div>

            <div class="carousel-inner">

                <?php foreach ($bannersList as $index => $banner): ?>

                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">

                        <div class="hero-slide">

                            <img
                                src="/<?php echo htmlspecialchars($banner['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($banner['title']); ?>"
                            >

                            <div class="hero-overlay"></div>

                            <div class="hero-content">

                                <div class="hero-small">
                                    LUMIÈRE FINE JEWELRY
                                </div>

                                <h1 class="hero-title">
                                    <?php echo htmlspecialchars($banner['title']); ?>
                                </h1>

                                <p class="hero-description">
                                    Khám phá những thiết kế trang sức tinh tế,
                                    được tạo nên để lưu giữ vẻ đẹp của những
                                    khoảnh khắc đặc biệt.
                                </p>

                                <a
                                    href="<?php echo htmlspecialchars($banner['target_link'] ?? '#products-section'); ?>"
                                    class="gold-button">
                                    Khám phá ngay
                                    <i class="fas fa-arrow-right"></i>
                                </a>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#homepageCarousel"
                data-bs-slide="prev">

                <span class="carousel-control-prev-icon"></span>

            </button>

            <button
                class="carousel-control-next"
                type="button"
                data-bs-target="#homepageCarousel"
                data-bs-slide="next">

                <span class="carousel-control-next-icon"></span>

            </button>

        </div>

    <?php else: ?>

        <div class="hero-slide">

            <img
                src="/assets/images/banner.png"
                alt="LUMIÈRE Jewelry">

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <div class="hero-small">
                    LUMIÈRE FINE JEWELRY
                </div>

                <h1 class="hero-title">
                    ELEGANCE<br>
                    THAT SHINES
                </h1>

                <p class="hero-description">
                    Trang sức tinh tế cho những khoảnh khắc
                    bạn muốn lưu giữ mãi mãi.
                </p>

                <a href="#products-section"
                   class="gold-button"
                   id="btnDiscoverNow">
                    Khám phá ngay
                    <i class="fas fa-arrow-right"></i>
                </a>

            </div>

        </div>

    <?php endif; ?>

</section>

<!-- ================= INTRO ================= -->

<section class="intro-section">

    <div class="section-label">
        LUMIÈRE JEWELRY
    </div>

    <h2 class="section-title">
        Vẻ đẹp được chế tác để tỏa sáng
    </h2>

    <div class="section-line"></div>

    <p class="intro-text">
        Mỗi món trang sức tại LUMIÈRE là sự kết hợp giữa
        thiết kế tinh tế, chất liệu chọn lọc và nghệ thuật
        chế tác tỉ mỉ. Chúng tôi tin rằng một món trang sức
        đẹp không chỉ làm bạn nổi bật, mà còn lưu giữ một câu chuyện.
    </p>

</section>

<!-- ================= COLLECTION ================= -->

<section id="collection-section" class="category-section">

    <div class="container">

        <div class="text-center mb-5">

            <div class="section-label">
                OUR COLLECTION
            </div>

            <h2 class="section-title">
                Khám phá bộ sưu tập
            </h2>

            <div class="section-line"></div>

        </div>

        <div class="row g-4">

            <?php if (!empty($categoriesList)): ?>

                <?php
                $categoryImages = [
                    'ring.jpg',
                    'necklace.jpg',
                    'earring.jpg',
                    'bracelet.jpg'
                ];
                ?>

                <?php foreach ($categoriesList as $index => $cat): ?>

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="#category-<?php echo $cat['category_id']; ?>"
                            class="category-card">

                            <img
                                src="/assets/images/<?php echo $categoryImages[$index % count($categoryImages)]; ?>"
                                alt="<?php echo htmlspecialchars($cat['category_name']); ?>"
                            >

                            <div class="category-overlay"></div>

                            <div class="category-content">

                                <h3>
                                    <?php echo htmlspecialchars($cat['category_name']); ?>
                                </h3>

                                <span>
                                    Khám phá →
                                </span>

                            </div>

                        </a>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="col-lg-3 col-md-6">
                    <a href="#products-section" class="category-card">
                        <img src="/assets/images/ring.jpg" alt="Nhẫn">
                        <div class="category-overlay"></div>
                        <div class="category-content">
                            <h3>Nhẫn</h3>
                            <span>Khám phá →</span>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="#products-section" class="category-card">
                        <img src="/assets/images/necklace.jpg" alt="Dây chuyền">
                        <div class="category-overlay"></div>
                        <div class="category-content">
                            <h3>Dây chuyền</h3>
                            <span>Khám phá →</span>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="#products-section" class="category-card">
                        <img src="/assets/images/earring.jpg" alt="Bông tai">
                        <div class="category-overlay"></div>
                        <div class="category-content">
                            <h3>Bông tai</h3>
                            <span>Khám phá →</span>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="#products-section" class="category-card">
                        <img src="/assets/images/bracelet.jpg" alt="Vòng tay">
                        <div class="category-overlay"></div>
                        <div class="category-content">
                            <h3>Vòng tay</h3>
                            <span>Khám phá →</span>
                        </div>
                    </a>
                </div>

            <?php endif; ?>

        </div>

    </div>

</section>

<!-- ================= FEATURED PRODUCTS ================= -->

<section class="products-section">

    <div class="container">

        <div class="text-center mb-5">

            <div class="section-label">
                SIGNATURE COLLECTION
            </div>

            <h2 class="section-title">
                Sản phẩm nổi bật
            </h2>

            <div class="section-line"></div>

        </div>

        <div class="row g-4">

            <?php
            $featuredProducts = array_slice($products ?? [], 0, 4);
            ?>

            <?php if (!empty($featuredProducts)): ?>

                <?php foreach ($featuredProducts as $product): ?>

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="/index.php?page=chi_tiet&id=<?php echo $product["product_id"]; ?>"
                            class="text-decoration-none text-dark">

                            <div class="product-card">

                                <div class="product-image-wrapper">

                                    <img
                                        src="/<?php echo htmlspecialchars($product["main_image"]); ?>"
                                        class="product-image"
                                        alt="<?php echo htmlspecialchars($product["product_name"]); ?>"
                                    >

                                    <span class="new-badge">
                                        FEATURED
                                    </span>

                                    <span
                                        class="product-heart toggle-heart"
                                        data-product-id="<?php echo $product["product_id"]; ?>">

                                        <i class="far fa-heart"></i>

                                    </span>

                                </div>

                                <div class="product-info">

                                    <div class="product-category">
                                        <?php echo htmlspecialchars($product["category_name"] ?? "LUMIÈRE"); ?>
                                    </div>

                                    <div class="product-name">
                                        <?php echo htmlspecialchars($product["product_name"]); ?>
                                    </div>

                                    <div class="product-price">

                                        <?php if (
                                            isset($product["sale_price"]) &&
                                            $product["sale_price"] > 0
                                        ): ?>

                                            <span class="fw-bold gold">
                                                <?php echo number_format($product["sale_price"], 0, ",", "."); ?> ₫
                                            </span>

                                            <span class="old-price">
                                                <?php echo number_format($product["price"], 0, ",", "."); ?> ₫
                                            </span>

                                        <?php else: ?>

                                            <span class="fw-bold">
                                                <?php echo number_format($product["price"], 0, ",", "."); ?> ₫
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>

                        </a>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="col-12 text-center">
                    <p class="text-muted">
                        Hiện chưa có sản phẩm nổi bật.
                    </p>
                </div>

            <?php endif; ?>

        </div>

        <div class="text-center">

            <a href="#products-section"
               class="view-all-btn">
                Xem toàn bộ sản phẩm
            </a>

        </div>

    </div>

</section>

<!-- ================= CATALOG ================= -->

<section id="products-section" class="catalog-section">

    <div class="container">

        <div class="text-center mb-5">

            <div class="section-label">
                LUMIÈRE SHOP
            </div>

            <h2 class="section-title">
                Tất cả sản phẩm
            </h2>

            <div class="section-line"></div>

        </div>

        <div class="row">

            <!-- FILTER -->

            <div class="col-lg-3">

                <div class="filter-box">

                    <div class="filter-title">
                        Bộ lọc
                    </div>

                    <?php $catGet = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0; ?>

                    <div class="mb-4">

                        <div class="filter-heading">
                            Danh mục
                        </div>

                        <ul class="list-unstyled" id="category-filter">

                            <li class="mb-3">

                                <a
                                    href="#all-sections"
                                    class="category-link active">

                                    <i class="fas fa-gem me-2"></i>
                                    Tất cả trang sức

                                </a>

                            </li>

                            <?php if (!empty($categoriesList)): ?>

                                <?php foreach ($categoriesList as $cat): ?>

                                    <li class="mb-3">

                                        <a
                                            href="#category-<?php echo $cat['category_id']; ?>"
                                            class="category-link">

                                            <i class="far fa-gem me-2"></i>

                                            <?php echo htmlspecialchars($cat['category_name']); ?>

                                        </a>

                                    </li>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </ul>

                    </div>

                    <?php $priceGet = isset($_GET['price_range']) ? $_GET['price_range'] : ""; ?>

                    <div class="mb-4">

                        <div class="filter-heading">
                            Mức giá
                        </div>

                        <div class="form-check mb-3">

                            <input
                                class="form-check-input price-filter-cb"
                                type="checkbox"
                                id="price1"
                                value="under10m"
                                <?php echo $priceGet === 'under10m' ? 'checked' : ''; ?>
                            >

                            <label class="form-check-label small" for="price1">
                                Dưới 10.000.000 ₫
                            </label>

                        </div>

                        <div class="form-check mb-3">

                            <input
                                class="form-check-input price-filter-cb"
                                type="checkbox"
                                id="price2"
                                value="10m-25m"
                                <?php echo $priceGet === '10m-25m' ? 'checked' : ''; ?>
                            >

                            <label class="form-check-label small" for="price2">
                                10.000.000 ₫ - 25.000.000 ₫
                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                class="form-check-input price-filter-cb"
                                type="checkbox"
                                id="price3"
                                value="over25m"
                                <?php echo $priceGet === 'over25m' ? 'checked' : ''; ?>
                            >

                            <label class="form-check-label small" for="price3">
                                Trên 25.000.000 ₫
                            </label>

                        </div>

                    </div>

                    <button
                        class="btn btn-gold w-100 mb-3"
                        id="btnApplyFilterUser">

                        Áp dụng bộ lọc

                    </button>

                    <div class="text-center">

                        <a
                            href="/index.php?page=home"
                            class="text-muted text-decoration-none"
                            style="font-size:12px;">

                            Xóa bộ lọc

                        </a>

                    </div>

                </div>

            </div>

            <!-- PRODUCTS -->

            <div class="col-lg-9">

                <div class="catalog-header">

                    <div class="catalog-count">

                        Hiển thị
                        <strong><?php echo count($products); ?></strong>
                        sản phẩm

                    </div>

                    <div class="d-flex align-items-center gap-2">

                        <span style="font-size:12px;">
                            Sắp xếp
                        </span>

                        <?php $sortGet = isset($_GET['sort']) ? $_GET['sort'] : "newest"; ?>

                        <select
                            id="sortSelectUser"
                            class="form-select form-select-sm">

                            <option
                                value="newest"
                                <?php echo $sortGet === 'newest' ? 'selected' : ''; ?>>
                                Nổi bật nhất
                            </option>

                            <option
                                value="price_asc"
                                <?php echo $sortGet === 'price_asc' ? 'selected' : ''; ?>>
                                Giá thấp đến cao
                            </option>

                            <option
                                value="price_desc"
                                <?php echo $sortGet === 'price_desc' ? 'selected' : ''; ?>>
                                Giá cao đến thấp
                            </option>

                        </select>

                    </div>

                </div>

                <div id="all-sections">

                    <?php

                    $groupedProducts = [];

                    if (!empty($products)) {

                        foreach ($products as $product) {

                            $catId = $product["category_id"] ?? 0;

                            $catName = $product["category_name"] ?? "Trang Sức Khác";

                            $groupedProducts[$catId]["name"] = $catName;

                            $groupedProducts[$catId]["products"][] = $product;

                        }

                    }

                    ?>

                    <?php if (!empty($groupedProducts)): ?>

                        <?php foreach ($groupedProducts as $catId => $group): ?>

                            <div
                                id="category-<?php echo $catId; ?>"
                                class="category-section mb-5 pt-4"
                                style="background:transparent;padding:20px 0;">

                                <div class="d-flex justify-content-between align-items-center mb-4">

                                    <div>

                                        <div class="section-label mb-1">
                                            COLLECTION
                                        </div>

                                        <h3
                                            class="serif mb-0"
                                            style="font-size:28px;font-weight:400;">

                                            <?php echo htmlspecialchars($group["name"]); ?>

                                        </h3>

                                    </div>

                                </div>

                                <div class="row g-4">

                                    <?php foreach ($group["products"] as $product): ?>

                                        <div class="col-md-6 col-xl-4 product-item">

                                            <a
                                                href="/index.php?page=chi_tiet&id=<?php echo $product["product_id"]; ?>"
                                                class="text-decoration-none text-dark">

                                                <div class="product-card">

                                                    <div class="product-image-wrapper">

                                                        <img
                                                            src="/<?php echo htmlspecialchars($product["main_image"]); ?>"
                                                            class="product-image"
                                                            alt="<?php echo htmlspecialchars($product["product_name"]); ?>"
                                                        >

                                                        <span
                                                            class="product-heart toggle-heart"
                                                            data-product-id="<?php echo $product["product_id"]; ?>">

                                                            <i class="far fa-heart"></i>

                                                        </span>

                                                    </div>

                                                    <div class="product-info">

                                                        <div class="product-category">

                                                            <?php echo htmlspecialchars($group["name"]); ?>

                                                        </div>

                                                        <div class="product-name">

                                                            <?php echo htmlspecialchars($product["product_name"]); ?>

                                                        </div>

                                                        <div class="product-price">

                                                            <?php if (
                                                                isset($product["sale_price"]) &&
                                                                $product["sale_price"] > 0
                                                            ): ?>

                                                                <span class="fw-bold gold">

                                                                    <?php
                                                                    echo number_format(
                                                                        $product["sale_price"],
                                                                        0,
                                                                        ",",
                                                                        "."
                                                                    );
                                                                    ?>

                                                                    ₫

                                                                </span>

                                                                <span class="old-price">

                                                                    <?php
                                                                    echo number_format(
                                                                        $product["price"],
                                                                        0,
                                                                        ",",
                                                                        "."
                                                                    );
                                                                    ?>

                                                                    ₫

                                                                </span>

                                                            <?php else: ?>

                                                                <span class="fw-bold">

                                                                    <?php
                                                                    echo number_format(
                                                                        $product["price"],
                                                                        0,
                                                                        ",",
                                                                        "."
                                                                    );
                                                                    ?>

                                                                    ₫

                                                                </span>

                                                            <?php endif; ?>

                                                        </div>

                                                    </div>

                                                </div>

                                            </a>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="text-center py-5">

                            <i
                                class="far fa-gem"
                                style="font-size:40px;color:#c9a66b;">
                            </i>

                            <p class="text-muted mt-3">
                                Hiện tại chưa có sản phẩm phù hợp.
                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= ABOUT ================= -->

<section id="about-us-section" class="about-section">

    <div class="container">

        <div class="section-label">
            OUR STORY
        </div>

        <h2 class="section-title">
            Về LUMIÈRE
        </h2>

        <div class="section-line"></div>

        <div class="about-quote">
            "Vẻ đẹp không chỉ được nhìn thấy,
            mà còn được cảm nhận."
        </div>

        <p class="about-text">

            LUMIÈRE JEWELRY được tạo nên từ niềm tin rằng
            trang sức là một phần của những câu chuyện đẹp.
            Mỗi thiết kế được lựa chọn và chăm chút với mong muốn
            mang đến sự tinh tế, tự tin và dấu ấn riêng cho người sở hữu.

            <br><br>

            Từ những thiết kế tối giản hằng ngày đến những món trang sức
            dành cho các dịp đặc biệt, LUMIÈRE theo đuổi vẻ đẹp thanh lịch,
            hiện đại và vượt thời gian.

        </p>

    </div>

</section>

<!-- ================= CONTACT ================= -->

<section id="contact-section" class="contact-section">

    <div class="container">

        <div class="text-center mb-5">

            <div class="section-label">
                CONTACT
            </div>

            <h2 class="section-title">
                Liên hệ với chúng tôi
            </h2>

            <div class="section-line"></div>

        </div>

        <div class="row g-4">

            <div class="col-md-4">

                <div class="contact-card">

                    <i class="fas fa-location-dot"></i>

                    <h6>
                        SHOWROOM
                    </h6>

                    <p>
                        123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="contact-card">

                    <i class="fas fa-phone"></i>

                    <h6>
                        HOTLINE
                    </h6>

                    <p>
                        1900 123 456
                        <br>
                        0987 654 321
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="contact-card">

                    <i class="fas fa-envelope"></i>

                    <h6>
                        EMAIL
                    </h6>

                    <p>
                        hello@lumiere.vn
                        <br>
                        support@lumiere.vn
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= FOOTER ================= -->

<footer class="footer">

    <div class="container">

        <div class="row">

            <div class="col-lg-4 mb-4">

                <div class="footer-brand">
                    ✦ LUMIÈRE
                </div>

                <p class="mt-3" style="max-width:350px;">

                    Điểm đến của những thiết kế trang sức
                    tinh tế dành cho những khoảnh khắc
                    đáng nhớ nhất.

                </p>

                <div class="d-flex gap-3 mt-4">

                    <a href="#">
                        <i class="fab fa-facebook-f"></i>
                    </a>

                    <a href="#">
                        <i class="fab fa-instagram"></i>
                    </a>

                    <a href="#">
                        <i class="fab fa-tiktok"></i>
                    </a>

                    <a href="#">
                        <i class="fab fa-pinterest-p"></i>
                    </a>

                </div>

            </div>

            <div class="col-lg-2 col-md-4 mb-4">

                <div class="footer-title">
                    Khám phá
                </div>

                <p>
                    <a href="#collection-section">
                        Bộ sưu tập
                    </a>
                </p>

                <p>
                    <a href="#products-section">
                        Sản phẩm
                    </a>
                </p>

                <p>
                    <a href="#about-us-section">
                        Về LUMIÈRE
                    </a>
                </p>

            </div>

            <div class="col-lg-3 col-md-4 mb-4">

                <div class="footer-title">
                    Hỗ trợ
                </div>

                <p>
                    <a href="#">
                        Chính sách giao hàng
                    </a>
                </p>

                <p>
                    <a href="#">
                        Chính sách đổi trả
                    </a>
                </p>

                <p>
                    <a href="#">
                        Chính sách bảo mật
                    </a>
                </p>

            </div>

            <div class="col-lg-3 col-md-4 mb-4">

                <div class="footer-title">
                    Liên hệ
                </div>

                <p>
                    1900 123 456
                </p>

                <p>
                    hello@lumiere.vn
                </p>

                <p>
                    TP. Hồ Chí Minh, Việt Nam
                </p>

            </div>

        </div>

        <div class="footer-bottom text-center">

            © 2026 LUMIÈRE JEWELRY
            · Thiết kế bởi Nhóm 6

        </div>

    </div>

</footer>

<!-- ================= SCRIPTS ================= -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="/assets/js/cart.js"></script>

<script src="/assets/js/main.js"></script>

<script src="/assets/js/chat.js"></script>

<script>

function toggleUserDropdown(e) {

    e.preventDefault();
    e.stopPropagation();

    const menu = document.getElementById("userDropdownMenu");

    if (menu.style.display === "block") {
        menu.style.display = "none";
    } else {
        menu.style.display = "block";
    }

}

document.addEventListener("click", function(e) {

    const wrapper = document.getElementById("userDropdownWrapper");

    const menu = document.getElementById("userDropdownMenu");

    if (
        wrapper &&
        menu &&
        !wrapper.contains(e.target)
    ) {

        menu.style.display = "none";

    }

});

</script>

</body>
</html>