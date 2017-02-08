#!/bin/bash

# Exit immediately on errors.
# But don't echo everything because of the TERMINUS_TOKEN
# Once CircleCI 2.0 is out, this should move to a shared Dockerfile
set -e

# drops-7 does not provide a settings.php file

cd $SITE_DIR
cp sites/default/default.settings.php sites/default/settings.php

terminus auth:login -n --machine-token="$TERMINUS_TOKEN"
terminus build-env:delete:ci -n "$TERMINUS_SITE" --keep=4 --yes
terminus env:wake -n "$TERMINUS_SITE.dev"
terminus build-env:create -n "$TERMINUS_SITE.dev" "$TERMINUS_ENV" --yes
