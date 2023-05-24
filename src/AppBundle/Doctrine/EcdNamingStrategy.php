<?php
namespace AppBundle\Doctrine;

use Doctrine\ORM\Mapping\DefaultNamingStrategy;
use Doctrine\ORM\Mapping\NamingStrategy;

class EcdNamingStrategy extends DefaultNamingStrategy implements NamingStrategy
{

    /**
     * {@inheritdoc}
     */
    public function classToTableName($className): string
    {
        if (str_contains($className, '\\')) {
            return substr($className, strrpos($className, '\\') + 1);
        }

        return $className;
    }

    public function classToBundleName($className): string
    {
        $bundleName = "";
        if (str_contains($className, 'Bundle')) {
            $bundleName = substr($className, 0, strrpos($className, 'Bundle') );
        }
        return $bundleName;
    }

    /**
     * {@inheritdoc}
     */
    public function propertyToColumnName($propertyName, $className = null): string
    {
        return $propertyName;
    }

    /**
     * {@inheritdoc}
     */
    public function embeddedFieldToColumnName($propertyName, $embeddedColumnName, $className = null, $embeddedClassName = null): string
    {
        return $propertyName . '_' . $embeddedColumnName;
    }

    /**
     * {@inheritdoc}
     */
    public function referenceColumnName(): string
    {
        return 'id';
    }

    /**
     * {@inheritdoc}
     *
     * @param string       $propertyName
     * @param class-string $className
     */
    public function joinColumnName($propertyName, $className = null): string
    {
        return $propertyName . '_' . $this->referenceColumnName();
    }

    /**
     * {@inheritdoc}
     */
    public function joinTableName($sourceEntity, $targetEntity, $propertyName = null): string
    {
        $scbn = $this->classToBundleName($sourceEntity);
        $sctn = $this->classToTableName($sourceEntity);
        $tctn = $this->classToTableName($targetEntity);
        $jtn =  strtolower($scbn . '_' . $sctn . '_' .$tctn);

        return $jtn;
    }

    /**
     * {@inheritdoc}
     */
    public function joinKeyColumnName($entityName, $referencedColumnName = null): string
    {
        return strtolower($this->classToTableName($entityName) . '_' .
            ($referencedColumnName ?: $this->referenceColumnName()));
    }

}