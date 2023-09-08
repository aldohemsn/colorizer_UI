#!/bin/bash

# Check if both AUTH_USER and AUTH_PASS are set and not empty
if [[ -n "${AUTH_USER}" && -n "${AUTH_PASS}" ]]; then
	    # Generate .htpasswd file from environment variables
	        echo "${AUTH_USER}:$(openssl passwd -apr1 ${AUTH_PASS})" > /tmp/.htpasswd
fi

# Start Apache in the foreground
exec apache2-foreground

