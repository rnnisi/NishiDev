# NishiDev Documentation (WIP)
There are bugs being fixed in the installation wrapper. 
Additionally, code is being developed to integrate other instruments in for more complete sensor testing capability. 
Documentation will updated with developments. 

Please contact me (rnnishide@gmail.com) for questions or concerns. 

Estimated completion mid-March 2021

## Purpose
This software is intended to help researchers test and subsequently optimize sensors which output analog signals. It will collect waveforms, displayed on an oscilloscope, over time. Due to a website GUI and installtion wrappers, there is no need for users to have comfort with any soft of programming. If users would like to develop the software further, there are loopholes written in where many capabilities or alternative uses may be developed accross all code. For example, the existing framework may serve as a useful template for integrating any other SCPI enabled test intruments. 

This software fascilliates remote control of automated data collection by adapting an oscilloscope with software for a raspberry pi. Remote control for researchers is executed by a system of servers, which are the "User End" of the software. The researcher can access the end system, a website GUI, to request experiments or other actions. Raspberry Pi's should get a copy of the NishiDev software and be configured on the network such that they can receieve requests from the end system. All devices should be on a local host, such that the end system can be accessed from any endpoint. Data is organized into an online data base as it is generated. The online data base can backed up automatically, and analysis can be done automatically with scripts submitted by users. These capabilities will be customized by the user, not directly written in, because the program is intended for general use. 

ith a browser and wifi capability connected to the local host. For true remote capability, a VPN should be used to access the local network.


I developed it for my lab because we needed to scale up our process for testing sensors at a low cost. 


## Overview 

### Architecture
The software has one user facing end, which is a system of servers. The user and the sensor facing end can interact with these servers. 
![SCHEME](https://github.com/rnnisi/NishiDev/blob/main/Figures/Schematic.png)

### GUI
This software uses a website as a GUI. The user can fill out HTML forms on the site to execute actions on the network. 
![GUI](https://github.com/rnnisi/NishiDev/blob/main/Figures/GUI.png)

### Use Case
To use this software, the instrument which is to be integrated to test the sensor should be hardwired to an rpi running sensor facing scripts. The researcher can then specify what the integrated instrument should be doing by remote control from the website interface. This software is designed for general use and so that many intruments may be integrated. Thus the researcher may use their discretion as to how the sensor will be tested for the desired performance aspect. The Pi connected to each intrument needs to be properly configured (done by the Initialize shell script), and connected to the local network. 

So far, the integration of a Rigol Oscilloscope for waveform acquisition has been thouroughly tested. Thus data acqusition and sorganization into a data base is robust. Integration of a function generator has been acheived, and I am currently working on improving error handling and usability. This repo will be updated as I progress.

The imagined use case is data acquistion of sensor output over a long period of time. It can be assumed that it takes about 30 seconds to collect each waveform. A target measurement, indicitave of the performance aspect of the device being collected, can be taken from each waveform. Thus collection over time yields statistically significant data. This can be used to optimize sensors, becuase it is an easy and precise method to test many similar prototypes for one performance aspect. This is not achievable normally with such a low-cost system. 

The image below depicts a 12 hour full use case I executed to test my software with a single photon detector. 

![UseCase](https://github.com/rnnisi/NishiDev/blob/main/Figures/UseCase.png)
## Program Requirements
This program is meant to be run on a Linux OS on a Rapberry Pi.

Installation wrapper will check your if your computer is compatible and install all necessary packages and libraries (with permission). 

### Hardware Necessities 
- Rapberry Pi with Wifi (one per instrument)
- Rigol DS1000 series oscilloscope (others may be compatible, have only tested this series)
- Siglent SDG1032X Function generator (if desired)
- local host
- VPN to local host (optional for true remote control)
- Additional memory space (optional for automated data transfer and analysis)

###  Software Necessities 
- Apache 
- python3 environment
- linux shell environment
- crontab


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
Library to adapt Rigol DS1000Z series scope for data acquisition. There are a lot of functions written into this library that are not used in the actual script that is run. This was left in for potential future developers. This code runs by using SCPI to communicate with the oscilloscope. Generates data and experiment logs which are uploaded synchronously to online data base. 

### FunctionGenerator.py
Control a function generator. Takes remote control commands to turn outputs on and off, specify output type, specify parameters of output, and specify wait times between changes in output. Will also log all changes made to system, and the status of the system after each change. Data will be uploaded to data base. 
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

### DataRefresh.sh (coming soon!)
Backup data to a more powerful computer. Wipe data from RPi's periodically to avoid running out of memory. Optionally will automatically run analysis scripts and add  files with more specific data to experimental directories. (Useful if one Pi is running the same experiment over and over again, avoid manually analyzing data). There will be an option added to the GUI to submit analyis scripts to be run automatically. 

### /UserEnd
Contains all code needed to run on server and support online GUI. Scripting done in PHP, structured in HTML, CSS styling. Live updates for data acqusition, run requests, and devices status. This is the support and the actual code for the endsystem. Any device accessing the control panel from a browser to submit requests or get data is intended endpoint. The website is intended as a user friendly GUI. Along with the installation wrappers, this should circumvent any need for users to have any programming savvy. 


## Important Notes for Use

### What you see is what you get. 
If you try to set the trigger level to a value not displayed on the screen of the scope, it will confuse the scope and disrupt the connection. Likewise if you are collecting a waveform that does not fit on the screen of the scope, the returned data will show an incomplete waveform. 

### Hardware resets sometimes, and that cannot be dealt with through this program. 
Sometimes the scope freezes or will reset, and you may need to be physically present to power cycle the scope in this case, as this type of issue may not be fixed remotely. 

### This is for testing purposes, it is not replacement for fast data acqusition systems.
It takes about a second to get each waveform; getting four waveforms takes four seconds. 


