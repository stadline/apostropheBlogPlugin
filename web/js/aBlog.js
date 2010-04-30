function checkAndSetPublish(status, slug_url)
{
	//todo: use jq to get the action from the Form ID
	
	var postStatus = $('#a_blog_post_status');
	var publishButton = $('#a-blog-publish-button');

	if (status == 'published') {
		publishButton.addClass('published');
	};

	publishButton.unbind('click').click(function(){

		$(this).blur();

		if (status == 'draft') 
		{
			postStatus.val('published');
			publishButton.addClass('published');			
		}
		else
		{
			postStatus.val('draft');
			publishButton.removeClass('published');			
		};
		
		// If slug_url
		if (typeof slug_url != 'undefined') 
		{
			updateBlogForm(slug_url);			
		};

	});			
}

function initTitle(slug_url)
{
	// Title Interface 
	// =============================================
	var titleInterface = $('#a_blog_post_title_interface');
	var titlePlaceholder = $('#a-blog-post-title-placeholder');
	var originalTitle = titleInterface.val();

	if (originalTitle == 'untitled') 
	{ // The blog post is 'Untitled' -- Focus the input		
		titleInterface.focus(); 
	};
	
	// Title: On Change Compare
	titleInterface.change(function(){
		if ($(this).val() != '') 
		{ // If the input is not empty
			// Pass the value to the admin form and update
			$('#a_blog_post_title').val($(this).val()); 
			updateBlogForm(slug_url);
		};
	});
	
	titleInterface.blur(function()
	{ // Check for Empty Title Field			
		if ($(this).val() == '') 
		{ 	
			$(this).next().show(); 
		}
	});

	titleInterface.focus(function()
	{	// Always hide the placeholder on focus
		$(this).next().hide(); 
	});
		
	titlePlaceholder.mousedown(function()
	{	// If you click the placeholder text 
		// focus the input (Mousedown is faster than click here)
		titleInterface.focus(); 
	}).hide();
}

function initPermalink(slug_url)
{
	// Permalink Interface  
	// =============================================
	var permalinkInterface = $('#a-blog-post-permalink-interface');
	var pInput = permalinkInterface.find('input');
	var pControls = permalinkInterface.find('ul.a-controls');
	var originalSlug = pInput.val();

	// Permalink: On Focus Listen for Changes
	pInput.focus(function(){
		$(this).select();
		$(this).keyup(function(){
			if ($(this).val().trim() != originalSlug)
			{
				permalinkInterface.addClass('has-changes');
				pControls.fadeIn();
			}
		});
	});

	// Permalink Interface Controls: Save | Cancel
	// =============================================
	pControls.click(function(event)
	{
		event.preventDefault();
		$target = $(event.target);
					
		if ($target.hasClass('a-save'))
		{
			if (pInput.val() == '') 
			{ 	
				pInput.val(originalSlug);
				pControls.hide();
			}
			if ((pInput.val() != '') && (pInput.val().trim() != originalSlug)) 
			{
				$('#a_blog_post_slug').val(pInput.val()); // Pass the value to the admin form and update
				updateBlogForm(slug_url);
			}										
		}
		
		if ($target.hasClass('a-cancel'))
		{
			pInput.val(originalSlug);
			pControls.hide();
		}
	});
}


// Ajax Update Blog Form
function updateBlogForm(slug_url, event)
{

	// $('#a-blog-post-status-indicator').ajaxStart(function(){
	// 	$(this).show();
	// });
	// $('#a-blog-post-status-indicator').ajaxStop(function(){
	// 	$(this).hide();
	// });

	$.ajax({
	  type:'POST',
	  dataType:'text',
	  data:jQuery('#a-admin-form').serialize(),
	  complete:function(xhr, textStatus)
		{
			
      if(textStatus == 'success')
      {
      //data is a JSON object, we can handle any updates with it
      var json = xhr.getResponseHeader('X-Json');
      var data = eval('(' + json + ')');
			
			// TODO: There needs to be a way to selectively call these functions
			// Comments are getting updated EVERYTIME anything gets updated and it's not necessary
			// We need to provide a scope or context to the ajax event

      if ( typeof(data.modified.template) != "undefined" ) {
        updateTemplate(data.template, data.feedback);
      };

      if ( typeof(data.modified.allow_comments) != "undefined" ) {
      	updateComments(data.aBlogPost.allow_comments); // Update Comments after ajax
			};

			checkAndSetPublish(data.aBlogPost.status, slug_url); // Re-set Publish button after ajax
      updateTitleAndSlug(data.aBlogPost.title, data.aBlogPost.slug); // Update Title and Slug after ajax
			updateMessage();
			aUI('#a-admin-form');
      }
	 	},
	 	url: slug_url
	});
}


function updateTitle(title, feedback)
{ // Update Title Function for Ajax calls when it is returned clean from Apostrophe
	var titleInput = $('#a_blog_post_title_interface');
		
	if (title != null) 
	{
		titleInput.val(title);			
	};
}


function updateSlug(slug, feedback)
{ // Update Slug Function for Ajax calls when it is returned clean from Apostrophe
	var permalinkInput = $('#a_blog_post_permalink_interface');
  var slugInput = $('#a_blog_post_slug');

	if (slug != null)
	{
		permalinkInput.val(slug);
     slugInput.val(slug);
	};

	// sendUserMessage(feedback); // See sendUserMessage function below
}


function updateTitleAndSlug(title, slug)
{ // Update TitleAndSlug Function to save u time :D !
	updateTitle(title);
	updateSlug(slug);
}


function toggleCheckbox(checkbox)
{ // Toggle any checkbox you want with this one
	checkbox.attr('checked', !checkbox.attr('checked')); 
}

function updateComments(enabled, feedback)
{
	if (enabled)
	{
		$('.section.comments .allow_comments_toggle').addClass('enabled').removeClass('disabled');
	}
	else
	{
		$('.section.comments .allow_comments_toggle').addClass('disabled').removeClass('enabled');		
	}
}

function updateTemplate(template, feedback)
{
	location.reload(true);
}

function updateMessage(msg)
{
	if (typeof msg == 'undefined') {
		msg = 'Saved!';
	};

	var publishButton = $('#a-blog-publish-button');
	var pUpdate = $('#a-blog-post-update');

	if (pUpdate.data('animating') != 1) {
		pUpdate.data('animating',1).text(msg).fadeIn(100, function(){
			publishButton.children().hide();
			pUpdate.fadeTo(500,1, function(){
				pUpdate.fadeOut(500, function(){
					if (publishButton.hasClass('published')) 
					{
						publishButton.children('.unpublish').fadeIn(100);
					}
					else	
					{
						publishButton.children('.publish').fadeIn(100);					
					}
					pUpdate.data('animating', 0);
				});
			});
		});
	};
	
}

function sendUserMessage(label, desc)
{	
	// Messages are turned off for now!
	// Send a message to the blog editor confirming a change made via Ajax
	
	// var mLabel = (label)?label.toString():""; // passed from ajaxAction
	// var mDescription = (desc)?desc.toString(): ""; // passed from ajaxAction
	// var newMessage = "<dt>"+mLabel+"</dt><dd>"+mDescription+"</dd>";
	// var messageContainer = $('#a-blog-post-status-messages');
	// messageContainer.append(newMessage).addClass('has-messages');
	// messageContainer.children('dt:last').fadeTo(5000,1).fadeOut('slow', function(){ $(this).remove(); }); // This uses ghetto fadeTo delay because jQ1.4 has built-in delay
	// messageContainer.children('dd:last').fadeTo(5000,1).fadeOut('slow', function(){	$(this).remove(); checkMessageContainer(); });  // This uses ghetto fadeTo delay because jQ1.4 has built-in delay
	// 
	// function checkMessageContainer()
	// {
	// 	if (!messageContainer.children().length) {
	// 		messageContainer.removeClass('has-messages');
	// 	};
	// }
}