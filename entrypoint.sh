#!/bin/bash

# Écrire les variables d'environnement dans /etc/environment
echo "API_URL=${API_URL:-http://localhost/glpi/apirest.php}" >> /etc/environment
echo "USER_TOKEN=${USER_TOKEN:-J0kFNMg3GOrf4x1vYRM36LPSllQcetWumaWH7siN}" >> /etc/environment
echo "WORKDIR_PATH=${WORKDIR_PATH:-/var/www/html/}" >> /etc/environment
echo "APP_TOKEN=${APP_TOKEN:-NwBbL7EOwFixORF0MYP8mTzQJL8wBoSQWJySkZ1m}" >> /etc/environment

# Vérifier si la variable d'environnement CRON_SCHEDULE est définie, sinon utiliser une valeur par défaut
CRON_SCHEDULE=${CRON_SCHEDULE:-"*/30 * * * *"}
echo "CRON_SCHEDULE=${CRON_SCHEDULE}" >> /etc/environment 
echo "${CRON_SCHEDULE} /bin/bash -c 'source /etc/environment && /usr/local/bin/php /var/www/html/script_main.php >> /var/log/cron_script_glpi.log 2>&1'" > /etc/cron.d/crontab_glpi_planning

# Définir la timezone
TZ=${TIMEZONE:-Africa/Nairobi}

# Configurer le fichier de timezone
ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo ${TZ} > /etc/timezone

exec "$@"