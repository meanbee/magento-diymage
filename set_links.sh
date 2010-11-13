#!/bin/bash -o xtrace

cd ../
mkdir app/code/local/Meanbee
cd app/code/local/Meanbee
ln -s ../../../../diymage/app/code/local/Meanbee/Diy/ Diy

cd ../../../etc/modules
ln -s ../../../diymage/app/etc/modules/Meanbee_Diy.xml .

cd ../../design/adminhtml/default/default/layout
ln -s ../../../../../../diymage/app/design/adminhtml/default/default/layout/diy.xml .
cd ../template/
ln -s ../../../../../../diymage/app/design/adminhtml/default/default/template/diy/ diy

cd ../../../../frontend
ln -s ../../../diymage/app/design/frontend/diy .

cd ../../../skin/adminhtml/default/default
ln -s ../../../../diymage/skin/adminhtml/default/default/diy/ diy

cd ../../../frontend
ln -s ../../diymage/skin/frontend/diy .
