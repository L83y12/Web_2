<?php
require_once '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// thông tin đơn
$sql = "SELECT o.*, u.fullname 
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.id = $id";

$order = mysqli_fetch_assoc(mysqli_query($conn, $sql));
if (!$order) {
    echo "<h2>Không tìm thấy đơn hàng</h2>";
    exit;
}

// chi tiết sản phẩm
$sqlDetail = "SELECT od.*, p.product_name
              FROM order_details od
              JOIN products p ON od.product_id = p.id
              WHERE od.order_id = $id";

$details = mysqli_query($conn, $sqlDetail) or die(mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title> ChickenJoy Admin | Quản Lý Đơn Đặt Hàng</title>

    <link rel="icon" type="image/png" href="../assets/images/logo-1.png" />
    <link rel="stylesheet" href="../assets/css/admin.css" />
</head>

<body class="admin-body">

    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../assets/images/logo.png" alt="Logo" class="logo-img">
            <h2><span> ChickenJoy</span></h2>
        </div>
        <nav class="sidebar-menu">
            <a href="../admin/dashboard.php">
                <img src="../assets/images/icons/home.png" alt="Trang chủ">
                <span>Trang chủ</span>
            </a>
            <a href="../admin/user-management.php">
                <img src="../assets/images/icons/user-add.png" alt="Quản lý người dùng">
                <span>Quản lý người dùng</span>
            </a>
            <a href="../admin/category.php">
                <img src="../assets/images/icons/burger.png" alt="Loại sản phẩm">
                <span>Loại sản phẩm</span>
            </a>
            <a href="../admin/list.php">
                <img src="../assets/images/icons/app.png" alt="Danh mục sản phẩm">
                <span>Danh mục sản phẩm</span>
            </a>
            <a href="../admin/import.php">
                <img src="../assets/images/icons/import.png" alt="Nhập hàng">
                <span>Nhập hàng</span>
            </a>

            <a href="../admin/price.php">
                <img src="../assets/images/icons/dollar.png" alt="Giá bán">
                <span>Giá bán</span>
            </a>

            <a href="../admin/order-management.php" class="active">
                <img src="../assets/images/icons/order-history.png" alt="Quản lý đơn đặt hàng">
                <span>Quản lý đơn đặt hàng</span>
            </a>
            <a href="../admin/inventory.php">
                <img src="../assets/images/icons/inventory-alt.png" alt="Quản lý tồn kho">
                <span>Quản lý tồn kho</span>
            </a>
            <div class="sidebar-footer">
                <a href="../index.php" class="logout-btn">
                    <span>Đăng xuất</span>
                </a>
            </div>
        </nav>
    </aside>

    <main class="main-content">

        <header class="main-header">
            <h1>Chi Tiết Đơn Hàng</h1>
        </header>

        <a href="order-management.php" class="back-btn">
            <img src="../assets/images/icons/arrow-small-left.png" alt="quay lại" class="icon"> Quay lại
        </a>
        <div class="detail-card">
            <p><strong>Mã đơn:</strong> DH<?= str_pad($order['id'], 3, '0', STR_PAD_LEFT) ?></p>

            <p><strong>Khách hàng:</strong> <?= $order['fullname'] ?></p>

            <p><strong>Ngày đặt:</strong> <?= date('d/m/Y', strtotime($order['order_date'])) ?></p>

            <p><strong>Tổng tiền:</strong> <?= number_format($order['total_price']) ?>đ</p>

            <p><strong>Tình trạng:</strong>
                <?php
                $class = "";
                switch ($order['status']) {
                    case 'pending':
                        $statusText = "Chưa xử lý";
                        $class = "pending";
                        break;
                    case 'confirmed':
                        $statusText = "Đã xác nhận";
                        $class = "confirmed";
                        break;
                    case 'delivered':
                        $statusText = "Đã giao";
                        $class = "delivered";
                        break;
                    case 'cancelled':
                        $statusText = "Đã huỷ";
                        $class = "cancelled";
                        break;
                }
                ?>

                <span class="status <?= $class ?>">
                    <?= $statusText ?>
                </span>
            </p>
            <!-- <h3>Danh sách món</h3>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Món</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($details)) { ?>
                        <tr>
                            <td><?= $item['product_name'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['price_at_purchase']) ?>đ</td>
                            <td><?= number_format($item['quantity'] * $item['price_at_purchase']) ?>đ</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table> -->
        </div>

    </main>
</body>

</html>