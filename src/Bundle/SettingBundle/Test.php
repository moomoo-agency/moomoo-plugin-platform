<?php

$themename = "base-theme";
$shortname = "mnt";

/* functions to andale the options array  */

function mnt_get_formatted_page_array() {
    global $suffusion_pages_array;
    if (isset($suffusion_pages_array) && $suffusion_pages_array != null) {
        return $suffusion_pages_array;
    }
    $ret = array();
    $pages = get_pages('sort_column=menu_order');
    if ($pages != null) {
        foreach ($pages as $page) {
            if (is_null($suffusion_pages_array)) {
                $ret[$page->ID] = array ("title" => $page->post_title, "depth" => count(get_ancestors($page->ID, 'page')));
            }
        }
    }
    if ($suffusion_pages_array == null) {
        $suffusion_pages_array = $ret;
        return $ret;
    }
    else {
        return $suffusion_pages_array;
    }
}

function mnt_get_category_array() {
    global $suffusion_category_array;
    if (isset($suffusion_category_array) && $suffusion_category_array != null) {
        return $suffusion_category_array;
    }
    $ret = array();
    $args = array(
        'orderby' => 'name',
        'parent' => 0
    );
    $categories = get_categories( $args );
    if($categories != null){
        foreach ($categories as $category) {
            if (is_null($suffusion_category_array)) {
                $ret[$category->cat_ID] = array ("name" => $category->name, "number" => $category->count);
            }
        }
    }

    if ($suffusion_category_array == null) {
        $suffusion_category_array = $ret;
        return $ret;
    }
    else {
        return $suffusion_category_array;
    }
}

function create_opening_tag($value) {
    $group_class = "";
    if (isset($value['grouping'])) {
        $group_class = "suf-grouping-rhs";
    }
    echo '<div class="suf-section fix">'."\n";
    if ($group_class != "") {
        echo "<div class='$group_class fix'>\n";
    }
    if (isset($value['name'])) {
        echo "<h3>" . $value['name'] . "</h3>\n";
    }
    if (isset($value['desc']) && !(isset($value['type']) && $value['type'] == 'checkbox')) {
        echo $value['desc']."<br />";
    }
    if (isset($value['note'])) {
        echo "<span class=\"note\">".$value['note']."</span><br />";
    }
}

/**
 * Creates the closing markup for each option.
 *
 * @param $value
 * @return void
 */
function create_closing_tag($value) {
    if (isset($value['grouping'])) {
        echo "</div>\n";
    }
    //echo "</div><!-- suf-section -->\n";
    echo "</div>\n";
}

function create_suf_header_3($value) { echo '<h3 class="suf-header-3">'.$value['name']."</h3>\n"; }

function create_section_for_text($value) {
    create_opening_tag($value);
    $text = "";
    if (get_option($value['id']) === FALSE) {
        $text = $value['std'];
    }
    else {
        $text = get_option($value['id']);
    }

    echo '<input type="text" id="'.$value['id'].'" placeholder="enter your title" name="'.$value['id'].'" value="'.$text.'" />'."\n";
    create_closing_tag($value);
}

function create_section_for_textarea($value) {
    create_opening_tag($value);
    echo '<textarea name="'.$value['id'].'" type="textarea" cols="" rows="">'."\n";
    if ( get_option( $value['id'] ) != "") {
        echo get_option( $value['id'] );
    }
    else {
        echo $value['std'];
    }
    echo '</textarea>';
    create_closing_tag($value);
}

function create_section_for_color_picker($value) {
    create_opening_tag($value);
    $color_value = "";
    if (get_option($value['id']) === FALSE) {
        $color_value = $value['std'];
    }
    else {
        $color_value = get_option($value['id']);
    }

    echo '<div class="color-picker">'."\n";
    echo '<input type="text" id="'.$value['id'].'" name="'.$value['id'].'" value="'.$color_value.'" class="color" />';
    echo ' « Click to select color<br/>'."\n";
    echo "<strong>Default: <font color='".$value['std']."'> ".$value['std']."</font></strong>";
    echo " (You can copy and paste this into the box above)\n";
    echo "</div>\n";
    create_closing_tag($value);
}

function create_section_for_radio($value) {
    create_opening_tag($value);
    foreach ($value['options'] as $option_value => $option_text) {
        $checked = ' ';
        if (get_option($value['id']) == $option_value) {
            $checked = ' checked="checked" ';
        }
        else if (get_option($value['id']) === FALSE && $value['std'] == $option_value){
            $checked = ' checked="checked" ';
        }
        else {
            $checked = ' ';
        }
        echo '<div class="mnt-radio"><input type="radio" name="'.$value['id'].'" value="'.
            $option_value.'" '.$checked."/>".$option_text."</div>\n";
    }
    create_closing_tag($value);
}

function create_section_for_multi_select($value) {
    create_opening_tag($value);
    echo '<ul class="mnt-checklist" id="'.$value['id'].'" >'."\n";
    foreach ($value['options'] as $option_value => $option_list) {
        $checked = " ";
        if (get_option($value['id']."_".$option_value)) {
            $checked = " checked='checked' ";
        }
        echo "<li>\n";
        echo '<input type="checkbox" name="'.$value['id']."_".$option_value.'" value="true" '.$checked.' class="depth-'.($option_list['depth']+1).'" />'.$option_list['title']."\n";
        echo "</li>\n";
    }
    echo "</ul>\n";
    create_closing_tag($value);
}

function create_section_for_category_select($page_section,$value) {
    create_opening_tag($value);
    $all_categoris='';
    echo '<div class="wrap" id="'.$value['id'].'" >'."\n";
    echo '<h1>Theme Options</h1> '."\n" .'
				<p><strong>'.$page_section.':</strong></p>';
    echo "<select id='".$value['id']."' class='post_form' name='".$value['id']."' value='true'>\n";
    echo "<option id='all' value=''>All</option>";
    foreach ($value['options'] as $option_value => $option_list) {
        $checked = ' ';
        echo 'value_id=' . $value['id'] .' value_id=' . get_option($value['id']) . ' options_value=' . $option_value;
        if (get_option($value['id']) == $option_value) {
            $checked = ' checked="checked" ';
        }
        else if (get_option($value['id']) === FALSE && $value['std'] == $option_value){
            $checked = ' checked="checked" ';
        }
        else {
            $checked = '';
        }
        echo '<option value="'.$option_list['name'].'" class="level-0" '.$checked.' number="'.($option_list['number']).'" />'.$option_list['name']."</option>\n";
        //$all_categoris .= $option_list['name'] . ',';
    }
    echo "</select>\n </div>";
    //echo '<script>jQuery("#all").val("'.$all_categoris.'")</\script>';
    create_closing_tag($value);
}

$options = array(
    array("name" => "Header Customization",
        "type" => "sub-section-3",
        "category" => "header-styles",
    ),
    array("name" => "Header Image",
        "desc" => "Set the image to use for the header background. ",
        "id" => $shortname."_header_background_image",
        "type" => "text",
        "parent" => "header-styles",
        "std" => ""),
    array("name" => "Body Background Settings",
        "type" => "sub-section-3",
        "category" => "body-styles",
    ),
    array("name" => "Body Background Color",
        "desc" => "Set the color of the background on which the page is. ",
        "id" => $shortname."_body_background_color",
        "type" => "color-picker",
        "parent" => "body-styles",
        "std" => "444444"),
    array("name" => "Sidebar Setup",
        "type" => "sub-section-3",
        "category" => "sidebar-setup",
    ),
    array("name" => "Sidebar Position",
        "id" => $shortname."_sidebar_alignment",
        "type" => "radio",
        "desc" => "Which side would you like your sidebar?",
        "options" => array("left" => "Left", "right" => "Right"),
        "parent" => "sidebar-setup",
        "std" => "right"),
    array("name" => "Navigation Bar Setup",
        "type" => "sub-section-3",
        "category" => "nav-setup",
    ),
    array("name" => "Pages to show in Navigation Bar",
        "desc" => "Select the pages you want to include. All pages are excluded by default",
        "id" => $shortname."_nav_pages",
        "type" => "multi-select",
        "options" => mnt_get_formatted_page_array($shortname."_nav_pages"),
        "parent" => "nav-setup",
        "std" => "none"
    ),
    array("name" => "Analytics",
        "type" => "sub-section-3",
        "category" => "analytics-setup",
    ),
    array("name" => "Custom Google Analytics Tracking Code",
        "desc" => "Enter your tracking code here for Google Analytics",
        "id" => $shortname."_custom_analytics_code",
        "type" => "textarea",
        "parent" => "analytics-setup",
        "std" => ""
    ),
    array("name" => "category posts to show on the front page",
        "desc" => "Select the category you want to include. All pages are excluded by default",
        "id" => $shortname."_front_page_first_section",
        "type" => "select",
        "options" => mnt_get_category_array($shortname."_nav_pages"),
        "parent" => "nav-setup",
        "std" => mnt_get_category_array($shortname."_nav_pages")
    ),
    array("name" => "category posts to show on the front page below",
        "desc" => "Select the category you want to include. All pages are excluded by default",
        "id" => $shortname."_front_page_second_section",
        "type" => "select-2",
        "options" => mnt_get_category_array($shortname."_nav_pages"),
        "parent" => "nav-setup",
        "std" => mnt_get_category_array($shortname."_nav_pages")
    ),
);

function create_form($options) {
    echo "<form id='options_form' method='post' name='form' >\n";
    foreach ($options as $value) {
        switch ( $value['type'] ) {
            case "sub-section-3":
                create_suf_header_3($value);
                break;

            case "text";
                create_section_for_text($value);
                break;

            case "textarea":
                create_section_for_textarea($value);
                break;

            case "multi-select":
                create_section_for_multi_select($value);
                break;

            case "radio":
                create_section_for_radio($value);
                break;

            case "color-picker":
                create_section_for_color_picker($value);
                break;
            case "select":
                create_section_for_category_select('first section',$value);
                break;
            case "select-2":
                create_section_for_category_select('second section',$value);
                break;
        }
    }

    ?>
    <input name="save" type="button" value="Save" class="button" onclick="submit_form(this, document.forms['form'])" />
    <input name="reset_all" type="button" value="Reset to default values" class="button" onclick="submit_form(this, document.forms['form'])" />
    <input type="hidden" name="formaction" value="default" />

    <script> function submit_form(element, form){
            form['formaction'].value = element.name;
            form.submit();
        } </script>

    </form>
<?php }  ?>



<?php

add_action('admin_menu', 'mynewtheme_add_admin');
function mynewtheme_add_admin() {
    global $themename, $shortname, $options, $spawned_options;

    if ( $_GET['page'] == basename(__FILE__) ) {
        if ( 'save' == $_REQUEST['formaction'] ) {
            foreach ($options as $value) {
                if( isset( $_REQUEST[ $value['id'] ] ) ) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
                }
                else {
                    delete_option( $value['id'] );
                }
            }

            foreach ($spawned_options as $value) {
                if( isset( $_REQUEST[ $value['id'] ] ) ) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
                }
                else {
                    delete_option( $value['id'] );
                }
            }
            header("Location: themes.php?page=options.php&saved=true");
            die;
        }
        else if('reset_all' == $_REQUEST['formaction']) {
            foreach ($options as $value) {
                delete_option( $value['id'] );
            }

            foreach ($spawned_options as $value) {
                delete_option( $value['id'] );
            }
            header("Location: themes.php?page=options.php&".$_REQUEST['formaction']."=true");
            die;
        }
    }

    add_theme_page($themename." Theme Options", "".$themename." Theme Options",
        'edit_themes', basename(__FILE__), 'mynewtheme_admin'); }

function mynewtheme_admin() {
    global $themename, $shortname, $options, $spawned_options, $theme_name;

    if ($_REQUEST['saved']) {
        echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved for this page.</strong></p></div>';
    }
    if ($_REQUEST['reset_all']) {
        echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Settings for <?php echo $themename; ?></h1>
        <div class="mnt-options">
            <?php
            create_form($options);
            ?>
        </div><!-- mnt-options -->
    </div><!-- wrap -->
<?php } // end function mynewtheme_admin()
?>
