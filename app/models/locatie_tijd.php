<?php

class LocatieTijd extends AppModel
{
    public $name = 'LocatieTijd';
    public $displayField = 'sluitingstijd';

    public $belongsTo = array(
        'Locatie' => array(
            'className' => 'Locatie',
            'foreignKey' => 'locatie_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    public function getLocationClosingTimes()
    {
        $recs = $this->find('all', array(
            'recursive' => -1,
        ));
        
        $result = array();
        foreach ($recs as $rec) {
            $locationId = $rec['LocatieTijd']['locatie_id'];
            $dayOfWeek = $rec['LocatieTijd']['dag_van_de_week'];
            $closingTime = $rec['LocatieTijd']['sluitingstijd'];
            if (empty($result[$locationId])) {
                $result[$locationId] = array($dayOfWeek => $closingTime);
            } else {
                $result[$locationId][$dayOfWeek] = $closingTime;
            }
        }
        
        return $result;
    }

    public function getClosingTime($locationId, $date)
    {
        static $closingTimes;
        if (! $closingTimes) {
            $closingTimes = $this->getLocationClosingTimes();
        }
    
        $dayOfWeek = date('w', $date);
        if (empty($closingTimes[$locationId][$dayOfWeek])) {
            return null;
        } else {
            return strtotime(
                date('Y-m-d', $date).' '.
                $closingTimes[$locationId][$dayOfWeek]
            );
        }
    }

    public function getLastClosingTime($locationId, $date)
    {
        if (!is_numeric($date)) {
            $date = strtotime($date);
        }
        $original = $date;
        $cnt=7;
        do {
            $closingTime = $this->getClosingTime($locationId, $date);
            $date = strtotime('-1day', $date);
            $cnt --;
            if ($cnt == 0) {
                break;
            }
        } while ($closingTime == null || $closingTime > $original);
        return $closingTime;
    }

    public function isOpen($locationId, $now)
    {
        $openingTimeCorrection = Configure::read('openingTimeCorrectionSec');
        $dateTime = date('Y-m-d H:i:s', $now);

        $date = date('Y-m-d', $now);
        $prevDate = date('Y-m-d', $now - DAY);
        $nextDate = date('Y-m-d', $now + DAY);

        $dayOfWeek = date('w', $now);
        $prevDayOfWeek = $dayOfWeek - 1 == -1 ? 0 : $dayOfWeek -1;
        $nextDayOfWeek = $dayOfWeek + 1 == 7 ? 0 : $dayOfWeek + 1;

        $isOpen = $this->find('count', array(
            'recursive' => -1,
            'conditions' => array(
                'locatie_id' => $locationId,
                'OR' => array(
                    array(
                        'dag_van_de_week' => $prevDayOfWeek,
                        "'$dateTime' BETWEEN ".
                            "DATE_SUB(DATE_ADD('$prevDate', INTERVAL openingstijd HOUR_SECOND), INTERVAL $openingTimeCorrection SECOND) ".
                            " AND ".
                            "DATE_ADD(DATE_ADD('$prevDate', INTERVAL sluitingstijd HOUR_SECOND), INTERVAL $openingTimeCorrection SECOND) ",
                    ),
                    array(
                        'dag_van_de_week' => $dayOfWeek,
                        "'$dateTime' BETWEEN ".
                            "DATE_SUB(DATE_ADD('$date', INTERVAL openingstijd HOUR_SECOND), INTERVAL $openingTimeCorrection SECOND) ".
                            " AND ".
                            "DATE_ADD(DATE_ADD('$date', INTERVAL sluitingstijd HOUR_SECOND), INTERVAL $openingTimeCorrection SECOND) ",
                    ),
                    array(
                        'dag_van_de_week' => $nextDayOfWeek,
                        "'$dateTime' BETWEEN ".
                            "DATE_SUB(DATE_ADD('$nextDate', INTERVAL openingstijd HOUR_SECOND), INTERVAL $openingTimeCorrection SECOND) ".
                            " AND ".
                            "DATE_ADD(DATE_ADD('$nextDate', INTERVAL sluitingstijd HOUR_SECOND), INTERVAL $openingTimeCorrection SECOND) ",
                    ),
                ),
            ),
        ));

        return (bool)$isOpen;
    }
}
