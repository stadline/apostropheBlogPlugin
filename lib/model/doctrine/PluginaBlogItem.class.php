<?php

/**
 * PluginaBlogItem
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class PluginaBlogItem extends BaseaBlogItem
{
  /**
   * These date methods are use in the routing of the permalink
   */
  public function getYear()
  {
    return date('Y', strtotime($this->getPublishedAt()));
  }

  public function getMonth()
  {
    return date('m', strtotime($this->getPublishedAt()));
  }

  public function getDay()
  {
    return date('d', strtotime($this->getPublishedAt()));
  }
  
  public function getFeedSlug()
  {
    return $this->slug;
  }


}