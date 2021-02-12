#!/usr/bin/env bash

#app/DoctrineMigrations/Version20210115080234.php

#src/MwBundle/Resources/config/daos.yml
#src/MwBundle/Resources/config/exports.yml
SEARCHPATH="ClipBundle"
REPLACEPATH="VillaBundle"

SEARCHTEXT=("ClipBundle" "Clip |" "clip_" "@Clip" "clip.")
REPLACETEXT=("VillaBundle" "Villa |" "villa_" "@Villa" "villa.")

FILES="src/ClipBundle/Controller/AfsluitredenenVrijwilligersController.php
src/ClipBundle/Controller/BinnenViaController.php
src/ClipBundle/Controller/DeelnamesController.php
src/ClipBundle/Controller/DocumentenController.php
src/ClipBundle/Controller/KlantenController.php
src/ClipBundle/Controller/MemosController.php
src/ClipBundle/Controller/VerslagenController.php
src/ClipBundle/Controller/VrijwilligersController.php
src/ClipBundle/Entity/Afsluitreden.php
src/ClipBundle/Entity/BinnenVia.php
src/ClipBundle/Entity/Deelname.php
src/ClipBundle/Entity/Document.php
src/ClipBundle/Entity/Training.php
src/ClipBundle/Entity/Vrijwilliger.php
src/ClipBundle/Filter/VrijwilligerFilter.php
src/ClipBundle/Form/AfsluitredenType.php
src/ClipBundle/Form/BinnenViaType.php
src/ClipBundle/Form/DeelnameType.php
src/ClipBundle/Form/TrainingType.php
src/ClipBundle/Form/VrijwilligerCloseType.php
src/ClipBundle/Form/VrijwilligerFilterType.php
src/ClipBundle/Form/VrijwilligerType.php
src/ClipBundle/Resources/views/afsluitredenen_vrijwilligers/add.html.twig
src/ClipBundle/Resources/views/afsluitredenen_vrijwilligers/delete.html.twig
src/ClipBundle/Resources/views/afsluitredenen_vrijwilligers/edit.html.twig
src/ClipBundle/Resources/views/afsluitredenen_vrijwilligers/index.html.twig
src/ClipBundle/Resources/views/binnen_via/add.html.twig
src/ClipBundle/Resources/views/binnen_via/delete.html.twig
src/ClipBundle/Resources/views/binnen_via/edit.html.twig
src/ClipBundle/Resources/views/binnen_via/index.html.twig
src/ClipBundle/Resources/views/deelnames/_list.html.twig
src/ClipBundle/Resources/views/deelnames/add.html.twig
src/ClipBundle/Resources/views/deelnames/delete.html.twig
src/ClipBundle/Resources/views/deelnames/edit.html.twig
src/ClipBundle/Resources/views/documenten/_list.html.twig
src/ClipBundle/Resources/views/klanten/close.html.twig
src/ClipBundle/Resources/views/klanten/open.html.twig
src/ClipBundle/Resources/views/klanten/view.html.twig
src/ClipBundle/Resources/views/memos/_list.html.twig
src/ClipBundle/Resources/views/memos/add.html.twig
src/ClipBundle/Resources/views/memos/delete.html.twig
src/ClipBundle/Resources/views/memos/edit.html.twig
src/ClipBundle/Resources/views/subnavigation.html.twig
src/ClipBundle/Resources/views/vrijwilligers/add.html.twig
src/ClipBundle/Resources/views/vrijwilligers/close.html.twig
src/ClipBundle/Resources/views/vrijwilligers/delete.html.twig
src/ClipBundle/Resources/views/vrijwilligers/edit.html.twig
src/ClipBundle/Resources/views/vrijwilligers/index.html.twig
src/ClipBundle/Resources/views/vrijwilligers/view.html.twig
src/ClipBundle/Service/AfsluitredenDao.php
src/ClipBundle/Service/AfsluitredenDaoInterface.php
src/ClipBundle/Service/BinnenViaDao.php
src/ClipBundle/Service/BinnenViaDaoInterface.php
src/ClipBundle/Service/DeelnameDao.php
src/ClipBundle/Service/DeelnameDaoInterface.php
src/ClipBundle/Service/DocumenteDao.php
src/ClipBundle/Service/DeocumentDaoInterface.php
src/ClipBundle/Service/KlantDao.php
src/ClipBundle/Service/KlantDaoInterface.php
src/ClipBundle/Service/VrijwilligerDao.php
src/ClipBundle/Service/VrijwilligerDaoInterface.php
src/ClipBundle/Controller/LocatiesController.php
src/ClipBundle/Entity/Locatie.php
src/ClipBundle/Filter/LocatieFilter.php
src/ClipBundle/Form/LocatieSelectType.php
src/ClipBundle/Form/LocatieType.php
src/ClipBundle/Service/LocatieDao.php
src/ClipBundle/Service/LocatieDaoInterface.php
src/ClipBundle/Resources/views/locaties/add.html.twig
src/ClipBundle/Resources/views/locaties/delete.html.twig
src/ClipBundle/Resources/views/locaties/edit.html.twig
src/ClipBundle/Resources/views/locaties/index.html.twig
src/ClipBundle/Resources/views/locaties/view.html.twig"

#FILES="src/MwBundle/Controller/DocumentenController.php"
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

