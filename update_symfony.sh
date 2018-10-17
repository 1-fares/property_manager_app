#!/bin/sh

/bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=2048 && \
/sbin/mkswap /var/swap.1 && \
/sbin/swapon /var/swap.1 && \
php -d memory_limit=-1 composer update && \
/sbin/swapoff /var/swap.1 && \
rm /var/swap.1
