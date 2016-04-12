#!/bin/bash

if ! type "puppet" > /dev/null; then
    apt-get update && apt-get -y install puppet
fi