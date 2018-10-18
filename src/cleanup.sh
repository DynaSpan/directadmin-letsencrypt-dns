#!/bin/bash

wget -q "https://example.com/letsencrypt-wildcard.php?pass={{MYPASS}}&certbot_domain=$CERTBOT_DOMAIN&certbot_validation=$CERTBOT_VALIDATION&cleanup" -O /dev/null &> /dev/null

sleep 5