<?php
/*
Template Name: Portfolio - Filter
*/
?>

<?php get_header(); ?>
<div class="container">
    <div class="row">
        <div class="span12">
                <header class="entry-header">
                    <h1 class="entry-title title-page"><?php the_title(); ?></h1>
                    <h2 class="sub-title-page"><?php echo get_post_meta(get_the_ID(), 'tdl_page_caption', TRUE); ?></h2>
                                
                    <?php 
                    // BREADCRUMBS
                     echo tdl_breadcrumbs();
                    ?>
                    <div class="clearfix"></div>       
                </header><!-- .entry-header -->
        </div>
    </div>
</div>

		<?php if(in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?> 
            <!-- woocommerce message -->
        <div class="container">
            <div class="row">
                <div class="span12">    
            <?php  woocommerce_show_messages(); ?>
                </div>
            </div>
        </div> 
        <?php } ?>

<div class="container">
    <div class="row">
        <div id="primary" class="span12 fullwidth">
        
		<?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                    <?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it. ?>
                        <div class="entry-thumbnail">
                            <?php the_post_thumbnail(); ?>
                        </div>
                    <?php } ?>
                    
                    <div class="entry-content">
                        <div class="content_wrapper four_side shopproductlist">
                            <?php the_content(); ?>
                            <div class="clearfix"></div>
                        </div>
                    </div><!-- .entry-content -->
                    
                </article><!-- #post-<?php the_ID(); ?> -->
                
                <div class="clearfix"></div>

        <?php endwhile; // end of the loop. ?>        
        
        
<?php $terms2 = get_terms( "port-group"); if ( !empty($terms2) ) { ?>            
<ul id="portfolio-filter" class="clearfix">
<li class="activeFilter" data-filter="*"><a href="#"><?php _e('Show All', 'tdl_framework'); ?></a></li>

<?php                            
$portcats = wp_get_object_terms( get_the_ID(), 'port-group' );
$portcatlist = array();
$portcatids = array();
                                
if( count( $portcats ) > 0 ) {
 foreach ( $portcats as $portcat) {
           $portcatids[] = $portcat->term_id;
           $portcatlist[] = $portcat->slug;
           }
                                    
           $termargs = array();
           $termargs['include'] = $portcatids;
                                
           } else { $termargs = ''; }
                            
           $terms = get_terms( "port-group", $termargs );
           $count = count( $terms );
           if ( $count > 0 ){
                foreach ( $terms as $term ) {
                echo '<li data-filter=".pf-' . $term->slug . '"><a href="#">' . $term->name . '</a></li>';
                }
           }
?>
</ul>
<?php } ?>

<?php
                        
$pageid = get_the_ID();
$layout = get_post_meta( $pageid, 'tdl_page_portfolio', true ) ? get_post_meta( $pageid, 'tdl_page_portfolio', true ) : 'normal';

$args = array( 'post_type' => 'portfolio', 'orderby' => 'menu_order', 'order' => 'ASC', 'posts_per_page' => -1 );
                        
if( count( $portcats ) > 0 ) {
    $args['tax_query'] = array( array( 'taxonomy' => 'port-group', 'field' => 'slug', 'terms' => $portcatlist ) );
}
                        
$portfolio = new WP_Query( $args );
if( $portfolio->have_posts() ):
?>

<div id="portfolio" class="row">
<?php
while ( $portfolio->have_posts() ) : $portfolio->the_post();
 $getterms = get_the_terms(get_the_ID(), 'port-group');
                        
     if ($getterms) {
     $portterms = array();
                            
     foreach ($getterms as $getterm) {
            $portterms[] = 'pf-' . $getterm->slug;
     }
                            
     $portterms = implode(" ", $portterms);
     }
                        
     $height = get_post_meta( $pageid, 'tdl_page_height', true ) ? get_post_meta( $pageid, 'tdl_page_height', true ) : '400';
                        
     $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
     $thumb = tdl_resize( $thumb[0], 600, $height, true, false );
                        
     ?>

<div id="portfolio-<?php the_ID(); ?>" class="portfolio-item <?php echo $portterms; ?> <?php echo $layout; ?>">
<?php if ( has_post_thumbnail() ):?>
    <figure>
    	<a class="link-to-post" href="<?php the_permalink(); ?>">
        <img src="<?php echo $thumb[0]; ?>" width="<?php echo $thumb[1]; ?>" height="<?php echo $thumb[2]; ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>" />
        </a>
    </figure>
 <?php endif; ?>   
    <div class="portfolio-item-details">
    	<span class="portfolio-item-category">
		<?php echo get_the_term_list( $post->ID, 'port-group', '', ', ', '' ); ?>
        </span>
    	<h4 class="portfolio-item-title"><a class="link-to-post" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    </div>
</div>

 <?php endwhile; ?>       
                        
</div>


                        <script type="text/javascript">
						var $ = jQuery.noConflict();
						
                        $(window).load(function() { 
							   
                            // cache container
                            var $container = $('#portfolio');
                            // initialize isotope
                            $container.isotope({
                                itemSelector : '.portfolio-item',
                                animationEngine : 'best-available',							
								layoutMode : 'fitRows',

								animationOptions: {
                        	     	easing: 'easeInOutQuad',
                        	     	queue: false
                        	   	}
                            });

                            
                            // filter items when filter link is clicked
                            $('#portfolio-filter li').click(function(){
                                
                                $('#portfolio-filter li').removeClass('activeFilter');
                                $(this).addClass('activeFilter');                                
                                var selector = $(this).attr('data-filter');
                                $container.isotope({ filter: selector });
                                return false;
                                
                            });
                        });                        
                        </script>
                        
                        <?php endif; wp_reset_postdata(); ?>



<?php edit_post_link( __( 'Edit', 'tdl_framework' ), '<span class="edit-link">', '</span>' ); ?>        
        
		</div>
	</div>
</div>

<?php get_footer(); ?>