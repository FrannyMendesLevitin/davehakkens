<?php
class Latest_Community_Uploads extends WP_Widget {

  public function __construct() {
    $widget_ops = array(
      'classname' => 'latest_community_uploads',
      'description' => 'Shows latest community image uploads',
    );
    parent::__construct( 'latest_community_uploads', 'Latest Community_uploads', $widget_ops );
  }

  /**
   * Outputs the content of the widget
   *
   * @param array $args
   * @param array $instance
   */
  public function widget( $args, $instance ) {
    echo !empty($args['before_widget']) ? $args['before_widget']: "<div class='latest-uploads'>";
    $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( '📷 Latest Images' );
    $max = ( ! empty( $instance['max'] ) ) ? $instance['max'] : 10;
    $user_id = ( ! empty( $instance['user_id'] ) ) ? $instance['user_id'] : '';
    $size = ( ! empty( $instance['size'] ) ) ? $instance['size'] : 'thumbnail';
    if ( $title) {
      echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
    }
    $args = array(
      'post_type'   => array('topic','reply'),
      'author'	    => $user_id,
      'numberposts' => 10*$max,
      'post_status' => null,
      'post_parent' => 'any', // any parent
      'fields'      => 'id=>parent',
    );
    $topics = get_posts($args);
    if ($topics) {
      $cachedReplies = array();
      $args = array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'author' => $user_id,
        'numberposts' => $max,
        'post_status' => null,
        'post_parent__in' => array_keys($topics),
      );
      $attachments = get_posts($args);
      if ($attachments) {
        foreach ($attachments as $post) {
          setup_postdata($post);
          $link = get_permalink($post->post_parent);
          $img  = wp_get_attachment_image($post->ID, $size);
          if(strpos($link, 'reply')){
            //calculate pagination
            if(array_key_exists ( $post->post_parent , $cachedReplies )){
              $siblings = $cachedReplies[$post->post_parent];
            }else{
              $args = array(
                'post_type' => 'reply',
                'numberposts' => -1,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'ASC',
                'post_parent' => $topics[$post->post_parent],
                'fields'      => 'ids',
              );
              $siblings = get_posts($args);
              $cachedReplies[$post->post_parent] = $siblings;
            }
            $indx = array_search($post->post_parent, $siblings);
            $page = floor($indx/get_option('_bbp_replies_per_page'));
            $link = get_permalink($topics[$post->post_parent]). ($page>0?"page/".($page+1)."/":"") . "#post-".$post->post_parent;
          }
          echo "<a href='". $link ."'>". $img ."</a>";
        }
      }
    }
    echo !empty($args['after_widget']) ? $args['after_widget']: "</div>";
  }

  /**
   * Outputs the options form on admin
   *
   * @param array $instance The widget options
   */
  public function form( $instance ) {
    // outputs the options form on admin
  }

  /**
   * Processing widget options on save
   *
   * @param array $new_instance The new options
   * @param array $old_instance The previous options
   *
   * @return array
   */
  public function update( $new_instance, $old_instance ) {
    // processes widget options to be saved
  }
}

//Buddypress profile nav item
function bp_user_nav_latest_uploads() {
  global $bp;
  $args = array(
    'name' => __('Latest Uploads', 'buddypress'),
    'slug' => 'latestU',
    'default_subnav_slug' => 'latestU',
    'position' => 80,
    'show_for_displayed_user' => true,
    'screen_function' => 'bp_user_nav_latest_uploads_screen',
    'item_css_id' => 'portfolio'
  );
  bp_core_new_nav_item( $args );
}
add_action( 'bp_setup_nav', 'bp_user_nav_latest_uploads', 99 );

function bp_user_nav_latest_uploads_screen() {
  add_action( 'bp_template_content', function() {
    the_widget("Latest_Community_Uploads", array("max"=>"50", "user_id"=>bp_displayed_user_id()));
  });
  bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}
?>
