<?php

/**
 * PluginaBlogPost
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class PluginaBlogPost extends BaseaBlogPost
{

  /**
   * This function attempts to find the "best" engine to route a given post to.
   */
  public function findBestEngine()
  {
    $engines = Doctrine::getTable('aPage')->createQuery()
      ->addWhere('engine = ?', 'aBlog')
      ->execute();

    if(count($engines) == 0)
      return '';
    else if(count($engines) == 1)
      return $engines[0];

    //When there are more than one engine page we need to use some heuristics to
    //guess what the best page is.
    $catIds = array();
    foreach($this->Categories as $category)
    {
      $catIds[$category['id']] = $category['id'];
    }

    if(count($catIds) < 1)
      return $engines[0];

    $best = array(0, '');
    
    foreach($engines as $engine)
    {
      $score = 0;
      foreach($engine->BlogCategories as $category)
      {
        if(isset($catIds[$category['id']]))
          $score = $score + 1;
      }
      if($score > $best[0])
      {
        $best = $engine;
      }
    }

    return $best;
  }

}