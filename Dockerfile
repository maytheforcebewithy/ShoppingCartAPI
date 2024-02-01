FROM php:8.3-cli

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y \
    zip \
    unzip \
    git \
    wget \
    postgresql-client \
    libpq-dev \
    netcat-openbsd

RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN docker-php-ext-install pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-scripts

EXPOSE 8000

CMD ["/usr/local/bin/symfony", "server:start", "--no-tls", "--port=8000", "--allow-http"]
