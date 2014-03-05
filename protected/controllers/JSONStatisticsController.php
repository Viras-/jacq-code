<?php
/**
 * Controller for fetching response
 *
 * @author aragon112358
 */
class JSONStatisticsController extends Controller
{
    public function actions() {
        return array(
            'japi'=>'JApi',
        );
    }

    /**
     * Main entry function for JSON service based statistics requests
     * @param string $periodStart
     * @param string $periodEnd
     * @param string $type
     * @param string $interval
     * @return array
     */
    public function japiShowResults($periodStart, $periodEnd, $type, $interval) {

        $db = $this->getDbHerbarInputLog();

        switch ($interval) {
            case 'day':   $interval = 'dayofyear'; break;
            case 'year':  $interval = 'year';      break;
            case 'month': $interval = 'month';     break;
            default :     $interval = 'week';      break;
        }

        $dbRows = $db->createCommand()
                     ->select(array('source_id', 'source_code'))
                     ->from('herbarinput.meta')
                     ->order('source_code')
                     ->queryAll();
        $institutionOrder = array();
        foreach ($dbRows as $dbRow) {
            $institutionOrder[] = array('source_id'   => $dbRow['source_id'],
                                        'source_code' => $dbRow['source_code']);
        }

        try {
            switch ($type) {
                case 'new_names':
                    $dbRows = $db->createCommand()
                                 ->select(array($interval . '(l.timestamp) AS period',
                                                'count(l.taxonID) AS cnt',
                                                'u.source_id'))
                                 ->from(array('log_tax_species l',
                                              'tbl_herbardb_users u',
                                              'herbarinput.meta m'))
                                 ->where(array('AND',
                                               'l.userID = u.userID',
                                               'u.source_id = m.source_id',
                                               'l.updated = 0',
                                               'l.timestamp >= :period_start',
                                               'l.timestamp <= :period_end'),
                                         array(':period_start' => $periodStart,
                                               ':period_end' => $periodEnd))
                                 ->group(array('period',
                                               'u.source_id'))
                                 ->order('period')
                                 ->queryAll();
                    break;
                case 'new_citations':
                    $dbRows = $db->createCommand()
                                 ->select(array($interval . '(l.timestamp) AS period',
                                                'count(l.citationID) AS cnt',
                                                'u.source_id'))
                                 ->from(array('log_lit l',
                                              'tbl_herbardb_users u',
                                              'herbarinput.meta m'))
                                 ->where(array('AND',
                                               'l.userID = u.userID',
                                               'u.source_id = m.source_id',
                                               'l.updated = 0',
                                               'l.timestamp >= :period_start',
                                               'l.timestamp <= :period_end'),
                                         array(':period_start' => $periodStart,
                                               ':period_end' => $periodEnd))
                                 ->group(array('period',
                                               'u.source_id'))
                                 ->order('period')
                                 ->queryAll();
                    break;
                case 'new_specimens':
                    $dbRows = $db->createCommand()
                                 ->select(array($interval . '(l.timestamp) AS period',
                                                'count(l.specimenID) AS cnt',
                                                'u.source_id'))
                                 ->from(array('log_specimens l',
                                              'tbl_herbardb_users u',
                                              'herbarinput.meta m'))
                                 ->where(array('AND',
                                               'l.userID = u.userID',
                                               'u.source_id = m.source_id',
                                               'l.updated = 0',
                                               'l.timestamp >= :period_start',
                                               'l.timestamp <= :period_end'),
                                         array(':period_start' => $periodStart,
                                               ':period_end' => $periodEnd))
                                 ->group(array('period',
                                               'u.source_id'))
                                 ->order('period')
                                 ->queryAll();
                    break;
                case 'new_type_specimens':
                    $dbRows = $db->createCommand()
                                 ->select(array($interval . '(l.timestamp) AS period',
                                                'count(l.specimens_types_ID) AS cnt',
                                                'u.source_id'))
                                 ->from(array('log_specimens_types l',
                                              'tbl_herbardb_users u',
                                              'herbarinput.meta m'))
                                 ->where(array('AND',
                                               'l.userID = u.userID',
                                               'u.source_id = m.source_id',
                                               'l.updated = 0',
                                               'l.timestamp >= :period_start',
                                               'l.timestamp <= :period_end'),
                                         array(':period_start' => $periodStart,
                                               ':period_end' => $periodEnd))
                                 ->group(array('period',
                                               'u.source_id'))
                                 ->order('period')
                                 ->queryAll();
                    break;
            }
            $error = '';
        }
        catch (Exception $e) {
            $error = $e->getMessage();
        }

        if ($error) {
            return array('display' => $error);
        } elseif (count($dbRows) > 0) {
            $periodMin = $periodMax = $dbRows[0]['period'];
            foreach ($dbRows as $dbRow) {
                $periodMin = ($dbRow['period'] < $periodMin) ? $dbRow['period'] : $periodMin;
                $periodMax = ($dbRow['period'] > $periodMin) ? $dbRow['period'] : $periodMax;
                $result[$dbRow['source_id']][$dbRow['period']] = $dbRow['cnt'];
            }
            for ($i = $periodMin; $i <= $periodMax; $i++) {
                foreach ($institutionOrder as $institution) {
                    if (!isset($result[$institution['source_id']][$i])) {
                        $result[$institution['source_id']][$i] = 0;
                    }
                }
            }
            $ret = "<table style='width:auto;'><tr>"
                 . "<td style='border-bottom:1px solid'></td>"
                 . "<td style='border-bottom:1px solid; border-left:1px solid'>min</td>"
                 . "<td style='border-bottom:1px solid'>max</td>"
                 . "<td style='border-bottom:1px solid'>avg</td>"
                 . "<td style='border-bottom:1px solid; border-right:1px solid'>median</td>";
            for ($i = $periodMin; $i <= $periodMax; $i++) {
                $ret .= "<td style='text-align:center; border-bottom:1px solid'>" . $i . "</td>";
                $periodSum[$i] = 0;
            }
            $ret .= "</tr>";
            foreach ($institutionOrder as $institution) {
                if (isset($result[$institution['source_id']]) && array_sum($result[$institution['source_id']]) > 0) {
                    $ret .= "<tr>"
                          . "<td>" . $institution['source_code'] . "</td>"
                          . "<td style='text-align:center; border-left:1px solid'>" . min($result[$institution['source_id']]) . "</td>"
                          . "<td style='text-align:center'>" . max($result[$institution['source_id']]) . "</td>"
                          . "<td style='text-align:center'>" . round($this->avg($result[$institution['source_id']]), 1) . "</td>"
                          . "<td style='text-align:center; border-right:1px solid'>" . $this->median($result[$institution['source_id']]) . "</td>";
                    for ($i = $periodMin; $i <= $periodMax; $i++) {
                        $ret .= "<td style='text-align:center'>" . $result[$institution['source_id']][$i] . "</td>";
                        $periodSum[$i] += $result[$institution['source_id']][$i];
                    }
                    $ret .= "</tr>";

                }
            }
            $ret .= "<tr>"
                  . "<td style='border-top:1px solid'>&sum;</td>"
                  . "<td style='text-align:center; border-top:1px solid; border-left:1px solid'>" . min($periodSum) . "</td>"
                  . "<td style='text-align:center; border-top:1px solid'>" . max($periodSum) . "</td>"
                  . "<td style='text-align:center; border-top:1px solid'>" . round($this->avg($periodSum), 1) . "</td>"
                  . "<td style='text-align:center; border-top:1px solid; border-right:1px solid'>" . $this->median($periodSum) . "</td>";
            for ($i = $periodMin; $i <= $periodMax; $i++) {
                $ret .= "<td style='text-align:center; border-top:1px solid'>" . $periodSum[$i] . "</td>";
            }
            $ret .= "</tr></table>";
            return array('display' => $ret); //var_export($result, true));
        } else {
            return array('display' => 'nothing found');
        }
    }

    /**
     * Return connection to herbarinput database
     * @return CDbConnection
     */
    private function getDbHerbarInput()
    {
        return Yii::app()->dbHerbarInput;
    }

    /**
     * Return connection to herbarinput database
     * @return CDbConnection
     */
    private function getDbHerbarInputLog()
    {
        return Yii::app()->dbHerbarInputLog;
    }

    private function avg($data)
    {
        return array_sum($data) / count($data);
    }

    private function median($data)
    {
        $anzahl = count($data);
        if ($anzahl == 0 ) {
            return 0;
        }
        sort($data);
        if($anzahl % 2 == 0) {
            // gerade Anzahl => der Median ist das arithmetische Mittel der beiden mittleren Zahlen
            return ($data[($anzahl / 2) - 1] + $data[$anzahl / 2]) / 2 ;
        } else {
            // ungerade Anzahl => der mittlere Wert ist der Median
            return $data[$anzahl / 2];
        }
    }
}
