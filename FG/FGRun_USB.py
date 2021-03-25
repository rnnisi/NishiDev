#!/usr/bin/env python3

import SiglentSDG1000X as FG
import time

siglent = FG.SiglentSDG1000X()
siglent.USB_Connect()
siglent.Start()
siglent.Run()
siglent.Done()


