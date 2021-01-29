#!/usr/bin/env bash

#app/DoctrineMigrations/Version20210115080234.php

#src/MwBundle/Resources/config/daos.yml
#src/MwBundle/Resources/config/exports.yml
SEARCHPATH="MwBundle"
REPLACEPATH="ClipBundle"

SEARCHTEXT=("MwBundle" "Maatschappelijk werk |" "mw_","@Mw","mw.")
REPLACETEXT=("ClipBundle" "Clip |" "clip_","@Clip","clip.")

FILES="src/MwBundle/Controller/AfsluitredenenVrijwilligersController.php
src/MwBundle/Controller/BinnenViaController.php
src/MwBundle/Controller/DeelnamesController.php
src/MwBundle/Controller/DocumentenController.php
src/MwBundle/Controller/KlantenController.php
src/MwBundle/Controller/MemosController.php
src/MwBundle/Controller/VerslagenController.php
src/MwBundle/Controller/VrijwilligersController.php
src/MwBundle/Entity/Afsluitreden.php
src/MwBundle/Entity/BinnenVia.php
src/MwBundle/Entity/Deelname.php
src/MwBundle/Entity/Document.php
src/MwBundle/Entity/Training.php
src/MwBundle/Entity/Vrijwilliger.php
src/MwBundle/Filter/VrijwilligerFilter.php
src/MwBundle/Form/AfsluitredenType.php
src/MwBundle/Form/BinnenViaType.php
src/MwBundle/Form/DeelnameType.php
src/MwBundle/Form/TrainingType.php
src/MwBundle/Form/VrijwilligerCloseType.php
src/MwBundle/Form/VrijwilligerFilterType.php
src/MwBundle/Form/VrijwilligerType.php
src/MwBundle/Resources/views/afsluitredenen_vrijwilligers/add.html.twig
src/MwBundle/Resources/views/afsluitredenen_vrijwilligers/delete.html.twig
src/MwBundle/Resources/views/afsluitredenen_vrijwilligers/edit.html.twig
src/MwBundle/Resources/views/afsluitredenen_vrijwilligers/index.html.twig
src/MwBundle/Resources/views/binnen_via/add.html.twig
src/MwBundle/Resources/views/binnen_via/delete.html.twig
src/MwBundle/Resources/views/binnen_via/edit.html.twig
src/MwBundle/Resources/views/binnen_via/index.html.twig
src/MwBundle/Resources/views/deelnames/_list.html.twig
src/MwBundle/Resources/views/deelnames/add.html.twig
src/MwBundle/Resources/views/deelnames/delete.html.twig
src/MwBundle/Resources/views/deelnames/edit.html.twig
src/MwBundle/Resources/views/documenten/_list.html.twig
src/MwBundle/Resources/views/klanten/close.html.twig
src/MwBundle/Resources/views/klanten/open.html.twig
src/MwBundle/Resources/views/klanten/view.html.twig
src/MwBundle/Resources/views/memos/_list.html.twig
src/MwBundle/Resources/views/memos/add.html.twig
src/MwBundle/Resources/views/memos/delete.html.twig
src/MwBundle/Resources/views/memos/edit.html.twig
src/MwBundle/Resources/views/subnavigation.html.twig
src/MwBundle/Resources/views/vrijwilligers/add.html.twig
src/MwBundle/Resources/views/vrijwilligers/close.html.twig
src/MwBundle/Resources/views/vrijwilligers/delete.html.twig
src/MwBundle/Resources/views/vrijwilligers/edit.html.twig
src/MwBundle/Resources/views/vrijwilligers/index.html.twig
src/MwBundle/Resources/views/vrijwilligers/view.html.twig
src/MwBundle/Service/AfsluitredenDao.php
src/MwBundle/Service/AfsluitredenDaoInterface.php
src/MwBundle/Service/BinnenViaDao.php
src/MwBundle/Service/BinnenViaDaoInterface.php
src/MwBundle/Service/DeelnameDao.php
src/MwBundle/Service/DeelnameDaoInterface.php
src/MwBundle/Service/KlantDao.php
src/MwBundle/Service/KlantDaoInterface.php
src/MwBundle/Service/VrijwilligerDao.php
src/MwBundle/Service/VrijwilligerDaoInterface.php"

FILES="src/MwBundle/Controller/DocumentenController.php"
for f in $FILES
do

  destFile=`sed "s/$SEARCHPATH/$REPLACEPATH/g" <<< "$f"`
  destDir=`dirname $destFile`


  `mkdir -p $destDir`
  if [ ! -f $destFile ]; then
    `cp $f $destFile`
  else
    extension="${destFile##*.}"
    destFile="${destFile/$extension/$extension.new}"
    `cp $f $destFile`
  fi
  echo "Copied $f to $destFile"
  for i in ${!SEARCHTEXT[@]}
  do
    search=${SEARCHTEXT[$i]}
    replace=${REPLACETEXT[$i]}
  echo "Replaced $search for $replace in file"
  `sed -i.bu "s/$search/$replace/g" $destFile`
  done
done

