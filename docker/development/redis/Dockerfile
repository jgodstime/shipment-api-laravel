FROM redis:latest


ARG HOST_UID

RUN usermod -u $HOST_UID redis && groupmod -g $HOST_UID redis
