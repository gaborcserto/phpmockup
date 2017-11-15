<?php

class jsonCreator {

	private $jsonfile = 'results.json';

	public function __construct(){
	}

	public function init($response) {
		$this->jsonData = fopen($this->jsonfile, 'w');

		$this->jsonWrite($response);
	}

	private function jsonWrite($response) {
		if(is_file($this->jsonfile)) {
			$str = file_get_contents($this->jsonfile);
			$jsonData = json_decode($str, true);

			print_r($jsonData);
			//$count = count($tempArray['data']);

			//$tempArray['data'][$count] = $response;
		} else {
			$tempArray = array (
				'data' => 
				array (
				  0 => $response
				)
			);
		}

		fwrite($this->jsonData, json_encode($tempArray));
		fclose($this->jsonData);
	}

	/*private function jsonData() {
		if($this->jsonCheck()) {
			$str = file_get_contents($this->jsonfile);	
			if($str) {
				$json = json_decode($str, true);
				return $json;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}*/

	/*private function jsonCheck() {
		if(is_file($this->jsonfile)) {
			print_r('')
			return true;
		} else {
			return false;
		}
	}*/

	public function checkId() {
		if(is_file($this->jsonfile)) {
			$str = file_get_contents($this->jsonfile);	
			if($str) {
				$json = json_decode($str, true);
			}
		}

		if(!empty($json)) {			
			$max = count($json);
			//print_r('sdfasdf');
			return ($json['data'][$max - 1]['id'] + 1);
		} else {
			//print_r('jó');
			return 1;
		}
	}
}