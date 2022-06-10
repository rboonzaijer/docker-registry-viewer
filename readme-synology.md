


# Docker Registry Viewer

-  List all your docker images and tags from your private registry in one simple overview

# Synology NAS (DSM 7) - Private Docker Registry

### (for localhost install, check out: [readme.md](readme.md)

- This guide assumes your Synology NAS hostname is 'nas'
- The registry will be available at http://nas:5500
- The viewer will be available at http://nas:8500

![](docs/nas-01-hostname.png)

- Download the 'registry' image

![](docs/registry-01-download-image.png)

- Create a container by double clicking on the image
- Name the container 'docker-registry'

![](docs/registry-02-create.png)

- At 'Advanced Settings': make sure the 'Enable auto-restart' is checked

![](docs/registry-03-auto-restart.png)

- At 'Port Settings': set the local port to 5500

![](docs/registry-04-port-5500.png)

- Now start the container and you already have your private registry up and running!




# Docker Registry Viewer

- Download the image 'rboonzaijer/docker-registry-viewer'

![](docs/viewer-01-download-image.png)

- Create a container by double clicking on the image
- Name the container 'docker-registry-viewer'

![](docs/viewer-02-create.png)

- At 'Advanced Settings': make sure the 'Enable auto-restart' is checked

![](docs/viewer-03-auto-restart.png)

- At 'Port Settings': set the local port to 8500

![](docs/viewer-04-port-8500.png)

- At 'Links': Click on 'Add'
- Locate the docker container named 'docker-registry' and select it
- For convenience: give it the same alias name as the container name ('docker-registry')

![](docs/viewer-05-link-to-registry.png)

- At 'Environment':
- Add at least the required variables: HOST + HOST_UI.

| KEY     | VALUE                 | REQUIRED | DESCRIPTION |
| ------- | --------------------- | -------- | ----------- |
| HOST    | docker-registry:5000  | YES      | The internal hostname and port that the viewer uses to connect to the registry container (note: port 5000!) |
| HOST_UI | nas:5500              | YES      | Will be used for displaying and to quickly copy a pull command to the clipboard                             |
| HTTPS   | true or false         | -        | Will be used to call the api ( http://nas:5500/v2/_catalog or https://nas:5500/v2/_catalog )                |

![](docs/viewer-06-variables.png)

- Now start this container

# Update Docker settings

- Docker Desktop > Settings > Docker Engine

```
"insecure-registries": [
	"nas:5500"
],
```

# Upload some images to your registry
```
docker pull alpine:3.16 && \
docker pull alpine:3.15 && \
docker pull alpine:3.14 && \
docker pull php:8.1-alpine3.15 && \
docker pull php:8.1-alpine3.16 && \
docker image tag alpine:3.16 nas:5500/alpine:3.16 && \
docker image tag alpine:3.15 nas:5500/alpine:3.15 && \
docker image tag alpine:3.14 nas:5500/alpine:3.14 && \
docker image tag php:8.1-alpine3.15 nas:5500/php:8.1-alpine3.15 && \
docker image tag php:8.1-alpine3.16 nas:5500/php:8.1-alpine3.16 && \
docker push nas:5500/alpine:3.16 && \
docker push nas:5500/alpine:3.15 && \
docker push nas:5500/alpine:3.14 && \
docker push nas:5500/php:8.1-alpine3.15 && \
docker push nas:5500/php:8.1-alpine3.16
```

- Navigate to the Docker Registry Viewer: [http://nas:8500](http://nas:8500)

![](docs/preview-nas.png)


# Usage in Dockerfile: link to your own registry:
```
FROM nas:5500/php:8.1-alpine3.16
...
```
