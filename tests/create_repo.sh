#!/bin/bash
path=$1
cd $path
mkdir $2
git init 
git add .
git commit -m "test commit"
git commit -m "another commit"