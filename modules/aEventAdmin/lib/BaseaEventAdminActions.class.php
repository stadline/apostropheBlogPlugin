<?php
require_once dirname(__FILE__).'/aEventAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aEventAdminGeneratorHelper.class.php';
/**
 * Base actions for the aEventPlugin aEventAdmin module.
 * 
 * @package     aEventPlugin
 * @subpackage  aEventAdmin
 * @author      Dan Ordille <dan@punkave.com>
 */
abstract class BaseaEventAdminActions extends autoAEventAdminActions
{ 
  
  public function preExecute()
  {
    parent::preExecute();
    if(sfConfig::get('app_aBlog_use_bundled_assets', true))
    {
      $this->getResponse()->addStylesheet('/apostropheBlogPlugin/css/aBlog.css');
      $this->getResponse()->addJavascript('/apostropheBlogPlugin/js/aBlog.js');
    }
  }

  // You must create with at least a title
  public function executeNew(sfWebRequest $request)
  {
    $this->forward404();
  }
  
  // Doctrine collection routes make it a pain to change the settings
  // of the standard routes fundamentally, so we provide another route
  public function executeNewWithTitle(sfWebRequest $request)
  {
    $this->form = new aNewEventForm();
    $this->form->bind($request->getParameter('a_new_event'));
    if ($this->form->isValid())
    {
      $this->a_event = new aEvent();
      $this->a_event->Author = $this->getUser()->getGuardUser();
      $this->a_event->setTitle($this->form->getValue('title'));
      $this->a_event->save();
      $this->getUser()->setFlash('new_post', true);
      $this->eventUrl = $this->generateUrl('a_event_admin_edit', $this->a_event);
      return 'Success';
    }
    return 'Error';
  }
    
  public function executeAutocomplete(sfWebRequest $request)
  {
    // Search is in virtual pages, the TITLE field is dead (or going to be) and not
    // I18N, we have to cope with that correctly. I tried to use Zend Search but we
    // can't easily distinguish blog pages from the rest and that seems to be a deeper
    // architectural problem. I still had to fix a few things in PluginaBlogItem which
    // was locking the virtual pages down and making them unsearchable by normal mortals.
    // Now it locks them down only when they are not status = published. Republish things
    // to get the benefit of this on existing sites
    
    $this->aEvents = aBlogItemTable::titleSearch($request->getParameter('q'), '@a_event_search_redirect');
    $this->setLayout(false);
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    if($this->getUser()->hasCredential('admin'))
    {
      $this->a_event = $this->getRoute()->getObject();
    }
    else
    {
      $this->a_event = Doctrine::getTable('aEvent')->findOneEditable($request->getParameter('id'), $this->getUser()->getGuardUser()->getId());
    }
    $this->forward404Unless($this->a_event);
    $this->form = $this->configuration->getForm($this->a_event);

    if($request->isXmlHttpRequest())
    {
      $this->setLayout(false);
      $response = array();
      $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->a_event = $this->form->save();
        //We need to recreate the form to handle the fact that it is not possible to change the value of a sfFormField
        $this->form = $this->configuration->getForm($this->a_event);
        $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $this->a_event)));
      }

      $response['errors'] = $this->form->getErrorSchema()->getErrors();
      aPageTable::queryWithTitles()
        ->addWhere('page_id = ?', $this->a_event['page_id'])
        ->execute();
      $response['aBlogPost'] = $this->a_event->toArray();
      $response['aBlogPost']['title'] = html_entity_decode($response['aBlogPost']['title'], ENT_COMPAT, 'UTF-8');
      $response['modified'] = $this->a_event->getLastModified();
      $response['time'] = aDate::time($this->a_event['updated_at']);
      //Any additional messages can go here
      $output = json_encode($response);
      $this->getResponse()->setHttpHeader("X-JSON", '('.$output.')');
      return sfView::HEADER_ONLY;
    }
    else
    {
      $this->processForm($request, $this->form);
    }
    $this->setTemplate('edit');
  }

  public function executeRedirect()
  {
    $aEvent = $this->getRoute()->getObject();
    aRouteTools::pushTargetEnginePage($aEvent->findBestEngine());
    $url = $this->generateUrl('a_event_post', $aEvent);
    $this->redirect($url);
  }

  public function executeCategories()
  {
    $this->redirect('@a_blog_category_admin');
  }

  public function executeIndex(sfWebRequest $request)
  {
    if(!aPageTable::getFirstEnginePage('aEvent'))
    {
      $this->setTemplate('engineWarning');
    }

    parent::executeIndex($request);
    aBlogItemTable::populatePages($this->pager->getResults());
  }

  public function executeEdit(sfWebRequest $request)
  {
		$this->getResponse()->addJavascript('/apostropheBlogPlugin/js/timepicker.js', 'last');
		$this->getResponse()->addJavascript('/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js','last');

    if($this->getUser()->hasCredential('admin'))
    {
      $this->a_event = $this->getRoute()->getObject();
    }
    else
    {
      $this->a_event = Doctrine::getTable('aEvent')->findOneEditable($request->getParameter('id'), $this->getUser()->getGuardUser()->getId());
    }
    $this->forward404Unless($this->a_event);
    $this->form = $this->configuration->getForm($this->a_event);

		// Retrieve the tags currently assigned to the event for the inlineTaggableWidget
		$this->existingTags = $this->form->getObject()->getTags();
		// Retrieve the 10 most popular tags for the inlineTaggableWidget
    $this->popularTags = TagTable::getAllTagNameWithCount(null, array('model' => 'aEvent', 'sort_by_popularity' => true, 'limit' => 10));

    aBlogItemTable::populatePages(array($this->a_event));
  }

  protected function buildQuery()
  {
    if (is_null($this->filters))
    {
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }
    $filters = $this->getFilters();
    $resetFilters = false;
    foreach($this->filters->getAppliedFilters() as $name => $field)
    {
      foreach($field as $key => $value)
      {
        if(is_null($value))
        {
          unset($filters[$name]);
          $resetFilters = true;
        }
      }
    }
    if($resetFilters)
    {
      $this->getUser()->setAttribute('aBlogAdmin.filters', $filters, 'admin_module');
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }

    $query = parent::buildQuery();
    $query->leftJoin($query->getRootAlias().'.Author')
      ->leftJoin($query->getRootAlias().'.Editors')
      ->leftJoin($query->getRootAlias().'.Categories')
      ->leftJoin($query->getRootAlias().'.Page');
    return $query;
  }
}