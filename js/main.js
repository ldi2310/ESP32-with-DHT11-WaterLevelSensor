document.addEventListener("DOMContentLoaded", function() {
    // Chọn nút "START" bằng id
    var startButton = document.getElementById("startButton");

    // Thêm sự kiện click cho nút "START"
    startButton.addEventListener("click", function() {
        // Chuyển hướng đến màn hình tiếp theo, có thể là một URL hoặc đường dẫn tới file HTML
        window.location.href = "screen.php"; // Thay "screen.html" bằng đường dẫn tới màn hình tiếp theo
    });
});