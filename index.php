<?php
session_start();
// Kết nối Database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "chickenjoy";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Kết nối database thất bại']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Truy vấn kiểm tra email, password và PHẢI là admin
    $sql = "SELECT * FROM users WHERE email = ? AND password = ? AND role = 'admin' AND status = 'active' LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['fullname'];
        
        echo json_encode(['success' => true, 'message' => 'Đăng nhập thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Sai tài khoản hoặc bạn không có quyền truy cập!']);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="assets/images/logo-1.png" />
    <title>ChickenJoy Admin | Đăng nhập</title>
    <link rel="stylesheet" href="assets/css/admin.css" />
</head>

<body class="login-page">

    <div class="login-box">
        <div class="login-header">
            <img src="assets/images/logo.png" alt="ChickenJoy Logo" class="logo" />
            <h2>Đăng nhập quản trị</h2>
        </div>

        <form id="loginForm" class="login-form">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập..." required />
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu..." required />
            </div>

            <p id="errorMsg" class="error-msg"></p>

            <button type="submit" class="btn-primary">Đăng nhập</button>
        </form>

        <footer>
            <p>© 2025 ChickenJoy Admin</p>
        </footer>
    </div>

    <script src="assets/js/login.js"></script>
</body>

</html>