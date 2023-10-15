# Release

This repository uses a github action to create releases. Checkout the action in `.github/workflows/release.yaml`.
In order to trigger the release you need to create a local `tag` and push it to the remote repository.

```shell
# create the tag
git tag -a <new version> -m '<new version>'

# and push it
git push --tags
```

alternatively you can use the existing `make` target:

```shell
make VERSION=<new version> release
```
