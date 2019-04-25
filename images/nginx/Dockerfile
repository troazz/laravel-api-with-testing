FROM nginx:alpine

RUN apk --update add supervisor

RUN rm /var/cache/apk/*

COPY nginx.conf /etc/nginx/conf.d/app.conf
COPY supervisord-web.conf /etc/supervisord.conf

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
