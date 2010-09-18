# Sending an email through gmail using Python
import smtplib
fromaddr = '_____@gmail.com'
toaddrs = '_____@gmail.com'
msg = 'msg'

#provide gmail user name and password
username = 'gmailUserName'
password = 'gmailPassword'

# functions to send an email
server = smtplib.SMTP('smtp.gmail.com:587')
server.ehlo()
server.starttls()
server.ehlo()
server.login(username,password)
server.sendmail(fromaddr, toaddrs, msg)
server.quit()
