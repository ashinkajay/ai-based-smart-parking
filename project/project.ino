#ifdef ESP32
  #include <WiFi.h>
  #include <HTTPClient.h>
#else
  #include <ESP8266WiFi.h>
  #include <ESP8266HTTPClient.h>
  #include <WiFiClient.h>
#endif

//Ultrasonic sensor
const int trigPin = 12; //D6
const int echoPin1 = 14; //D5
const int echoPin2 = 2; //D4
const int echoPin3 = 0; //D3
const int echoPin4 = 4; //D2
const int echoPin5 = 5; //D1
//define sound velocity in cm/uS
#define SOUND_VELOCITY 0.034
#define CM_TO_INCH 0.393701
float distanceCm1,distanceCm2,distanceCm3,distanceCm4,distanceCm5;

String cars = "0000000000";

// Replace with your network credentials
const char* ssid     = "Mobile hotspot";
const char* password = "asdfg32334";

// REPLACE with your Domain name and URL path or IP address with path
String serverName = "http://192.168.43.68/project/";

// Keep this API Key value to be compatible with the PHP code provided in the project page. 
// If you change the apiKeyValue value, the PHP file /post-esp-data.php also needs to have the same key 
String api_key_value = "tPmAT5Ab3j7F9"; //Note used here

void setup() {
  pinMode(trigPin, OUTPUT); // Sets the trigPin as an Output
  pinMode(echoPin1, INPUT); // Sets the echoPin as an Input
  pinMode(echoPin2, INPUT); // Sets the echoPin as an Input
  pinMode(echoPin3, INPUT); // Sets the echoPin as an Input
  pinMode(echoPin4, INPUT); // Sets the echoPin as an Input
  pinMode(echoPin5, INPUT); // Sets the echoPin as an Input
  
  Serial.begin(115200);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) { 
    delay(500);
    Serial.print(".");
  }
    
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  
}

String get_time(){
      WiFiClient client;
      HTTPClient http;
      // Prepare HTTP GET request for gate open
      String httpRequestData = serverName + "get-time.php?api_key=" + api_key_value;
      // Your Domain name with URL path or IP address with path
      http.begin(client, httpRequestData);
      // Specify content-type header
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      //Serial.print("httpRequestData: ");
      //Serial.println(httpRequestData);
      // Send HTTP GET request
      int httpResponseCode = http.GET();
      String payload = http.getString();    //Get the response payload
      //Serial.println(httpResponseCode);   //Print HTTP return code
      //Serial.println("break");
      //Serial.println(payload);    //Print request response payload
      return payload;
    }
    
void loop() {

    // Clears the trigPin
    digitalWrite(trigPin, LOW);
    delayMicroseconds(2);
    // Sets the trigPin on HIGH state for 10 micro seconds
    digitalWrite(trigPin, HIGH);
    delayMicroseconds(10);
    digitalWrite(trigPin, LOW);

    distanceCm1 = pulseIn(echoPin1, HIGH) * SOUND_VELOCITY/2;
    distanceCm2 = pulseIn(echoPin2, HIGH) * SOUND_VELOCITY/2;
    distanceCm3 = pulseIn(echoPin3, HIGH) * SOUND_VELOCITY/2;
    distanceCm4 = pulseIn(echoPin4, HIGH) * SOUND_VELOCITY/2;
    distanceCm5 = pulseIn(echoPin5, HIGH) * SOUND_VELOCITY/2;
  
    WiFiClient client;
    HTTPClient http;
    
    // Prepare HTTP GET request for gate open
    String httpRequestData = serverName + "gate-open-check.php?api_key=" + api_key_value;
    // Your Domain name with URL path or IP address with path
    http.begin(client, httpRequestData);
    // Specify content-type header
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    Serial.print("httpRequestData: ");
    Serial.println(httpRequestData);
    // Send HTTP GET request
    int httpResponseCode = http.GET();
    String payload = http.getString();    //Get the response payload
    Serial.println(httpResponseCode);   //Print HTTP return code
    Serial.println("break");
    Serial.println(payload);    //Print request response payload 

 
    // Update parking status locally
    String carsTemp = cars;
    if(distanceCm1 < 20 && cars[0] == '0'){
      carsTemp.setCharAt(0, '1');
      Serial.println("Car detected");
    }
    else if(distanceCm1 >= 20 && cars[0] == '1') {
      carsTemp.setCharAt(0, '0');
      Serial.println("Car went out");
    }

    
    //HTTP post car parking sensor
    if(carsTemp != cars){
      cars = carsTemp;
      String update_parking_status = serverName + "update-parking-status.php";
      // Prepare your HTTP POST request data
      httpRequestData = "api_key=" + api_key_value + "&cars=" + cars + "";
      // Your Domain name with URL path or IP address with path
      http.begin(client, update_parking_status);
      // Specify content-type header
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      Serial.print("httpRequestData: ");
      Serial.println(httpRequestData);
      // Send HTTP POST request
      httpResponseCode = http.POST(httpRequestData);
      payload = http.getString();    //Get the response payload
      Serial.println(httpResponseCode);   //Print HTTP return code
      Serial.println("break");
      Serial.println(payload);    //Print request response payload 
    }
    
  
    // Free resources
    http.end();
  
    //Send an HTTP POST request every 2 seconds
    //delay(2000);
    Serial.println(get_time());

}