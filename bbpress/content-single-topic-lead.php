<?php

/**
 * Single Topic Lead Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div class="topic-lead">
  <div class="date"><?php bbp_topic_post_date(); ?></div>
  <h1><?php the_title(); ?></h1>
  <div class="author">

      <?php bbp_topic_author_link( array( 'sep' => '', 'show_role' => false ) ); ?>
      <?php do_action( 'bbp_theme_after_topic_author_details' ); ?>

      <?php
        $user = get_userdata( bbp_get_reply_author_id() );
        if ( !empty( $user->user_nicename ) ) {
          $user_nicename = $user->user_nicename;
          echo "".$user_nicename;
        }
      ?>


    </div>




  <div class="content">
    <?php do_action( 'bbp_template_before_lead_topic' ); ?>
    <?php do_action( 'bbp_theme_before_topic_content' ); ?>
    <?php bbp_topic_content(); ?>
    <?php do_action( 'bbp_theme_after_topic_content' ); ?>
    <?php do_action( 'bbp_template_after_lead_topic' ); ?>
  </div>
   <div class="actions">
    <?php do_action( 'bbp_theme_before_topic_admin_links' ); ?>
    <?php bbp_topic_admin_links(); ?>
    <?php do_action( 'bbp_theme_after_topic_admin_links' ); ?>
  </div>

</div>
