<?php

use Phinx\Migration\AbstractMigration;

class CountriesAndCities extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $countries = $this->table('countries');
        $countries->addColumn('name', 'string');
        $countries->addColumn('iso', 'string', array('limit' => 3));
        $countries->addIndex(['id'], ['unique' => true]);
        $countries->addIndex(['name']);
        $countries->addIndex(['iso']);
        $countries->create();

        $states = $this->table('states');
        $states->addColumn('name', 'string');
        $states->addColumn('iso', 'string', array('limit' => 10));
        $states->addColumn('country_iso', 'string', array('limit' => 3));
        $states->addIndex(['id'], ['unique' => true]);
        $states->addIndex(['name']);
        $states->addIndex(['iso']);
        $states->create();
        $states->addForeignKey('country_iso', 'countries', 'iso', array('delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'));

        $this->populateCountries();
        $this->populateStates();

        $users = $this->table('users');
        $users->addColumn('country', 'string', array('limit' => 3, 'null' => true));
        $users->addColumn('state', 'string', array('null' => true));
        $users->addColumn('city', 'string', array('null' => true));
        $users->addForeignKey('country', 'countries', 'iso', array('delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'));
        $users->addForeignKey('state', 'states', 'name', array('delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'));
        $users->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $users = $this->table('users');
        $users->dropForeignKey('country');
        $users->dropForeignKey('state');
        $users->removeColumn('country');
        $users->removeColumn('state');
        $users->removeColumn('city');
        $users->update();

        $this->dropTable('states');
        $this->dropTable('countries');
    }


    private function populateCountries()
    {
        $json_data  = file_get_contents(__DIR__.'/../fixtures/countries/countries.json');
        $result     = json_decode($json_data, true);
        foreach($result as $country) {
            $countryName = $country['name'];
            $countryIso  = $country['code'];
            $this->execute("INSERT INTO countries (name,iso) VALUES ('".$countryName."','".$countryIso."')");
        }
    }

    private function populateStates()
    {
        $directory      = __DIR__.'/../fixtures/states/';
        $files          = array_diff(scandir($directory), array('..', '.'));
        foreach($files as $stateInfo){
            $json_data  = file_get_contents($directory.$stateInfo);
            $result     = json_decode($json_data, true);
            if($result){
                $countryCode  = null;
                foreach($result as $state) {
                    if(!$countryCode){
                        $countryCode = substr($state['code'],0,2);
                    }
                    $stateName  = utf8_encode($state['name']);
                    $stateIso   = $state['code'];
                    $this->execute("INSERT INTO states (country_iso,name,iso) VALUES ('".$countryCode."','".$stateName."','".$stateIso."')");
                }
            }
        }
    }

}