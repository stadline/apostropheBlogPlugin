<div class="a-form-row a-admin-text a-admin-form-field-tags">
  <label for="blog-tag-list">Recommended Tags</label>

  <div id="blog-tag-list">
    <?php $n=1; foreach ($tags as $tag => $count): ?>
    		<?php echo link_to_function($tag, '', array('class' => (in_array($tag, $a_blog_event->getTags())) ? 'selected recommended-tag' : 'recommended-tag', )) ?><?php echo ($n < count($tags)) ? ', ' : ''; ?>			  
    <?php $n++; endforeach ?>
  </div>
</div>

<script type="text/javascript">

$(document).ready(function(){

	var tagList;
	var recommendedTag = $('.recommended-tag');
			
	recommendedTag.click(function(){
	
	tagList = $('#a_blog_event_tags').attr('value');
	theTag = $(this).text();
	
		if (!$(this).hasClass('selected'))
		{ //Only add it if it hasn't been added already
			tagList += ", "+theTag;
			$('#a_blog_event_tags').attr('value',tagList);
		}
		else
		{
			newTagList = tagList.split(',');
			tagPosition = $.inArray(" "+theTag, newTagList);

			if (tagPosition == -1)
			{ // If it can't find the tag in the array, it is the first tag in the list
				tagPosition = 0;
			}

			newTagList.splice(tagPosition,1);
			$('#a_blog_event_tags').attr('value',newTagList.toString());
		}
		
		$(this).toggleClass('selected');
		
	});
});

</script>