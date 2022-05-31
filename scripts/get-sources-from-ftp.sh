#!/usr/bin/env bash

# a (relatively) good place to store your credentials (outside repo, ...)
read ftp_credentials < ~/.lftp/leportail.credentials

if [ -z "$ftp_credentials" ]; then
  echo "Could NOT read credentials. Exiting."
  exit 2
fi

lftp -c " \
  set \
    ssl:verify-certificate no; \
    ftp:list-options -a; \
  open $ftp_credentials; \
  mirror \
      --continue \
      --verbose \
      --parallel=3 \
      -x '^tmp\/cache\/' \
    /v3/ \
    /home/gpenaud/work/ecolieu/website-le-portail/app/; \
  ; \
  close -a;"
