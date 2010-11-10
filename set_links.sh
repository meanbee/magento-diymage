#!/bin/bash

PWD=`pwd`

cd $PWD
cd app/code/local/Meanbee
ln -s ../../../../diymage/app/code/local/Meanbee/Diy/ Diy

cd $PWD
cd app/etc/modules
ln -s ../../../diymage/app/etc/modules/Meanbee_Diy.xml .

cd $PWD
cd app/design/adminhtml/default/default/layout
ln -s ../../../../../../diymage/app/design/adminhtml/default/default/layout/diy.xml .
cd ../template/
ln -s ../../../../../../diymage/app/design/adminhtml/default/default/template/diy/ diy

cd $PWD
cd app/design/frontend
ln -s ../../../diymage/app/design/frontend/diy .

cd $PWD
cd skin/adminhtml/default/default
ln -s ../../../../diymage/skin/adminhtml/default/default/diy/ diy

cd $PWD
cd skin/frontend
ln -s ../../diymage/skin/frontend/diy .