FROM richan/laravel-apache:php-7.4-latest

# ARG APP_ENV
ARG APP_KEY
ARG APP_URL
ARG CMS_URL
ARG ASSET_URL
ARG SANCTUM_STATEFUL_DOMAINS
ARG SESSION_DOMAIN
# ARG SESSION_SECURE_COOKIE
# ARG DB_HOST
ARG DB_DATABASE
# ARG DB_USERNAME
# ARG DB_PASSWORD
# ARG REDIS_HOST
# ARG REDIS_PORT
# ARG REDIS_PASSWORD
# ARG MAIL_DRIVER
# ARG MAIL_HOST
# ARG MAIL_PORT
# ARG MAIL_USERNAME
# ARG MAIL_PASSWORD
# ARG MAIL_ENCRYPTION
# ARG MAIL_FROM_ADDRESS
# ARG AWS_ACCESS_KEY_ID
# ARG AWS_SECRET_ACCESS_KEY
# ARG AWS_DEFAULT_REGION
# ARG AWS_BUCKET
ARG AWS_BUCKET_ROOT
# ARG AWS_URL
# ARG MEDIA_DISK
# ARG PUSHER_APP_ID
# ARG PUSHER_APP_KEY
# ARG PUSHER_APP_SECRET
# ARG PUSHER_APP_CLUSTER
# ARG PUSHER_APP_KEY
ARG SENTRY_LARAVEL_DSN
# ARG NOCAPTCHA_SITEKEY
# ARG NOCAPTCHA_SECRET
ARG PUBSUB_QUEUE
# ARG PUBSUB_PROJECT_ID
ARG PUBSUB_SUBSCRIBER_NAME
ARG ALLOW_EVENT_INVOCATION

# ENV APP_ENV=${APP_ENV}
ENV APP_KEY=${APP_KEY}
ENV APP_URL=${APP_URL}
ENV CMS_URL=${CMS_URL}
ENV ASSET_URL=${ASSET_URL}
ENV SANCTUM_STATEFUL_DOMAINS=${SANCTUM_STATEFUL_DOMAINS}
ENV SESSION_DOMAIN=${SESSION_DOMAIN}
# ENV SESSION_SECURE_COOKIE=${SESSION_SECURE_COOKIE}
# ENV DB_HOST=${DB_HOST}
ENV DB_DATABASE=${DB_DATABASE}
# ENV DB_USERNAME=${DB_USERNAME}
# ENV DB_PASSWORD=${DB_PASSWORD}
# ENV REDIS_HOST=${REDIS_HOST}
# ENV REDIS_PORT=${REDIS_PORT}
# ENV REDIS_PASSWORD=${REDIS_PASSWORD}
# ENV MAIL_DRIVER=${MAIL_DRIVER}
# ENV MAIL_HOST=${MAIL_HOST}
# ENV MAIL_PORT=${MAIL_PORT}
# ENV MAIL_USERNAME=${MAIL_USERNAME}
# ENV MAIL_PASSWORD=${MAIL_PASSWORD}
# ENV MAIL_ENCRYPTION=${MAIL_ENCRYPTION}
# ENV MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}
# ENV AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID}
# ENV AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY}
# ENV AWS_DEFAULT_REGION=${AWS_DEFAULT_REGION}
# ENV AWS_BUCKET=${AWS_BUCKET}
ENV AWS_BUCKET_ROOT=${AWS_BUCKET_ROOT}
# ENV AWS_URL=${AWS_URL}
# ENV MEDIA_DISK=${MEDIA_DISK}
# ENV PUSHER_APP_ID=${PUSHER_APP_ID}
# ENV PUSHER_APP_KEY=${PUSHER_APP_KEY}
# ENV PUSHER_APP_SECRET=${PUSHER_APP_SECRET}
# ENV PUSHER_APP_CLUSTER=${PUSHER_APP_CLUSTER}
# ENV PUSHER_APP_KEY=${PUSHER_APP_KEY}
ENV SENTRY_LARAVEL_DSN=${SENTRY_LARAVEL_DSN}
# ENV NOCAPTCHA_SITEKEY=${NOCAPTCHA_SITEKEY}
# ENV NOCAPTCHA_SECRET=${NOCAPTCHA_SECRET}
ENV PUBSUB_QUEUE=${PUBSUB_QUEUE}
# ENV PUBSUB_PROJECT_ID=${PUBSUB_PROJECT_ID}
ENV PUBSUB_SUBSCRIBER_NAME=${PUBSUB_SUBSCRIBER_NAME}
ENV ALLOW_EVENT_INVOCATION=${ALLOW_EVENT_INVOCATION}

COPY . /var/www/html/

WORKDIR /var/www/html

# Remove private packages
RUN composer config --unset repositories.0
RUN composer remove "richan-fongdasen/laravel-api-generator" --dev --no-update

# Install the dependency packages
RUN composer install --optimize-autoloader --no-dev

# Remove the sample env files.
RUN rm .env.gcr.example
RUN rm .env.example
# RUN php artisan key:generate

# Optimize Laravel
RUN php artisan clear-compiled
RUN php artisan optimize
RUN php artisan storage:link

# This command will fix (ERROR: Invalid route action) especially if there is any route using a closure action
# For better performance, it is recommended to keep this command disabled.
#
# RUN php artisan route:clear

RUN chown -R www-data:www-data storage/
RUN chmod -R 0775 storage/
