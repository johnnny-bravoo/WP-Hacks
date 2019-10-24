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
