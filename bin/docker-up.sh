echo "Starting containers"
docker-compose up -d
echo "Starting shell on web container"
docker-compose exec --user 1000:1000 web bash
