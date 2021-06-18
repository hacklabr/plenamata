</div>
<?php wp_reset_postdata() ?>

<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-10 logos-area">
                    <div class="before-logos">
                        <?php dynamic_sidebar('before_footer_logos_area') ?>
                    </div>
                    <div class="logos">
                        <?php dynamic_sidebar('footer_logos_area') ?>
                    </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 addicional-info">
                <?php 
                    $copyright_option = get_theme_mod( 'footer_copyright_text', '' );
                    $show_name_and_year = checked( 1, get_theme_mod('footer_show_year_and_name'), false );
                    if(!empty($copyright_option)): ?>
                    <div class="copyright-info">
                        <?= $copyright_option ?>
                        <?php 
                        if($show_name_and_year): 
                            echo date("Y") . " "; 
                            echo bloginfo("name");
                        endif;
                        ?>
                    </div>
                <?php
                    endif;
                ?>

                <div class="social-networks">
                    <?php the_social_networks_menu() ?>
                </div>

                <div class="footer-menu">
                    <?= wp_nav_menu(['theme_location' => 'footer-menu', 'container' => 'nav', 'menu_id' => 'footer-menu', 'menu_class' => 'footer-menu', 'container_class' => 'footer-menu']) ?>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer() ?>

</body>
</html>