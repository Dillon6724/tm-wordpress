<style>
  <?php include 'styles.css'; ?>
</style>
<?php
/*
Plugin Name: Trending Articles
*/

class trending extends WP_Widget {

  function trending() {
    $widget_ops = array( 'classname' => 'trending', 'description' => 'Displays your upcoming posts to tease your readers' ); // Widget Settings
    $control_ops = array( 'id_base' => 'trending' ); // Widget Control Settings
    $this->WP_Widget( 'trending', 'Trending Articles', $widget_ops, $control_ops ); // Create the widget
  }

  function widget() {
    $response = $this->get_parsley_data();
    for ($i=0; $i < 10; $i++) { ?>
      <div class="trending-container">
        <h1><?php echo $i; ?></h1>
        <img src=<?php echo $response['data'][$i]['image_url']; ?>>
        <h3><?php echo $response['data'][$i]['title']; ?></h3>
        <h6><?php echo $response['data'][$i]['author']; ?></h6>
      </div>
    <?php }
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
}

add_action('widgets_init', create_function('', 'return register_widget("trending");'));

?>
