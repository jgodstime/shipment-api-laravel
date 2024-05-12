FROM nginx:latest

ARG HOST_UID

RUN usermod -u $HOST_UID nginx && groupmod -g $HOST_UID nginx
