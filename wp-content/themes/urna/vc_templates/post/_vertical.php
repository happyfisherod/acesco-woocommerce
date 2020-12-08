<?php 
    $thumbsize                 = isset($thumbsize) ? $thumbsize : 'medium';
?>
<div class="post">
    <?php 
    if ( has_post_thumbnail() ) {
        ?>
          <div class="entry-thumb">
              <a href="<?php the_permalink(); ?>" class="entry-image tbay-image-loaded">
                <?php
                  if(  isset($elementor_activate) && $elementor_activate ) {
                    $thumbnail_id = get_post_thumbnail_id(get_the_ID());

                    echo urna_tbay_get_attachment_image_loaded($thumbnail_id, $thumbsize);
                  } elseif  ( defined('URNA_VISUALCOMPOSER_ACTIVED') && URNA_VISUALCOMPOSER_ACTIVED ) {

                      $thumbnail_id = get_post_thumbnail_id(get_the_ID());

                      if (in_array( $thumbsize, get_intermediate_image_sizes() ))  {
                          echo urna_tbay_get_attachment_image_loaded($thumbnail_id, $thumbsize);
                      } else {
                          $custom_src = vc_get_image_by_size($thumbnail_id, $thumbsize); 

                          if( is_array($custom_src) ) {
                              $custom_src = $custom_src['0'];
                          }
                          urna_tbay_src_image_loaded($custom_src,array('alt' => get_the_title()));
                      }

                  } else {
                      the_post_thumbnail();
                  }
                ?>
              </a>  
          </div>
        <?php
    }
    ?>
    <div class="entry-content">
        <?php
        if (get_the_title()) { ?>
              <h4 class="entry-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h4>
        <?php } ?>
          <ul class="entry-meta-list">
            <li class="entry-date"><i class="linear-icon-calendar-31"></i><?php echo urna_time_link(); ?></li>
            <li class="comments-link"><i class="linear-icon-bubbles"></i> <?php comments_popup_link( '0', '1', '%' ); ?></li>
          </ul>
    </div>
</div>