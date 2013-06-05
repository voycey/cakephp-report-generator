<?php
    App::uses('AppController', 'Controller');
    /**
     * Reports Controller
     */
    class ReportsController extends AppController {

        public $report_array = array();

        public function export($data, $filename) {
            $this->autoRender = false;
            $modelClass = $this->modelClass;
            $this->response->type('Content-Type: text/csv');
            $this->response->download($filename . '.csv');
            $this->response->body($data);
        }

        function traverseArray($array) {
            // Loops through each element. If element again is array, function is recalled. If not, result is echoed.
            foreach($array as $key=>$value) {
                if(is_array($value)) {
                    $this->traverseArray($value);
                } else {
                    $this->report_array[$key][] = $value;
                }
            }
        }

        function transpose($array) {
            array_unshift($array, null);
            return call_user_func_array('array_map', $array);
        }

        function array_column($array,$column) {
            foreach($array as &$value) {
                $value = $value[$column];
            }
            return $array;
        }
        
        function generateCSV($sql_result) {
            $this->traverseArray($sql_result);
            $headings = array_keys($this->report_array);
            $transposed = $this->transpose($this->report_array);
            $final_csv = implode("#", $headings) . "\r\n";
            foreach($transposed as $table_row) {
                foreach($table_row as $cell) {
                    $final_csv .= $cell . "#";
                }
                $final_csv = rtrim($final_csv, '#') . "\r\n";
            }
            return $final_csv;
        }

       
        //example method
        public function users_addresses_report() {
            $this->autoRender = false;
            $result = $this->Report->users_addresses();
            $final_csv = $this->generateCSV($result);
            $this->export($final_csv, "address_report");

        }

    }
