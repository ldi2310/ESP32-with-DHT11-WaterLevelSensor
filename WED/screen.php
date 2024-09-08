<?php
    include_once('database/esp-database.php');
    if (isset($_GET["readingsCount"])){
      $data = $_GET["readingsCount"];
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      $readings_count = $_GET["readingsCount"];
    }
    // default readings count set to 20
    else {
      $readings_count = 20;
    }

    $last_reading = getLastReadings();
    $last_reading_temp = $last_reading["value1"];
    $last_reading_humi = $last_reading["value2"];
    $last_reading_walv = $last_reading["value3"];
    $last_reading_time = $last_reading["reading_time"];

    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
    //$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time - 1 hours"));
    // Uncomment to set timezone to + 7 hours (you can change 7 to any number)
    //$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time + 7 hours"));

    $min_temp = minReading($readings_count, 'value1');
    $max_temp = maxReading($readings_count, 'value1');
    $avg_temp = avgReading($readings_count, 'value1');

    $min_humi = minReading($readings_count, 'value2');
    $max_humi = maxReading($readings_count, 'value2');
    $avg_humi = avgReading($readings_count, 'value2');

    $min_walv = minReading($readings_count, 'value3');
    $max_walv = maxReading($readings_count, 'value3');
    $avg_walv = avgReading($readings_count, 'value3');
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="css/screen.css">
    <link rel="stylesheet" href="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="sidebar">
  <a class="active" href="main.html">Home</a>
  <a href="#news">News</a>
  <a href="aboutUs.html">Contact</a>
  <a href="#about">About</a>
</div>

<div class="main">

    <div class="read">
        <h1> ESP Weather Station</h1>
        <form class="vl" method="get">
            <input type="number" name="readingsCount" min="1" placeholder="Number of readings (<?php echo $readings_count; ?>)">
            <input type="submit" value="UPDATE">
        </form>
        <p>Last reading: <?php echo $last_reading_time; ?></p>
    </div>

    <section class="content">
        <div class="gauge--1">
            <div class="banner">
                <h3>TEMPERATURE</h3>
                <div class="mask">
                    <div class="semi-circle"></div>
                    <div class="semi-circle--mask"></div>
                </div>
                <p style="font-size: 30px;" id="temp">--</p>
                <table cellspacing="5" cellpadding="5">
                    <tr>
                        <th colspan="3">Temperature <?php echo $readings_count; ?> readings</th>
                    </tr>
                    <tr>
                        <td>Min</td>
                        <td>Max</td>
                        <td>Average</td>
                    </tr>
                    <tr>
                        <td><?php echo $min_temp['min_amount']; ?> &deg;C</td>
                        <td><?php echo $max_temp['max_amount']; ?> &deg;C</td>
                        <td><?php echo round($avg_temp['avg_amount'], 2); ?> &deg;C</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="gauge--2">
            <div class="banner">
                <h3>HUMIDITY</h3>
                <div class="mask">
                    <div class="semi-circle"></div>
                    <div class="semi-circle--mask"></div>
                </div>
                <p style="font-size: 30px;" id="humi">--</p>
                <table cellspacing="5" cellpadding="5">
                    <tr>
                        <th colspan="3">Humidity <?php echo $readings_count; ?> readings</th>
                    </tr>
                    <tr>
                        <td>Min</td>
                        <td>Max</td>
                        <td>Average</td>
                    </tr>
                    <tr>
                        <td><?php echo $min_humi['min_amount']; ?> %</td>
                        <td><?php echo $max_humi['max_amount']; ?> %</td>
                        <td><?php echo round($avg_humi['avg_amount'], 2); ?> %</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="gauge--3">
            <div class="banner">
                <h3>WATER LEVEL</h3>
                <div class="bench">
                    <div class="box">
                        <div class="glass">
                            <div class="bor"></div>
                            <div class="wave"></div>
                            <img src="images/plant.png">
                        </div>
                        <img src="images/plant.png">
                    </div>
                </div>
                <p style="font-size: 30px;" id="walv">--</p>
                <table cellspacing="5" cellpadding="5">
                    <tr>
                        <th colspan="3">Water level <?php echo $readings_count; ?> readings</th>
                    </tr>
                    <tr>
                        <td>Min</td>
                        <td>Max</td>
                        <td>Average</td>
                    </tr>
                    <tr>
                        <td><?php echo $min_walv['min_amount']; ?> %</td>
                        <td><?php echo $max_walv['max_amount']; ?> %</td>
                        <td><?php echo round($avg_walv['avg_amount'], 2); ?> %</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>

        <div class="chart-container1">
            <canvas id="myChart1"></canvas>
        </div>
        
        <div class="btn1">
            <h3>Control Pump</h3>
            <div id="toggleButton1">
                <i class="indicator1"></i>
            </div>
            
            <div id="switchContainer1">
                <i class="switch1"></i>
            </div>
        </div>

        <div class="btn2">
            <h3>Control Fan</h3>
            <div id="toggleButton2">
                <i class="indicator2"></i>
            </div>

            <div id="switchContainer2">
                <i class="switch2"></i>
            </div>
        </div> 
    </section>

    <div class="container">
        <h2> View Latest <?php echo $readings_count; ?> Readings</h2>
        <table cellspacing="5" cellpadding="5" id="tableReadings">
            <tr>
                <th>ID</th>
                <th>Sensor</th>
                <th>Location</th>
                <th>Temperature</th>
                <th>Humidity</th>
                <th>Water Level</th>
                <th>Timestamp</th>
            </tr>
            <?php
            $result = getAllReadings($readings_count);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $row_id = $row["id"];
                    $row_sensor = $row["sensor"];
                    $row_location = $row["location"];
                    $row_value1 = $row["value1"];
                    $row_value2 = $row["value2"];
                    $row_value3 = $row["value3"];
                    $row_reading_time = $row["reading_time"];
                    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
                    //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 1 hours"));
                    // Uncomment to set timezone to + 7 hours (you can change 7 to any number)
                    //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time + 7 hours"));

                    echo '<tr>
                            <td>' . $row_id . '</td>
                            <td>' . $row_sensor . '</td>
                            <td>' . $row_location . '</td>
                            <td>' . $row_value1 . '</td>
                            <td>' . $row_value2 . '</td>
                            <td>' . $row_value3 . '</td>
                            <td>' . $row_reading_time . '</td>
                        </tr>';
                }
                $result->free();
            }
            ?>
        </table>
    </div>
</div>

<script>
    var value1 = <?php echo $last_reading_temp; ?>;
    var value2 = <?php echo $last_reading_humi; ?>;
    var value3 = <?php echo $last_reading_walv; ?>;
    setTemperature(value1);
    setHumidity(value2);
    setWaterLevel(value3);

    function setTemperature(curVal){
        //set range for Temperature in Celsius -5 Celsius to 38 Celsius
        var minTemp = -5.0;
        var maxTemp = 38.0;
        //set range for Temperature in Fahrenheit 23 Fahrenheit to 100 Fahrenheit
        //var minTemp = 23;
        //var maxTemp = 100;

        var newVal = scaleValue(curVal, [minTemp, maxTemp], [0, 180]);
        $('.gauge--1 .semi-circle--mask').attr({
            style: '-webkit-transform: rotate(' + newVal + 'deg);' +
                '-moz-transform: rotate(' + newVal + 'deg);' +
                'transform: rotate(' + newVal + 'deg);'
        });
        $("#temp").text(curVal + ' ºC');
    }

    function setHumidity(curVal){
        //set range for Humidity percentage 0 % to 100 %
        var minHumi = 0;
        var maxHumi = 100;

        var newVal = scaleValue(curVal, [minHumi, maxHumi], [0, 180]);
        $('.gauge--2 .semi-circle--mask').attr({
            style: '-webkit-transform: rotate(' + newVal + 'deg);' +
                '-moz-transform: rotate(' + newVal + 'deg);' +
                'transform: rotate(' + newVal + 'deg);'
        });
        $("#humi").text(curVal + ' %');
    }

    function setWaterLevel(curVal){
        //set range for Water level percentage 0 % to 100 %
        var minWalv = 0;
        var maxWalv = 100;

        var translateYVal = 50 - (curVal * 1.0);

        // Update the water level text
        $("#walv").text(curVal + ' %');

        // Update the translateY value of the wave
        $(".wave").css("transform", "translateY(" + translateYVal + "%)");
        $("#walv").text(curVal + ' %');
    }

    function scaleValue(value, from, to) {
        var scale = (to[1] - to[0]) / (from[1] - from[0]);
        var capped = Math.min(from[1], Math.max(from[0], value)) - from[0];
        return ~~(capped * scale + to[0]);
    }

    document.getElementById('toggleButton1').onclick = function() {
        toggleSwitch('toggleButton1', 'switchContainer1');
        toggleMode('button1');
    };

    document.getElementById('toggleButton2').onclick = function() {
        toggleSwitch('toggleButton2', 'switchContainer2');
        toggleMode('button2');
    };
    
    function toggleMode(buttonName) {
        // Gửi yêu cầu AJAX đến tệp PHP khi nút được nhấn
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Xử lý phản hồi từ máy chủ nếu cần
                console.log(this.responseText);
            }
        };
        xhttp.open("POST", "database/toggle_relay.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(buttonName + "=true");
    }

    function toggleSwitch(buttonId, switchId) {
        var toggleButton = document.getElementById(buttonId);
        toggleButton.classList.toggle('active');
        
        var switchContainer = document.getElementById(switchId);
        switchContainer.style.display = toggleButton.classList.contains('active') ? 'block' : 'none';
    }

    document.getElementById('switchContainer1').onclick = function() {
        this.classList.toggle('active');
        toggleState('switch1');
    };

    document.getElementById('switchContainer2').onclick = function() {
        this.classList.toggle('active');
        toggleState('switch2');
    };

    function toggleState(switchName) {
        // Gửi yêu cầu AJAX để thay đổi trạng thái của switch
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Xử lý phản hồi từ máy chủ nếu cần
                console.log(this.responseText);
            }
        };
        xhttp.open("POST", "database/toggle_relay.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(switchName + "=true");
    }

    // Khai báo mảng để lưu giữ dữ liệu
    var value1Values = [];
    var value2Values = [];
    var value3Values = [];
    var timeLabels = [];

    // Lấy dữ liệu từ bảng đọc và thêm vào mảng tương ứng
    <?php
    $result = getAllReadings($readings_count);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Lấy giá trị từ cột value1
            $value1 = $row["value1"];
            $value2 = $row["value2"];
            $value3 = $row["value3"];
            // Lấy thời gian từ cột reading_time
            $reading_time = $row["reading_time"];

            // Thêm giá trị vào mảng value1Values
            echo "value1Values.push($value1);";
            echo "value2Values.push($value2);";
            echo "value3Values.push($value3);";

            // Thêm thời gian vào mảng timeLabels
            echo "timeLabels.push('$reading_time');";
        }
        $result->free();
    }
    ?>

    // Tạo biểu đồ
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: timeLabels, // Sử dụng mảng thời gian làm dữ liệu cho trục x
            datasets: [
                {
                    label: 'Temperature',
                    data: value1Values, // Sử dụng mảng giá trị value1 làm dữ liệu cho trục y
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    fill: {
                        target: 'origin',
                        above: 'rgba(255, 99, 132, 0.2)',   // Màu của phần được đổ bóng ở trên line
                        below: 'rgba(255, 99, 132, 0.05)'    // Màu của phần được đổ bóng dưới line
                    }
                },
                {
                    label: 'Humidity', // Nhãn cho dữ liệu thứ hai
                    data: value2Values, // Sử dụng mảng giá trị value2 làm dữ liệu cho trục y
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    fill: {
                        target: 'origin',
                        above: 'rgba(54, 162, 235, 0.2)',   // Màu của phần được đổ bóng ở trên line
                        below: 'rgba(54, 162, 235, 0.05)'   // Màu của phần được đổ bóng dưới line
                    }
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        color: 'white', // Màu của chữ trên trục y
                        display: false // Ẩn số trên trục y
                    },
                    display: false // Ẩn trục y
                },
                x: {
                    ticks: {
                        color: 'white', // Màu của chữ trên trục x
                        display: false // Ẩn số trên trục x
                    },
                    display: false // Ẩn trục x
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'white' // Màu của chữ trong legend
                    },
                    display: true // Ẩn legend
                }
            }
        }
    });

    // Tạo biểu đồ
    var ctx = document.getElementById('myChart1').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: timeLabels, // Sử dụng mảng thời gian làm dữ liệu cho trục x
            datasets: [{
                label: 'Water Level', // Nhãn cho dữ liệu thứ hai
                data: value3Values, // Sử dụng mảng giá trị value2 làm dữ liệu cho trục y
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                fill: {
                    target: 'origin',
                    above: 'rgba(54, 162, 235, 0.2)',   // Màu của phần được đổ bóng ở trên line
                    below: 'rgba(54, 162, 235, 0.05)'   // Màu của phần được đổ bóng dưới line
                }
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        color: 'white', // Màu của chữ trên trục y
                        display: false // Ẩn số trên trục y
                    },
                    display: false // Ẩn trục y
                },
                x: {
                    ticks: {
                        color: 'white', // Màu của chữ trên trục x
                        display: false // Ẩn số trên trục x
                    },
                    display: false // Ẩn trục x
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'white' // Màu của chữ trong legend
                    },
                    display: true // Ẩn legend
                }
            }
        }
    });
</script>

</body>
</html>
