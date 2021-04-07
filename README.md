# Beta Test Notes

## Rasperry Pi's
I am pre-loading the software onto three raspberry pi's, listed here by hostname:
* * * 
**rnnishiPI0w:*** Acts as master node, is connected with oscilloscope to collect data and delegates to function generator (pi3). Also hosts control panel

**FG-Beta**: servant to master node, connected with function generator. (pi0)
* * *
I was able to do these configurations by running installation wrappers in the /home/pi directory. It was necessary to already have crontab set up (an editor picked) before running the wrapper for it to work. I believe the installation wrappers are mostly effective at this point, but they may have more bugs, which is why i pre-loaded three pi's with fresh SD cards for the beta test. 

## To Use 

It should be really simple to use. Both rasperry pi's need to be connected by USB to the appropriate device. 

Both Pi's need to be on a local network that you are also on as the user. 

Once both rasperry pi's are powered, hardwired via USB to the function generator/oscilliscope, and connected to your internet or local host, you can access the control panel at the url: 

http://rnnishipi0w.local/control_panel/control_panel.php

This is the user interface. From here, it should be really simple to use. You can submit a request manually or by importing an old request. There are instructions for all the features on the pages. Submit requests to 'rnnishipi0w'. If this hostname is typed incorrectly the request will not be recieved 

### It should be self explantory once you are on the control panel, but here are my notes from testing

- This program is pretty slow, this is the drawback of scrappy automation. It will probably be a useful tool for long experiments for sensors as it is very hands off. 
- Both the function generator and scope programs have embedded files with a line that switches between "idle" and "busy" if the device is idle or busy. If that file says busy, the program will not accept another request. In the case of crashes this can sometimes cause the program to freeze. There is a funciton in the control panel website to reset the scope to idle. There is not for the funciton generator, this can be changed manually if necessary by going into: pi@FG-Beta.local:/home/pi/NishiDev/FG/memory.txt
- My SD card for rnnishiPI0w freezes up, or the Pi3 it is in freezes up. Sometimes it gets really slow. Rebooting/powercygling the scope helps sometimes if you notice things are really slow 
- I was able to collect data for 24 h, but you only get about 2 waveform CSV sets per minute if always triggered. 
- Timing the function generator and data acquistion is slow, it is better to continously collect data for longer than the function generator will be active, so that there is data from before and after the function generator is turned on. 
- The side panel, under rnnishiPI0w, has the link for the data base. Each directory should have a file that also has the function generator program if applicable. Sometimes there are bugs with updating the online data base. 
- Data for waveforms in the online data base updates live in the linked database. It is good to check at the beggining of a longer run that files are properly being posted. If files are properly being posted online at the beggining (they usually are but there are bugs once and a while), the program generally will work for the whole run. If it is not outputting data files at the start, it will not correct itself. 
- Function generator log does not update live in the linked data base. It does update live to http://fg-beta.local/fromFG/
- Trying to read a signal that is not displayed on the physical screen of the oscilloscope will not work, and the scope will get confused. It does not process signals not in the display range.

# NishiDev Documentation (WIP)

## Purpose
This software is intended to help researchers test and subsequently optimize sensors which output analog signals. It will collect waveforms, displayed on an oscilloscope, over time. Due to a website GUI and installtion wrappers, there is no need for users to have comfort with any soft of programming. If users would like to develop the software further, there are loopholes written in where many capabilities or alternative uses may be developed accross all code. For example, the existing framework may serve as a useful template for integrating any other SCPI enabled test intruments. 

This software fascilliates remote control of automated data collection by adapting an oscilloscope with software for a raspberry pi. Remote control for researchers is executed by a system of servers, which are the "User End" of the software. The researcher can access the end system, a website GUI, to request experiments or other actions. Raspberry Pi's should get a copy of the NishiDev software and be configured on the network such that they can receieve requests from the end system. All devices should be on a local host, such that the end system can be accessed from any endpoint. Data is organized into an online data base as it is generated. The online data base can backed up automatically, and analysis can be done automatically with scripts submitted by users. These capabilities will be customized by the user, not directly written in, because the program is intended for general use. 

This software depends on a local host to connect everything. Pi VPN is reccomended for truly remote function.


I developed it for my lab because we needed to scale up our process for testing sensors at a low cost. 


## Overview 

### Basic notes on Architecture
The software has one user facing end, which is a system of servers. The user and the sensor facing end can interact with these servers. There can be many sensor facing ends for one user facing end. There should be one user end on one local network. 
![SCHEME](https://github.com/rnnisi/NishiDev/blob/main/Figures/Schematic.png)

The front end supports a website, which is an intuitive for use GUI. The user can fill out HTML forms on the site to execute actions on the network. 
![GUI](https://github.com/rnnisi/NishiDev/blob/main/Figures/GUI.png)

### Use Case
To use this software, the instrument which is to be integrated to test the sensor should be hardwired to an rpi running sensor facing scripts. The researcher can then specify what the integrated instrument should be doing by remote control from the website interface. This software is designed for general use and so that many intruments may be integrated. Thus the researcher may use their discretion as to how the sensor will be tested for the desired performance aspect. The Pi connected to each intrument needs to be properly configured (done by the Initialize shell script), and connected to the local network. 

So far, the integration of a Rigol Oscilloscope for waveform acquisition has been thouroughly tested. Thus data acqusition and sorganization into a data base is robust. Integration of a function generator has been acheived, and I am currently working on improving error handling and usability. This repo will be updated as I progress.

The imagined use case is data acquistion of sensor output over a long period of time. It can be assumed that it takes about 30 seconds to collect each waveform. A target measurement, indicitave of the performance aspect of the device being collected, can be taken from each waveform. Thus collection over time yields statistically significant data. This can be used to optimize sensors, becuase it is an easy and precise method to test many similar prototypes for one performance aspect. This is not achievable normally with such a low-cost system. 

The image below depicts a 12 hour full use case I executed to test my software with a single photon detector. 

![UseCase](https://github.com/rnnisi/NishiDev/blob/main/Figures/UseCase.png)
## Program Requirements
This program is meant to be run on a Linux OS on a Rapberry Pi.

Installation wrapper will check your if your computer is compatible and install all necessary packages and libraries (with permission). They are somewhat buggy still. Especialy important is checking that the names of all the nodes are properly inputted into shell scripts, as these dictate where each node is looking for messages from other nodes. It is also important to note that this program heavily relies on crontab, which runs from root. Therefore all files need to have full paths explicitly written into the code. It is not reccomended to move files around. 

This repository should be cloned and installed into the /home/pi directory. If needed in another location, code will need to be altered. 

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
- crontab, reccomended that it is configured before installation to allow wrappers to work properly.
- php environment, must recognize .php files and execute as php (as exposed to executing as HTML)


### Python Libraries 
- pyvisa
- time
- os
- sys
- subprocess
- threading
- _thread
- multiprocessing

## Notable files+directories included

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

### /control_panel
This directory contains all the backend, mostly php files, for the remote control panel. 

### FGcheck_requests.sh
Checks if there is a new request for fg, gets requests

### FGRun_USB.py
Runs FG program using embedded file which holds requests
## For Future Developers

### SiglentSDG1000X.py
This is the library which contains a class with all of the SCPI based functions to control the Siglent function generator. All functions are indexed into a library that is self contained in the class so that functions may be called by keys. 

### RunSpecs.txt
This is the file which contains the last or currently active command set for the function generator 

### lastRunparams.txt
Contains the ID for the last job, so that function generator only starts running if there is a new request made (and it is not already running something. 

### Job ID's

Each request has an ID: Req_x. These requests are numerically ordered as given in the control panel. 

Each job on a master node as an ID: Exp_n. These experiments are numerically ordered as recieved by the master node. 

Each function generator run has an ID: FG-Run_m.txt. These runs are numerically ordered as recieved by the function generator node. 

Each node will have all three ID values (if possible). 

### Syntax for requests to intruments

Each run is dictated by a Request, given its own ID, which contains all the information for configuration and data collection for the duration of the run. 

This program uses its own syntax to publish requests such that requests are able to be easily processed by Pi's on the sensor ends. The basic mechanism for front to back-end communication (user to sensor end) is scraping. The Pi's running sensor test programs are constantly scraping the "requests.txt" page, looking for command adressed to them. The commands are published with the following syntax such that the sensor test programs can interpret and execute them. 

| Characters | Meaning |
| --- | --- |
| Req_xxx: | This is the ID of the request, which is the first item in the line. Each line is one request|
| ; | Seperates information within a command sest| 
| & | Seperates command sets for function generator| 
| , | Seperates parameter specifiers meant for one action| 
| ! | Seperates command sets meant for different intruments, such that ncommand sets following are for a different intrument than sets before| 
| $ | End of request| 

For future development, it is reccomended that additional characters, such as "+, -, <, >" and so on. Use of these characters to standardize the commands sent between ends is to avoid complications due to string processing between different languages and environments. 

## Exchange of information between nodes (a lot of curl scraping)

The python script which runs the master node and controls the oscilloscope for data collection is relatively intricate and specifically structured to its function. However code for the control and automation of peripheral instruments which respond to the master node can follow the same basic structure between different intruments.  Included is a library to interact with a Siglent Function generator. **A good project for a future developer could be adding in code for a variable power source, following the infrastructure already built to implement the function generator. **

The master node scrapes the requests page, taking jobs adressed to itself. The master node also configures, starts, and stops data collection through the oscilloscope. Each master node hosts its own online database, which is linked on the control panel. The master node publishes commands for the function generator. The function generator node is constantly scraping the published commands hosted by the master nodes. As soon as it finds a new set of commands, it will execute them. It will log all of the activity, and post that on its own online file. Upon completion of the run, the Master node will collect the activity log from the function generator node and copy it into the directory for the experiment in the online database. 

The function generator recieves a list of commands in sets as published on the requests page. The request syntax for the function generator is:

  & channel, action, parameters
  
The channel of course specifies which output channel of the function generator the command is adressed to (1 or 2). The action specifies how the output should be changed, and parameters is the value of that change if applicable. Each action is a key for a python dictionary in the library running on the function generator node. The key returns a function in the function generator class that, when run, will execute the action, taking input parameters (such as frequency or amplitude) if necessary. 

Users can manually enter the runs by inputting into dynamic HTML forms supported with JavaScript, or by importing an old run and optionally altering the run in a textbox. It is up to the user to specificy the timing properly such that the function generator and data collection timing is compatible. "wait" times designate time intervals in which the function generator node should sit passively at the specified settings while data is collected. 

The function generator program does not have as robust error handling as the function generator, as it is less susceptible to freezing and loss of data. If a command is incorrectly issued by the user or the function generator is not responding properly, the state of the function generator will be reported and data will be collected regardless. **It is up to the user to ensure that the function generator was executing its outputs correctly by checking the actvity logs, as a incorrectly issued command could fail to change function generator output channels as desired, causing deceptive data. It is reccomended to have one channel of the oscilliscope monitoring input signals to avoid mishaps.**

Below is a directory tree to help show how data/processes are structured in this system. Dark blue indicates the directory files below are in, pink indicates the file is available on the local netowork via any connected device, and green indicates an actual file in the directory. 

![Tree](https://github.com/rnnisi/NishiDev/blob/main/Figures/Directories.png)

This structure may be replicated for many other intruments that use SCPI to increase control over the environment the sensor is tested in. 

There are intructions in the control panel websites for user convenience, so I will not go into detail on how to fill out forms in this documentation. 
  
## Important Notes for Use

### What you see is what you get. 
If you try to set the trigger level to a value not displayed on the screen of the scope, it will confuse the scope and disrupt the connection. Likewise if you are collecting a waveform that does not fit on the screen of the scope, the returned data will show an incomplete waveform. 

### Hardware resets sometimes, and that cannot be dealt with through this program. 
Sometimes the scope freezes or will reset, and you may need to be physically present to power cycle the scope in this case, as this type of issue may not be fixed remotely. 

### This is for testing purposes, it is not replacement for fast data acqusition systems.
It takes about a second to get each waveform; getting four waveforms takes four seconds. 

### There are bugs in installation scripts
The actual software has been tested and all works to my knowledge, but there are issues with the installation scripts. These mostly seem to be due to slight differences in how different SD cards are configured for a raspberry pi. For foolproof use, it would be best to copy the SD cards I have given for testing, and then search and replace through the whole program to correct node names. For example, the beta-test I set up has the master node name "rnnishiPI0w". To get a second master node running for a second lab on a pi with the hostname 'Master2', the SD card of rnnishiPI0w should be copied, then 's/rnnishiPI0w/Master2/ ./*/* to update the program for the new master node. The same tactic will need to be used for the function generator nodes as well. 

### It is the users job to configure timing between function generator runs and data collection with the scope properly so that the two nodes run processes in paralell. Communication between these nodes is limited, and poor timing could cause experiments to be ruined by improper timing. 

### For true remote capability, use pi VPN to access the control panel while out of the network 

### There is room and infrastructure for future developers to build out.
I tried to leave helpful comments and well structured code so that another programmer can go in and build out/alter this program for other needs. Developers are welcome to contact me with questions. 
