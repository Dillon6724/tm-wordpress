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

  function widget($args, $instance) {
    extract( $args );
    $article_max = $instance;
    $response = $this->get_parsley_data($article_max);
    $ranking = 1;
    echo var_dump($instance);
    echo $before_widget;
    ?><div class="treding-widget-title">Trending Articles</div><div class="trending-widget-container"><?php
    foreach ($response->data as $article) { ?>
      <a class="trending-article-container" href=<?php echo $article->link; ?>>
        <div class="ranking"><?php echo $ranking; ?></div>
        <img src=<?php echo $article->image_url; ?>>
        <div class="title"><?php echo $article->title; ?></div>
        <div class="author"><?php echo $article->author; ?></div>
      </a>
    <?php $ranking++; }
    ?></div><?php
    echo $after_widget;
  }

  function get_parsley_data($article_max) {
    $key = getenv('TM_PARSELY_KEY');
    $secret = getenv('TM_PARSELY_SECRET');
    // curl things
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.parsely.com/v2/analytics/posts?apikey=".$key."&secret=".$secret."&page=1&limit=".$article_max."&sort=views&period_start=1w");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output);
  }

  function update($new_instance) {
    $instance = array();
    $instance['article-max'] = strip_tags($new_instance['article-max']);
    return $instance;
  }

  function form($instance) {
    $defaults = array( 'article-max' => 10);
    $instance = wp_parse_args( (array) $instance, $defaults );?>
    <label for="<?php echo $this->get_field_id('title'); ?>">How many articles:</label>
    <input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name('article-max'); ?>" value="<?php echo $instance['article-max']; ?>"><?php
  }
}


add_action('widgets_init', create_function('', 'return register_widget("trending");'));

?>
