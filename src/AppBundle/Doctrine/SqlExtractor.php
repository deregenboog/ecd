<?php
namespace AppBundle\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;

class SqlExtractor
{
    /**
     * Get SQL from query
     *
     * @author Yosef Kaminskyi
     * @param QueryBuilderDql $query
     * @return int
     */
    public static function getFullSQL($query)
    {
        $sql = $query->getSql();
        $paramsList = self::getListParamsByDql($query->getDql());
        $paramsArr =self::getParamsArray($query->getParameters());
        $fullSql='';
        for($i=0;$i<strlen($sql);$i++){
            if($sql[$i]=='?'){
                $nameParam=array_shift($paramsList);

                if(is_string ($paramsArr[$nameParam])){
                    $fullSql.= '"'.addslashes($paramsArr[$nameParam]).'"';
                }
                elseif(is_array($paramsArr[$nameParam])){ //should be recursive...
                    $sqlArr='';
                    foreach ($paramsArr[$nameParam] as $var){
                        if(!empty($sqlArr))
                            $sqlArr.=',';

                        if(is_string($var)){
                            $sqlArr.='"'.addslashes($var).'"';
                        }
                        else if(is_array($var))
                        {
                            $sqlArr.= array_values($var)[0];
                        }
                        else{
                            $sqlArr.= $var;
                        }

                    }
                    $fullSql.=$sqlArr;
                }elseif(is_object($paramsArr[$nameParam])){
                    switch(get_class($paramsArr[$nameParam])){
                        case \DateTime::class:
                            $fullSql.= "'".$paramsArr[$nameParam]->format('Y-m-d H:i:s')."'";
                            break;
                        default:
                            //@todo this should be recursive I think
                            if($paramsArr[$nameParam] instanceof ArrayCollection)
                            {
                                $t = array_map(function ($elm) {
                                    return $elm->getId();
                                }, $paramsArr[$nameParam]->toArray());
                                $fullSql .= implode(",",$t);
                            }
                            else
                            {
                                $fullSql.= $paramsArr[$nameParam]->getId();
                            }

                    }

                }
                else
                    $fullSql.= $paramsArr[$nameParam];

            }  else {
                $fullSql.=$sql[$i];
            }
        }
        return $fullSql;
    }

    /**
     * Get query params list
     *
     * @author Yosef Kaminskyi <yosefk@spotoption.com>
     * @param  Doctrine\ORM\Query\Parameter $paramObj
     * @return int
     */
    protected static function getParamsArray($paramObj)
    {
        $parameters=array();
        foreach ($paramObj as $val){
            /* @var $val Doctrine\ORM\Query\Parameter */
            $parameters[$val->getName()]=$val->getValue();
        }

        return $parameters;
    }
    public static function getListParamsByDql($dql)
    {
        $parsedDql = preg_split("/:/", $dql);
        $length = is_array($parsedDql) || $parsedDql instanceof \Countable ? count($parsedDql) : 0;
        $parmeters = array();
        for($i=1;$i<$length;$i++){
            if(ctype_alpha($parsedDql[$i][0])){
                $param = (preg_split("/[' ' )]/", $parsedDql[$i]));
                $parmeters[] = $param[0];
            }
        }

        return $parmeters;
    }
}