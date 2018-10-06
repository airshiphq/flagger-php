<?php

namespace Airship;

class Entity
{
    const KEY_ID           = 'id';
    const KEY_TYPE         = 'type';
    const KEY_DISPLAY_NAME = 'displayName';
    const KEY_ATTRIBUTES   = 'attributes';
    const KEY_GROUP        = 'group';
    const KEY_IS_GROUP     = 'isGroup';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var \Airship\Entity|null
     */
    private $group;

    /**
     * @var bool
     */
    private $isGroup;

    /**
     * @param int                  $id
     * @param string               $type
     * @param string|null          $displayName
     * @param array                $attributes
     * @param \Airship\Entity|null $group
     * @param bool                 $isGroup
     */
    public function __construct(
        $id,
        $type = 'User',
        $displayName = null,
        $attributes = [],
        $group = null,
        $isGroup = false
    ) {
        $this->id          = $id;
        $this->type        = $type;
        $this->displayName = $displayName !== null ? $displayName : (string) $id;
        $this->attributes  = $attributes;
        $this->group       = $group !== null ? $group->setIsGroup(true) : null;
        $this->isGroup     = $isGroup;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     *
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return \Airship\Entity|null
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param \Airship\Entity $group
     *
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGroup()
    {
        return $this->isGroup;
    }

    /**
     * @param bool $isGroup
     *
     * @return $this
     */
    public function setIsGroup($isGroup)
    {
        $this->isGroup = $isGroup;

        return $this;
    }

    public function toArray()
    {
        $array = [
            self::KEY_TYPE         => $this->type,
            self::KEY_ID           => $this->id,
            self::KEY_DISPLAY_NAME => $this->displayName,
        ];

        if (!empty($this->attributes)) {
            $array[self::KEY_ATTRIBUTES] = $this->attributes;
        }

        if (!empty($this->group) && $this->group instanceof Entity) {
            $array[self::KEY_GROUP] = $this->group->toArray();
        }

        if (!empty($this->isGroup)) {
            $array[self::KEY_IS_GROUP] = $this->isGroup;
        }

        return $array;
    }
}
