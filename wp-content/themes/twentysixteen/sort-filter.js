var val = jQuery('#sortOption').val();

jQuery('#sortOption').on("change", function(e) {
    val = jQuery('#sortOption').val();
    // console.log(val);
    console.log(frontend_ajax_object.ajaxurl,"url");
});

jQuery.ajax({
        method: 'post',
        dataType:"json",
        url:frontend_ajax_object.ajaxurl,
        data: { 
            "action": 'list_shortcode',
            "valu": val},     
});
