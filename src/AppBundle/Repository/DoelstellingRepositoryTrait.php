<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Doelstelling;
use AppBundle\Exception\AppException;
use AppBundle\Model\Doelstellingcijfer;

trait DoelstellingRepositoryTrait
{
    protected $cijfers = null;

    public function getAvailableDoelstellingcijfers():array
    {
        if(!is_array($this->cijfers))
        {
            $this->initDoelstellingcijfers();
        }

        return $this->cijfers;
    }

    public function getDoelstelingcijfer($name): Doelstellingcijfer
    {
        if(!is_array($this->cijfers))
        {
            $this->initDoelstellingcijfers();
        }

        if(array_key_exists($name,$this->cijfers)) return $this->cijfers[$name];

        //throw new AppException("Doelstellingcijfer ($name) kan niet meer herleidt worden naar gegevens.");
        return new Doelstellingcijfer(0,"! $name (kan niet herleiden)",function($doelstelling){return 0; },"");

    }

    public function setVariable($name,$value)
    {
        $this->$name = $value;
    }

    private function addDoelstellingcijfer($humanDescription, $kpl,$label,\Closure $callback)
    {
        $this->cijfers[$label] = new Doelstellingcijfer($kpl,$label,$callback,$humanDescription);
    }
//    public function getMethods():array
//    {
//        $rc = new \ReflectionClass(get_class($this));
//        $m = $rc->getMethods(\ReflectionMethod::IS_PUBLIC);
//        $methods = [];
//        foreach($m as $method)
//        {
//            $params = $method->getParameters();
//            foreach ($params as $reflectionParameter) {
//               $type =  $reflectionParameter->getType();
//                if(null === $type) continue;
//
//                if($type->getName() == "AppBundle\Entity\Doelstelling") $methods[] = $method;
//            }
//        }
//        return $methods;
//    }
}