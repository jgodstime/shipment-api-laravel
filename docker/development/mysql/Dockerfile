FROM mysql:8.4.0

ARG HOST_UID

RUN usermod -u $HOST_UID mysql && groupmod -g $HOST_UID mysql
