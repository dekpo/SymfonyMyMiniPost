<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class TablePrefixEventListener
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
     *
     * @return void
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (!$classMetadata->isInheritanceTypeSingleTable() || $classMetadata->getName() === $classMetadata->rootEntityName) {
            $classMetadata->setPrimaryTable([
                'name' => $this->getPrefix($classMetadata->getName(), $classMetadata->getTableName()) . $classMetadata->getTableName()
            ]);
        }

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY && $mapping['isOwningSide']) {
                $mappedTableName = $mapping['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->getPrefix($mapping['targetEntity'], $mappedTableName) . $mappedTableName;
            }
        }
    }

    /**
     * @param string $className
     * @param string $tableName
     *
     * @return string
     */
    protected function getPrefix(string $className, string $tableName): string
    {
        
        // get the namespaces from the class name
        // $className might be "App\Calendar\Entity\CalendarEntity"

        // Fix(Dekpo) No need for $bundleName nor $nameSpaces but in case...
        /*
        $nameSpaces = explode('\\', $className);
         $bundleName = isset($nameSpaces[1]) ? strtolower($nameSpaces[1]) : null;
        if (!$bundleName || !isset($this->config[$bundleName])) {
            return '';
        }
        $prefix = $this->config[$bundleName];
        */

        $prefix = $this->config[0];

        // table is already prefixed
        if (strpos($tableName, $prefix) === 0) {
            return '';
        }

        return $prefix;
    }
}