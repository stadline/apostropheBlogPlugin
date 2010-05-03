<?php

/**
 * PluginaBlogPost form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaBlogPostForm extends BaseaBlogPostForm
{
  public function setup()
  {
    parent::setup();
    
    $this->setWidget('published_at', new sfWidgetFormJQueryDateTime(
			array('image' => '/apostrophePlugin/images/a-icon-datepicker.png')
		));

    $this->setWidget('template',
      new sfWidgetFormChoice(array('multiple' => false, 'choices' => sfConfig::get('app_aBlog_templates'))));
    $this->setValidator('template',
      new sfValidatorChoice(array('required' => true, 'multiple' => false, 'choices' => array_flip(sfConfig::get('app_aBlog_templates')))));

    if(count(sfConfig::get('app_aBlog_templates')) <= 1)
    {
      unset($this['template']);
    }

    if(!sfConfig::get('app_aBlog_comments', false))
    {
      unset($this['allow_comments']);
    }
    
    $this->getWidgetSchema()->setDefault('published_at', date('Y/m/d H:i'));

    $this->widgetSchema->setNameFormat('a_blog_item[%s]');
  }

  public function updateCategoriesList($values)
  {
    $link = array();
    if(!is_array($values))
      $values = array();
    foreach ($values as $value)
    {
      $existing = Doctrine::getTable('aBlogCategory')->findOneBy('name', $value);
      if($existing)
      {
        $aBlogCategory = $existing;
      }
      else
      {
        $aBlogCategory = new aBlogCategory();
        $aBlogCategory['name'] = $value;
      }
      $aBlogCategory['posts'] = true;
      $aBlogCategory->save();
      $link[] = $aBlogCategory['id'];
    }
    if(!is_array($this->values['categories_list']))
    {
      $this->values['categories_list'] = array();
    }
    $this->values['categories_list'] = array_merge($link, $this->values['categories_list']);
  }

}
