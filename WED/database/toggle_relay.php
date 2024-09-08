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

// Xử lý khi nút bấm hoặc switch được nhấn
if(isset($_POST['button1']) || isset($_POST['button2']) || isset($_POST['switch1']) || isset($_POST['switch2'])) {
    // Xác định nút hoặc switch được nhấn
    if (isset($_POST['button1'])) {
        $buttonName = 'button1';
        $stateName = 'state1';
        $modeName = 'mode1';
    } elseif (isset($_POST['button2'])) {
        $buttonName = 'button2';
        $stateName = 'state2';
        $modeName = 'mode2';
    } elseif (isset($_POST['switch1'])) {
        $buttonName = 'switch1';
        $stateName = 'state1';
        $modeName = 'mode1';
    } elseif (isset($_POST['switch2'])) {
        $buttonName = 'switch2';
        $stateName = 'state2';
        $modeName = 'mode2';
    }

    // Lấy trạng thái và chế độ hiện tại của relay từ cơ sở dữ liệu
    $sql = "SELECT $stateName, $modeName FROM relay_control WHERE id = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentState = $row[$stateName];
        $currentMode = $row[$modeName];

        // Toggle chế độ hoặc trạng thái dựa trên loại button/switch
        if ($buttonName == 'button1' || $buttonName == 'button2') {
            $newMode = ($currentMode == "manual") ? "auto" : "manual";
            $sql = "UPDATE relay_control SET $modeName='$newMode' WHERE id=1";
        } else {
            $newState = ($currentState == "on") ? "off" : "on";
            $sql = "UPDATE relay_control SET $stateName='$newState' WHERE id=1";
        }

        if ($conn->query($sql) === TRUE) {
            // Trả về thông tin trạng thái và chế độ mới
            $response = array("state" => $currentState, "mode" => $currentMode);
            echo json_encode($response);
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "No records found";
    }
}

// Đóng kết nối
$conn->close();
?>
