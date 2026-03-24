<?php
require_once '../config/database.php';

// 1. Xử lý thêm người dùng
if (isset($_POST['add_user'])) {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $address = "Chưa cập nhật";

    $sql = "INSERT INTO users(fullname, email, phone, password, address)
            VALUES('$name', '$email', '$phone', '$password', '$address')";
    mysqli_query($conn, $sql);
    header("Location: user-management.php");
    exit();
}

// 2. XỬ LÝ CHỨC NĂNG TÌM KIẾM
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// 3. Lấy danh sách user (Có lọc theo tìm kiếm)
if (!empty($search)) {
    // Tìm theo tên hoặc email hoặc số điện thoại
    $sql = "SELECT * FROM users WHERE 
            fullname LIKE '%$search%' OR 
            email LIKE '%$search%' OR 
            phone LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM users";
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="../assets/images/logo-1.png" />
    <title> ChickenJoy Admin | Quản Lý Người Dùng</title>
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
            <a href="../admin/user-management.php" class="active">
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

            <a href="../admin/order-management.php">
                <img src="../assets/images/icons/order-history.png" alt="Quản lý đơn đặt hàng">
                <span>Quản lý đơn đặt hàng</span>
            </a>
            <a href="../admin/inventory.php">
                <img src="../assets/images/icons/inventory-alt.png" alt="Quản lý tồn kho">
                <span>Quản lý tồn kho</span>
            </a>
            <div class="sidebar-footer">
                <a href="../index.html" class="logout-btn">
                    <span>Đăng xuất</span>
                </a>
            </div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <h1>Quản Lý Người Dùng</h1>
            <div class="header-actions">
                <a href="#" class="btn-primary" onclick="openModal()">+ Thêm người dùng</a>
            </div>
        </header>

        <div class="table-toolbar">
            <form method="GET" action="user-management.php" style="width: 100%;">
                <input type="text" name="search" placeholder="🔍 Tìm kiếm tên, email hoặc SĐT..."
                    value="<?= htmlspecialchars($search) ?>" />
                <button type="submit" style="display: none;">Tìm kiếm</button>
            </form>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Mật khẩu</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { 
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['fullname'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['password'] ?></td>
                    <td>
                        <?php if ($row['status'] == 'active') { ?>
                        <span class="status active">Hoạt động</span>
                        <?php } else { ?>
                        <span class="status hidden">Bị khóa</span>
                        <?php } ?>
                    </td>
                    <td>
                        <div class="actions">
                            <?php if ($row['status'] == 'active') { ?>
                            <a href="lock-user.php?id=<?= $row['id'] ?>" class="btn-hide">Khóa</a>
                            <?php } else { ?>
                            <a href="unlock-user.php?id=<?= $row['id'] ?>" class="btn-show">Mở khóa</a>
                            <?php } ?>
                            <a href="reset-password.php?id=<?= $row['id'] ?>" class="btn-edit">Reset</a>
                        </div>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='7' style='text-align:center;'>Không tìm thấy người dùng nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <div id="userModal" class="modal">
        <div class="modal-content">
            <h2>Thêm người dùng</h2>
            <form method="POST">
                <input type="text" name="fullname" placeholder="Họ tên" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="SĐT" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <button type="submit" name="add_user">Thêm</button>
                <button type="button" onclick="closeModal()">Hủy</button>
            </form>
        </div>
    </div>

    <script>
    function openModal() {
        document.getElementById("userModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("userModal").style.display = "none";
    }
    </script>
</body>

</html>