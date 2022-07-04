#include "FS.h"
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <NTPClient.h>
#include <WiFiUdp.h>
#include <Servo.h>

// Network and server details
const char* ssid     = "Mobile hotspot";
const char* password = "asdfg32334";
// REPLACE with your Domain name and URL path or IP address with path
String serverName = "http://anunow.xyz/";
// Keep this API Key value to be compatible with the PHP code provided in the project page. 
String api_key_value = "tPmAT5Ab3j7F9"; //Note used here


// AWS, MQTT and HTTP settings
WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "pool.ntp.org");
const char* AWS_endpoint = "a3c42m2wshs6eg-ats.iot.us-east-1.amazonaws.com"; //MQTT broker ip
void callback(char* topic, byte* payload, unsigned int length) {
  Serial.print("Message arrived [");
  Serial.print(topic);
  Serial.print("] ");
  for (int i = 0; i < length; i++) {
  Serial.print((char)payload[i]);
  }
  Serial.println();
}
WiFiClientSecure espClient;
PubSubClient client(AWS_endpoint, 8883, callback, espClient); //set MQTT port number to 8883 as per //standard
long lastMsg = 0;
char msg[150];
byte mac[6];
char mac_Id[18];
void setup_wifi() {
  delay(10);
  // We start by connecting to a WiFi network
  espClient.setBufferSizes(512, 512);
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  timeClient.begin();
  while(!timeClient.update()){
    timeClient.forceUpdate();
  }
  espClient.setX509Time(timeClient.getEpochTime());
}
void reconnect() {
  // Loop until we're reconnected
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    // Attempt to connect
    if (client.connect("Esp8266_123")) {
      Serial.println("connected");
      // Once connected, publish an announcement...
      client.publish("outTopic", "hello world");
      // ... and resubscribe
      client.subscribe("inTopic");
    }
    else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");
      char buf[256];
      espClient.getLastSSLError(buf,256);
      Serial.print("WiFiClientSecure SSL error: ");
      Serial.println(buf);
      // Wait 5 seconds before retrying
      delay(5000);
    }
  }
}


//Additional user defined functions
int get_time(){
      WiFiClient wificlient;
      HTTPClient http;
      // Prepare HTTP GET request for gate open
      String httpRequestData = serverName + "get-time.php?api_key=" + api_key_value;
      // Your Domain name with URL path or IP address with path
      http.begin(wificlient, httpRequestData);
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
      return payload.toInt();
    }

    
//Variable definitions
float distanceCm[6]; //1st for sensor for slot and 5th sensor is for gate
String cars = "0000000000";
int car_entry_time[10]; //For storing entry timing of cars
Servo servo_entry;
Servo servo_exit;


void setup() {
  servo_entry.attach(D0);
  servo_entry.write(0);
  servo_exit.attach(D8);
  servo_exit.write(0);
  
  // Setup serial, wifi, AWS
  Serial.begin(115200);
  Serial.setDebugOutput(true);
  setup_wifi();
  delay(1000);
  if (!SPIFFS.begin()) {
    Serial.println("Failed to mount file system");
    return;
  }
  Serial.print("Heap: "); Serial.println(ESP.getFreeHeap());
  // Load certificate file
  File cert = SPIFFS.open("/cert.der", "r"); //replace cert.crt eith your uploaded file name
  if (!cert) {
    Serial.println("Failed to open cert file");
  }
  else
    Serial.println("Success to open cert file");
  delay(1000);
  if (espClient.loadCertificate(cert))
    Serial.println("cert loaded");
  else
    Serial.println("cert not loaded");
  // Load private key file
  File private_key = SPIFFS.open("/private.der", "r"); //replace private eith your uploaded file name
  if (!private_key) {
    Serial.println("Failed to open private cert file");
  }
  else
    Serial.println("Success to open private cert file");
  delay(1000);
  if (espClient.loadPrivateKey(private_key))
    Serial.println("private key loaded");
  else
    Serial.println("private key not loaded");
  // Load CA file
  File ca = SPIFFS.open("/ca.der", "r"); //replace ca eith your uploaded file name
  if (!ca) {
    Serial.println("Failed to open ca ");
  }
  else
    Serial.println("Success to open ca");
  delay(1000);
  if(espClient.loadCACert(ca))
    Serial.println("ca loaded");
  else
    Serial.println("ca failed");
  Serial.print("Heap: "); Serial.println(ESP.getFreeHeap());
  WiFi.macAddress(mac);
  snprintf(mac_Id, sizeof(mac_Id), "%02x:%02x:%02x:%02x:%02x:%02x",
  mac[0], mac[1], mac[2], mac[3], mac[4], mac[5]);
  Serial.print(mac_Id);
}

    
void loop() {
    //MQTT connection related code
    if (!client.connected()) {
      reconnect();
    }
    client.loop();
    long now = millis(); //TIme delay for MQTT publish

    distanceCm[0] = digitalRead(D1);
    distanceCm[1] = digitalRead(D2);
    distanceCm[2] = digitalRead(D3);
    distanceCm[3] = digitalRead(D4);
    distanceCm[4] = digitalRead(D5);
    distanceCm[5] = digitalRead(D6);
  
    WiFiClient wificlient;
    HTTPClient http;
    
    // Open gate after checking space
    if(distanceCm[0] == 0){
      String httpRequestData = serverName + "gate-open-check.php?api_key=" + api_key_value;
      // Your Domain name with URL path or IP address with path
      http.begin(wificlient, httpRequestData);
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
      if(payload == "1"){
        for(int pos=0;pos<=90;pos++){
          servo_entry.write(pos);
          delay(30);
        }
        delay(1000);
        for(int pos=90;pos>=0;pos--){
        servo_entry.write(pos);
        delay(30);
        }
      }
    }
    

    // Open exit gate and update database
    if(distanceCm[5]== 0){
      String httpRequestData = serverName + "exit-gate-open.php?api_key=" + api_key_value;
      // Your Domain name with URL path or IP address with path
      http.begin(wificlient, httpRequestData);
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
      if(payload == "1"){
        for(int pos=0;pos<=90;pos++){
          servo_exit.write(pos);
          delay(30);
        }
        delay(1000);
        for(int pos=90;pos>=0;pos--){
        servo_exit.write(pos);
        delay(30);
        }
      }
    }
    
    
    // Update parking status locally
    String carsTemp = cars;
    for(int i=0; i < 4; i++){
      if(distanceCm[i+1] == 0 && cars[i] == '0'){
        carsTemp.setCharAt(i, '1');
        car_entry_time[i] = get_time();
        Serial.println("Car detected");
      }
      else if(distanceCm[i+1] == 1 && cars[i] == '1') {
        carsTemp.setCharAt(i, '0');
        Serial.println("Car went out");
        // Publish message to AWS
        if (now - lastMsg > 2000) {
          lastMsg = now;
          snprintf (msg, 150, "{\"slot\":\"%d\",\"entry_time\": \"%d\",\"exit_time\": \"%d\"}", i+1, car_entry_time[i], get_time() );
          Serial.print("Publish message: ");
          Serial.println(msg);
          client.publish("carparking", msg);
          Serial.print("Heap: "); 
          Serial.println(ESP.getFreeHeap()); //Low heap can cause problems
        }
      }
    }
    

    
    //HTTP post car parking sensor
    if(carsTemp != cars){
      cars = carsTemp;
      String update_parking_status = serverName + "update-parking-status.php";
      // Prepare your HTTP POST request data
      String httpRequestData = "api_key=" + api_key_value + "&cars=" + cars + "";
      // Your Domain name with URL path or IP address with path
      http.begin(wificlient, update_parking_status);
      // Specify content-type header
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      Serial.print("httpRequestData: ");
      Serial.println(httpRequestData);
      // Send HTTP POST request
      int httpResponseCode = http.POST(httpRequestData);
      String payload = http.getString();    //Get the response payload
      Serial.println(httpResponseCode);   //Print HTTP return code
      Serial.println("break");
      Serial.println(payload);    //Print request response payload
    }
    
  
    // Free resources
    http.end();
  
    //Send an HTTP POST request every 2 seconds
    //delay(2000);

}
