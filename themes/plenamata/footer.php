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
    </div>
</footer>
<?php wp_footer() ?>

</body>
</html>