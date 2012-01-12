<?php
/**
  * This class is used as the utility class for the entire plugin
  * this will load the configuration needed to make the plugin work
  * link and load the models for the controller as the developer
  * has the option to overwrite the default plugin contact model
  * with their own.
  */
class SurveyUtil {
  
  /**
    * Value of the configurations file.
    */
  static $configs = null;

  /**
    * Load the configuration if needed and return value of the key
    * @param string key (optional) if empty returns entire configuration array
    * @return mixed result of configuration
    */
  static function getConfig($key = null){
    self::loadConfig();
    if($key === null && self::$configs){
      return self::$configs;
    }
    
    if(isset(self::$configs[$key])){
      return self::$configs[$key];
    }
    
    return null;
  }
  
  /**
    * Writes to this static class the key and value
    * @param string key
    * @param string value
    * @return void
    */
  static function writeConfig($key = null, $value){
    if($key){
      self::$configs[$key] = $value;
    }
  }
  
  /**
    * Loads the configuration if needed.
    * @return void
    */
  static function loadConfig(){
    if(!self::$configs){
      Configure::load('survey');
      self::$configs = Configure::read('Survey');
    }
  }
}
?>
