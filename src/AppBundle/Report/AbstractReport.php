<?php

namespace AppBundle\Report;

use AppBundle\Service\AbstractDao;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

abstract class AbstractReport
{
    /**
     * EntityRepository.
     */
    protected $repository;

    /**
     * @var AbstractDao
     */
    protected $dao;

    /**
     * @var \DateTime
     */
    protected $startDate;

    /**
     * @var \DateTime
     */
    protected $endDate;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $reports = [];

    /**
     * @var array
     */
    protected $tables = [];

    protected $beginstand;

    protected $gestart;

    protected $afgesloten;

    protected $eindstand;

    protected $xPath;

    protected $yPath;

    protected $nPath;

    protected $xDescription;

    protected $yDescription;

    public function getTitle()
    {
        return $this->title;
    }

    public function setFilter(array $filter)
    {
        if (array_key_exists('startdatum', $filter)) {
            $this->startDate = $filter['startdatum'];
        }

        if (array_key_exists('startdatum', $filter)) {
            $this->endDate = $filter['einddatum'];
        }

        return $this;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getReports()
    {
        $this->init();
        $this->build();

        return $this->reports;
    }

    public function getFormOptions()
    {
        return [
            'enabled_filters' => [
                'startdatum',
                'einddatum',
            ],
        ];
    }

    abstract protected function init();

    protected function build()
    {
        foreach ($this->tables as $title => $table) {
            $table = new Table($table, $this->xPath, $this->yPath, $this->nPath);
            $table->setStartDate($this->startDate)->setEndDate($this->endDate);

            $this->reports[] = [
                'title' => $title,
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ];
        }
    }

    /**
     * Get SQL from query
     *
     * @author Yosef Kaminskyi
     * @param QueryBuilderDql $query
     * @return int
     */
    public function getFullSQL($query)
    {
        $sql = $query->getSql();
        $paramsList = $this->getListParamsByDql($query->getDql());
        $paramsArr =$this->getParamsArray($query->getParameters());
        $fullSql='';
        for($i=0;$i<strlen($sql);$i++){
            if($sql[$i]=='?'){
                $nameParam=array_shift($paramsList);

                if(is_string ($paramsArr[$nameParam])){
                    $fullSql.= '"'.addslashes($paramsArr[$nameParam]).'"';
                }
                elseif(is_array($paramsArr[$nameParam])){
                    $sqlArr='';
                    foreach ($paramsArr[$nameParam] as $var){
                        if(!empty($sqlArr))
                            $sqlArr.=',';

                        if(is_string($var)){
                            $sqlArr.='"'.addslashes($var).'"';
                        }else
                            $sqlArr.=$var;
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
    protected function getParamsArray($paramObj)
    {
        $parameters=array();
        foreach ($paramObj as $val){
            /* @var $val Doctrine\ORM\Query\Parameter */
            $parameters[$val->getName()]=$val->getValue();
        }

        return $parameters;
    }
    public function getListParamsByDql($dql)
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
