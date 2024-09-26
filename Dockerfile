# Utiliser l'image PHP officielle version 7.4
FROM php:7.4-cli

# Installer curl et cron et les paquets utiles
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    pkg-config \
    zip \
    unzip \
    cron \
    git \
    tzdata \
    && docker-php-ext-install curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* zip

# Activer l'extension curl pour PHP 
RUN docker-php-ext-install curl

# Copier votre script PHP dans le répertoire de travail
WORKDIR /var/www/html
COPY . .

# Définir la timezone
ENV TZ=Africa/Nairobi

# Configurer le fichier de timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Copier le fichier crontab pour l'exécution quotidienne à 4h du matin
COPY ./crontask/crontab_glpi_planning /etc/cron.d/crontab_glpi_planning

# Copier le fichier de log pour le crontab pour l'exécution quotidienne à 4h du matin
COPY ./crontask/cron_script_glpi.log /var/log/cron_script_glpi.log

# Donner les permissions nécessaires pour le fichier crontab
RUN chmod 644 /etc/cron.d/crontab_glpi_planning

# Donner les permissions correctes au fichier de log
RUN chmod 664 /var/log/cron_script_glpi.log

# Écrire les variables d'environnement dans /etc/environment
RUN echo "API_URL=http://localhost/glpi/apirest.php" >> /etc/environment && \
    echo "USER_TOKEN=J0kFNMg3GOrf4x1vYRM36LPSllQcetWumaWH7siN" >> /etc/environment && \
    echo "APP_TOKEN=NwBbL7EOwFixORF0MYP8mTzQJL8wBoSQWJySkZ1m" >> /etc/environment

# Appliquer le crontab
RUN crontab /etc/cron.d/crontab_glpi_planning