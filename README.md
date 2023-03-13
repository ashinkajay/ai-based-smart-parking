# AI based Smart Parking Management System
This project is based on an internet of things (IoT) based parking system that uses IR sensor to detect the parked cars in the parking area. The designed system has an automated exit gate and entry gate which opens automatically based on the slot availability. The system stores the live occupancy on a MySQL Database hosted on the cloud. This information is used to display a parking map at the entrace of the gate to show the available slots. The users can scan a QR code on the display to get the parking map on their phones. The historical data is stored on an AWS DynamoDB database. This data is read to notebook instance in AWS Sagemaker for doing analytics.

# Introduction
Today the parking enterprise is being converted with the help of new technology which makes the towns to reduce the congestion to a great extent. Normal parking not only consumes your precious time but fuel as well.  Statistics state that vehicles spend up to 1 million barrels of oil supply daily looking for a parking spot. AI-based parking is an innovative combination of modern technology and creative human intelligence. The system works on constant real-time data collection. This kind of systems usually monitors the parked cars and informs the available parking slots to the user. This data at a specific parking area can be used to allow/deny entry of cars accordingly based on the available space. The data is also send to an AWS DynamoDB database which is used to store all the historical data. This data is used for analytics. This data transfer happens over MQTT protocol. This data is accessed by an AWS Sagemaker notebook instance to do the analytics. 

# Methodology
In this work we designed a car parking system which uses infrared sensors to detect the presence or absence of a car in the parking slot and track the occupancy. The infrared sensors is controlled by a Node MCU ESP8266 microcontroller. The sensor data is sent over to a MySQL database on Linux based webhosting on the cloud.. This database stores the live occupancy of each slot in the car parking area. The data is transferred to this database over HTTP protocol. This live data is accessed by the webpages using PhP server side code. <br>
<br>
a) Tracking slots <br>
Each slot is given a slot number and their corresponding infrared sensors detect the presence or absence of a car in that particular slot. This data is stored locally on Node MCU. Once there is a change detected in any of these slots, this data is send to the MySQL database on the Linux based web hosting over HTTP protocol as a POST request.

b) Operating entry gate <br>
    Once the infrared sensor at the entry gate detect the car, the Node MCU sends a HTTP GET request to the web hosting server. The PhP script checks the database for free slot availability. If slots are available, a payload with value 1 is send back and the occupancy is incremented, else 0 is send back. On receiving a value 1, the Node MCU gives signal to the servo motor to open the gate.

c) Operating exit gate <br>
    Once the infrared sensor at the exit gate detect the car, the Node MCU sends a signal to the servo motor to open the gate and the occupancy on the MySQL Database is decremented by a value of 1 using an HTTP GET request.

d) Tracking historical data <br>
    When a new car comes in the slot, the Node MCU sends a GET request to the webserver to get the current time. The slot along with the time is stored locally for sometime. When the car leaves the slot, the slot details along with the entry and exit times is send as an MQTT request to the AWS IoT core service. This data is stored in AWS DynamoDB database.

Components used:
1) ESP8266 Node MCU
2) HW-201 IR Sensors
3) SG-90 Micro Servo motor
4) 5V Micro USB power adapter
5) Breadboard
6) Connecting wires
7)Toy cars
8) Model parking slot building materials
9) Internet connection with WiFi


Software Used:
1) Arduino IDE
2) AWS Sagemaker
3) AWS IoT Core
4) AWS Dynamodb
5) Linux Cpanel hosting
6) MySQL Database

[test](https://google.com)
