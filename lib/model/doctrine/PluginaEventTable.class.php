<?php

/**
 * PluginaEventTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginaEventTable extends aBlogItemTable
{
  protected $categoryColumn = 'events';
    
  /**
   * Returns an instance of this class.
   *
   * @return object PluginaEventTable
   */
  public static function getInstance()
  {
    return Doctrine::getTable('aEvent');
  }

  public function filterByYMD(Doctrine_Query $q, sfWebRequest $request)
  {
    $rootAlias = $q->getRootAlias();

    $sYear = $request->getParameter('year', 0);
    $sMonth = $request->getParameter('month', 0);
    $sDay = $request->getParameter('day', 0);
    $startDate = "$sYear-$sMonth-$sDay 00:00:00";

    $eYear = $request->getParameter('year', 3000);
    $eMonth = $request->getParameter('month', 12);
    $eDay = $request->getParameter('day', 31);
    $endDate = "$eYear-$eMonth-$eDay 23:59:59";

    $q->addWhere($rootAlias.'.start_date BETWEEN ? AND ?', array($startDate, $endDate));
  }

  public function addUpcomming(Doctrine_Query $q, $limit = null)
  {
    $q->orderBy('start_date');
    if(!is_null($limit))
      $q->limit($limit);
  }
}