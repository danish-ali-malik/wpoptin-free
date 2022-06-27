<?php

/**
 * Admin View: Tab - Triggers & Conditions
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
global  $wpoptins ;
$WPOptins = new WPOptins();
if ( isset( $_GET['wpop_optin_id'] ) ) {
    $wpoptin_id = (int) sanitize_text_field( $_GET['wpop_optin_id'] );
}
if ( isset( $_GET['goal'] ) ) {
    $goal = (string) sanitize_text_field( $_GET['goal'] );
}
if ( isset( $_GET['is_child'] ) && $_GET['is_child'] == 'true' ) {
    $is_child = true;
}
$show_pages = [];
$pages_show = '';
$pages_hide = '';
$posts_show = '';
$posts_hide = '';
$tags_hide = '';
$tags_show = '';
$cats_show = '';
$cats_hide = '';
$trigger_method = $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_trigger_method',
    false,
    $is_child
);
$show_pages = $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_pages_show_name',
    false,
    $is_child
);
$hide_pages = $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_pages_hide_name',
    false,
    $is_child
);
$show_posts = $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_posts_show_name',
    false,
    $is_child
);
$hide_posts = $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_posts_hide_name',
    false,
    $is_child
);
$show_tags = $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_tags_show_name',
    false,
    $is_child
);
$hide_tags = $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_tags_hide_name',
    false,
    $is_child
);
$show_cats = $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_cats_show_name',
    false,
    $is_child
);
$hide_cats = $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_cats_hide_name',
    false,
    $is_child
);
if ( !isset( $trigger_method ) && empty($trigger_method) ) {
    $trigger_method = 'auto';
}
$pages = get_pages( [
    'sort_order'  => 'asc',
    'sort_column' => 'post_title',
    'parent'      => -1,
    'post_type'   => 'page',
    'post_status' => 'publish',
] );
if ( !isset( $pages ) || empty($pages) ) {
    $pages = [];
}

if ( $pages ) {
    foreach ( $pages as $page ) {
        $pages_show .= '<option value="' . $page->ID . '" ';
        if ( isset( $show_pages ) && !empty($show_pages) ) {
            $pages_show .= '' . selected( true, in_array( $page->ID, $show_pages ), false ) . '';
        }
        $pages_show .= '>' . $page->post_title . '</option>';
    }
    foreach ( $pages as $page ) {
        $pages_hide .= '<option value="' . $page->ID . '" ';
        if ( isset( $hide_pages ) && !empty($hide_pages) ) {
            $pages_hide .= '' . selected( true, in_array( $page->ID, $hide_pages ), false ) . '';
        }
        $pages_hide .= '>' . $page->post_title . '</option>';
    }
}

$posts = get_posts( [
    'posts_per_page' => -1,
    'order'          => 'DESC',
    'sort_column'    => 'post_title',
    'post_type'      => 'post',
    'post_status'    => 'publish',
] );
if ( !isset( $posts ) || empty($posts) ) {
    $posts = [];
}

if ( $posts ) {
    foreach ( $posts as $post ) {
        $posts_show .= '<option value="' . $post->ID . '"';
        if ( isset( $show_posts ) && !empty($show_posts) ) {
            $posts_show .= '' . selected( true, in_array( $post->ID, $show_posts ), false ) . '';
        }
        $posts_show .= '>' . $post->post_title . '</option>';
    }
    foreach ( $posts as $post ) {
        $posts_hide .= '<option value="' . $post->ID . '"';
        if ( isset( $hide_posts ) && !empty($hide_posts) ) {
            $posts_hide .= '' . selected( true, in_array( $post->ID, $hide_posts ), false ) . '';
        }
        $posts_hide .= '>' . $post->post_title . '</option>';
    }
}

$tags = get_tags();
if ( !isset( $tags ) || empty($tags) ) {
    $tags = [];
}

if ( $tags ) {
    foreach ( $tags as $tag ) {
        $tags_show .= '<option value="' . $tag->name . '"';
        if ( isset( $show_tags ) && !empty($show_tags) ) {
            $tags_show .= '' . selected( true, in_array( $tag->name, $show_tags ), false ) . '';
        }
        $tags_show .= '>' . $tag->name . '</option>';
    }
    foreach ( $tags as $tag ) {
        $tags_hide .= '<option value="' . $tag->name . '"';
        if ( isset( $hide_tags ) && !empty($hide_tags) ) {
            $tags_hide .= '' . selected( true, in_array( $tag->name, $hide_tags ), false ) . '';
        }
        $tags_hide .= '>' . $tag->name . '</option>';
    }
} else {
    $tags_hide .= '<option value="" disabled selected>' . __( 'Nothing found!' ) . '</option>';
    $tags_show .= '<option value="" disabled selected>' . __( 'Nothing found!' ) . '</option>';
}

$cats = get_categories();
if ( !isset( $cats ) || empty($cats) ) {
    $cats = [];
}

if ( $cats ) {
    foreach ( $cats as $cat ) {
        $cats_show .= '<option value="' . $cat->name . '"';
        if ( isset( $show_cats ) && !empty($show_cats) ) {
            $cats_show .= '' . selected( true, in_array( $cat->name, $show_cats ), false ) . '';
        }
        $cats_show .= '>' . $cat->name . '</option>';
    }
    foreach ( $cats as $cat ) {
        $cats_hide .= '<option value="' . $cat->name . '"';
        if ( isset( $hide_cats ) && !empty($hide_cats) ) {
            $cats_hide .= '' . selected( true, in_array( $cat->name, $hide_cats ), false ) . '';
        }
        $cats_hide .= '>' . $cat->name . '</option>';
    }
} else {
    $cats_hide .= '<option value="" disabled selected>' . __( 'Nothing found!' ) . '</option>';
    $cats_show .= '<option value="" disabled selected>' . __( 'Nothing found!' ) . '</option>';
}

if ( empty($trigger_method) ) {
    $trigger_method = 'auto';
}

if ( empty($WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_auto_method',
    false,
    $is_child
)) ) {
    $wpop_auto_method = 'im';
} else {
    $wpop_auto_method = $WPOptins->wpop_get_settings(
        $wpoptin_id,
        'conditions',
        'wpop_auto_method',
        false,
        $is_child
    );
}


if ( empty($WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_sec_value',
    false,
    $is_child
)) ) {
    $wpop_sec_value = '5';
} else {
    $wpop_sec_value = $WPOptins->wpop_get_settings(
        $wpoptin_id,
        'conditions',
        'wpop_sec_value',
        false,
        $is_child
    );
}


if ( empty($WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_scrol_perc_value',
    false,
    $is_child
)) ) {
    $wpop_scrol_perc_value = '50';
} else {
    $wpop_scrol_perc_value = $WPOptins->wpop_get_settings(
        $wpoptin_id,
        'conditions',
        'wpop_scrol_perc_value',
        false,
        $is_child
    );
}


if ( empty($WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_scroll_method',
    false,
    $is_child
)) ) {
    $wpop_scroll_method = 'scroll_perc';
} else {
    $wpop_scroll_method = $WPOptins->wpop_get_settings(
        $wpoptin_id,
        'conditions',
        'wpop_scroll_method',
        false,
        $is_child
    );
}

?>
<div class="tab-content fadeIn" id="triggers-panel">
    <div class="row">
        <div class="col s9">
            <form class="xo_addNew_tnc">
                <ul class="xo_toggle_ul">
                    <li class="xo_toggle_tr xo_toggle_li wpop-triggers-tab">
            <span class="acc_i">
            </span>
                        <h2>
							<?php 
esc_html_e( "Triggers", 'wpoptin' );
?>
                        </h2>
                        <div class="xo_toggle_holder xo_tr_holder">
                            <h6>
								<?php 
esc_html_e( "Chose the right trigger at the right time to grab the attention of your visitors.", 'wpoptin' );
?>
                            </h6>
                            <div class="xo_trigger_icons xo_trigger_auto_main wpop-tooltipped"
                                 id="tt_auto" data-position="right"
                                 data-tooltip="<?php 
esc_html_e( "Show immediately or after some seconds.", 'wpoptin' );
?>">
                                <input class="xo_tr_radio" type="radio"
                                       value="auto"
									<?php 
checked( $trigger_method, 'auto', true );
?>
                                       name="wpop_trigger_method">
                                <i class="icon mt-magic" aria-hidden="true">
                                </i>
                            </div>

                            <div class="xo_trigger_icons xo_trigger_scroll_main wpop-tooltipped"
                                 data-position="right"
                                 data-tooltip="<?php 
esc_html_e( "Show on scroll or after any selector.", 'wpoptin' );
?>"
                                 id="tt_scroll">
                                <input class="xo_tr_radio" type="radio"
                                       value="scroll"
									<?php 
checked( $trigger_method, 'scroll', true );
?>
                                       name="wpop_trigger_method">
                                <i class="icon mt-long-arrow-down pulse-down"
                                   aria-hidden="true">
                                </i>
                            </div>


                            <div class="xo_trigger_icons xo_trigger_exit_main wpop-tooltipped"
                                 data-position="right"
                                 data-tooltip="<?php 
esc_html_e( "Show when exit intent detected.", 'wpoptin' );
?>"
                                 id="tt_scroll" id="tt_exit">
                                <input class="xo_tr_radio" type="radio"
                                       value="exit"
									<?php 
checked( $trigger_method, 'exit', true );
?>
                                       name="wpop_trigger_method">
                                <i class="icon mt-sign-out" aria-hidden="true">
                                </i>
                            </div>


                            <div class="xo_trigger_icons xo_trigger_click_main wpop-tooltipped"
                                 data-position="right"
                                 data-tooltip="<?php 
esc_html_e( "Show on click", 'wpoptin' );
?>"
                                 id="tt_click">
                                <input class="xo_tr_radio" type="radio"
                                       value="click"
									<?php 
checked( $trigger_method, 'click', true );
?>
                                       name="wpop_trigger_method">
                                <i class="icon mt-hand-pointer-o"
                                   aria-hidden="true">
                                </i>
                            </div>

                            <div class="xo_auto_cond">
                                <div class="xo_cond_wrap xo_auto_cond_wrap">
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect"
                                           for="wpop_auto_method">
                                        <input type="radio"
                                               id="wpop_auto_method"
                                               class="mdl-radio__button xo_auto_im"
                                               name="wpop_auto_method"
                                               value="im"
											<?php 
checked( $wpop_auto_method, 'im', true );
?> >
                                        <span class="mdl-radio__label">
                      <?php 
esc_html_e( "Immediately", 'wpoptin' );
?>
                    </span>
                                    </label>
                                </div>
                                <div class="xo_cond_wrap xo_auto_cond_wrap">
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect"
                                           for="xo_auto_sec">
                                        <input type="radio" id="xo_auto_sec"
                                               class="mdl-radio__button xo_auto_sec"
                                               name="wpop_auto_method"
                                               value="sec"
											<?php 
checked( $wpop_auto_method, 'sec', true );
?>>
                                        <span class="mdl-radio__label">
                      <?php 
esc_html_e( "After some seconds.", 'wpoptin' );
?>
                    </span>
                                    </label>
                                </div>


                                <div class="xo_cond_wrap xo_auto_cond_wrap xo_auto_p">
                                    <p>
										<?php 
esc_html_e( "After", 'wpoptin' );
?>
                                        <input class="xo_auto_sec" type="number"
                                               name="wpop_sec_value" min="1"
                                               value="<?php 
echo  $wpop_sec_value ;
?>">
										<?php 
esc_html_e( "seconds", 'wpoptin' );
?>
                                    </p>
                                </div>
                            </div>


                            <div class="xo_scroll_cond">

                                <div class="xo_cond_wrap xo_scroll_cond_wrap">
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect"
                                           for="wpop_scroll_method">
                                        <input type="radio"
                                               id="wpop_scroll_method"
                                               class="mdl-radio__button wpop_scroll_method"
                                               name="wpop_scroll_method"
											<?php 
checked( $wpop_scroll_method, 'scroll_perc', true );
?>
                                               value="scroll_perc">
                                        <span class="mdl-radio__label">
                      <?php 
esc_html_e( "On scroll", 'wpoptin' );
?>
                    </span>
                                    </label>
                                </div>

                                <div class="xo_cond_wrap xo_scroll_cond_wrap xo_scroll_percent">
                                    <p>
										<?php 
esc_html_e( "After", 'wpoptin' );
?>
                                        <input class="xo_scrol_perc"
                                               type="number"
                                               name="wpop_scrol_perc_value"
                                               min="1"
                                               value="<?php 
echo  $wpop_scrol_perc_value ;
?>">
										<?php 
esc_html_e( " % from top", 'wpoptin' );
?>
                                    </p>
                                </div>

                                <div class="xo_cond_wrap xo_scroll_cond_wrap">
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect"
                                           for="xo_scroll_slect">
                                        <input type="radio" id="xo_scroll_slect"
                                               class="mdl-radio__button xo_scroll_slect"
                                               name="wpop_scroll_method"
											<?php 
checked( $wpop_scroll_method, 'scroll_slect', true );
?>
                                               value="scroll_slect">
                                        <span class="mdl-radio__label">
                      <?php 
esc_html_e( "After any selector", 'wpoptin' );
?>
                    </span>
                                    </label>
                                </div>

                                <div class="xo_cond_wrap xo_scroll_cond_wrap xo_scroll_s">
                                    <div class="input-field">
                                        <input type="text"
                                               class="xo_scrol_slect"
                                               id="wpop_scrol_slect_value"
                                               value="<?php 
echo  $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_scrol_slect_value',
    false,
    $is_child
) ;
?>"
                                               name="wpop_scrol_slect_value">
                                        <label for="wpop_scrol_slect_value">
											<?php 
esc_html_e( " Selector", 'wpoptin' );
?>
                                        </label>
                                        <div class="icon material-icons wpop-tooltipped wpop-help-icon"
                                             data-position="bottom"
                                             data-tooltip="<?php 
esc_html_e( "Add \n                         <b>.\n                    </b> for class \n                  <b>#\n                  </b> for ID of element. For example: <b>.wpoptin</b> <b>#wpoptin</b>", 'wpoptin' );
?>">
                                            help_outline
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="xo_click_cond">
                                <div class="xo_cond_wrap xo_click_cond_wrap">
                                    <div class="input-field">
                                        <input type="text"
                                               class="xo_scrol_slect"
                                               id="wpop_click_class"
                                               value="<?php 
echo  $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_click_class',
    false,
    $is_child
) ;
?>"
                                               name="wpop_click_class">
                                        <label for="wpop_click_class">
											<?php 
esc_html_e( "Selector", 'wpoptin' );
?>
                                        </label>
                                        <div class="icon material-icons wpop-tooltipped wpop-help-icon"
                                             data-position="bottom"
                                             data-tooltip="<?php 
esc_html_e( "Add \n                   <b>.\n              </b> for class \n            <b>#\n            </b> for ID of element. For example: <b>.wpoptin</b> or <b>#wpoptin</b>", 'wpoptin' );
?>">
                                            help_outline
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="xo_toggle_d xo_toggle_li wpop-display-tab">
      <span class="acc_i">
      </span>
                        <h2>
							<?php 
esc_html_e( "Display", 'wpoptin' );
?>
                        </h2>
                        <div class="xo_d_holder xo_toggle_holder">
                            <div class="xo_display_wrap">
                                <div class="xo_cond_wrap xo_main_disp_wrap">
                                    <div class="xo_cond_col">
                                        <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
                                               for="wpop_show_all">
                                            <input type="checkbox"
                                                   id="wpop_show_all"
												<?php 
checked( $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_show_all',
    false,
    $is_child
), 'on', true );
?>
                                                   name="wpop_show_all"
                                                   class="filled-in">
                                            <span class="mdl-checkbox__label">
                  <?php 
esc_html_e( "Show everywhere", 'wpoptin' );
?>
                </span>
                                        </label>
                                    </div>

                                    <div class="xo_check_all">
                                        <div class="xo_cond_col">

                                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
                                                   for="wpop_show_home">
                                                <input type="checkbox"
                                                       id="wpop_show_home"
													<?php 
checked( $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_show_home',
    false,
    $is_child
), 'on', true );
?>
                                                       name="wpop_show_home"
                                                       class="filled-in">
                                                <span class="mdl-checkbox__label">
                    <?php 
esc_html_e( "Home or front page", 'wpoptin' );
?>
                  </span>
                                            </label>
                                        </div>


                                        <div class="xo_cond_col">

                                            <label class="mdl-checkbox"
                                                   for="wpop_pages_method">
                                                <input type="checkbox"
                                                       id="wpop_pages_method"
													<?php 
checked( $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_pages_method',
    false,
    $is_child
), 'on', true );
?>
                                                       name="wpop_pages_method"
                                                       class="filled-in">
                                                <span class="mdl-checkbox__label">
                        <?php 
esc_html_e( "Pages", 'wpoptin' );
?>
                      </span>
                                            </label>

                                            <div class="xo_cond_wrap xo_pages_s">
                                                <label>
													<?php 
esc_html_e( "Display on following pages", 'wpoptin' );
?>
                                                </label>
                                                <select name="wpop_pages_show_name[]"
                                                        class="spages"
                                                        multiple="true">
													<?php 
echo  $pages_show ;
?>
                                                </select>
                                            </div>

                                            <div class="xo_cond_wrap xo_pages_h">
                                                <label>
													<?php 
esc_html_e( "Do not display on following pages", 'wpoptin' );
?>
                                                    (
                                                    <i>
														<?php 
esc_html_e( "It will override above condition", 'wpoptin' );
?>
                                                    </i>)
                                                </label>
                                                <select name="wpop_pages_hide_name[]"
                                                        class="spages"
                                                        multiple="true">
													<?php 
echo  $pages_hide ;
?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="xo_cond_col">

                                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
                                                   for="wpop_posts_method">
                                                <input type="checkbox"
                                                       id="wpop_posts_method"
													<?php 
checked( $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_posts_method',
    false,
    $is_child
), 'on', true );
?>
                                                       name="wpop_posts_method"
                                                       class="filled-in">
                                                <span class="mdl-checkbox__label">
                    <?php 
esc_html_e( "Posts", 'wpoptin' );
?>
                  </span>
                                            </label>

                                            <div class="xo_cond_wrap xo_posts_s">
                                                <label>
													<?php 
esc_html_e( "Display on following posts", 'wpoptin' );
?>
                                                </label>
                                                <select name="wpop_posts_show_name[]"
                                                        class="sposts"
                                                        multiple="true">
													<?php 
echo  $posts_show ;
?>
                                                </select>
                                            </div>
                                            <div class="xo_cond_wrap xo_posts_h">
                                                <label>
													<?php 
esc_html_e( "Do not display on following posts", 'wpoptin' );
?>
                                                    (
                                                    <i>
														<?php 
esc_html_e( "It will override above condition", 'wpoptin' );
?>
                                                    </i>)
                                                </label>
                                                <select name="wpop_posts_hide_name[]"
                                                        class="sposts"
                                                        multiple="true">
													<?php 
echo  $posts_hide ;
?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="xo_cond_col">

                                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
                                                   for="wpop_cats_method">
                                                <input type="checkbox"
                                                       id="wpop_cats_method"
													<?php 
checked( $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_cats_method',
    false,
    $is_child
), 'on', true );
?>
                                                       name="wpop_cats_method"
                                                       class="filled-in">
                                                <span class="mdl-checkbox__label">
                    <?php 
esc_html_e( "Categories", 'wpoptin' );
?>
                  </span>
                                            </label>

                                            <div class="xo_cond_wrap xo_cats_s">
                                                <label>
													<?php 
esc_html_e( "Display on following categories", 'wpoptin' );
?>
                                                </label>
                                                <select name="wpop_cats_show_name[]"
                                                        class="scats"
                                                        multiple="true">
													<?php 
echo  $cats_show ;
?>
                                                </select>
                                            </div>
                                            <div class="xo_cond_wrap xo_cats_h">
                                                <label>
													<?php 
esc_html_e( "Do not display on following categories", 'wpoptin' );
?>
                                                    (
                                                    <i>
														<?php 
esc_html_e( "It will override above condition", 'wpoptin' );
?>
                                                    </i>)
                                                </label>
                                                <select name="wpop_cats_hide_name[]"
                                                        class="scats"
                                                        multiple="true">
													<?php 
echo  $cats_hide ;
?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="xo_cond_col">

                                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
                                                   for="wpop_tags_method">
                                                <input type="checkbox"
                                                       id="wpop_tags_method"
													<?php 
checked( $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'conditions',
    'wpop_tags_method',
    false,
    $is_child
), 'on', true );
?>
                                                       name="wpop_tags_method"
                                                       class="filled-in">
                                                <span class="mdl-checkbox__label">
                    <?php 
esc_html_e( "Tags", 'wpoptin' );
?>
                  </span>
                                            </label>

                                            <div class="xo_cond_wrap xo_tags_s">
                                                <label>
													<?php 
esc_html_e( "Display on following tags", 'wpoptin' );
?>
                                                </label>
                                                <select name="wpop_tags_show_name[]"
                                                        class="stags"
                                                        multiple="true">
													<?php 
echo  $tags_show ;
?>
                                                </select>
                                            </div>

                                            <div class="xo_cond_wrap xo_tags_h">
                                                <label>
													<?php 
esc_html_e( "Do not display on following tags", 'wpoptin' );
?>
                                                    (
                                                    <i>
														<?php 
esc_html_e( "It will override above condition", 'wpoptin' );
?>
                                                    </i>)
                                                </label>
                                                <select name="wpop_tags_hide_name[]"
                                                        class="stags"
                                                        multiple="true">
													<?php 
echo  $tags_hide ;
?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="xo_toggle_c xo_toggle_li wpop-conditions-tab">
      <span class="acc_i">
      </span>
                        <h2>
							<?php 
esc_html_e( "Conditions", 'wpoptin' );
?>
                        </h2>
                        <div class="xo_c_holder xo_toggle_holder">
                            <div class="xo_availble_wrap">
                                <div class="xo_col_half">
                                    <div class="xo_new_head">
                                        <h1>
											<?php 
esc_html_e( "Visitor Status", 'wpoptin' );
?>
                                        </h1>
                                    </div>
                                    <p>
                                        <label>

											<?php 
?>

                                                <input type="checkbox"
                                                       href="#wpop-upgrade-visitor-status"
                                                       id="wpop_vistor_method"
                                                       class="filled-in modal-trigger"/>

											<?php 
?>

                                            <span>
                  <?php 
esc_html_e( "Visitor is logged in", 'wpoptin' );
?>
                </span>
                                        </label>

										<?php 

if ( wpop_fs()->is_free_plan() ) {
    ?>

                                        <div id="wpop-upgrade-visitor-status"
                                             class="modal wpop-upgrade-modal">
                                            <div class="modal-content">
                                                <div class="wpop-modal-content">
                                                    <span class="wpop-lock-icon"><i
                                                                class="material-icons">lock_outline</i> </span>
                                                    <h5><?php 
    esc_html_e( "Premium Feature", 'wpoptin' );
    ?></h5>
                                    <p><?php 
    esc_html_e( "We're sorry, Visitor Status conditions are not included in your plan. Please upgrade to the premium plan to unlock this and all other cool features. Keep increase conversion by 10x while testing new ideas.", 'wpoptin' );
    ?></p>
                                    <hr>
                                    <p><?php 
    esc_html_e( "Use following coupon to get 27% discount for limited time", 'wpoptin' );
    ?>
                                        <br><code>BFCMOP</code></p>
                                    <a href="<?php 
    echo  wpop_fs()->get_upgrade_url() ;
    ?>"
                                       class="waves-effect waves-light btn z-depth-3"><i
                                                class="material-icons right">lock_open</i><?php 
    esc_html_e( "Upgrade to pro", 'wpoptin' );
    ?>
                                    </a>

                                </div>
                            </div>
                        </div>

						<?php 
}

?>

                        </p>
                        <p>
                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
                                   for="wpop_vistor_not">

								<?php 
?>

                                    <input type="checkbox"
                                           href="#wpop-upgrade-visitor-status"
                                           id="wpop_vistor_not"
                                           class="filled-in modal-trigger"/>

								<?php 
?>

                                <span class="mdl-checkbox__label">
                  <?php 
esc_html_e( "Visitor is not logged in", 'wpoptin' );
?>
                </span>
                            </label>
                        </p>
        </div>
        <div class="xo_col_half">
            <div class="xo_new_head">
                <h1>
					<?php 
esc_html_e( "Device Status", 'wpoptin' );
?>
                </h1>
            </div>
            <p>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
                       for="wpop_device_method">

					<?php 
?>

                        <input type="checkbox"
                               href="#wpop-upgrade-device-status"
                               id="wpop_device_method"
                               class="filled-in modal-trigger"/>

					<?php 
?>


                    <span class="mdl-checkbox__label">
                  <?php 
esc_html_e( "Only on mobile devices", 'wpoptin' );
?>
                </span>
                </label>

				<?php 

if ( wpop_fs()->is_free_plan() ) {
    ?>

                <div id="wpop-upgrade-device-status"
                     class="modal wpop-upgrade-modal">
                    <div class="modal-content">
                        <div class="wpop-modal-content">
                            <span class="wpop-lock-icon"><i
                                        class="material-icons">lock_outline</i> </span>
                            <h5><?php 
    esc_html_e( "Premium Feature", 'wpoptin' );
    ?></h5>
            <p><?php 
    esc_html_e( "We're sorry, Device Status conditions are not included in your plan. Please upgrade to the premium plan to unlock this and all other cool features. Keep increase conversion by 10x while testing new ideas.", 'wpoptin' );
    ?></p>
            <hr>
            <p><?php 
    esc_html_e( "Use following coupon to get 27% discount for limited time", 'wpoptin' );
    ?>
                <br><code>BFCMOP</code></p>
            <a href="<?php 
    echo  wpop_fs()->get_upgrade_url() ;
    ?>"
               class="waves-effect waves-light btn z-depth-3"><i
                        class="material-icons right">lock_open</i><?php 
    esc_html_e( "Upgrade to pro", 'wpoptin' );
    ?>
            </a>

        </div>
    </div>
</div>

<?php 
}

?>

</p>
<p>

    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
           for="wpop_device_not">

		<?php 
?>

            <input type="checkbox" href="#wpop-upgrade-device-status"
                   id="wpop_device_not" class="filled-in modal-trigger"/>

		<?php 
?>


        <span class="mdl-checkbox__label">
                  <?php 
esc_html_e( "Not on mobile devices", 'wpoptin' );
?>
                </span>
    </label>
</p>
</div>
</div>
</li>
</ul>
<input type="hidden" value="<?php 
echo  $wpoptin_id ;
?>" name="xo_new_id"
       id="xo_new_id">
<input name="xo_new_sub" type="submit" data-snc="false"
       class="xo_new_tnc xo_submit"
       value="<?php 
esc_html_e( "Save", 'wpoptin' );
?>">
<input name="xo_new_sub" type="submit" data-snc="true"
       class="xo_new_tnc xo_submit right"
       value="<?php 
esc_html_e( "Save & Continue", 'wpoptin' );
?>">
</form>
</div>
<div class="col s3">
    <div class="xo_optin_accounts_right">
        <div class="xo_lost_holder">
            <div class="card">
                <div class="wpop-card-icon">
                    <i class="material-icons dp48">sentiment_very_dissatisfied
                    </i>
                </div>
                <h4>
					<?php 
esc_html_e( "Feeling Lost?", 'wpoptin' );
?>
                </h4>
                <div class="card-content">
                    <a target="_blank"
                       href="<?php 
echo  $this->wpop_doc_url( $goal ) ;
?>"
                       class="btn waves-effect waves-light">
						<?php 
esc_html_e( "Check this out", 'wpoptin' );
?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div> 