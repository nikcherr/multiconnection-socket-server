FROM ubuntu:16.04
RUN apt-get update
RUN apt-get install -y nginx \
    php-cli

Run echo 'Hi, I am in your container' \
    >/usr/share/nginx/html/index.html

ENTRYPOINT ["nginx", "-g", "daemon off;"]
EXPOSE 80