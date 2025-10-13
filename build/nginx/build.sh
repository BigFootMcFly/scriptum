#!/bin/bash

# ---------------------------------------------------------------------------
# setting constants
PACKAGE_REPOSITORY=git.magrathea.hu/bigfoot/scriptum
BUILD_TAG=nginx
UID=$(id -u)
GID=$(id -g)

# ---------------------------------------------------------------------------

clear

echo This is a sample build script, if the workflow cannot be used.
echo Please customize the repository address/name for your own.
echo

# ---------------------------------------------------------------------------
echo Building "${BUILD_TAG}" package ..
echo

# ---------------------------------------------------------------------------
echo Changing to project root directory...

pushd ../.. > /dev/null

# ---------------------------------------------------------------------------
echo Building assets...

pushd src > /dev/null
npm run build
popd > /dev/null

# ---------------------------------------------------------------------------
echo Determining tag name...

branch=$(git branch --show-current)
tag=${PACKAGE_TAG:-temp}

[[ $branch == dev ]] && tag=testing
[[ $branch == main ]] && tag=latest
[[ $branch == master ]] && tag=latest

# ---------------------------------------------------------------------------
echo Creating multi platform driver...

docker buildx create --name goliath; docker buildx use goliath; docker buildx inspect --bootstrap;

# ---------------------------------------------------------------------------
echo Building image...

docker build . \
    --tag ${PACKAGE_REPOSITORY}:$tag \
    --build-arg GROUP_ID=${GID} \
    --build-arg USER_ID=${UID} \
    --file build/${BUILD_TAG}/Dockerfile \
    --provenance=false \
    --sbom=false \
#    --push \

# ---------------------------------------------------------------------------
echo Changing back to build directory...

popd > /dev/null

# ---------------------------------------------------------------------------
echo Done.
