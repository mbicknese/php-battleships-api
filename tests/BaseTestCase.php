<?php

namespace App\Tests;

use App\AppKernel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTestCase extends WebTestCase
{
    protected static $class = AppKernel::class;

    /**
     * Creates schema
     */
    protected static function createSchema()
    {
        if (self::$kernel === null) {
            throw new \RuntimeException('Can not create schema before kernel boot');
        }
        /** @var EntityManager $em */
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($em);
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->updateSchema($metadata, true);
    }
}
