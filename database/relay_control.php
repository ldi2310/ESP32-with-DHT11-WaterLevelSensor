<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "doancoso1";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý yêu cầu GET để lấy trạng thái của các relay
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT state1, state2, mode1, mode2 FROM relay_control WHERE id = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = [
            "state1" => $row["state1"],
            "state2" => $row["state2"],
            "mode1" => $row["mode1"],
            "mode2" => $row["mode2"]
        ];
        echo json_encode($response);
    } else {
        echo json_encode(["error" => "No records found"]);
    }
}

// Đóng kết nối
$conn->close();
?>
