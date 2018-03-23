#!/bin/bash
mkdir -p {ctemplates,tmp,log,webroot/{upload,public_upload,cache,tmp},private_upload}
chmod 777 ctemplates/
chmod 777 webroot/upload
chmod 777 webroot/cache
chmod 777 webroot/public_upload
chmod 777 private_upload
chmod 777 log
chmod 777 tmp
chmod 777 webroot/tmp
cp script/log_bak.sh log/
chmod a+x log/log_bak.sh
