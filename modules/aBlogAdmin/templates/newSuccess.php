<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php use_helper('I18N', 'Date', 'jQuery', 'a') ?>
<?php include_partial('aBlogAdmin/assets') ?>

<div class="a-admin-container <?php echo $sf_params->get('module') ?>">
  <?php include_partial('aBlogAdmin/form_bar', array('title' => __('Edit ABlogAdmin', array(), 'messages'))) ?>

  <div class="a-subnav-wrapper blog">
  	<div class="a-subnav-inner">
    	<?php include_partial('aBlogAdmin/form_header', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration)) ?>
  	</div>
	</div>
  
  <?php include_partial('aBlogAdmin/flashes') ?>
  
  <div class="a-admin-content main">
  <?php a_area('body', array(
  'editable' => false, 'toolbar' => 'basic', 'slug' => 'aBlogPost-'.$a_blog_post['id'],
  'allowed_types' => array('aRichText', 'aImage', 'aButton', 'aSlideshow', 'aVideo', 'aPDF'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aImage' => array('width' => 400, 'flexHeight' => true, 'resizeType' => 's'),
    'aButton' => array('width' => 400, 'flexHeight' => true, 'resizeType' => 's'),
    'aSlideshow' => array("width" => 400, "flexHeight" => true, 'resizeType' => 's', ),
    'aPDF' => array('width' => 400, 'flexHeight' => true, 'resizeType' => 's'),   
  ))
  ) ?>
  </div>
  
  
  <div class="a-admin-right">
    <?php echo form_tag_for($form, '@a_blog_admin', array('id'=>'a-admin-form')) ?>
    
    <?php include_partial('aBlogAdmin/optionsForm', array('a_blog_post' => $a_blog_post, 'form' => $form)) ?>
    <?php include_partial('aBlogAdmin/form_actions', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
    
    </form>
  </div>

  <div class="a-admin-footer">
    <?php //include_partial('aBlogAdmin/form_footer', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
