document.addEventListener('DOMContentLoaded', function() {
    var loginForm = document.getElementById('loginForm');
    var errorMsg = document.getElementById('errorMsg');

    loginForm.onsubmit = function(e) {
        e.preventDefault();

        // Lấy dữ liệu từ form (nhớ đổi id/name input trong HTML thành 'email')
        var email = document.getElementById('username').value; 
        var password = document.getElementById('password').value;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'auth.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    window.location.href = 'admin/dashboard.php'; // Chuyển đến trang quản trị
                } else {
                    errorMsg.innerText = response.message;
                    errorMsg.style.display = 'block';
                }
            }
        };

        // Gửi dữ liệu
        xhr.send('email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password));
    };
});