FROM php:8.1-cli

RUN apt update && apt upgrade -y && apt -y install cron nano

RUN apt install libmagickwand-dev libmagickcore-dev -y \
&& docker-php-ext-install mysqli pdo_mysql fileinfo \
&& pecl install imagick && docker-php-ext-enable imagick

COPY ./etc/cron.d/cron /etc/cron.d/cron

RUN chmod +x /etc/cron.d/cron

## Apply cron job
RUN crontab /etc/cron.d/cron

RUN touch /var/log/cron.log
CMD cron && tail -f /var/log/cron.log