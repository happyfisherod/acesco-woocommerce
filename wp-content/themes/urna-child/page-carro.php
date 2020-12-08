<?php


/*

template name: Carro

*/
$cook = $_COOKIE['cartCookie'];


get_header();

if($cook){

    
    


$sidebar_configs = urna_tbay_get_page_layout_configs();
urna_tbay_render_breadcrumbs();

if ( isset($sidebar_configs['left']) && !isset($sidebar_configs['right']) ) {
	$sidebar_configs['main']['class'] .= ' pull-right';
}


global $wpdb;
$table_name = $wpdb->prefix.'fake_cart_products';

if(isset($_GET['delete'])){
    $wpdb->delete( $table_name, array( 'id' => $_GET['delete'] ) );
}



$prepare = $wpdb->prepare( "SELECT * FROM $table_name WHERE cookie_id LIKE '$cook'" );
$datatable = $wpdb->get_results( $prepare );


$distribuidoresArray = array();
foreach($datatable as $dt){
    $distribuidoresArray[] = $dt->vendor;
    
}
$dis = array_unique($distribuidoresArray);

if (sizeof( WC()->cart->get_cart() ) > 0 ) {
    $cartDis = array(); 
    $productIDs = array();
    $variationIDs = array();
    foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
        $post_obj    = get_post( $cart_item['product_id'] ); // The WP_Post object
        $cartDis[] = $post_obj->post_author; // <=== The post author ID
        $productIDs[] = $cart_item['product_id'];
        $variationIDs[] = $cart_item['variation_id'];

        //var_dump($cart_item);

    }
    $cartDisUnique = array_unique($cartDis);
    $productIDsUnique = array_unique($productIDs);
    $variationIDsUnique = array_unique($variationIDs);
}


?>


<section id="main-container" class="container inner">
    <div id="main-content" class="main-page clearfix">
    <?php if (sizeof( WC()->cart->get_cart() ) > 0 ) { ?>

    <div class="woocommerce">
        <div class="woocommerce-notices-wrapper">
            <div class="woocommerce-error" role="alert">
            <?php
            echo '<a href="'.wc_get_cart_url().'?" tabindex="1" class="button wc-forward cartPage">Ver carrito de compras</a> Por favor Finalice su compra.';
            ?>
            </div>
        </div>
    </div>

    <!-- foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
    $post_obj    = get_post( $cart_item['product_id'] ); // The WP_Post object
    $post_author = $post_obj->post_author; // <=== The post author ID

    }-->


    <?php } ?>
        <div id="main" class="site-main">
           <h3>¿A dónde te lo llevamos?</h3>
            <p>Para poder mostrarte los productos del distribuidor mas cercano, requerimos que nos indiques la ubicación donde serán enviados los productos. </p>
            <p><b>IMPORTANTE:</b> Podrás comprar a un distribuidor a la vez.</p>
            
            
        <?php 
        if(isset($_GET['addto'])){ ?>

        <?php } ?>





            <div class="geobox"> 
                <div class="address-input"> 
                    <input id="pac-input" type="text" placeholder="Busca por dirección....">
					<button class="accordion"></button>
                <div class="map-box"> 
                    <div id="map"></div>
                </div>
                </div>
                <div id="infowindow-content">
                    <img src="" width="16" height="16" id="place-icon">
                    <span id="place-name"  class="title"></span><br>
                    <span id="place-address"></span>
                </div>
                <h3> Selecciona tu distribuidor</h3>
                <p>Te listamos los distribuidores y productos disponibles de tu zona.<br>
               
            </div>




                <div class="woocommerce sorry" style="display:none;">
                    <div class="woocommerce-notices-wrapper">
                        <div class="woocommerce-error" role="alert">
                            <span>Lo sentimos, no hay distribuidores cerca de la zona a la que tu requieres.</span>
                        </div>
                    </div>
                </div>
				
				<form class="woocommerce-cart-form form-comparador" style="display:none;" action="https://construyamos.com/colombia/" method="post">
                    <?php 


					?>

                    <div class="ajax-loader-wapper" style="display:none;"><div class="ajax-loader"></div></div>
                    <div class="comparador"> 
                        <div class="row">
                            <div class="col-md-12">

                                <?php
                                foreach($dis as $ud){
                                    $shippingVal = get_user_meta( $ud, '_dps_pt', true );
                                    $store_info   = dokan_get_store_info($ud);
                                    if($store_info['location'] !== ''){

                                    
                                ?>
                                    <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents dist-loc" data-location="<?php echo $store_info['location'] ?>" data-shipping="<?php if($shippingVal != ''){echo $shippingVal; }else{ echo '0'; } ?>" data-distance="">

                                        <div class="distribuidor">
                                            <div class="name"><?php echo $store_info['store_name'] ?></div>
                                            <div class="km"></div>
                                        </div>
                                        <div class="cart_item head">

                                            <span class="product-action">Acción</span><span class="product-info">Producto</span><span class="product-price">Precio</span><span class="product-quantity">Cantidad</span><span class="product-subtotal">Total</span>
                                            <span class="product-remove">&nbsp;</span>
                                        </div>

                                        <?php
                                        $data = $wpdb->get_results( "SELECT * FROM $table_name WHERE vendor = $ud AND cookie_id LIKE '$cook'" );
                                        foreach($data as $d){
                                            $pr = wc_get_product($d->product);
                                            if($pr){
                                            $product_obj    = new WC_Product($d->product);
                                            

                                            $var_obj = new WC_Product_Variable($d->product);

                                                
                            
                                            if($d->variation !=''){
                                            foreach($var_obj->get_children() as $gd){
                                                    if($gd == $d->variation){
    
                                                    $stock =  wc_get_product($gd)->get_stock_status();
                                                    ?>
    
                                                    <div class="cart_item <?php if(in_array($gd, $variationIDsUnique)){ echo 'outofstock added'; }?> <?php if($stock == 'outofstock'){ echo 'outofstock';} ?>">
                                                    <?php

                                                    if($cartDisUnique){
                                                        if($cartDisUnique[0] != $ud){
                                                        ?>
                                                            <span><input type="checkbox" disabled></span>
                                                        <?php
                                                        }else{
                                                        ?>
                                                            <span><input type="checkbox" data-product-id="<?php echo $d->product; ?>" data-product-var="<?php echo $gd ?>" data-price="" data-qty=""></span>
                                                        <?php

                                                        }

                                                    }else{
                                                    ?>
                                                        <span><input type="checkbox" data-product-id="<?php echo $d->product; ?>" data-product-var="<?php echo $gd ?>" data-price="" data-qty=""></span>
                                                    <?php

                                                    }
                                                    ?>

                                                        
                                                        <span class="product-info" data-title="Product">
                                                            
                                                                <img
                                                                    width="528"
                                                                    height="528"
                                                                    src="<?php echo get_the_post_thumbnail_url( $d->product ); ?>"
                                                                    class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image"
                                                                    alt=""
                                                                />
                                                            
                                                            <span class="product-name" data-title="Product">
                                                                <span class="name"><?php echo $product_obj->name; ?></span>
                                                                <ul class="variation">
                                                                    <?php 
                                                                        $varContent = wc_get_product($gd);
                                                                        foreach($varContent->get_attributes() as $key =>$vc){
                                                                            $tribute = str_replace('-', ' ', $key);
                                                                            $atribute = str_replace('pa_', '', $tribute);
                                                                            echo '<li>';
                                                                            echo '<dt class="variation-Acabado"><b>'. $atribute .':</b> </dt>';
                                                                            echo '<dd class="variation-Acabado"><p>'. $vc .'</p></dd>';
                                                                            echo '</li>';
                                                                        }
    
                                                                    ?>
                                                                </ul>
                                                            </span>
                                                        </span>
                                                        <span class="product-price" data-title="Price">
                                                                <?php 
    
                                                                if($stock == 'outofstock'){ 
                                                                                                                            
                                                                    echo 'Sin Stock';
    
                                                                }else{
                                                                    $varContentPrice = wc_get_product($gd)->get_price();

                                                                    if(wc_get_product($gd)->get_sale_price() != ''){
                                                                        
                                                                        echo '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><span class="regular-price"data-price="'. wc_get_product($gd)->get_sale_price() .'">'. wc_get_product($gd)->get_sale_price() .'</span><p style="color:#178c00; font-size:13px;">¡En oferta!</p></span>';
                                                                        echo '';
                                                                    }else{

                                                                        echo '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><span class="regular-price" data-price="'. $varContentPrice .'">'. $varContentPrice .'</span></span>';

                                                                    }

    
                                                                }
    
                                                                
                                                                ?>
                                                        </span>
                                                        <span class="product-quantity" data-title="Quantity">
                                                            <div class="quantity">
                                                                <label class="screen-reader-text" for="quantity_5f0331b02d9c9">Caballete cantidad</label>
                                                                <span class="box">
                                                                    <button class="minus" type="button" value="&nbsp;"><i class="linear-icon-minus"></i></button>
                                                                    <input type="number" class="input-text qty text" step="1" min="0" max="" name=""  value="<?php echo $d->qty; ?>" title="Cantidad" size="4" placeholder="" inputmode="numeric"/>
                                                                    <button class="plus" type="button" value="&nbsp;"><i class="linear-icon-plus"></i></button>
                                                                </span>
                                                            </div>
                                                        </span>
                                                        <span class="product-subtotal price" data-title="Total">

                                                            <?php if($variationIDsUnique){ ?>

                                                            <?php if($stock == 'outofstock'){ 
                                                                
                                                                echo 'Sin Stock';
                                                                
                                                            }elseif(in_array($gd, $variationIDsUnique)){
                                                                echo 'En tu carrito';
                                                            }else{
    
                                                                $totalPrice = $d->qty * $varContentPrice;
                                                                
                                                                echo '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><span class="price-text" data-price="'. $totalPrice .'">'. $totalPrice .'</span></span>';
    
    
                                                            } ?>



                                                            <?php }else{ ?>
    
                                                                <?php if($stock == 'outofstock'){ 
                                                                    
                                                                    echo 'Sin Stock';

                                                                }else{
        
                                                                    $totalPrice = $d->qty * $varContentPrice;
                                                                    
                                                                    echo '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><span class="price-text" data-price="'. $totalPrice .'">'. $totalPrice .'</span></span>';
        
        
                                                                } ?>
    
                                                            <?php }?>
                                                            
                                                        </span>
                                                        <span class="product-remove">
                                                            <a href="<?php echo get_site_url(); ?>/carro-2?delete=<?php echo $d->id; ?>">
                                                                <i class="linear-icon-trash2"></i>
                                                            </a>
                                                        </span>
                                                    </div>
    
                                                    <?php
                                                    }

                                                
                                            }
                                        }else{ 
                                            $stock =  wc_get_product($d->product)->get_stock_status();
                                            ?>

                                            <div class="cart_item <?php if(in_array($d->product, $productIDsUnique)){ echo 'outofstock added'; }?> <?php if($stock == 'outofstock'){ echo 'outofstock';} ?>">

                                                <?php
                                                    if($cartDisUnique){
                                                        if($cartDisUnique[0] != $ud){
                                                        ?>
                                                            <span><input type="checkbox" disabled></span>
                                                        <?php
                                                        }else{
                                                        ?>
                                                            <span><input type="checkbox" data-product-id="<?php echo $d->product; ?>" data-product-var="" data-price="" data-qty=""></span>
                                                        <?php

                                                        }

                                                    }else{
                                                    ?>
                                                        <span><input type="checkbox" data-product-id="<?php echo $d->product; ?>" data-product-var="" data-price="" data-qty=""></span>
                                                    <?php

                                                    }
                                                    ?>


                                                
                                                <span class="product-info" data-title="Product">
                                                        <img
                                                            width="528"
                                                            height="528"
                                                            src="<?php echo get_the_post_thumbnail_url( $d->product ); ?>"
                                                            class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image"
                                                            alt=""
                                                        />
                                                    <span class="product-name" data-title="Product">
                                                        <span class="name"><?php echo $product_obj->name; ?></span>
                                                    </span>
                                                </span>
                                                <span class="product-price" data-title="Price">
                                                        <?php 

                                                        if($stock == 'outofstock'){ 
                                                                                                                    
                                                            echo 'Sin Stock';

                                                        }else{
                                                            $varContentPrice = wc_get_product($product_obj)->get_price();
                                                            echo '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><span class="regular-price" data-price="'. $varContentPrice .'">'. $varContentPrice .'</span></span>';

                                                        }

                                                        
                                                        ?>
                                                </span>
                                                <span class="product-quantity" data-title="Quantity">
                                                    <div class="quantity">
                                                        <label class="screen-reader-text" for="quantity_5f0331b02d9c9">Caballete cantidad</label>
                                                        <span class="box">
                                                            <button class="minus" type="button" value="&nbsp;"><i class="linear-icon-minus"></i></button>
                                                            <input type="number" class="input-text qty text" step="1" min="0" max="" name=""  value="<?php echo $d->qty; ?>" title="Cantidad" size="4" placeholder="" inputmode="numeric"/>
                                                            <button class="plus" type="button" value="&nbsp;"><i class="linear-icon-plus"></i></button>
                                                        </span>
                                                    </div>
                                                </span>
                                                <span class="product-subtotal price" data-title="Total">
                                                    <?php if($productIDsUnique){ ?>

                                                        <?php if($stock == 'outofstock'){ 
                                                            
                                                            echo 'Sin Stock';
                                                        }elseif(in_array($d->product, $productIDsUnique)){
                                                            echo 'En tu carrito';
                                                        }else{
                                                            $totalPrice = $d->qty * $varContentPrice;
                                                            echo '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><span class="price-text" data-price="'. $totalPrice .'">'. $totalPrice .'</span></span>';

                                                        } ?>

                                                    <?php }else{ ?>


                                                        <?php if($stock == 'outofstock'){ 
                                                            
                                                            echo 'Sin Stock';

                                                        }else{

                                                            $totalPrice = $d->qty * $varContentPrice;
                                                            
                                                            echo '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><span class="price-text" data-price="'. $totalPrice .'">'. $totalPrice .'</span></span>';


                                                        } ?>



                                                    <?php } ?>


                                                    
                                                </span>
                                                <span class="product-remove">
                                                    <a href="<?php echo get_site_url(); ?>/carro-2?delete=<?php echo $d->id; ?>">
                                                        <i class="linear-icon-trash2"></i>
                                                    </a>
                                                </span>
                                            </div>

                                    <?php
                                        }
                                    }
                                    } ?>

                                    <div class="actions"> 
                                        
                                        <div class="box-details"> 
                                            <span class="desktop">&nbsp;</span>
                                            <span class="desktop">&nbsp;</span>
                                            <span class="desktop">&nbsp;</span>
                                            <span class="actionButton"><button class="refresh-totals" disabled>Actualizar precio</button></span>
                                            <span>
                                                
                                                <div class="totals">
                                                    <p class="cart-totals-dist">Total Distribuidor:</p>
                                                    <p class="cart-totals-dist mobile"><span>$ <span class="num" data-price="">40</span></span></p>
                                                    <p class="selected-totals-dist">Total Seleccionado:</p>
                                                    <p class="selected-totals-dist mobile"><span>$ <span class="num" data-price="">0</span></span></p>
                                                    <p class="shipping-details"> Tiempo de entrega:</p>
                                                    <p class="shipping-details mobile"> 
                                                        <?php
                                                            if($shippingVal == 1){
                                                                echo '1 día laborable';
                                                            }elseif($shippingVal == 2){
                                                                echo '1-2 días laborables';
                                                            }elseif($shippingVal == 3){
                                                                echo '1-3 días laborables';
                                                            }elseif($shippingVal == 4){
                                                                echo '3-5 días laborables';
                                                            }elseif($shippingVal == 5){
                                                                echo '12 semanas';
                                                            }elseif($shippingVal == 6){
                                                                echo '2-3 semanas';
                                                            }elseif($shippingVal == 7){
                                                                echo '3-4 semanas';
                                                            }elseif($shippingVal == 8){
                                                                echo '4-6 semanas';
                                                            }elseif($shippingVal == 9){
                                                                echo '6-8 semanas';
                                                            }elseif($shippingVal == 0){
                                                                echo 'No especifica';
                                                            }
                                                        ?>
                                                    
                                                    </p>
                                                </div>
                                            </span>
                                            <span>
                                                <div class="totals desktop">
                                                    <p class="cart-totals-dist"><span>$ <span class="num" data-price="">40</span></span></p>
                                                    <p class="selected-totals-dist"><span>$ <span class="num" data-price="">0</span></span></p>
                                                    <p class="shipping-details"> 
                                                        <?php
                                                            if($shippingVal == 1){
                                                                echo '1 día laborable';
                                                            }elseif($shippingVal == 2){
                                                                echo '1-2 días laborables';
                                                            }elseif($shippingVal == 3){
                                                                echo '1-3 días laborables';
                                                            }elseif($shippingVal == 4){
                                                                echo '3-5 días laborables';
                                                            }elseif($shippingVal == 5){
                                                                echo '12 semanas';
                                                            }elseif($shippingVal == 6){
                                                                echo '2-3 semanas';
                                                            }elseif($shippingVal == 7){
                                                                echo '3-4 semanas';
                                                            }elseif($shippingVal == 8){
                                                                echo '4-6 semanas';
                                                            }elseif($shippingVal == 9){
                                                                echo '6-8 semanas';
                                                            }elseif($shippingVal == 0){
                                                                echo 'No especifica';
                                                            }
                                                        ?>
                                                    
                                                    </p>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="box-actions">
                                            <div class="send">
                                                <?php 
                                                    if($cartDisUnique){
                                                        if($cartDisUnique[0] != $ud){
                                                            
                                                            echo '<button class="finalizar-compra cartPage" style="display:none;">Comprar productos</button>';
                                                        }else{
                                                            
                                                            echo '<button class="send-to-cart">Comprar productos</button>';

                                                        }

                                                    }else{

                                                        echo '<button class="send-to-cart">Comprar productos</button>';

                                                    }
                                                
                                                
                                                ?>
                                            </div>
                                            <div class="back-to">
                                                <p><a href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>"><- Volver al catálogo</a></p>
                                                <?php
                                                $seller_id  = get_user_by( 'id', $ud );
                                                $vendor = dokan()->vendor->get( $seller_id );
                                                ?>
                                                <p><a href="<?php echo $vendor->get_shop_url(); ?>"><- Ver la tienda del distribuidor</a></p>
                                            </div>
                                        </div>
                                    </div>



                                    </div>



                                    
                                        <?php
                                }
                            }
                            ?>
                            
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


<div class="modal fade not" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <p>Por favor finalice su compra antes de seguir.</p>
      </div>
      <div class="modal-footer">
            <a href="<?php echo wc_get_cart_url(); ?>" class="btn btn-primary finalizar-compra-btn cartPage">Finalizar compra</a>

      </div>
    </div>
  </div>
</div>

<?php }else{ ?>
    <section id="main-container" class="container inner">
        <div id="main-content" class="main-page clearfix">
            <div id="main" class="site-main" style="text-align: center;">
            <h1>¡No has agregado productos a tu selección!</h1>
                <p>Visita nuestro <a href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>">catálogo.</a></p>
            </div>
        </div>
    </section>
<?php 

}
get_footer(); 


?>