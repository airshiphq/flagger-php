<?php

namespace Airship;

class Flag
{
    public $flagName;

    /**
     * @var Airship
     */
    private $delegate;

    public function __construct($flagName, Airship $delegate)
    {
        $this->flagName = $flagName;
        $this->delegate = $delegate;
    }

    /**
     * @param Entity|array $entity
     *
     * @return string
     */
    public function getTreatment($entity)
    {
        return $this->delegate->getTreatment($this, $entity);
    }

    /**
     * @param Entity|array $entity
     *
     * @return mixed|null
     */
    public function getPayload($entity)
    {
        return $this->delegate->getPayload($this, $entity);
    }

    /**
     * @param Entity|array $entity
     *
     * @return bool
     */
    public function isEligible($entity)
    {
        return $this->delegate->isEligible($this, $entity);
    }

    /**
     * @param Entity|array $entity
     *
     * @return bool
     */
    public function isEnabled($entity)
    {
        return $this->delegate->isEnabled($this, $entity);
    }
}
