<?php use_helper('I18N','jQuery') ?>

<?php echo jq_form_remote_tag(array('url' => url_for('a_event_admin_update',$a_event) , 'update' => 'a-admin-blog-post-form'), array('id'=>'a-admin-form', 'class' => 'blog')) ?>

<?php if (!$form->getObject()->isNew()): ?><input type="hidden" name="sf_method" value="PUT" /><?php endif; ?>

<?php echo $form->renderHiddenFields() ?>


<?php // Title and Slug are hidden and handled with inputs in the editSuccess ?>
<div class="post-title post-slug option">
  <?php echo $form['title']->renderRow() ?>
  <?php echo $form['slug']->getWidget()->render('a_event[slug]', $a_event['slug']) ?>
  <?php echo $form['slug']->renderError() ?>
</div>


<?php // Huge Publish Button and Publish Date ?>
<div class="published section">
	<a href="#" class="a-btn big a-publish-post <?php echo ($a_event['status'] == 'published')? 'published':'' ?>" onclick="return false;" id="a-blog-publish-button">
	  <span class="publish"><?php echo __('Publish', array(), 'apostrophe_blog') ?></span>
	  <span class="unpublish"><?php echo __('Unpublish', array(), 'apostrophe_blog') ?></span>
	</a>
	<div id="a-blog-item-update" class="a-btn big a-publish-post">Saved</div>
	
	<div class="post-status option">
	  <?php echo $form['status']->renderRow() ?>
	</div>

	<?php echo __('Publish now or', array(), 'apostrophe_blog') ?>  <a href="#" onclick="return false;" class="post-date-toggle a-sidebar-toggle"><?php echo __('set a date', array(), 'apostrophe_blog') ?></a>

	<div class="post-published-at option">
	  <?php echo $form['published_at']->render(array('onClose' => 'updateBlogMulti')) ?>
	  <?php echo $form['published_at']->renderError() ?>
	</div>
</div>



<?php // Author & Editors Section ?>
<hr />
<div class="author section">

	<div class="post-author">
	  	<h4><?php echo __('Author', array(), 'apostrophe_blog') ?>:
			<?php if ($sf_user->hasCredential('admin')): ?>
				</h4>	
				<div class="author_id option">
				<?php echo $form['author_id']->render() ?>
				<?php echo $form['author_id']->renderError() ?>				
				</div>
			<?php else: ?>
				<span><?php echo $a_event->Author ?></span></h4>	
			<?php endif ?>

	</div>

	<div class="post-editors">

		<?php if (!count($a_event->Editors)): ?>
		  <a href="#" onclick="return false;" class="post-editors-toggle a-sidebar-toggle"><?php echo __('allow others to edit this post', array(), 'apostrophe_blog') ?></a>
	  	<div class="post-editors-options option" id="editors-section">
		<?php else: ?>
			<hr/>
	  	<div class="post-editors-options option show-editors" id="editors-section">			
		<?php endif ?>

	    <h4><?php echo __('Editors', array(), 'apostrophe_blog') ?>: </h4>
	    <?php echo $form['editors_list']->render()?>
	    <?php echo $form['editors_list']->renderError() ?>
	
	  </div>
	</div>
</div>



<?php // Blog Post Categories ?>
<hr />
<div class="categories section" id="categories-section">
	<h4><?php echo __('Categories', array(), 'apostrophe_blog') ?></h4>
	<?php echo $form['categories_list']->render() ?>
	<?php echo $form['categories_list']->renderError() ?>
</div>



<?php // Blog Post Tags ?>
<hr />
<div class="tags section">
	<h4><?php echo __('Tags', array(), 'apostrophe_blog') ?></h4>

	<?php echo $form['tags']->render() ?>
	<?php echo $form['tags']->renderError() ?>

	<script src='/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js'></script>
	<script type="text/javascript" charset="utf-8">
	  $(function() {
	    pkTagahead(<?php echo json_encode(url_for("taggableComplete/complete")) ?>);
	  });
	</script>
	<?php include_component('aEventAdmin','tagList', array('a_event' => $form->getObject())) ?>
</div>



<?php // Blog Post Templates ?>
<?php if(isset($form['template'])): ?>
<hr />
<div class="template section">
	<h4><?php echo __('Template', array(), 'apostrophe_blog') ?></h4>

	<?php echo $form['template']->render() ?>
	<?php echo $form['template']->renderError() ?>
</div>
<?php endif ?>



<?php if(isset($form['allow_comments'])): ?>
<hr />
<div class="comments section">
	<h4><a href="#" class="allow_comments_toggle <?php echo ($a_event['allow_comments'])? 'enabled' : 'disabled' ?>"><span class="enabled" title="<?php echo __('Click to disable comments', array(), 'apostrophe_blog') ?>"><?php echo __('Comments are enabled', array(), 'apostrophe_blog') ?></span><span class="disabled" title="<?php echo __('Click to enable comments', array(), 'apostrophe_blog') ?>"><?php echo __('Comments are disabled', array(), 'apostrophe_blog') ?></span></a></h4> 
	<div class="allow_comments option">
	<?php echo $form['allow_comments']->render() ?>
	<?php echo $form['allow_comments']->renderError() ?>
	</div>
	
</div>
<?php endif ?>

<?php include_partial('formScripts', array('a_event' => $a_event, 'form' => $form)) ?>