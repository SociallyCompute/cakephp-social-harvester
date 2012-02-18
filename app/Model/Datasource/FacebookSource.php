<?php
/**
 * Facebook DataSource
 *
 * Used for reading from and (not at the moment) writing to Facebook, through models.
 *
 */
App::uses('HttpSocket', 'Network/Http');
//import Facebook API
vendor('facebook/facebook');

class FacebookSource extends DataSource {

	//the schema that describes the data source we're pulling from: http://book.cakephp.org/2.0/en/models/datasources.html?highlight=_schema
    protected $_schema = array( 
        'pages' => array(
            'id' => array(
                'type' => 'string',
                'null' => true,
                'key' => 'primary',
                'length' => 11,
            ),
            'name' => array(
                'type' => 'string',
                'null' => true,
                'key' => 'primary',
                'length' => 140
            ),
            'link' => array(
                'type' => 'string',
                'null' => true,
                'key' => 'primary',
                'length' => 140
            ),
            'likes' => array(
                'type' => 'integer',
                'null' => true,
                'key' => 'primary',
                'length' => 20
            ),
            'picture' => array(
                'type' => 'string',
                'null' => true,
                'key' => 'primary',
                'length' => 140
            ),
            'website' => array(
                'type' => 'string',
                'null' => true,
                'key' => 'primary',
                'length' => 140
            ),
        )
    );

    public function __construct($config) {
		//You can get an API key and secret by registering a Facebook application here: https://developers.facebook.com/apps
		//For more info: https://developers.facebook.com/docs/reference/php/
    	$api_key = ;
		$secret = ;

		$facebook = new Facebook($api_key, $secret);
        );
        parent::__construct($config);
    }
    public function listSources() {
        return array('pages');
    }
    public function read($model, $queryData = array()) {
        if (!isset($queryData['conditions']['username'])) {
            $queryData['conditions']['username'] = $this->config['login'];
        }
        $url = "/statuses/user_timeline/";
        $url .= "{$queryData['conditions']['username']}.json";

        $response = json_decode($this->connection->get($url), true);
        $results = array();

        foreach ($response as $record) {
            $record = array('Tweet' => $record);
            $record['User'] = $record['Tweet']['user'];
            //unset($record['Tweet']['user']);
            $results[] = $record;
        }
        return $results;
    }
    public function create($model, $fields = array(), $values = array()) {
        $data = array_combine($fields, $values);
        $result = $this->connection->post('/statuses/update.json', $data);
        $result = json_decode($result, true);
        if (isset($result['id']) && is_numeric($result['id'])) {
            $model->setInsertId($result['id']);
            return true;
        }
        return false;
    }
    public function describe($model) {
        return $this->_schema['tweets'];
    }
}
?>