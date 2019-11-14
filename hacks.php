/* to display categories in dropdown */

<?php
$queried_object = get_queried_object();
$selected_cat = $queried_object->term_id;
?>
<select name="event-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'> 
    <?php 
        $option = '<option value="' . get_option('home') . '/maskinpark/">All Categories</option>';
        $categories = get_categories(); 
        foreach ($categories as $category) {
			$selected = ( $category->term_id == esc_attr( $selected_cat ) ) ? 'selected' : '';
            $option .= '<option '.$selected.' value="'.get_option('home').'/category/'.$category->slug.'">';
            $option .= $category->cat_name;
            $option .= ' ('.$category->category_count.')';
            $option .= '</option>';
        }
        echo $option;
    ?>
</select>


<?php
/* WP Custom quries for SELECT and UPDATE */

$querystr = "
    SELECT *
    FROM $wpdb->posts wposts, $wpdb->postmeta metadate, $wpdb->postmeta metatime
    WHERE (wposts.ID = metadate.post_id AND wposts.ID = metatime.post_id)
    AND (metadate.meta_key = 'tf_events_startdate' AND metadate.meta_value > $yesterday )
    AND metatime.meta_key = 'tf_events_starttime'
    AND wposts.post_type = 'tf_events'
    AND wposts.post_status = 'publish'
    ORDER BY metadate.meta_value ASC, metatime.meta_value DESC LIMIT 30
 ";

$update = "UPDATE wp207_postmeta wm 
JOIN wp207_posts wp ON wm.post_id = wp.ID 
SET wm.meta_value='not available' 
WHERE 
    wm.meta_key='status' AND
    wm.meta_value='available' AND
    DATEDIFF(NOW(), wp.post_date) > 182";


$final = $wpdb->query( $wpdb->prepare( 
	"
	UPDATE $wpdb->posts 
	SET post_parent = %d
	WHERE ID = %d
		AND post_status = %s
	",
        7, 15, 'static'
) );

$post_id = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE (meta_key = 'mfn-post-link1' AND meta_value = '". $from ."')");
