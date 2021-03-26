#!/usr/bin/env python3

import pyvisa
import time
import os
import subprocess 
import sys

class SiglentSDG1000X:
        def __init__(self):
                self.rm = pyvisa.ResourceManager("@py")
                self.channels = "2"
                self.nickname = "rnnishiPI0w"
                self.interface = "USB"
                self.FirstOutFile = "FG-Run_1"
                self.AssignOutFile = "'FG-Run_' + str(i)"
                self.args = sys.argv
                self.Actions = {}
                self.path = "/home/pi/NishiDev/FG/"
                self.MemoryFile = self.path + "memory.txt"
                self.keys = ["OUT_ON", "OUT_OFF", "SINE", "SQUARE", "RAMP", "PULSE", "NOISE", "ARB", "DC", "PRBS", "FREQ", "PERIOD", "VPP", "OFFSET", "PHAE", "WAIT"]
                self.Actions.update({ "OUT_ON" : "self.TurnOutputOn(channel)"})
                self.Actions.update({ "OUT_OFF" : "self.TurnOutputOff(channel)"})
                self.Actions.update({ "SINE" : "self.SetSineWave(channel)"})
                self.Actions.update({ "SQUARE" : "self.SetSquareWave(channel)"})
                self.Actions.update({ "RAMP" : "self.SetRampWave(channel)"})
                self.Actions.update({ "PULSE" : "self.SetPulseWave(channel)"})
                self.Actions.update({ "NOISE" : "self.SetNoiseWave(channel)"})
                self.Actions.update({ "ARB" : "self.SetArbWave(channel)"})
                self.Actions.update({ "DC" : "self.SetDCWave(channel)"})
                self.Actions.update({ "PRBS" : "self.SetPRBSWave(channel)"})
                self.Actions.update({ "FREQ" : "self.SetFrequency(channel, val)"})
                self.Actions.update({ "PERIOD" : "self.SetPeriod(channel, val)"})
                self.Actions.update({ "VPP" : "self.SetV_pp(channel, val)"})
                self.Actions.update({ "OFFSET" : "self.SetOffset(channel, val)"})
                self.Actions.update({ "PHASE" : "self.SetPhase(channel, val)"})
                self.Actions.update({ "WAIT" : "self.Wait(val)"})
                self.Actions.update({ "DONE" : "self.Done()"})
                self.Actions.update({ "START" : "self.Start()"})
        def USB_Connect(self):
                resources = self.rm.list_resources('?*')
                print(resources)
                for i in resources:
                        print(i)
                        if i.find("USB") != -1:
                                self.USB_addy = i.strip()
                                try:
                                        self.sig = self.rm.open_resource(self.USB_addy)
                                        IDN = self.sig.query("*IDN?\n")
                                        print(IDN)
                                        if IDN.find("SDG1032X") != -1:
                                                print("Connected to: ", str(IDN))
                                                return True
                                        else:
                                            pass
                                except:
                                        pass
                        else:
                                pass
                self.sig = False
                return False
        def checkFile(self, f, ff):     # check names of files with name f, number output files to avoid overwriting
                i = 2                           # format for f should be : NAME_n.txt with n = 1, 2, 3.....
                if os.path.isfile(self.path + f + ".txt") == False:
                        self.out = self.path + f + ".txt"       
                        self.exp = 1
                        print(" if condition met: ", os.path.isfile(self.path+f))
                        print("in os statement: ", self.path +f)
                        print("checkFile", self.exp)
                        self.outONLINE = "/var/www/html/fromFG/Exp_" + str(self.args[1]) + "-FG-log.txt"
                        return [self.out, self.exp, self.outONLINE]
                else:
                        while os.path.isfile(self.path+f+".txt") == True:
                                f = eval(ff)
                                print(f)
                                i = i + 1
                        self.out = self.path + str(f) + '.txt'
                        self.exp = str(i - 1)
                        self.outONLINE = "/var/www/html/fromFG/" + str(self.args[1])[2:] + "-FG-log.txt"
                        return [self.out, self.exp, self.outONLINE] # Program will use sequential file labels to

        def OpenClose(self, fname, message):
                out = open(fname, 'a+')
                out.write(message)
                out.close()
        def SetupDataWriteOut(self):
                print(self.checkFile(self.FirstOutFile, self.AssignOutFile))
                self.out, self.exp, self.outONLINE = self.checkFile(self.FirstOutFile, self.AssignOutFile)
                self.OF = open(self.out, 'w')
                self.OFONLINE = open(self.outONLINE, 'w')
                self.MASTER_ST = time.perf_counter()
                header = "Function Gen Log\n"+str(time.asctime())+"\nST: " + str(self.MASTER_ST) + "\nID tags: " + str(self.args[2]) + ", " + str(self.args[1])[2:] + ", FG-Run_" + str(self.exp) + "\nMasterNode: " + str(self.args[3]) + "\n---"
                self.OF.write(header)
                self.OFONLINE.write(header)
                self.OFONLINE.close()
        def ReadFile(self, infile): # read infile, return contents list of each line
                read = open(infile, 'r')
                lines = read.readlines()
                read.close()
                return(lines)
        def OpenMemory(self):
                self.mem = self.ReadFile(self.MemoryFile)
                self.mem_addy = self.mem[0]
                self.requests = self.mem[1]
                self.mem[2] = "busy"
                self.memory = open(self.MemoryFile, 'r+')
                for i in self.mem:
                        print("open mem i " , i)
                        self.memory.write(str(i))
                self.memory.close()
        def CloseMemory(self):
                self.rw_memory = open(self.MemoryFile, 'w')
                self.mem[2] = "idle"
                for i in self.mem:
                        print("CLose memi ", i)
                        self.rw_memory.write(str(i))
                self.rw_memory.close()
        def ConnectFromMemory(self):
                try:
                        self.rig = self.rm.open_resource(self.mem_addy)
                        print("Connected from memory.")
                        print("Connected to: ",self.rig.query("*IDN?"))
                        return True
                except:
                        print("Unable to connect from memory...\nProceed to auto-connect...")
                        return False
        def UpdateMemory(self, NewAddy):
                self.mem[0] = NewAddy + "\n"
        def VerifyIDN(self):
                try:    # if connection is made, check if dev takes SCPI,
                        self.idn = self.rig.query("*IDN?\n")    # check if we can acquire id
                        if str(self.idn)[:len(self.IDN)-1] == self.IDN[:len(self.IDN)-1]:               # check manufacturer and type to make sure it is right device
                                print("Rigol scope located\n")
                        else:
                                pass
                        return True
                except:
                        return False
        def Connect(self):
                self.OpenMemory()
                self.idn = self.USB_Connect()
                if self.idn != False:
                        if self.VerifyIDN() == True:
                                print("Connected to: ", self.idn)
                        else:
                                print("USB connection established with unknown device. Please connect to an SDG1032X Function Generator to use this program.")
                                self.CloseMemory()
                                sys.exit()
                else:
                        print("Failed to establish USB connection. Please check wiring")
                        self.CloseMemory()
                        sys.exit()
                return True
        def GetParams(self):
                time.sleep(0.2)
                try:
                        C1 = self.sig.query('C1:BSWV?')
                except:
                        C1 = 'None'
                        print("Unable to collect parameters for Channel 1")
                try:
                        C2 = self.sig.query('C2:BSWV?')
                except:
                        C2 = 'None'
                        print("Unable to collect parameters for Channel 2")
                return C1, C2
        def LogAction(self, key):
                line = "\n" + str(time.perf_counter()-self.MASTER_ST) + ", " +key +     ", [" + ', '.join(self.GetParams()).strip("\n") + "]"
                try:
                    self.OF.write(line)
                    self.OpenClose(self.outONLINE, line)
                except:
                    f = "/home/pi/NishiDev/FG/ErrorReport.txt"
                    errf = open(f, 'a+')
                    errf.write("Error + (" + str(time.asctime()) + "): " + str(args) + "\n") 
                    errf.close()
        def TurnOutputOn(self, channel):
                self.sig.write('C'+str(channel)+':OUTP ON')
                key = "OUT_ON"
                self.LogAction(key)
        def TurnOutputOff(self, channel):
                self.sig.write('C'+str(channel)+':OUTP OFF')
                key = "OUT_OFF"
                self.LogAction(key)
        def SetSineWave(self,channel):
                self.sig.write('C'+str(channel)+':BSWV WVTP,SINE')
                key = "SINE"
                self.LogAction(key)
        def SetSquareWave(self,channel):
                self.sig.write('C'+str(channel)+':BSWV WVTP,SQUARE')
                key = "SQUARE"
                self.LogAction(key)
        def SetRampWave(self,channel):
                self.sig.write('C'+str(channel)+':BSWV WVTP,RAMP')
                key = "RAMP"
                self.LogAction(key)
        def SetPulseWave(self,channel):
                self.sig.write('C'+str(channel)+':BSWV WVTP,PULSE')
                key = "PULSE"
                self.LogAction(key)
        def SetNoiseWave(self,channel):
                self.sig.write('C'+str(channel)+':BSWV WVTP,NOISE')
                key = "NOISE"
                self.LogAction(key)
        def SetArbWave(self,channel):
                self.sig.write('C'+str(channel)+':BSWV WVTP,ARB')
                key = "ARB"
                self.LogAction(key)
        def SetDCWave(self,channel):
                self.sig.write('C'+str(channel)+':BSWV WVTP,DC')
                key = "DC"
                self.LogAction(key)
        def SetPRBSWave(self,channel):
                self.sig.write('C'+str(channel)+':BSWV WVTP,PRBS')
                key = "PRBS"
                self.LogAction(key)
        def SetFrequency(self,channel, freq): # in kHz
                key = "FREQ"
                try:
                        self.sig.write('C'+str(channel)+':BSWV FRQ,' + str(float(freq)*1000))
                        self.LogAction(key)
                except:
                        print("Not a valid frequency, unable to set.")
        def SetPeriod(self,channel, period):
                key = "PERIOD"
                try:
                        self.sig.write('C'+str(channel)+':BSWV PERI,' + str(period))
                        self.LogAction(key)
                except:
                        print("Not a valid frequency, unable to set.")
        def SetV_pp(self, channel, Vpp):
                key = "VPP"
                try:
                        self.sig.write('C' + str(channel)+':BSWV AMP,' + str(Vpp))
                        self.LogAction(key)
                except:
                        print("Unable to set peak to peak amplitude to specified value.")
        def SetOffset(self, channel, off):
                key = "OFFSET"
                try:
                        self.sig.write('C' + str(channel)+':BSWV OFST,' + str(off))
                        self.LogAction(key)
                except:
                        print("Unable to set peak to peak amplitude to specified value.")
        def SetPhase(self, channel, degrees):
                key = "PHASE"
                try:
                        self.sig.write('C' + str(channel)+':BSWV PHSE,' + str(Vdegrees))
                        self.LogAction(key)
                except:
                        print("Unable to set peak to peak amplitude to specified value.")
        def Wait(self, duration):
                key = "WAIT"
                self.LogAction(key)
                try:
                        time.sleep(float(duration))
                except:
                        self.OF.write("\nCould not process wait time. Proceeding.")
                        self.OFONLINE.write("\nCould not process wait time. Proceeding.")
        def Run(self):
                req = str(self.ReadFile("/home/pi/NishiDev/FG/RunSpecs.txt")[0].strip("\n")).split(" & ")    # Each parent command sequence should be in its own line. do that with scraping
                print(req)
                for i in req:
                        time.sleep(0.1)
                        r = i.split(";")
                        print(r)
                        channel = r[0].replace('&', '').strip(' ')
                        action = r[1].replace(';', '').strip(' ')
                        try:
                                val = r[2].replace(';','').strip(' ')
                        except IndexError:
                                val = ' '
                        print("Channel: ", channel, ", Action: ", action, ", Specs: ", val)     
                        exc = self.Actions.get(action.upper(), "No action found")
                        print(exc)
                        if exc != "No action found":
                                eval(exc)
                        else:
                                self.LogAction(str(exc) + " FAILED")
        def Start(self):
                key = "START"
                self.SetupDataWriteOut()
                self.OpenMemory()       
                self.LogAction(key)
                if self.sig == False:
                        self.OF.write("\nFailed to connect. Exiting...")
                        self.OFONLINE.write("\nfailed to connect. Exiting...")
                        self.CloseMemory()
                        sys.exit()
        def Done(self):
                key = "DONE"
                self.LogAction(key)
                self.CloseMemory()





