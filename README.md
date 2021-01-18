# NishiDev Documentation (WIP)
There are bugs being fixed in the installation wrapper. 
Additionally, code is being developed to integrate other instruments in for more complete sensor testing capability. 
Documentation will updated with developments. 

Please contact me (rnnishide@gmail.com) for questions or concerns. 

Estimated completion mid-March 2021

## Purpose
This software is intended to help researchers test and subsequently optimize sensors which output analog signals. It will collect waveforms, displayed on an oscilloscope, over time. 

This software fascilliates remote control of automated data collection by adapting an oscilloscope with software for a raspberry pi. Remote control for researchers is executed by a system of servers, which are the "User End" of the software. The researcher can access the end system, a website GUI, to request experiments or other actions. Raspberry Pi's should get a copy of the NishiDev software and be configured on the network such that they can receieve requests from the end system. All devices should be on a local host, such that the end system can be accessed from any endpoint w
ith a browser and wifi capability connected to the local host. For true remote capability, a VPN should be used to access the local network.
 
This software also handles data organization into an online and local data base. It is reccomended that some system to pull and back-up data be implemented to avoid running out of storage on nodes. This code has loopholes such that other devices can be adapted into the system, such as a function generator or power supply, with other SCPI enabled intruments. 

I developed it for my lab because we needed to scale up our process for testing sensors at a low cost. 


## Overview 

### Architecture
The software has one user facing end, which is a system of servers. The user and the sensor facing end can interact with these servers. 
![SCHEME](https://github.com/rnnisi/NishiDev/blob/main/schematics.png)

### GUI
This software uses a website as a GUI. The user can fill out HTML forms on the site to execute actions on the network. 
![GUI](https://github.com/rnnisi/NishiDev/blob/main/GUI.png)

### Use Case
The imagined use case is data acquistion of sensor output over a long period of time. It can be assumed that it takes about 30 seconds to collect each waveform. A target measurement, indicitave of the performance aspect of the device being collected, can be taken from each waveform. Thus collection over time yields statistically significant data. This can be used to optimzie sensors, becuase it is an easy and precise method to test many similar prototypes for one performance aspect. 


## Program Configuration and Requirements
This program is meant to be run on a Linux OS on a Rapberry Pi.

Installation wrapper will handle other requirements. 

### Other Packages
- Apache 

### Python Libraries 
- pyvisa
- time
- os
- sys
- subprocess
- threading
- _thread
- multiprocessing

## Contents
### GetNishiDev.sh
Shell script which will get a raspberry-pi ready to run this softare and pull code from this repo. Intended for users who are not familiar with linux installations or programming in general. Users must email me to get permission to run this script. 

### Initialize.sh
Installation wrapper, run with sudo command to configre system and get necessary packages. Intended to allow users who are not familiar with linux terminal to confgure this software's architecture. 

### RigolDSS1000Z.py
Library to adapt Rigol DS1000Z series scope for data acquisition. There are a lot of functions written into this library that are not used in the actual script that is run. This was left in for potential future developers. This code runs by using SCPI to communicate with the oscilloscope. 

### USB_Run.py
Sensor facing program, run to get data by integrating oscilloscope into system. Scope and Pi running program should be connected via USB cable.  Runs with command line arguements for RunTime, Trigger status, and optionally channels.

### ExpLog.txt 
Track runs

### memory.txt
Embeded file which contains the SCPI address last used, requests, and status of scope.
  
### plot.py
Run in directory with data csv files : ./plot.py <waveform number>. Script will plot all waveform csv's under same experimental number, takes number of waveform as command line arguement. Deploy in Exp_n directory to plot Wfm_i.csv

### DataTransfer.py
Library for data transfer to online server. 

### ConfigureOnlineDB.sh
Will install necessary software to use raspberry-pi as server and update permissions. Use root to run.

### CheckRequests.sh
Run with crontab on sensor facing Pi so that Pi listens to control servers and executes requests. 

### check_reset.sh
Runs in background on sensor facing Pi so that Pi can listen to troubleshooting requests while running jobs. 

### /UserEnd
Contains all code needed to run on server and support online GUI. Scripting done in PHP, structured in HTML, CSS styling. 


## Important Notes for Use

### What you see is what you get. 
If you try to set the trigger level to a value not displayed on the screen of the scope, it will confuse the scope and disrupt the connection. Likewise if you are collecting a waveform that does not fit on the screen of the scope, the returned data will show an incomplete waveform. 

### Hardware resets sometimes, and that cannot be dealt with through this program. 
Sometimes the scope freezes or will reset, and you may need to be physically present to power cycle the scope in this case, as this type of issue may not be fixed remotely. 

### This is for testing purposes, it is not replacement for fast data acqusition systems.
It takes about a second to get each waveform; getting four waveforms takes four seconds. 


