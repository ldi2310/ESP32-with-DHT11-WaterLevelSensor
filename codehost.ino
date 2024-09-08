#include <WiFi.h>
#include <WiFiClientSecure.h>
#include <HTTPClient.h>
#include <DHT.h>
#include <ArduinoJson.h>

#define dhttype DHT11
#define dhtpin 4

const int WaterLevelSensor = 16;

int WaterLevelSensorValve(int WaterLevelSensor) {
  int Valve = analogRead(WaterLevelSensor);
  return Valve == 0 ? 0 : (520.0 / 100.0) * Valve;
}

// Replace with your network credentials
const char* ssid = "internet clone";
const char* password = "23102005";

// REPLACE with your Domain name and URL path or IP address with path
const char* serverName = "http://192.168.196.107/doanco1/database/esp-post-data.php";
const char* serverGet = "http://192.168.196.107/doanco1/database/relay_control.php";

DHT dht(dhtpin, dhttype);

String apiKeyValue = "tPmAT5Ab3j7F9";
String sensorName = "DHT11";
String sensorLocation = "Plant";

const int relay1 = 12;
const int relay2 = 14;

String mode1 = "manual";
String mode2 = "manual";

void readStateFromServer() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverGet);

    int httpResponseCode = http.GET();
    if (httpResponseCode > 0) {
      Serial.print("State HTTP Response code: ");
      Serial.println(httpResponseCode);

      String payload = http.getString();
      Serial.println("Response: " + payload);

      DynamicJsonDocument doc(1024);
      deserializeJson(doc, payload);

      String state1 = doc["state1"].as<String>();
      String state2 = doc["state2"].as<String>();
      mode1 = doc["mode1"].as<String>();
      mode2 = doc["mode2"].as<String>();

      Serial.println("State1 value: " + state1);
      Serial.println("State2 value: " + state2);
      Serial.println("Mode1: " + mode1);
      Serial.println("Mode2: " + mode2);

      if (mode1 == "manual") {
        if (state1 == "on") {
          digitalWrite(relay1, HIGH);
          Serial.println("Relay1 is ON");
        } else if (state1 == "off") {
          digitalWrite(relay1, LOW);
          Serial.println("Relay1 is OFF");
        } else {
          Serial.println("Invalid state1 value");
        }
      }

      if (mode2 == "manual") {
        if (state2 == "on") {
          digitalWrite(relay2, HIGH);
          Serial.println("Relay2 is ON");
        } else if (state2 == "off") {
          digitalWrite(relay2, LOW);
          Serial.println("Relay2 is OFF");
        } else {
          Serial.println("Invalid state2 value");
        }
      }
    } else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }
}

void sendToServer() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    String httpRequestData = "api_key=tPmAT5Ab3j7F9&sensor=DHT11&location=Plant&value1=" + 
                              String(dht.readTemperature()) + "&value2=" + 
                              String(dht.readHumidity()) + "&value3=" + 
                              String(WaterLevelSensorValve(WaterLevelSensor));

    int httpResponseCode = http.POST(httpRequestData);
    if (httpResponseCode > 0) {
      Serial.print("Sensor data HTTP Response code: ");
      Serial.println(httpResponseCode);
    } else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }
}

void controlRelay1Automatically() {
  float temperature = dht.readTemperature();
  if (temperature > 25) {
    digitalWrite(relay1, HIGH);
    Serial.println("Relay1 is ON (Auto)");
  } else {
    digitalWrite(relay1, LOW);
    Serial.println("Relay1 is OFF (Auto)");
  }
}

void controlRelay2Automatically() {
  if (WaterLevelSensorValve(WaterLevelSensor) < 50) {
    digitalWrite(relay2, HIGH);
    Serial.println("Relay2 is ON (Auto)");
  } else {
    digitalWrite(relay2, LOW);
    Serial.println("Relay2 is OFF (Auto)");
  }
}

void setup() {
  dht.begin();
  Serial.begin(115200);

  pinMode(relay1, OUTPUT);
  pinMode(relay2, OUTPUT);

  digitalWrite(relay1, LOW);
  digitalWrite(relay2, LOW);

  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(250);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  readStateFromServer();

  if (mode1 == "auto") {
    controlRelay1Automatically();
  }

  if (mode2 == "auto") {
    controlRelay2Automatically();
  }

  sendToServer();

  delay(2000);
}
