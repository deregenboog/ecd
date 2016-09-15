#!/bin/bash

mysqldump --opt --add-drop-table ecd postcodegebieden > postcodegebieden.sql
mysqldump --opt --add-drop-table ecd stadsdelen > stadsdelen.sql
mysqldump --opt --add-drop-table ecd nationaliteiten > nationaliteiten.sql
mysqldump --opt --add-drop-table ecd landen > landen.sql
mysqldump --opt --add-drop-table ecd groepsactiviteiten_redenen > groepsactiviteiten_redenen.sql
mysqldump --opt --add-drop-table ecd zrm_settings > zrm_settings.sql

# use unchanged :
# management_report_views.sql
# metadata.sql
