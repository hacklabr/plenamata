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
                    <!--a href="#">
                        <#?php //_e( 'Natura & Co - Compromisso com a Vida 2030', 'plenamata' );?>
                    </a-->
                </div>
                <div class="each-item">
                    <a href="<?= get_privacy_policy_url() ?>">
                        <?php _e( 'Privacy Statement', 'plenamata' );?>
                    </a>
                </div>
            </div>
        </section>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

<?php if ( is_post_type_archive( 'verbete' ) ) : ?>

<script>
        $(function(){
            $("#filtro-glossario").keyup(function(){
                let texto = $(this).val();

                let itensHidden = [];
                let totalItens = [];
                let ids = $('main').find('h2');//pegando todas as tags h2 filho de main

                $("summary").each(function(){
                    resultado = $(this).text().toUpperCase().indexOf(' '+texto.toUpperCase());

                    if(resultado < 0) {
                        $(this).fadeOut().parent().addClass("hide-verbete");
                    }else {
                        $(this).fadeIn().parent().removeClass("hide-verbete");
                    }

                    for (let i = 0; i< ids.length; i++) {
                        totalItens[i] = $('#'+ids[i].id).siblings('.glossary__entries').children().length;
                        itensHidden[i] = $('#'+ids[i].id).siblings('.glossary__entries').find('.hide-verbete').length;

                        if(itensHidden[i] == totalItens[i]){
                            $('#'+ids[i].id).fadeOut();
                        }
                        else{
                            $('#'+ids[i].id).fadeIn();
                        }
                    }

                });
            });
        });
</script>

<?php endif; ?>

</body>
</html>
