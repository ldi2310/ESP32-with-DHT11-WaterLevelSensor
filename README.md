Embedded programming with ESP32 using C++ to collect data from DHT11 and WaterLevel Sensor, through which it controls a fan and a pump based on the given parameters. A web interface is used to easily monitor and adjust.
- Flash the ESP32 with [codehost.ino](https://github.com/ldi2310/ESP32-with-DHT11-WaterLevelSensor/blob/main/codehost.ino)
- screen.php is main wedsite
- API from database
  + Data update form ESP32 [esp-database.php](https://github.com/ldi2310/ESP32-with-DHT11-WaterLevelSensor/blob/main/WED/database/esp-database.php)
  + Read data environment [esp-post-data.php](https://github.com/ldi2310/ESP32-with-DHT11-WaterLevelSensor/blob/main/WED/database/esp-post-data.php)
  + Read data and control Relay [toggle_relay.php](https://github.com/ldi2310/ESP32-with-DHT11-WaterLevelSensor/blob/main/WED/database/toggle_relay.php)
  + Control Relay [relay_control.php](https://github.com/ldi2310/ESP32-with-DHT11-WaterLevelSensor/blob/main/WED/database/relay_control.php)
  
# User Interface
![Screenshot 2024-09-11 211732](https://github.com/user-attachments/assets/057439e0-8a70-4643-ac14-0e2c484c0dbd)
![Screenshot 2024-09-11 211746](https://github.com/user-attachments/assets/5ff56f26-3fa6-4df4-9c3f-aa03c1a857ae)
