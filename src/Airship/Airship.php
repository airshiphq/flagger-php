<?php
namespace Airship;

use Airship\Client\ClientInterface;

class Airship
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function __toString()
    {
        return '[Airship object]';
    }

    /**
     * @param string $flagName
     *
     * @return Flag
     */
    public function flag($flagName)
    {
        return new Flag($flagName, $this);
    }

    /**
     * @param              $flag
     * @param Entity|array $entity
     *
     * @return array
     */
    private function getObjectValues($flag, $entity)
    {
        if ($entity instanceof Entity) {
            $entity = $entity->toArray();
        }

        $objectValues = $this->client->sendRequest([
            'flag' => $flag->flagName,
            'entity' => $entity,
        ]);

        return $objectValues;
    }

    /**
     * @param string       $flag
     * @param Entity|array $entity
     *
     * @return string
     */
    public function getTreatment($flag, $entity)
    {
        $objectValues = $this->getObjectValues($flag, $entity);
        if (isset($objectValues['treatment'])) {
            return $objectValues['treatment'];
        }

        return 'off';
    }

    /**
     * @param string       $flag
     * @param Entity|array $entity
     *
     * @return mixed|null
     */
    public function getPayload($flag, $entity)
    {
        $objectValues = $this->getObjectValues($flag, $entity);
        if (isset($objectValues['payload'])) {
            return $objectValues['payload'];
        }

        return null;
    }

    /**
     * @param string       $flag
     * @param Entity|array $entity
     *
     * @return bool
     */
    public function isEligible($flag, $entity)
    {
        $objectValues = $this->getObjectValues($flag, $entity);
        if (isset($objectValues['isEligible'])) {
            return $objectValues['isEligible'];
        }

        return false;
    }

    /**
     * @param string       $flag
     * @param Entity|array $entity
     *
     * @return bool
     */
    public function isEnabled($flag, $entity)
    {
        $objectValues = $this->getObjectValues($flag, $entity);
        if (isset($objectValues['isEnabled'])) {
            return $objectValues['isEnabled'];
        }

        return false;
    }
}
