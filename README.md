通过发送 magic packet  远程唤醒主机

1.前提   网卡支持 远程唤醒  并在bios设置开启
2.网卡高级设置中  唤醒功能 值设置为  幻数据包
3.网卡高级设置-电源管理 勾选允许此设备唤醒计算机

如果想在外网唤醒主机
4.请在路由器中设置 端口映射  端口任意

附： 如果路由器支持绑定花生壳，那么就可以通过域名发出magic packet

app功能请移步 https://github.com/lijianwei123/phone_close_pc

