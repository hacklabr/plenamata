<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Newspack
 */

?>

	<?php do_action( 'before_footer' ); ?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">

		<?php //get_template_part( 'template-parts/footer/footer', 'branding' ); ?>
		<?php get_template_part( 'template-parts/footer/footer', 'widgets' ); ?>
        <section id="copyright">
            <div class="wrapper">
                <div class="each-item">
                    <?php printf( __( 'COPYRIGHT © %s PlenaMata', 'plenamata' ), date( 'Y' ) );?>
                </div>
                <div class="each-item">
                    <a href="#">
                        <?php _e( 'Política de Privacidade', 'plenamata' );?>
                    </a>
                </div>
                <div class="each-item">
                    <a href="#">
                        <?php _e( 'Natura & Co - Compromisso com a Vida 2030', 'plenamata' );?>
                    </a>
                </div>
            </div>
        </section>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
