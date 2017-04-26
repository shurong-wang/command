#!/usr/bin/expect -f

set user shurong
set host 114.55.179.26
set password asdzxc123
set timeout -1

spawn ssh $user@$host
expect "*password:"
send "$password\r"
send "cd ~/tenk/kquant-fe/kquantplatform\r"
send "git pull\r"
send "uwsgi --reload project-master.pid\r"

interact
