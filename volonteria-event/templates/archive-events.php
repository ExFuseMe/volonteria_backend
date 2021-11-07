<?php
get_header();
?>
<div class="wrapper">
    <?php
        if ( have_posts() ) {

            // Load posts loop.
            while ( have_posts() ) {the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h2>
                    <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                </h2>
                </article>
    
            <?php }

                
            } else {
                echo "No post";
            }
    ?>
</div>