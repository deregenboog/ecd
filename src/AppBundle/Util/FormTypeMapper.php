<?php

namespace AppBundle\Util;

class FormTypeMapper
{

    /**
     * Maps an entity's FQCN to its corresponding form type FQCN.
     *
     * @param string $entityClass The FQCN of the entity.
     * @return string The FQCN of the corresponding form type.
     */
    public static function mapEntityToFQCNFormType(string $entityClass): ?string
    {
        // Use reflection to get the short name of the class
        $reflectedClass = new \ReflectionClass($entityClass);
        $shortName = $reflectedClass->getShortName();

        // Construct the form type's class name by assuming the form types follow a specific naming pattern
        // Here we're assuming that form types are in the "Form" namespace parallel to the "Entity" namespace
        // and that the form type's name is the entity's short name followed by "Type"
        $formTypeClassName = str_replace('Entity', 'Form', $reflectedClass->getNamespaceName()) . '\\' . $shortName . 'Type';

        // Check if the class exists before returning it
        if (class_exists($formTypeClassName)) {
            return $formTypeClassName;
        }

        // Return null if the form type class does not exist
        return null;
    }

}