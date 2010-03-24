<?php

/**
 * PluginaBlogItem form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaBlogItemForm extends BaseaBlogItemForm
{
  public function setup()
  {
    parent::setup();
    $user = sfContext::getInstance()->getUser();
    
    
    unset(
      $this['type'], $this['page_id'], $this['created_at'], $this['updated_at']
    );
    
    //TODO: Refactor query into model and change query to table_method, also need admins to get all categories
    $q = Doctrine_Query::create()
      ->from('aBlogCategory c');
    if(!$user->hasCredential('admin'))
    {
      $q->innerJoin('c.Users u')
        ->where('u.id = ?', $user->getGuardUser()->getId());
    }
    $this->setWidget('categories_list',
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aBlogCategory', 'query' => $q)));
    $this->setValidator('categories_list',
      new sfValidatorDoctrineChoice(array('model' => 'aBlogCategory', 'query' => $q, 'required' => false)));
    
    $q = Doctrine::getTable('sfGuardUser')->createQuery();
    if(!$user->hasCredential('admin'))
    {
      $q->addWhere('sfGuardUser.id = ?', $user->getGuardUser()->getId());
    }
    $this->setWidget('author_id',
      new sfWidgetFormDoctrineChoice(array('multiple' => false, 'model' => 'sfGuardUser', 'query' => $q)));
    $this->setValidator('author_id',
      new sfValidatorDoctrineChoice(array('model' => 'sfGuardUser', 'query' => $q, 'required' => true)));
      
    $this->setWidget('editors_list',
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'query' => $q)));
    $this->setValidator('editors_list',
      new sfValidatorDoctrineChoice(array('model' => 'sfGuardUser', 'query' => $q, 'required' => false)));
    
    $this->setWidget('template', 
      new sfWidgetFormChoice(array('multiple' => false, 'choices' => sfConfig::get('app_aBlog_templates'))));
    $this->setValidator('template',
      new sfValidatorChoice(array('required' => true, 'multiple' => false, 'choices' => array_flip(sfConfig::get('app_aBlog_templates')))));
      
    $this->widgetSchema['tags']       = new sfWidgetFormInput(array('default' => implode(', ', $this->getObject()->getTags())), array('class' => 'tag-input', 'autocomplete' => 'off'));
    $this->validatorSchema['tags']    = new sfValidatorString(array('required' => false));
  }
  
  public function doSave($con = null)
  {
    $tags = $this->values['tags'];
    $tags = preg_replace('/\s\s+/', ' ', $tags);
    $tags = str_replace(', ', ',', $tags);

    $this->object->setTags($tags);
    
    parent::doSave($con);
  }
  
}
