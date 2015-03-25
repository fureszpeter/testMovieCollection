#!/bin/bash

function writeOut(){
    local str=$1;
    local color=$2;
    echo "--------------------------------"
    echo "| $str" $color
    echo "--------------------------------"
}

cat <<EOF >>/etc/environment
LANGUAGE=en_US.UTF-8
LC_ALL=en_US.UTF-8
LANG=en_US.UTF-8
LC_TYPE=en_US.UTF-8
EOF

locale-gen en_US en_US.UTF-8 && sudo dpkg-reconfigure locales

writeOut "Provisioning virtual machine..."
writeOut "Updating to closest mirrors..."
sed -i.bak -r "s/^(deb|deb-src) (http[^ ]+) (.*)$/\1 mirror\:\/\/mirrors\.ubuntu\.com\/mirrors\.txt \3/" /etc/apt/sources.list
sudo apt-get update

writeOut "Installing Git and wget"
sudo apt-get install zsh git wget memcached mc mcedit -y

writeOut "Installing Nginx"
sudo apt-get install nginx -y

writeOut "Installing PHP"
sudo apt-get install php5-common php5-dev php5-cli php5-fpm -y
sed -i "s/user =.*/user = vagrant/" /etc/php5/fpm/pool.d/www.conf
sed -i "s/[;]cgi\.fix_pathinfo=.*/cgi\.fix_pathinfo\=1/" /etc/php5/fpm/php.ini

writeOut "Installing PHP extensions"
apt-get install curl php5-mysql php5-imagick php5-intl \
    php5-apcu php5-memcache php5-memcached php5-xdebug \
    php5-redis php5-curl php5-gd \
    php5-mcrypt php5-mysql php-gettext php-soap -y

sudo php5enmod mcrypt
sudo service php5-fpm restart

writeOut "Setting up Xdebug"
cat <<EOF >>/etc/php5/fpm/conf.d/20-xdebug.ini
xdebug.default_enable = 1
xdebug.idekey = "PHPSTORM"
xdebug.remote_enable = 1
xdebug.remote_autostart = 0
xdebug.remote_port = 9000
xdebug.remote_handler=dbgp
xdebug.remote_log="/var/log/xdebug/xdebug.log"
xdebug.remote_host=10.0.2.2 ; IDE-Environments IP, from vagrant box.
EOF

writeOut "Installing Composer for PHP"
mkdir -p /home/vagrant/bin && cd /home/vagrant/bin && curl -sS https://getcomposer.org/installer | php
ln -s /home/vagrant/bin/composer.phar /usr/local/bin/composer

writeOut "Setting up Nginx"
rm -rf /etc/nginx/sites-enabled/*
cp -r /vagrant/vagrant/config/etc/nginx/* /etc/nginx/
service nginx restart
service php5-fpm restart
