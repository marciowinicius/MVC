<?php

/*
 * Script:    DataTables server-side script for PHP and MySQL
 * Copyright: 2012 - John Becker, Beckersoft, Inc.
 * Copyright: 2010 - Allan Jardine
 * License:   GPL v2 or BSD (3-point)
 */

namespace app\model\Eloquent;

use PDO;
use core\FSBRDatabase;

class Datatable
{

    private $_db;
    private static $DATE_COLUMNS = ['data'];

    function __construct()
    {
        try {
            $this->_db = FSBRDatabase::getConnection();
        } catch (PDOException $e) {
            error_log("Failed to connect to database: " . $e->getMessage());
        }
    }

    public function get($table, $index_column, $columns, $customWhere = "", $customOrder = "", $customGroupBy = "")
    {
        // Paging
        $sLimit = "";
        if (isset($_REQUEST['iDisplayStart']) && $_REQUEST['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_REQUEST['iDisplayStart']) . ", " . intval($_REQUEST['iDisplayLength']);
        }

        // Ordering
        $sOrder = "";
        if (isset($_REQUEST['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_REQUEST['iSortingCols']); $i++) {
                if ($_REQUEST['bSortable_' . intval($_REQUEST['iSortCol_' . $i])] == "true") {
                    $sortDir = (strcasecmp($_REQUEST['sSortDir_' . $i], 'ASC') == 0) ? 'ASC' : 'DESC';
                    $sOrder .= "`" . $columns[intval($_REQUEST['iSortCol_' . $i])] . "` " . $sortDir . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        if (!empty($customOrder) && empty($sOrder)) {
            $sOrder = "ORDER BY  " . $customOrder . " ";
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if (isset($_REQUEST['sSearch']) && $_REQUEST['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($columns); $i++) {
                if (isset($_REQUEST['bSearchable_' . $i]) && $_REQUEST['bSearchable_' . $i] == "true") {
                    $column = $columns[$i];
                    if (in_array($columns[$i], self::$DATE_COLUMNS)) {
                        $column = 'DATE_FORMAT(' . $columns[$i] . ', \'%d/%m/%Y %H:%i:%s\')';
                    }
                    $sWhere .= "" . $column . " LIKE :search OR ";
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

//        echo $sWhere;exit();

        // Individual column filtering
        for ($i = 0; $i < count($columns); $i++) {
            if (isset($_REQUEST['bSearchable_' . $i]) && $_REQUEST['bSearchable_' . $i] == "true" && $_REQUEST['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`" . $columns[$i] . "` LIKE :search" . $i . " ";
            }
        }

        if (!empty($customWhere)) {
            if (empty($sWhere)) {
                $sWhere = "WHERE (" . $customWhere . ")";
            } else {
                $sWhere .= " AND (" . $customWhere . ")";
            }

            $customWhere = " WHERE (" . $customWhere . ")";
        }

        if (!empty($customGroupBy)) {
            $customGroupBy = "GROUP BY " . $customGroupBy;
        }


        // SQL queries get data to display
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $columns)) . " FROM " . $table . " " . $sWhere . " " . " " . $customGroupBy . " " . $sOrder . " " . $sLimit;
        $statement = $this->_db->prepare($sQuery);

        // Bind parameters
        if (isset($_REQUEST['sSearch']) && $_REQUEST['sSearch'] != "") {
            $statement->bindValue(':search', '%' . str_replace(' ', '%', $_REQUEST['sSearch']) . '%', PDO::PARAM_STR);
        }
        for ($i = 0; $i < count($columns); $i++) {
            if (isset($_REQUEST['bSearchable_' . $i]) && $_REQUEST['bSearchable_' . $i] == "true" && $_REQUEST['sSearch_' . $i] != '') {
                $statement->bindValue(':search' . $i, '%' . str_replace(' ', '%', $_REQUEST['sSearch_' . $i]) . '%', PDO::PARAM_STR);
            }
        }
        $statement->execute();
        $rResult = $statement->fetchAll(PDO::FETCH_ASSOC);

//        echo json_encode($rResult);exit();

        $iFilteredTotal = current($this->_db->query('SELECT FOUND_ROWS()')->fetch());

        // Get total number of rows in table
        $sQuery = "SELECT COUNT(" . $index_column . ") FROM " . $table . " " . $customWhere . " " . $customGroupBy;
        $auxResult = $this->_db->query($sQuery)->fetch();
        $iTotal = intval(current(is_array($auxResult) ? $auxResult : array()));

        // Output
        $output = array(
            "sEcho" => intval(isset($_REQUEST['sEcho']) ? $_REQUEST['sEcho'] : 0),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        // Return array of values
//        foreach ($rResult as $aRow) {
//            $row = array();
//            for ($i = 0; $i < count($columns); $i++) {
//                if ($columns[$i] == "version") {
//                    // Special output formatting for 'version' column
//                    $row[] = ($aRow[$columns[$i]] == "0") ? '-' : $aRow[$columns[$i]];
//                } else if ($columns[$i] != ' ') {
//                    $row[] = $aRow[$columns[$i]];
//                }
//            }
//            $output['aaData'][] = $row;
//        }
        $output['aaData'] = $rResult;

        return $output;
    }

}

?>