<?php

class Loader {
	private static $loader;

	private $libPath 	 = "";
	private $controlPath = "";
	private $modelPath 	 = "";
	private $viewPath  	 = "";

	public $loadedFiles = array();
	
	public $debug = false;

	function __construct() {
		global $iWasThere;

		$this->libPath 	= INSTALL_PATH . "shared";
		$this->controlPath 	= INSTALL_PATH . "control";
		$this->modelPath 	= INSTALL_PATH . "model";
		$this->viewPath  	= INSTALL_PATH . "view";
	}

	public function getInstance() {
		if (null === self::$loader ) {
			self::$loader = new self;
		}
		return self::$loader;
	}


	private function _load($path, $name, $postfix) {
		global $iWasThere;
		require($path . '/' . $name . $postfix);
		$this->loadedFiles[] = $name;
		if ($this->debug) echo $name, "<br />";
	}

	/*
		$type: noraml or class
	*/
	function lib($lib, $type = 'normal') {
		if (!in_array($lib, $this->loadedFiles)) {

			$this->_load($this->libPath, $lib, '.lib.php');

			if ($type == "class") {
				if (class_exists($model, false)) {
					$$lib = new $lib;
				} else {
					trigger_error("Unable to load class: $lib", E_USER_WARNING);
				}
			}
			return TRUE;
		}
		return;
	}

	function model($model, $loader = "") {
		if (!in_array($model, $this->loadedFiles)) {
			$this->_load($this->modelPath, $model, '.php');

			if ($this->debug) echo "$model => $loader <br />";

			if (class_exists($model, false)) {
				//$$model = new $model;
			} else {
				trigger_error("Unable to load class: $model", E_USER_WARNING);
			}

		}
	}

	function control($control) {
		if (!in_array($control, $this->loadedFiles)) {
			if ($control == "control") {
				$this->_load($this->controlPath, $control, '.php');
			} else {
				$this->_load($this->controlPath, $control, '.c.php');
			}

			if (class_exists($control, false)) {
				//$$control = new $control;
			} else {
				trigger_error("Unable to load class: $control", E_USER_WARNING);
			}

		}
	}
	
	function from($path) { // ()->file()
		
	}

}
?>
