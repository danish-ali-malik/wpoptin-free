<?php

/*
* Include wp-load.php tp access wp functions
*/
$incPath = str_replace( ABSPATH, "", getcwd() );
include $incPath . '/wp-load.php';
global  $wpoptins ;
global  $WPOptins ;
if ( isset( $wpoptins ) ) {
    foreach ( $wpoptins as $wpoptin ) {
        $wpoptin_id = $wpoptin['ID'];
        
        if ( !isset( $wpoptins[$wpoptin_id]['customizer']['design']['sticky-bar'] ) ) {
            $stick_bar = true;
        } else {
            $stick_bar = $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'sticky-bar'
            );
        }
        
        ?>
.xo_bar_wrap.xo_<?php 
        echo  $wpoptin_id ;
        ?> {

<?php 
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'background-color'
        ) ) {
            ?> background-color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'background-color'
            ) ;
            ?> !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'color'
        ) ) {
            ?> color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'color'
            ) ;
            ?> !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'border-color'
        ) ) {
            ?> border-color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'border-color'
            ) ;
            ?> !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'border',
            'border-wrap-top'
        ) ) {
            ?> border-top-width: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'border',
                'border-wrap-top'
            ) ;
            ?>px !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'border',
            'border-wrap-bottom'
        ) ) {
            ?> border-bottom-width: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'border',
                'border-wrap-bottom'
            ) ;
            ?>px !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'border',
            'border-wrap-left'
        ) ) {
            ?> border-left-width: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'border',
                'border-wrap-left'
            ) ;
            ?>px !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'border',
            'border-wrap-right'
        ) ) {
            ?> border-right-width: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'border',
                'border-wrap-right'
            ) ;
            ?>px !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'border',
            'wrapborder-style'
        ) ) {
            ?> border-style: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'border',
                'wrapborder-style'
            ) ;
            ?> !important;
<?php 
        }
        
        if ( $WPOptins->wpop_get_settings( $wpoptin_id, 'type' ) == 'bar' && !$stick_bar ) {
            ?> position:absolute!important; <?php 
        }
        ?>
}


<?php 
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'link-color'
        ) ) {
            ?>
.xo_bar_wrap.xo_<?php 
            echo  $wpoptin_id ;
            ?> .xo_offer_c_wrap .xo_offer_c a, .xo_<?php 
            echo  $wpoptin_id ;
            ?> .xo_offer_c a {
    color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'link-color'
            ) ;
            ?> !important;
}

<?php 
        }
        
        ?>


.xo_bar_wrap.xo_<?php 
        echo  $wpoptin_id ;
        ?> .xo_front_c .xo_timer_wrap {
    background-color: <?php 
        echo  $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'counterbg-color'
        ) ;
        ?>;
    color: <?php 
        echo  $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'counter-color'
        ) ;
        ?> !important;

}



<?php 
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'counter-color'
        ) ) {
            ?>
.xo_bar_wrap.xo_<?php 
            echo  $wpoptin_id ;
            ?> .xo_front_c .xo_timer_wrap p {
    color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'counter-color'
            ) ;
            ?> !important;

}

<?php 
        }
        
        ?>

.xo_bar_wrap.xo_<?php 
        echo  $wpoptin_id ;
        ?> .xo_front_c .xo_offer_c_wrap .xo_cupon_wrap {

<?php 
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'cuponbg-color'
        ) ) {
            ?> background-color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'cuponbg-color'
            ) ;
            ?> !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'cupon-color'
        ) ) {
            ?> color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'cupon-color'
            ) ;
            ?> !important;

<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'cuponborder-style'
        ) ) {
            ?> border-style: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'border',
                'cuponborder-style'
            ) ;
            ?> !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'cuponborder-color'
        ) ) {
            ?> border-color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'cuponborder-color'
            ) ;
            ?> !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'border',
            'cuponborder-size'
        ) ) {
            ?> border-width: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'border',
                'cuponborder-size'
            ) ;
            ?>px !important;
<?php 
        }
        
        ?>
}

.xo_bar_wrap.xo_<?php 
        echo  $wpoptin_id ;
        ?> .xo_newsletter_form .xo_newsletter_sub, .xo_bar_wrap.xo_<?php 
        echo  $wpoptin_id ;
        ?> .xo_front_c .xo_offer_btn .xo_offer_cta {

<?php 
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'btnbgcolor'
        ) ) {
            ?> background-color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'btnbgcolor'
            ) ;
            ?>;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'btncolor'
        ) ) {
            ?> color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'btncolor'
            ) ;
            ?>;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'btnbordercolor'
        ) ) {
            ?> border-color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'btnbordercolor'
            ) ;
            ?>;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'border',
            'btn-size'
        ) ) {
            ?> border-width: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'border',
                'btn-size'
            ) ;
            ?>px;
<?php 
        }
        
        ?>
}

.xo_bar_wrap.xo_<?php 
        echo  $wpoptin_id ;
        ?> .xo_newsletter_form .xo_newsletter_sub:hover, .xo_bar_wrap.xo_<?php 
        echo  $wpoptin_id ;
        ?> .xo_front_c .xo_offer_btn .xo_offer_cta:hover {
<?php 
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'btnbgcolor-hover'
        ) ) {
            ?> background-color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'btnbgcolor-hover'
            ) ;
            ?>;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'btncolor-hover'
        ) ) {
            ?> color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'btncolor-hover'
            ) ;
            ?>;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'btnbordercolor-hover'
        ) ) {
            ?> border-color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'btnbordercolor-hover'
            ) ;
            ?>;
<?php 
        }
        
        ?>
}

.xo_bar_wrap.xo_<?php 
        echo  $wpoptin_id ;
        ?> .xo_newsletter_Wrap .xo_newsletter_form .xo_newsletter_email {
<?php 
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'emailfield-bgcolor'
        ) ) {
            ?> background-color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'emailfield-bgcolor'
            ) ;
            ?> !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'emailfield-color'
        ) ) {
            ?> color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'emailfield-color'
            ) ;
            ?> !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'design',
            'emailfield-bordercolor'
        ) ) {
            ?> border-color: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'design',
                'emailfield-bordercolor'
            ) ;
            ?> !important;
<?php 
        }
        
        
        if ( $WPOptins->wpop_get_settings(
            $wpoptin_id,
            'customizer',
            'border',
            'emailfield-size'
        ) ) {
            ?> border-width: <?php 
            echo  $WPOptins->wpop_get_settings(
                $wpoptin_id,
                'customizer',
                'border',
                'emailfield-size'
            ) ;
            ?>px !important;
<?php 
        }
        
        ?>
}

<?php 
    }
}