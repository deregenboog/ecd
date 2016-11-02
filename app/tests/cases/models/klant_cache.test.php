<?php


App::import('Model', 'Klant');
App::import('Model', 'Intake');
App::import('Model', 'Registratie');

class SimpleIntake extends Intake
{  // lightweight class
    public $actsAs = null;
    public $order = 'datum_intake DESC';

    public $hasOne = array();
    public $hasMany = array();
    public $hasAndBelongsToMany = array();

    public $belongsTo = array(
        'Klant' => array(
            'className' => 'SimpleKlant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
}

class SimpleRegistratie extends Registratie
{  // lightweight class
    public $actsAs = null;

    public $hasOne = array();
    public $hasMany = array();
    public $hasAndBelongsToMany = array();

    public $belongsTo = array(
        'Klant' => array(
            'className' => 'SimpleKlant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
}
 
class SimpleKlant extends Klant
{  // lightweight class
    public $actsAs = null;
    public $order = 'achternaam ASC';
    public $virtualFields = null;

    public $hasOne = array();

    public $belongsTo = array(
            'Geboorteland' => array(
                    'className' => 'Land',
                    'foreignKey' => 'land_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => ''
            ),
            'LasteIntake' => array(
                    'className' => 'SimpleIntake',
                    'foreignKey' => 'laste_intake_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => ''
            ),
            'LaatsteRegistratie' => array(
                    'className' => 'SimpleRegistratie',
                    'foreignKey' => 'laatste_registratie_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => ''
            )
    );
    
    public $hasMany = array(
            'Intake' => array(
                    'className' => 'SimpleIntake',
                    'foreignKey' => 'klant_id',
                    'dependent' => false,
                    'conditions' => '',
                    'fields' => '',
                    'order' => array(
                            'Intake.datum_intake DESC',
                            'Intake.modified DESC'
                    )
            ),
            'Registratie' => array(
                    'className' => 'SimpleRegistratie',
                    'foreignKey' => 'klant_id',
                    'dependent' => false,
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
                    'limit' => '',
                    'offset' => '',
                    'exclusive' => '',
                    'finderQuery' => '',
                    'counterQuery' => ''
                ),
            );
}



class KlantCacheTestCase extends CakeTestCase
{

    // var $fixtures = array('app.klant');

    public $fixtures = array('app.klant', 'app.geslacht', 'app.land', 'app.ldap_user', 'app.intake', 'app.registratie', 'app.medewerker', 'app.log');


    public function startTest()
    {
        $this->Model = & ClassRegistry::init('SimpleKlant');
        $this->Model->id = 1; // Use this fixture
        $this->_cacheDisable = Configure::read('Cache.disable');
        Configure::write('Cache.disable', false);
        registry_reset(true);
    }

    public function endTest()
    {
        Configure::write('Cache.disable', $this->_cacheDisable);
    }


    
    public function testRegistry()
    {
        $this->assertFalse(registry_isset('test', 'key'));

        registry_set('test', 'key', array('some value', 'other value'));
        $this->assertTrue(registry_isset('test', 'key'));

        $res = registry_get('test', 'key');
        $this->assertEqual($res[1], 'other value');

        registry_delete('test', 'key');
        $this->assertFalse(registry_get('test', 'key'));
    }


    public function testRelationsAreCachedCorrectly()
    {
        $key = $this->Model->getCacheKeyRelations($this->Model->id);
        $result = registry_get('relations', $key, true);

        $this->assertFalse($result);

        $relationCache = $this->Model->getRelatedToId($this->Model->id);
        $result = registry_get('relations', $key, true);

        $this->assertTrue($result);
        $this->assertEqual($relationCache, $result);
    }


    public function testRelationsAreCachedCorrectlyAfterCacheClear()
    {
        $key = $this->Model->getCacheKeyRelations($this->Model->id);
        $result = registry_get('relations', $key, true);

        $this->assertFalse($result);

        $relationCache = $this->Model->getRelatedToId($this->Model->id);
        $result = registry_get('relations', $key, true);

        $this->assertTrue($result);
        $this->assertEqual($relationCache, $result);
    }




    public function testFindRelatedHasBelongsToRelationShips()
    {
        $relationCache = $this->Model->getRelatedToId($this->Model->id);
        $notFound = array();

        foreach ($this->Model->belongsTo as $model => $info) {
            if (!array_key_exists($model, $relationCache)) {
                $notFound[] = $info['className'];
            }
        }
        if (!$this->assertTrue(empty($notFound), "I couldn't find some belongsTo relationships in the relations cache: "
            . implode(', ', $notFound) .
            " when searched in "
            . print_r($relationCache, true)
        )) {
            debug(array('found only' => array_keys($relationCache),
                'expected also' => array_keys($this->Model->belongsTo)));
        }
    }



    public function testFindRelatedHasHasManyRelationships()
    {
        $relationCache = $this->Model->getRelatedToId($this->Model->id);
        $notFound = array();
        foreach ($this->Model->hasMany as $model => $info) {
            if (!array_key_exists($model, $relationCache)) {
                $notFound[] = $info['className'];
            }
        }
        if (!$this->assertTrue(empty($notFound), "I couldn-t find some has many relationships in the relations cache: "
            . implode(', ', $notFound) .
            " when searched in "
            . print_r($relationCache, true)
        )) {
            debug(array('found only' => array_keys($relationCache),
                'expected also' => array_keys($this->Model->hasMany)));
        }
    }

    public function testDeleteRelationsCache()
    {
        $relations = $this->Model->getRelatedToId($this->Model->id);

        foreach ($relations as $model => $ids) {
            foreach ($ids as $id) {
                if (!empty($id)) {
                    // Force caching
                    $res = $this->Model->$model->getRelatedToId($id);

                    $key = $this->Model->$model->getCacheKeyRelations($id);
                    $cached = registry_get('relations', $key);
                }
            }
        }

        $this->Model->deleteRelationsCacheAndParentsRelationsCache($this->Model->id);

        $foundCaches = array();
        foreach ($relations as $model => $ids) {
            foreach ($ids as $id) {
                if (isset($this->Model->belongsTo[$model]) && !empty($id)) {
                    $key = $this->Model->$model->getCacheKeyRelations($id);
                    $cached = registry_get('relations', $key);
                    if ($cached) {
                        $foundCaches[] = "$model:$id";
                    }
                }
            }
        }
        $this->assertTrue(empty($foundCaches), 'Tried to delete parents relation caches, but found some not deleted ones: ' . implode(', ', $foundCaches));
    }

    public function testDeletePropertyCache()
    {
        $eventId = $this->Model->id;
        $this->Model->getById($eventId);
        $key = $this->Model->getCacheKey($eventId);
        $cache = Cache::read('properties.'.$key);
        $this->assertFalse(empty($cache), "Cache for event {$key} should have been set now, found empty!");
        $this->Model->deletePropertyCache($eventId);
        $cache = Cache::read('properties.'.$key);
        $this->assertTrue(empty($cache), "Cache for event {$key} should have been empty, found "
            . print_r($cache, true)
        );
    }


    public function testAfterSaveNotCreated_deletedPropertyCache()
    {
        $this->Model->id = $this->Model->id;
        $this->Model->afterSave(false);
        $key = $this->Model->getCacheKey($this->Model->id);
        $cache = Cache::read($key);
        $this->assertTrue(empty($cache), "After updating [saved, but not created],
            Cache for event {$key} should have been empty, found "
            . print_r($cache, true)
        );
    }

    public function testBeforeDelete_deletedRelationsCache()
    {
        $relations = $this->Model->getRelatedToId($this->Model->id);

        $this->Model->beforeDelete(null);

        $foundCaches = array();
        foreach ($relations as $model => $ids) {
            foreach ($ids as $id) {
                if (isset($this->Model->belongsTo[$model]) && !empty($id)) {
                    $key = $this->Model->$model->getCacheKeyRelations($id);
                    $cached = registry_get('relations', $key);
                    if ($cached) {
                        $foundCaches[] = "$model:$id";
                    }
                }
            }
        }
        $this->assertTrue(empty($foundCaches), "After deleting, the relations cache of the parent objects should be empty,
            but found some not deleted ones: " . implode(', ', $foundCaches)
        );
    }

    public function testBeforeDelete_deletedPropertyCache()
    {
        $this->Model->id = $this->Model->id;
        $this->Model->getById($this->Model->id);
        $key = $this->Model->getCacheKey($this->Model->id);
        $cached = registry_get('properties', $key, true);
        $this->assertTrue($cached);

        $this->Model->beforeDelete(null);
        $cached = registry_get('properties', $key, true);
        $this->assertFalse($cached, "After deleting the event {$this->Model->id},
            Cache for event {$key} should have been empty, found "
            . print_r($cached, true)
        );
    }

    public function testComputedPropertiesCaching()
    {
        $id = $this->Model->id;
        $res = $this->Model->getComputedPropertyById($id, 'prop_1');
        $this->assertFalse($res);
        $res = $this->Model->getComputedPropertyById($id, 'prop_2');
        $this->assertFalse($res);

        $this->Model->setComputedPropertyById($id, 'prop_1', 'value');
        $prop_2 =  array('a' => 1, 'b' => 2);
        $this->Model->setComputedPropertyById($id, 'prop_2', $prop_2);

        $res = $this->Model->getComputedPropertyById($id, 'prop_1');
        $this->assertEqual($res, 'value');
        $res = $this->Model->getComputedPropertyById($id, 'prop_2');
        $this->assertEqual($res, $prop_2);

        $this->Model->deletePropertyCache($id);
        $res = $this->Model->getComputedPropertyById($id, 'prop_1');
        $this->assertFalse($res);
        $res = $this->Model->getComputedPropertyById($id, 'prop_2');
        $this->assertFalse($res);
    }




    public function testGetAllById_cached_nonCached()
    {
        $id = $this->Model->id;

        $fromCache = $this->Model->getAllById($id);

        $fromFind = $this->Model->find('first', array(
            'conditions' => array($this->Model->alias . '.id' => $id),
            'recursive' => 1
        ));

        $this->assertEqual($fromCache, $fromFind);

        $fromCache2 = $this->Model->getAllById($id);

        $this->assertEqual($fromCache, $fromCache2);
    }

    public function testGetAllById_dataRemainsInCache()
    {
        $id = $this->Model->id;

        $fromCache = $this->Model->getById($id);

        // Data should be in in the cache:
        $type = 'properties';
        $key = $this->Model->getCacheKey($id);

        // First, in a global variable:

        if ($this->assertFalse(empty($GLOBALS['AplicationRegistry']["$type.$key"]))) {
            $this->assertEqual($fromCache, $GLOBALS['AplicationRegistry']["$type.$key"]);
        }

        // Force reading from memory cache by unsetting the globals:
        unset($GLOBALS['AplicationRegistry']["$type.$key"]);

        // It should be in memory cache too:
        $cached = registry_get('properties', $key, true);
        $this->assertTrue($cached);
        $this->assertEqual($fromCache, $cached);
    }

    public function testGetById_consecutiveReadsAreEqual()
    {
        $id = $this->Model->id;

        $fromCache = $this->Model->getById($id);
        $fromCache2 = $this->Model->getById($id);
        $this->assertEqual($fromCache, $fromCache2);
    }

    public function testGetAllById_consecutiveReadsAreEqual()
    {
        $id = $this->Model->id;

        $fromCache = $this->Model->getAllById($id);
        $fromCache2 = $this->Model->getAllById($id);
        $this->assertEqual($fromCache, $fromCache2);
    }



    public function testGetAllById_klantHasRightIntakes_afterIntakeCreation()
    {
        $id = $this->Model->id;
        $before = $this->Model->getAllById($id);
        $this->assertEqual($before['LasteIntake']['id'], 2);

        $countBefore = count($before['Intake']);

        $data = array(
            'Intake' => array(
                'klant_id' => 1,
                'medewerker_id' => 1,
                'datum_intake' => '2013-10-15',
                'verblijfstatus_id' => 1,
                'postadres' => 'Another Real Place',
                'postcode' => '1058AA',
                'woonplaats' => 'Amsterdam',
                'verblijf_in_NL_sinds' => '2010-10-01',
                'verblijf_in_amsterdam_sinds' => '2010-10-05',
                'legitimatie_id' => 1,
                'legitimatie_nummer' => 'Lorem ipsum dolor sit amet',
                'legitimatie_geldig_tot' => '2033-10-16',
                'primaireproblematiek_id' => 1,
                'primaireproblematieksfrequentie_id' => 1,
                'primaireproblematieksperiode_id' => 1,
                'verslavingsfrequentie_id' => 1,
                'verslavingsperiode_id' => 1,
                'verslaving_overig' => 'Lorem ipsum dolor sit amet',
                'eerste_gebruik' => '2013-10-16',
                'inkomen_overig' => 'Lorem ipsum dolor sit amet',
                'woonsituatie_id' => 1,
                'verwachting_dienstaanbod' => 'Something completely different',
                'toekomstplannen' => 'Something completely different',
                'opmerking_andere_instanties' => 'Something completely different',
                'medische_achtergrond' => 'Something completely different',
                'locatie1_id' => 1,
                'locatie2_id' => 2,
                'mag_gebruiken' => 1,
                'indruk' => 'Something completely different',
                'doelgroep' => 1,
                'informele_zorg' => 1,
                'dagbesteding' => 1,
                'inloophuis' => 1,
                'hulpverlening' => 1,
                'created' => '2012-10-16 12:41:05',
                'modified' => '2012-10-16 12:41:05',
                'locatie3_id' => 4,
                'infobaliedoelgroep_id' => 1,
                'toegang_vrouwen_nacht_opvang' => 1,
            ),
            'Inkomen' => array( 'Inkomen' => array( 0 => '1' ))

        );

        if (!$this->Model->Intake->save($data, false)) {
            debug($this->Model->Intake->validationErrors);
        } else {
            $after = $this->Model->getAllById($id);
            $this->assertEqual($after['LasteIntake']['id'], $this->Model->Intake->id);
            $countAfter = count($after['Intake']);
            $this->assertEqual($countAfter, $countBefore + 1);
        }
    }

    public function testGetAllById_klantHasRightRegistrations_afterRegistration()
    {
        $id = $this->Model->id;
        $before = $this->Model->getAllById($id);
        $this->assertEqual($before['LaatsteRegistratie']['id'], 3);

        $countBefore = count($before['Registratie']);

        $data = array(
            'locatie_id' => 3,
            'klant_id' => 1,
            'binnen' => date('Y-m-d H:i:s'),
        );

        if (!$this->Model->Registratie->save($data, false)) {
            debug($this->Model->Intake->validationErrors);
        } else {
            $after = $this->Model->getAllById($id);
            // Last registration is not set automatically, but by a controller action. Pity!
            // $this->assertEqual($after['LaatsteRegistratie']['id'], $this->Model->Registratie->id);
            $countAfter = count($after['Registratie']);
            $this->assertEqual($countAfter, $countBefore + 1);
        }
    }
}
