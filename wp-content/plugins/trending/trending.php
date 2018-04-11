<style>
  <?php include 'styles.css'; ?>
</style>
<?php
/*
Plugin Name: Trending Articles
*/

class trending extends WP_Widget {

  function trending() {
    $widget_ops = array( 'classname' => 'trending', 'description' => 'Displays your trending articles' ); // Widget Settings
    $control_ops = array( 'id_base' => 'trending' ); // Widget Control Settings
    $this->WP_Widget( 'trending', 'Trending Articles', $widget_ops, $control_ops ); // Create the widget
  }

  function widget($args, $instance) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : ' no title';
    $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
    $response = $this->get_parsley_data();
    $ranking = 1;
    echo $before_widget;?>

    <div class="treding-widget-title"><?php echo $title;?></div><div class="trending-widget-container"><?php
    foreach ($response->data as $article) { ?>
      <a class="trending-article-container" href=<?php echo $article->link; ?>>
        <div class="ranking"><?php echo $ranking; ?></div>
        <img src=<?php echo $article->image_url; ?>>
        <div class="title"><?php echo $article->title; ?></div>
        <div class="author"><?php echo $article->author; ?></div>
      </a><?php
     $ranking++;
    }
    ?></div><?php
    // After widget //
    echo $after_widget;
  }

  function get_parsley_data() {
    $key = getenv('TM_PARSELY_KEY');
    $secret = getenv('TM_PARSELY_SECRET');
    // curl things
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.parsely.com/v2/analytics/posts?apikey=".$key."&secret=".$secret."&page=1&limit=10&sort=views&period_start=1w");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output);
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
    return $instance;
  }

  function form($instance) {
    $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';?>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
  <?php
  }
}


add_action('widgets_init', create_function('', 'return register_widget("trending");'));

?>
