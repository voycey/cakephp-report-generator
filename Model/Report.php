<?php
    App::uses('AppModel', 'Model');
    App::uses('ConnectionManager', 'Model');
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');
   
    class Report extends AppModel {

        public $useTable = false;

        protected $_schema = array(
            'deleted' => array(
                'type' => 'boolean',
                'length' => 1,
                'default' => 0,
                'null' => false
            ),
            'deleted_date' => array(
                'type' => 'datetime',
                'null' => false
            )
        );
        public function run_query($query) {
            $db = $this->getDataSource('default');
            return $db->fetchAll($query);

        }

        public function users_addresses() {
            $query = "Select
                          addresses.billing_line_1,
                          addresses.billing_line_2,
                          addresses.billing_state,
                          addresses.billing_postcode,
                          countries.name as billing_country,
                          users.email,
                          user_details.job_title,
                          user_details.company
                        From
                          users Inner Join
                          user_details On user_details.user_id = users.id Inner Join
                          addresses On addresses.user_id = users.id Inner Join
                          countries On addresses.billing_country_id = countries.id";
            return $this->run_query($query);

        }
    }
?>
