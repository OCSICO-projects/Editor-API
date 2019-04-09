# Editor API

## Requirements

- Install docker repo for project and up needed containers

## Development

- Do all operations from `editor_workspace` container. Enter to the `editor_workspace` docker's container:
```
docker exec -it --user=laradock editor_workspace bash
```
- Deploy via `envoy`:
```
docker exec -it --user=laradock editor_workspace vendor/laravel/envoy/envoy run deploy [--migrate]
```