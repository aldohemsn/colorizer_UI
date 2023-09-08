# Use the official PHP image with Apache
FROM php:8.2-apache

# Copy the apache2-utils deb package into the Docker image
COPY apache2-utils_2.4.57-2_amd64.deb /tmp/

# Install necessary tools from the provided deb package and clean up
RUN dpkg -i /tmp/apache2-utils_2.4.57-2_amd64.deb && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/apache2-utils_2.4.57-2_amd64.deb

# Disable the rewrite module
RUN a2dismod rewrite

# Enable the headers module
RUN a2enmod headers

# Copy the repository content to the Apache document root
COPY . /var/www/html/

# Remove the custom.conf and start.sh from the public directory (if they were accidentally placed there)
RUN rm -f /var/www/html/custom.conf /var/www/html/start.sh

# Copy the custom configuration to the appropriate location
COPY custom.conf /etc/apache2/sites-available/

# Set the environment variables to empty values
ENV AUTH_USER=
ENV AUTH_PASS=

# Enable the custom configuration and disable the default one
RUN a2ensite custom.conf && a2dissite 000-default

# Copy the startup script and make it executable
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expose port 80 for Apache
EXPOSE 80

# Use the custom startup command
CMD ["/usr/local/bin/start.sh"]

