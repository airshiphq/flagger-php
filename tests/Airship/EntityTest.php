<?php

use Airship\Entity;
use Airship\TestCase;

class EntityTest extends TestCase
{
    public function testToArray()
    {
        $group = new Entity(2, 'Group', 'GroupName', ['att2' => 'val2']);

        $target = new Entity(1, 'Type', 'EntityName', ['att' => 'val'], $group);

        $this->assertEquals(
            [
                Entity::KEY_TYPE => 'Type',
                Entity::KEY_ID => 1,
                Entity::KEY_DISPLAY_NAME => 'EntityName',
                Entity::KEY_ATTRIBUTES => [
                    'att' => 'val',
                ],
                Entity::KEY_GROUP => [
                    Entity::KEY_TYPE => 'Group',
                    Entity::KEY_ID => 2,
                    Entity::KEY_DISPLAY_NAME => 'GroupName',
                    Entity::KEY_ATTRIBUTES => [
                        'att2' => 'val2',
                    ],
                    Entity::KEY_IS_GROUP => true
                ]
            ],
            $target->toArray()
        );
    }
}
