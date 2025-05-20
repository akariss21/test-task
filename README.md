Launch steps:
1) git clone this project
2) cp ./backend/.env.example ./backend/.env
3) docker-compose build(or docker compose)
4) docker-compose up -d
5) Success
Additional points:
1) docker ps( to see all containers)
2) docker exec -it <container id> sh(to go to the shell directory of the current container)
3) docker exec -it <container id> bash(root of directory)
