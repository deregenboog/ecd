<?php
namespace AppBundle\Repository;

trait DoelstellingRepositoryTrait
{
    public function getMethods():array
    {
        $rc = new \ReflectionClass(get_class($this));
        $m = $rc->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methods = [];
        foreach($m as $method)
        {
            $params = $method->getParameters();
            foreach ($params as $reflectionParameter) {
               $type =  $reflectionParameter->getType();
                if(null === $type) continue;

                if($type->getName() == "AppBundle\Entity\Doelstelling") $methods[] = $method;
            }
        }
        return $methods;
    }
}