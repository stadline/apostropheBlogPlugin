<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginaBlogEventTable extends aBlogItemTable
{
    /**
   * Builds the query for blog posts and events based on the request parameters.
   * 
   * @param Doctrine_Query $q
   * @param string $tableName This is going to be either aBlogPost or aBlogEvent
   * @return Doctrrin_Query $q
   */
  public function buildQuery(sfWebRequest $request, $tableName = 'aBlogEvent', Doctrine_Query $q = null)
  {
    Doctrine::getTable('aBlogItem')->buildQuery($request, $tableName, $q);
    $rootAlias = $q->getRootAlias();
    $q->orderBy($rootAlias.'.start_date desc');
    return $q;
  }
  
  public function addDateRangeQuery(sfWebRequest $request, Doctrine_Query $q = null)
  {
    if (!$q)
    {
      $q = $this->createQuery('e');
    }

    $rootAlias = $q->getRootAlias();

    $q->addWhere($rootAlias.'.start_date > ?', $request->getParameter('year', date('Y')).'-'.$request->getParameter('month', 1).'-'.$request->getParameter('day', 1).' 0:00:00')
      ->addWhere($rootAlias.'.start_date < ?', $request->getParameter('year', date('Y')).'-'.$request->getParameter('month', 12).'-'.$request->getParameter('day', 31).' 23:59:59');
    
    return $q;
  }
  
  public function addUpcomingEventsQuery(Doctrine_Query $q = null)
  {
    if (!$q)
    {
      $q = $this->createQuery('e');
    }
    
    $rootAlias = $q->getRootAlias();
    
    $q->addWhere($rootAlias.'.published = ?', true)
      ->addWhere($rootAlias.'.start_date > ?', date('Y-m-d'))
      ->orderBy($rootAlias.'.start_date, '. $rootAlias .'.start_time');
    
    return $q;
  }

  public function getLuceneIndex()
  {
    return aZendSearch::getLuceneIndex($this);
  }
   
  public function getLuceneIndexFile()
  {
    return aZendSearch::getLuceneIndexFile($this);
  }

  public function searchLucene($luceneQuery)
  {
    return aZendSearch::searchLucene($this, $luceneQuery);
  }

  public function searchLuceneWithScores($luceneQuery)
  {
    return aZendSearch::searchLuceneWithScores($this, $luceneQuery);
  }
  
  public function rebuildLuceneIndex()
  {
    return aZendSearch::rebuildLuceneIndex($this);
  }
  
  public function optimizeLuceneIndex()
  {
    return aZendSearch::optimizeLuceneIndex($this);
  }
  
  public function addSearchQuery(Doctrine_Query $q = null, $luceneQuery)
  {
    return aZendSearch::addSearchQuery($this, $q, $luceneQuery, null);
  }

  public function addSearchQueryWithScores(Doctrine_Query $q = null, $luceneQuery, &$scores)
  {
    return aZendSearch::addSearchQueryWithScores($this, $q, $luceneQuery, null, $scores);
  }
}
