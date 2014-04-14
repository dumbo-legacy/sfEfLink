<?php
/**
 * sfEfLink.
 *
 * Class to redirect to another application.
 *
 * @package    sfEfLink
 * @subpackage 
 * @author     Yaismel Miranda Pons <yaismelmp@googlemail.com>
 * @version    1.2
 */
class sfEfLink
{
  /**
  * @desc  This function redirects to an application.
  * 
  * @param <string> $app Name of the application.
  * @param <string> $module application module name.
  * @return <string> return string url
  * @examples  sfEfLink::toApp('frontend','principal/index')
  *            sfEfLink::toApp('backend')
  */
  static public function ToApp($app, $module = '') 
  {           
    try{
      $currentScript = basename(sfContext::getInstance()->getRequest()->getScriptName());
      $currentConfig = sfConfig::getAll();
      $currentApp    = sfContext::getInstance()->getConfiguration()->getApplication();    

      $env      = sfContext::getInstance()->getConfiguration()->getEnvironment();                   
      $env      = ($env == 'prod') ? null : $env;
      $newApp   = ($env == null) ? $app.'.php' : $app.'_'.$env.'.php';
      $context  = (sfContext::hasInstance($app)) ? sfContext::getInstance($app) : sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration($app, $env, false));

      if(!file_exists(sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.$newApp)){ $newApp = 'index.php'; }

      $myurl    = str_replace($currentScript, $newApp, $context->getController()->genUrl($module, true));
      $myurl    = ($module == '') ? str_replace(strstr($myurl, $newApp), '', $myurl).$newApp : $myurl;

      sfContext::switchTo($currentApp);  sfConfig::add($currentConfig);  unset($context);
      
      return $myurl;
    }
    catch(Exception $err){
      return "#";
    }
  }
}
