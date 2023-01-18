FROM node:17

RUN apt-get update -y && \
    apt-get install -y --no-install-recommends gosu

COPY entrypoint.sh /usr/local/bin/entrypoint.sh

WORKDIR /app

EXPOSE 3000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
