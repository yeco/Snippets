#!/bin/sh
# run with ./apache_refresh.sh
#If permissions error do: chmod u+x filename.sh
#WARNING THIS MAY DELETE OR CHANGE YOUR APACHE SETTING SO BACKUP IF YOU HAVE ANYTHING YOU WANT TO SAVE


echo "*******************************   Starting Apache Server Refresh - Leopard"

#make a temp directory to work with, you can change this if you choose
echo "*******************************   Creating the source folder"
mkdir ~/Desktop/apache-temp
cd ~/Desktop/apache-temp

#get current apache version, update or change if necessary 
echo "*******************************   Downloading Apache Server"
curl -O http://www.gtlib.gatech.edu/pub/apache/httpd/httpd-2.2.11.tar.gz

#if you change the apache version above be sure to change it here as well
echo "*******************************   Extracting Apache Server"
tar xzvf httpd-2.2.11.tar.gz
cd httpd-2.2.11

#you can change the default install paths, "Darwin" is the default leopard install, if you don't know what this means then don't change this!
echo "*******************************   Configuring Darwin Layout"
./configure --enable-layout=Darwin \
--enable-mods-shared=all \
--with-ssl=/usr \
--with-mpm=prefork \
--disable-unique-id \
--enable-ssl \
--enable-dav \
--enable-cache \
--enable-proxy \
--enable-logio \
--enable-deflate \
--with-included-apr \
--enable-cgi \
--enable-cgid \
--enable-suexec

#make the files
echo "*******************************   Compiling Apache Server"
make

#install the files
echo "*******************************   Installing Apache Server"
sudo make install

#backup hosts file
echo "*******************************   Backing up hosts file"
cd /private/etc
sudo cp hosts hosts.backup

#delete hosts file
echo "*******************************   Deleting Hosts File"
sudo rm hosts

echo "*******************************   Creating New Hosts File"
cd ~/Desktop/apache-temp
cat > hosts << EOF
##
# Host Database
#
# localhost is used to configure the loopback interface
# when the system is booting.  Do not change this entry.
##
127.0.0.1   localhost
255.255.255.255 broadcasthost
::1             localhost 
fe80::1%lo0 localhost
EOF

#move new hosts file to /etc dir
echo "*******************************   Moving Hosts Filer"
sudo mv ~/Desktop/apache-temp/hosts /etc

#set hosts file permissions
echo "*******************************   Setting Hosts File Permissions"
cd /private/etc
sudo chown root hosts

#backup httpd.conf file
echo "*******************************   Backing up httpd.conf File"
cd /private/etc/apache2
sudo cp httpd.conf httpd.conf.backup

#delete to httpd.conf file
echo "*******************************   Deleting httpd.conf File"
sudo rm httpd.conf

#copy httpd.conf file from originals folder
echo "*******************************   Copying New httpd.conf File"
cd original
sudo cp httpd.conf /private/etc/apache2/httpd.conf

#backup httpd-vhosts.conf file
echo "*******************************   Backing up httpd-vhosts.conf File"
cd /private/etc/apache2/extra
sudo cp httpd-vhosts.conf httpd-vhosts.conf.backup

#copy httpd-vhosts.conf file from originals extra folder
echo "*******************************   Copying New httpd-vhosts.conf File"
cd /private/etc/apache2/original/extra
sudo cp httpd-vhosts.conf /private/etc/apache2/extra/httpd-vhosts.conf

#remove the temp folder
echo "*******************************   Remove the temp folder"
rm -R ~/Desktop/apache-temp

echo "*******************************   Starting Apache Server"
sudo apachectl start

echo "*******************************   Done"