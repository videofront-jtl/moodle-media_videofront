#!/usr/bin/env bash

find ../ -name ".DS_Store"   -depth -exec rm -v {} \;

rm -f media_videofront.zip

zip -r ./media_videofront.zip ../videofront/ --exclude=*sh --exclude=*map --exclude=*scss --exclude=*.git* --exclude=.gitignore

open ./