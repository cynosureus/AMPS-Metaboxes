jQuery(document).ready(function () {
      
 	jQuery("#add-button-link").click(function(){

 		event.preventDefault();

 		items_count = jQuery(".content-item-boxes").length;

 		new_item_num = items_count;

 		new_item_html = '<p class = "content-item-boxes" id = "content-item-boxes-' + items_count + '">';
 		new_item_html += '<label for="content-url-1">URL: </label>';
 		new_item_html += '<input type="text" name="content-url[' + items_count +']" value = ""/>';
 		new_item_html += '<label for="content-caption-1"> Caption: </label>';
 		new_item_html += '<input type="text" name="content-caption[' + items_count +']" value = ""/>'
 		new_item_html += '<span class = "remove-material"><a href = "#" class = "remove-material-link" id = "remove-material-link-' + items_count + '"> Remove</a></span>'

 		new_item_html += '</p>';

 		jQuery("#content-items-container").append(new_item_html);
 		create_remove_function();


 	});


 	create_remove_function();
 	

 	function create_remove_function () {

 		jQuery(".remove-material-link").click(function(){

 		event.preventDefault();
 		id = jQuery(this).attr("id");
 		parts = id.split('-');
 		id = parts.pop();
 		
 		jQuery("#content-item-boxes-" + id).remove();

 	});

 	}

 });


