<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginaBlogPostTable extends Doctrine_Table
{
  public function buildQuery(sfWebRequest $request, $tableName = 'aBlogPost', $q)
  {    
    Doctrine::getTable('aBlogItem')->buildQuery($request, $tableName, $q);
    $rootAlias = $q->getRootAlias();
    $q->orderBy($rootAlias.'.published_at desc');
    return $q;
  }
  
  public function addDateRangeQuery(sfWebRequest $request, Doctrine_Query $q = null)
  {
    if (!$q)
    {
      $q = $this->createQuery('p');
    }
    
    $rootAlias = $q->getRootAlias();

    // if it's an RSS feed, we don't want to be concerned with a time frame, just give us the latest stuff
    if ($request->getParameter('format') != 'rss')
    {
      $q->addWhere($rootAlias.'.published_at > ? and '.$rootAlias.'.published_at < ?', array( 
        $request->getParameter('year', 0).'-'.$request->getParameter('month', 1).'-'.$request->getParameter('day', 1).' 0:00:00',
        $request->getParameter('year', date('Y')).'-'.$request->getParameter('month', 12).'-'.$request->getParameter('day', 31).' 23:59:59'
      ));
    }
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
