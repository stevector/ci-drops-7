#!/bin/bash

# Exit immediately on errors, and echo commands as they are executed.
# Once CircleCI 2.0 is out, these are commands that would move to a shared
# Dockerfile
set -ex

composer global require -n "hirak/prestissimo:^0.3"
composer global require -n "consolidation/cgr"
cgr "pantheon-systems/terminus:~1" --stability beta
terminus --version
cgr "drush/drush:~8"
mkdir -p ~/.terminus/plugins
composer create-project -n -d ~/.terminus/plugins pantheon-systems/terminus-build-tools-plugin:~1
composer create-project -n -d ~/.terminus/plugins pantheon-systems/terminus-secrets-plugin:~1
