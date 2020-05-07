FROM nginx

COPY docker/nginx/app.conf /etc/nginx/conf.d/app.conf

EXPOSE 80 443

ENTRYPOINT ["nginx"]

CMD ["-g", "daemon off;"]