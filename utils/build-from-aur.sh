#!/bin/bash

#
# Copyright (c) 2024 Pryzmat sp. z o.o. (Pryzmat LLC)
# All rights reserved.
# 14.01.2025, 21:51
# build-from-aur.sh
# referral-hub
#
# This software and its accompanying documentation are protected by copyright law and international treaties.
# Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
# is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
#

# List of package names in the AUR
packages=(
  "php83"
  "php83-apache"
  "php83-bcmath"
  "php83-bz2"
  "php83-calendar"
  "php83-cgi"
  "php83-cli"
  "php83-ctype"
  "php83-curl"
  "php83-dba"
  "php83-dblib"
  "php83-dom"
  "php83-embed"
  "php83-enchant"
  "php83-exif"
  "php83-ffi"
  "php83-fileinfo"
  "php83-firebird"
  "php83-fpm"
  "php83-ftp"
  "php83-gd"
  "php83-gettext"
  "php83-gmp"
  "php83-iconv"
  "php83-imap"
  "php83-intl"
  "php83-ldap"
  "php83-litespeed"
  "php83-mbstring"
  "php83-mysql"
  "php83-odbc"
  "php83-opcache"
  "php83-openssl"
  "php83-pcntl"
  "php83-pdo"
  "php83-pear"
  "php83-pecl"
  "php83-pgsql"
  "php83-phar"
  "php83-phpdbg"
  "php83-posix"
  "php83-pspell"
  "php83-shmop"
  "php83-simplexml"
  "php83-snmp"
  "php83-soap"
  "php83-sockets"
  "php83-sodium"
  "php83-sqlite"
  "php83-sysvmsg"
  "php83-sysvsem"
  "php83-sysvshm"
  "php83-tidy"
  "php83-tokenizer"
  "php83-xml"
  "php83-xmlreader"
  "php83-xmlwriter"
  "php83-xsl"
  "php83-zip"
)

# Function to clone and build a package
build_package() {
  pkg_name="$1"
    echo "Cloning $pkg_name..."
    git clone "https://aur.archlinux.org/$pkg_name.git" && cd "./$pkg_name" && makepkg -si --noconfirm
    cd ..
}

# Build each package
for pkg in "${packages[@]}"; do
  build_package "$pkg"
done
