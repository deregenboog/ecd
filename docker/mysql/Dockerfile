FROM mysql:8.0 AS build-stage

ENV MYSQL_ROOT_PASSWORD ecd
ENV MYSQL_DATABASE ecd
ENV MYSQL_USER ecd
ENV MYSQL_PASSWORD ecd

# Alter docker-entrypoint.sh, so it doesn't start the server after db initialization
RUN sed -i 's/^\s*exec "\$@"//g' /usr/local/bin/docker-entrypoint.sh

# Copy SQL-files
COPY sql/* /docker-entrypoint-initdb.d/

# Initialize database in custom datadir
RUN [ "docker-entrypoint.sh", "mysqld", "--datadir", "/mysql-data" ]


FROM mysql:8.0
#
# Copy datadir from build-stage
COPY --from=build-stage /mysql-data /var/lib/mysql

# Set sql_mode to default values except ONLY_FULL_GROUP_BY (see https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_sql_mode)
CMD [ "mysqld", "--sql_mode", "STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION" ]
