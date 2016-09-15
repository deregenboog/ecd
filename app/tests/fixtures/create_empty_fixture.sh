# Read http://redmine.toltech.nl/projects/azavista/wiki/CommonFixtures

# This script takes an existing (baked) fixture, and creates another one based
# on it, with the prefix empty_ in the file name, enough to create the table
# but without any record.

# All empty_ fixtures are automatically added to the 'empty' common_fixtures
# collection, so that they are available in your tests if you do this:

#    public function __construct() {
#          new CommonFixture($this, array('empty'));
#    }

# This speeds up loading the common fixtures a lot, and you can care of only
# using the $fixtures you really need for your unit tests. With this, using the
# other common fixtures groups (hotel, location...) should become useless.


if [ "$1" == "" ]; then
    echo "Please provide the name of an existing fixture, as reference"
    exit 1
fi

if [ -f empty_$1 ]; then
    echo "empty_$1 already exists"
    exit 1
fi

FROM='class '
TO='class Empty'

echo "<?php " > empty_$1

CLASS=`grep 'class ' $1`
echo $CLASS | sed -e "s|$FROM|$TO|g" >> empty_$1

NAME=` grep 'var $name' $1`
echo $NAME >> empty_$1

TABLE=` grep 'var $table' $1`
echo $TABLE >> empty_$1

echo "	var \$fields = array( 'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'), ); " >> empty_$1

echo "	var \$records = array( ); " >> empty_$1

echo "} ?>" >> empty_$1

echo empty_$1  written
echo "---"

cat empty_$1 
