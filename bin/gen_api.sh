#!/bin/bash
# generate api documentation

# folder for api eport
OUTDIR=/tmp/knowledgeroot-api

# folder with libs of knowledgeroot
LIBDIR=`dirname $0`/../lib

# run phpdoc
phpdoc -d $LIBDIR -t $OUTDIR