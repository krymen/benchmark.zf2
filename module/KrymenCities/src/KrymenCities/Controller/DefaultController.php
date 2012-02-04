<?php

namespace KrymenCities\Controller;

use Zend\Mvc\Controller\ActionController;

class DefaultController extends ActionController
{
    protected $em;

    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    public function indexAction()
    {
        $topCities = $this->em->getRepository('Wowo\Cities\Entity\City')->findTop10Cities();
        $topStates = $this->em->getRepository('Wowo\Cities\Entity\State')->findTop10States();
        return array(
                'topCities' => $topCities,
                'topStates' => $topStates,
                'topCitiesSummary' => $this->calculateSummary($topCities, true),
                'topStatesSummary' => $this->calculateSummary($topStates, false),
        );
    }

    protected function calculateSummary($rows, $isObject)
    {
        $summary = array('population' => 0, 'landArea' => 0);
        foreach ($rows as $row) {
            $summary['population'] += $isObject ? $row->getPopulation() : $row['sumPopulation'];
            $summary['landArea']   += $isObject ? $row->getLandArea()   : $row['sumLandArea'];
        }
        return $summary;
    }
}
