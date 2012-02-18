<?php

class TweetsController extends AppController {

	public $name = "Tweets";
	public $helpers = array('Html', 'Form');

	public function index() {
		// Will use the username defined in the $twitter as shown above:
		$tweets = $this->Tweet->find('all');
		$this->set('tweets', $tweets);

		// Finds tweets by another username
		$conditions= array('username' => 'caketest');
		$otherTweets = $this->Tweet->find('all', compact('conditions'));
	}

}

?>