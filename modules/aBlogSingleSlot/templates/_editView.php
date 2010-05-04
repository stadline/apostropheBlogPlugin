<h4 class="a-slot-form-title">Choose a Blog Post</h4>

<div class="a-form-row search">
	<?php echo $form['search']->renderLabel(__('Search by Title', array(), 'apostrophe_blog')) ?>
	<div class="a-form-field">
		<?php echo $form['search']->render() ?>
		<?php echo $form['search']->renderHelp() ?>
	</div>
	<div class="a-form-error"><?php echo $form['search']->renderError() ?></div>
</div>

<?php // Just echo the form. You might want to render the form fields differently ?>
<?php use_javascript('/sfJqueryReloadedPlugin/js/plugins/jquery.autocomplete.min.js') ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#a-<?php echo $form->getName() ?>').addClass('a-options dropshadow');
	  $('#<?php echo $form['search']->renderId() ?>').autocomplete('<?php echo url_for("@a_blog_admin_autocomplete") ?>');
	  $('#<?php echo $form['search']->renderId() ?>').result(function(event, data, formatted){
	    if (data) {
	      $('#<?php echo $form['blog_item']->renderId() ?>').val(data[1]);
	      $('#<?php echo $form['search']->renderId() ?>').val(data[2]);
	    }
	  });
	});
</script>
