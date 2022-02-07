<?php
/*
Template Name: OrdersReport
*/
?>

<?php get_header(); ?>

<div id="primary" class="site-content">
    <main id="main" class="site-main" role="main">
        <select class="custom-select" style="width:200px; margin-bottom: 20px;" id="sortOption">
            <option value="id">Sort:</option>
            <option value="order_id">Order</option>
            <option value="customer_id">Customer</option>
            <option value="date">Date</option>
        </select>
       
        <?php echo do_shortcode('[data-table order_by="customer_id" table="orders_report"]'); ?>

    </main><!-- #content -->
 

</div><!-- #primary -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>

<script type="text/javascript">
 
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
            "action": "list_shortcode",
            valu: val},
        success: function(data){alert(data);}
});

</script>

<!-- <option value="0">Sort:</option>
          <option value="id">Id</option>
          <option value="order_id">Order</option>
          <option value="customer_id">Customer</option>
          <option value="date">Date</option>


           -->