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
    $ranking = 1;
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
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['soup_number'] = strip_tags($new_instance['soup_number']);
    $instance['post_type'] = $new_instance['post_type'];
    $instance['show_newsletter'] = $new_instance['show_newsletter'];
    $instance['newsletter_url'] = strip_tags($new_instance['newsletter_url'],'<a>');
    $instance['author_credit'] = $new_instance['author_credit'];
    return $instance;
  }

  // Widget Control Panel //
  function form($instance) {
    $defaults = array( 'title' => 'Upcoming Posts', 'soup_number' => 3, 'post_type' => 'future', 'show_newsletter' => false, newsletter_url => '', author_credit => 'on' );
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>
    <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
    <input class="widefat" id="<?php echo $this-/>get_field_id('title'); ?>" type="text" name="<?php echo $this->get_field_name('title'); ?>'" value="<?php echo $instance['title']; ?>" />
    <label for="<?php echo $this->get_field_id('soup_number'); ?>"><?php _e('Number of upcoming posts to display'); ?></label>
    <input class="widefat" id="<?php echo $this-/>get_field_id('soup_number'); ?>" type="text" name="<?php echo $this->get_field_name('soup_number'); ?>" value="<?php echo $instance['soup_number']; ?>" />
    <label for="<?php echo $this->get_field_id('post_type'); ?>">Post status:</label>
    <h3><select class="widefat" id="<?php echo $this->get_field_id('post_type'); ?>" style="width: 100%;" name="<?php echo $this->get_field_name('post_type'); ?>"><option selected="selected" value="future,draft">>Both scheduled posts and drafts</option><option selected="selected" value="future">>Scheduled posts only</option><option selected="selected" value="draft">>Drafts only</option></select></h3>
    <label for="<?php echo $this->get_field_id('show_newsletter'); ?>"><?php _e('Show Newsletter?'); ?></label>
    <input class="checkbox" type="checkbox" checked="checked" /> id="<?php echo $this->get_field_id('show_newsletter'); ?>" name="<?php echo $this->get_field_name('show_newsletter'); ?>" />
    <label for="<?php echo $this->get_field_id('newsletter_url'); ?>"><?php _e('Newsletter URL:'); ?></label>
    <input class="widefat" id="<?php echo $this-/>get_field_id('newsletter_url'); ?>" type="text" name="<?php echo $this->get_field_name('newsletter_url'); ?>" value="<?php echo $instance['newsletter_url']; ?>" />
    <label for="<?php echo $this->get_field_id('author_credit'); ?>"><?php _e('Give credit to plugin author?'); ?></label>
    <input class="checkbox" type="checkbox" checked="checked" /> id="<?php echo $this->get_field_id('author_credit'); ?>" name="<?php echo $this->get_field_name('author_credit'); ?>" />
    <?php
  }


add_action('widgets_init', create_function('', 'return register_widget("trending");'));

?>
