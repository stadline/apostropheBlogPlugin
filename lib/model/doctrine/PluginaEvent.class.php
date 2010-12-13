<?php

/**
 * PluginaEvent
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginaEvent extends BaseaEvent
{
  public $engine = 'aEvent';
  
  public function getTemplateDefaults()
  {
    return array(
      'singleColumnTemplate' => array(
        'name' => 'Single Column',
        'areas' => array('blog-body')
      )
    );
  }
  
  public function getVirtualPageSlug()
  {
    return '@a_event_search_redirect?id=' . $this->id;
  }

  public function getYear()
  {
    return date('Y', strtotime($this->getStartDate()));
  }

  public function getMonth()
  {
    return date('m', strtotime($this->getStartDate()));
  }

  public function getDay()
  {
    return date('d', strtotime($this->getStartDate()));
  }

  // Per the vCal/iCal specs and Google Calendars, if the times are null
  // they are not included
  
  public function getVcalStartDateTime()
  {
    if (!is_null($this->getStartTime()))
    {
      $start = aDate::normalize($this->getStartDate()) + aDate::normalize($this->getStartTime(), true);
      $when = gmdate('Ymd\THis', $start) . 'Z';
    }
    else
    {
      $when = gmdate('Ymd', aDate::normalize($this->getStartDate())) . 'Z';
    }
    return $when;
  }

  public function getVcalEndDateTime()
  {
    if (!is_null($this->getEndTime()))
    {
      $start = aDate::normalize($this->getEndDate()) + aDate::normalize($this->getEndTime(), true);
      $when = gmdate('Ymd\THis', $start) . 'Z';
    }
    else
    {
      $when = gmdate('Ymd', aDate::normalize($this->getEndDate())) . 'Z';
    }
    return $when;
  }

  // Google Calendar
  public function getUTCDateRange()
  {
    return $this->getVcalStartDateTime() . '/' . $this->getVcalEndDateTime();
  }
  
  public function isAllDay()
  {
    return (is_null($this->start_time) && is_null($this->end_time));
  }  
}
