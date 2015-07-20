<?php

namespace Mentoring\User;

class CountryService
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $dbal;


    public function __construct($dbal)
    {
        $this->dbal = $dbal;
    }

    /**
     * @param string $countryIso
     * @return array
     */
    public function fetchStatesNameByCountry($countryIso)
    {
        $data = $this->dbal->fetchAll('SELECT name,iso FROM states WHERE country_iso = :country_iso', ['country_iso' => $countryIso]);

        $states = [];
        foreach ($data as $stateData) {
            $states[$stateData['name']] = $stateData['name'];
        }
        return $states;
    }

}
