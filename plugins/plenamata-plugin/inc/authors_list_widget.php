<?php
/**
 * Class Authors_list_widget
 */

class authors_list_widget extends WP_Widget {
 
    /**
     * Constructs the new widget.
     *
     * @see WP_Widget::__construct()
     */
    function __construct() {
        // Instantiate the parent object.
        parent::__construct( false, __( 'Authors List', 'plenamata' ) );
    }
 
    /**
     * The widget's HTML output.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Display arguments including before_title, after_title,
     *                        before_widget, and after_widget.
     * @param array $instance The settings for the particular instance of the widget.
     */
    function widget( $args, $instance ) {
        $users = get_users(); ?>
       	<div class="category-most-read authors-list-sidebar">
            <div class="header">
                <p><?= $instance['title'] ?> </p>
            </div>
            <div class="posts">
                <?php foreach ($users as $user): ?>
                    <?php if($user->roles[0] == "colunista"): ?>
                    <div class="post">   
                        <a href="<?= esc_url( get_author_posts_url( $user->ID ) ); ?>">
                            <?= get_avatar($user->ID); ?>
                            <?=  $user->display_name;?>
                        </a>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        </div>
    <?php
    }
 
    /**
     * The widget update handler.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance The new instance of the widget.
     * @param array $old_instance The old instance of the widget.
     * @return array The updated instance of the widget.
     */
    function update( $new_instance, $old_instance ) {
        return $new_instance;
    }
 
    /**
     * Output the admin widget options form HTML.
     *
     * @param array $instance The current widget settings.
     * @return string The HTML markup for the form.
     */
    function form( $instance ) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('', 'plenamata');
		$min_posts = !empty($instance['min_posts']) ? $instance['min_posts'] : 1;
		$max_posts = !empty($instance['max_posts']) ? $instance['max_posts'] : 3;
		$featured_image = !empty($instance['featured_image']) ? $instance['featured_image'] : esc_html__('show', 'plenamata');

	?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'plenamata'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
	<?php
    }
}
 
add_action( 'widgets_init', 'plenamata_register_widgets' );
 
/**
 * Register the new widget.
 *
 * @see 'widgets_init'
 */
function plenamata_register_widgets() {
    register_widget( 'authors_list_widget' );
}