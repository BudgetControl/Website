FROM php:8.2.4RC1-apache

RUN apt update \
        && apt install -y \
            g++ \
            libicu-dev \
            libpq-dev \
            libzip-dev \
            zip \
            zlib1g-dev \
        && docker-php-ext-install \
            intl \
            opcache \
            pdo \
            mysqli \
            pdo_mysql 
RUN a2enmod rewrite
RUN service apache2 restart
RUN mkdir /var/www/logs
WORKDIR /var/www/workdir
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

###########################################
# apache conf
###########################################

COPY bin/apache/default.conf /etc/apache2/sites-available/budgetcontrol.cloud.conf
RUN a2ensite budgetcontrol.cloud.conf
RUN a2enmod rewrite

###########################################

RUN mkdir /var/www/script
COPY bin/entrypoint.sh /var/www/script/entrypoint.sh
RUN chmod +x /var/www/script/entrypoint.sh

EXPOSE 3000

ENTRYPOINT [ "/var/www/script/entrypoint.sh" ] 