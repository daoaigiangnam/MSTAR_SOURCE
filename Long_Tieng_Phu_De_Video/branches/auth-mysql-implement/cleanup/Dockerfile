FROM alpine:3.22

RUN apk add --no-cache bash coreutils findutils sqlite

WORKDIR /app
COPY cleanup.sh /app/cleanup.sh
RUN chmod +x /app/cleanup.sh

CMD ["/bin/bash", "/app/cleanup.sh"]
