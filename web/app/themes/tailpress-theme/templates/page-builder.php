<?php 
/*
Template Name: Page Builder
*/

get_header();
?>
<section class="hero bg-primary text-white flex items-center justify-center h-screen">
    <?php if ( get_field('hero_image') ) : ?>
        <img src="<?php echo get_field('hero_image'); ?>" alt="" class="bgcover">
    <?php endif; ?>
    <div class="text-center max-w-screen-md hero-description">
        <?php if ( get_field('hero_title') ) : ?>
            <h1 class="text-5xl font-bold mb-4"><?php echo get_field('hero_title'); ?></h1>
        <?php else: ?>
            <h1 class="text-5xl font-bold mb-4">No title yet</h1>
        <?php endif; ?>
        <?php if ( get_field('hero_description') ) : ?>
            <p class="text-xl mb-6"><?php echo get_field('hero_description'); ?></p>
        <?php else: ?>
            <p class="text-xl mb-6">No paragraph set</p>
        <?php endif; ?>
    </div>
</section>
<?php 
get_footer();
?>