<?php

class ProductController
{
    private $conn;

    public function __construct()
    {
        require_once BASE_PATH . "/config/database.php";

        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function showChiTiet($product_id)
    {
        if (!$product_id) {
            header("Location: /index.php?page=home");
            exit();
        }

        $conn = $this->conn;

        // Lấy thông tin sản phẩm
        $stmt = $conn->prepare(
            "SELECT *
             FROM v_product_details
             WHERE product_id = :id
             AND status = 'show'"
        );

        $stmt->execute([
            ":id" => $product_id
        ]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            header("Location: /index.php?page=home");
            exit();
        }

        // Lấy ảnh phụ của sản phẩm
        $stmt2 = $conn->prepare(
            "SELECT *
             FROM product_images
             WHERE product_id = :id"
        );

        $stmt2->execute([
            ":id" => $product_id
        ]);

        $extraImages = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        // Lấy danh sách đánh giá đã được duyệt
        $stmtReviews = $conn->prepare(
            "SELECT 
                r.*,
                u.full_name
             FROM reviews r
             JOIN users u ON r.user_id = u.user_id
             WHERE r.product_id = :pid
             AND r.status = 'approved'
             ORDER BY r.review_id DESC"
        );

        $stmtReviews->execute([
            ":pid" => $product_id
        ]);

        $reviewsList = $stmtReviews->fetchAll(PDO::FETCH_ASSOC);

        // Tính điểm trung bình
        $totalReviews = count($reviewsList);
        $avgRating = 0;

        if ($totalReviews > 0) {
            $sum = 0;

            foreach ($reviewsList as $review) {
                $sum += (int) $review["rating"];
            }

            $avgRating = round($sum / $totalReviews, 1);
        }

        require_once BASE_PATH . "/app/views/user/product-details.php";
    }

    public function submitReview()
    {
        // Kiểm tra đăng nhập
        if (
            !isset($_SESSION["user_logged_in"]) ||
            $_SESSION["user_logged_in"] !== true
        ) {
            header("Location: /index.php?page=login");
            exit();
        }

        // Lấy user_id từ session
        $user_id = (int) ($_SESSION["user_id"] ?? 0);

        // Lấy dữ liệu từ form
        $product_id = (int) ($_POST["product_id"] ?? 0);
        $rating = (int) ($_POST["rating"] ?? 0);
        $comment = trim($_POST["comment"] ?? "");

        // Kiểm tra user
        if ($user_id <= 0) {
            header("Location: /index.php?page=login");
            exit();
        }

        // Kiểm tra product
        if ($product_id <= 0) {
            header("Location: /index.php?page=home");
            exit();
        }

        // Kiểm tra số sao
        if ($rating < 1 || $rating > 5) {
            header(
                "Location: /index.php?page=chi_tiet&id=" .
                $product_id .
                "&review=invalid"
            );
            exit();
        }

        // Kiểm tra nội dung
        if ($comment === "") {
            header(
                "Location: /index.php?page=chi_tiet&id=" .
                $product_id .
                "&review=empty"
            );
            exit();
        }

        try {
            // Kiểm tra sản phẩm có tồn tại không
            $stmtProduct = $this->conn->prepare(
                "SELECT product_id
                 FROM products
                 WHERE product_id = :pid"
            );

            $stmtProduct->execute([
                ":pid" => $product_id
            ]);

            $product = $stmtProduct->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                header("Location: /index.php?page=home");
                exit();
            }

            // Kiểm tra người dùng đã đánh giá sản phẩm chưa
            $stmtCheck = $this->conn->prepare(
                "SELECT review_id
                 FROM reviews
                 WHERE user_id = :uid
                 AND product_id = :pid"
            );

            $stmtCheck->execute([
                ":uid" => $user_id,
                ":pid" => $product_id
            ]);

            if ($stmtCheck->fetch(PDO::FETCH_ASSOC)) {
                header(
                    "Location: /index.php?page=chi_tiet&id=" .
                    $product_id .
                    "&review=exists"
                );
                exit();
            }

            // Thêm đánh giá
            $stmtInsert = $this->conn->prepare(
                "INSERT INTO reviews
                    (user_id, product_id, rating, comment, status)
                 VALUES
                    (:uid, :pid, :rating, :comment, 'pending')"
            );

            $stmtInsert->execute([
                ":uid" => $user_id,
                ":pid" => $product_id,
                ":rating" => $rating,
                ":comment" => $comment
            ]);

            // Gửi thành công
            header(
                "Location: /index.php?page=chi_tiet&id=" .
                $product_id .
                "&review=success"
            );
            exit();

        } catch (PDOException $e) {
            header(
                "Location: /index.php?page=chi_tiet&id=" .
                $product_id .
                "&review=error"
            );
            exit();
        }
    }
}