#!/usr/bin/python
# -*- coding: utf8 -*-

'''
依赖cherrypy
'''
import struct
import socket

import os
import sys

import cherrypy

from cherrypy import request


class Wakeup(object):
    '''
    url /wakeup
    '''
    @cherrypy.expose
    def do(self):
        self.mac = request.params['mac']
        self.wake()
    def wake(self):
        '''
                            远程唤醒主机干活
                           发送magic packet Magic Packet，
                           其格式为：6个0xFF加16个目标网卡MAC地址，总长度为6+16*6=102个字节
          @see http://blog.csdn.net/catxl313/article/details/5218598
        '''
        
        
        addr_byte = self.mac.split(':')
        #组包
        hw_addr = struct.pack('bbbbbb', int(addr_byte[0], 16), \
                              int(addr_byte[1], 16),\
                              int(addr_byte[2], 16),\
                              int(addr_byte[3], 16),\
                              int(addr_byte[4], 16),\
                              int(addr_byte[5], 16),\
                              ) 
        msg = '\xff'*6 + hw_addr * 16
        
        #发送udp广播包
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        s.setsockopt(socket.SOL_SOCKET, socket.SO_BROADCAST, 1)
        s.sendto(msg, ('255.255.255.255', 9000))
        s.close()
def test():
    cherrypy.engine.autoreload.stop()
    cherrypy.engine.autoreload.unsubscribe()
    
    cherrypy.config.update(self.settings)
    cherrypy.tree.mount(Wakeup(), '/')
    cherrypy.engine.start()
        
if __name__ == '__main__':
    test()

