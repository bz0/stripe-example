FROM mysql:8.0

COPY ./my.cnf /etc/mysql/conf.d/my.cnf

RUN mkdir /var/log/mysql
RUN chown mysql:mysql /var/log/mysql

RUN set -eux && \
 mkdir /var/lib/mysql-files