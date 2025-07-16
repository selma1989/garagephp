#Utiliser une image php officielle avec Apache
FROM php:8.2-apache 

#Installer les dépendences et bibliothèques
RUN apt-get update && apt-get install -y && apt-get install -y --no-install-recommends \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
#Ajouter ServerName
RUN echo "ServerName localhost" >> /etc/apache2.conf
#Activer le mod_rewrite d'apache pour les URLs
RUN a2enmod mod_rewrite

#Installer Composer
copy --from=compser:latest /urs/bin/composer /usr/bin/composer

#Définir le répertoire de travail
WORKDIR /var/www/html

#Copier les fichiers de dépendences et les installer
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

#Copier le reste du code de l'appli
COPY . .

#Executer le dump de l'autoloader de composer (performances)
RUN composer dump-autoload --optimize

#Changer propriétaire des fichiers afin de donner le droit au serveur d'écrire dans les fichiers (EX: logs)
RUN mkdir -p storage/Logs && \
    chown -R www-data:www-data /var/www/html/storage