#!/bin/sh

/usr/sbin/crond -f -l 8 -L /dev/stdout &
/usr/sbin/httpd -DFOREGROUND
