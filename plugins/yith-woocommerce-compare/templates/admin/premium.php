<style>
.section{
    margin-left: -20px;
    margin-right: -20px;
    font-family: "Raleway",san-serif;
}
.section h1{
    text-align: center;
    text-transform: uppercase;
    color: #808a97;
    font-size: 35px;
    font-weight: 700;
    line-height: normal;
    display: inline-block;
    width: 100%;
    margin: 50px 0 0;
}
.section ul{
    list-style-type: disc;
    padding-left: 15px;
}
.section:nth-child(even){
    background-color: #fff;
}
.section:nth-child(odd){
    background-color: #f1f1f1;
}
.section .section-title img{
    display: table-cell;
    vertical-align: middle;
    width: auto;
    margin-right: 15px;
}
.section h2,
.section h3 {
    display: inline-block;
    vertical-align: middle;
    padding: 0;
    font-size: 24px;
    font-weight: 700;
    color: #808a97;
    text-transform: uppercase;
}

.section .section-title h2{
    display: table-cell;
    vertical-align: middle;
    line-height: 25px;
    border: none;
}

.section-title{
    display: table;
}

.section h3 {
    font-size: 14px;
    line-height: 28px;
    margin-bottom: 0;
    display: block;
}

.section p{
    font-size: 13px;
    margin: 25px 0;
}
.section ul li{
    margin-bottom: 4px;
}
.landing-container{
    max-width: 750px;
    margin-left: auto;
    margin-right: auto;
    padding: 50px 0 30px;
}
.landing-container:after{
    display: block;
    clear: both;
    content: '';
}
.landing-container .col-1,
.landing-container .col-2{
    float: left;
    box-sizing: border-box;
    padding: 0 15px;
}
.landing-container .col-1 img{
    width: 100%;
}
.landing-container .col-1{
    width: 55%;
}
.landing-container .col-2{
    width: 45%;
}
.premium-cta{
    background-color: #808a97;
    color: #fff;
    border-radius: 6px;
    padding: 20px 15px;
}
.premium-cta:after{
    content: '';
    display: block;
    clear: both;
}
.premium-cta p{
    margin: 7px 0;
    font-size: 14px;
    font-weight: 500;
    display: inline-block;
    width: 60%;
}
.premium-cta a.button{
    border-radius: 6px;
    height: 60px;
    float: right;
    background: url(<?php echo YITH_WOOCOMPARE_URL?>assets/images/upgrade.png) #ff643f no-repeat 13px 13px;
    border-color: #ff643f;
    box-shadow: none;
    outline: none;
    color: #fff;
    position: relative;
    padding: 9px 50px 9px 70px;
}
.premium-cta a.button:hover,
.premium-cta a.button:active,
.premium-cta a.button:focus{
    color: #fff;
    background: url(<?php echo YITH_WOOCOMPARE_URL?>assets/images/upgrade.png) #971d00 no-repeat 13px 13px;
    border-color: #971d00;
    box-shadow: none;
    outline: none;
}
.premium-cta a.button:focus{
    top: 1px;
}
.premium-cta a.button span{
    line-height: 13px;
}
.premium-cta a.button .highlight{
    display: block;
    font-size: 20px;
    font-weight: 700;
    line-height: 20px;
}
.premium-cta .highlight{
    text-transform: uppercase;
    background: none;
    font-weight: 800;
    color: #fff;
}

.section.one{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/01-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.two{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/02-bg.png) no-repeat #f1f1f1; background-position: 15% 75%
}
.section.three{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/03-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.four{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/04-bg.png) no-repeat #f1f1f1; background-position: 15% 75%
}
.section.five{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/05-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.six{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/06-bg.png) no-repeat #f1f1f1; background-position: 15% 75%
}
.section.seven{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/07-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.eight{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/08-bg.png) no-repeat #f1f1f1; background-position: 15% 75%
}
.section.nine{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/09-bg.png) no-repeat #fff; background-position: 15% 75%
}
.section.ten{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/10-bg.png) no-repeat #f1f1f1; background-position: 15% 75%
}
.section.eleven{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/11-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.twelve{
    background: url(<?php echo YITH_WOOCOMPARE_URL ?>assets/images/12-bg.png) no-repeat #f1f1f1; background-position: 15% 75%
}

@media (max-width: 768px) {
    .section{margin: 0}
    .premium-cta p{
        width: 100%;
    }
    .premium-cta{
        text-align: center;
    }
    .premium-cta a.button{
        float: none;
    }
}

@media (max-width: 480px){
    .wrap{
        margin-right: 0;
    }
    .section{
        margin: 0;
    }
    .landing-container .col-1,
    .landing-container .col-2{
        width: 100%;
        padding: 0 15px;
    }
    .section-odd .col-1 {
        float: left;
        margin-right: -100%;
    }
    .section-odd .col-2 {
        float: right;
        margin-top: 65%;
    }
}

@media (max-width: 320px){
    .premium-cta a.button{
        padding: 9px 20px 9px 70px;
    }

    .section .section-title img{
        display: none;
    }
}
</style>
<div class="landing">
    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="premium-cta">
                <p>
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Compare%2$s to benefit from all features!','yith-woocommerce-compare' ),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-woocommerce-compare' ); ?></span>
                    <span><?php _e('to the premium version','yith-woocommerce-compare' ); ?></span>
                </a>
            </div>
        </div>
    </div>
    <div class="one section section-even clear">
        <h1><?php _e('Premium Features','yith-woocommerce-compare');?></h1>
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/01.png" alt="Dedicated Page" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/01-icon.png" alt="icon 01"/>
                    <h2><?php _e('A DEDICATED PAGE','yith-woocommerce-compare' ); ?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Don\'t you want to compare your products in a modal window anymore?%3$sWith the premium version of %1$sYITH WooCommerce Compare%2$s, a new page will be created automatically in your site and, adding it among the menu entries, you will be able to give to your users the chance to access it easily whenever they want.', 'yith-woocommerce-compare'), '<b>', '</b>','<br>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="two section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/02-icon.png" alt="icon 02" />
                    <h2><?php _e('CATEGORY COMPARATION','yith-woocommerce-compare');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __( 'People are often confused by finding products of different categories in the comparison table, creating difficulties in comparing the products they are interested into.The %1$s"Compare by category"%2$s option exists for this need: you will be able to separate the products in the table by category affinity.', 'yith-woocommerce-compare'), '<b>', '</b>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/02.png" alt="CATEGORY COMPARATION" />
            </div>
        </div>
    </div>
    <div class="three section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/03.png" alt="Category esclusion" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/03-icon.png" alt="icon 03" />
                    <h2><?php _e( 'CATEGORY EXCLUSION ','yith-woocommerce-compare');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('In your shop there may be product categories you don\'t want to be affected by the plugin features, deleting the comparison button for them. The premium version of the plugin offers this too.%3$sAnd there\'s more! Activating the %1$s"Reverse exclusion list"%2$s option, you can also invert the behavior of the feature, allowing the comparison only to those products of the selected categories.', 'yith-woocommerce-compare' ), '<b>', '</b>','<br>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="four section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/04-icon.png" alt="icon 04" />
                    <h2><?php _e('TABLE IMAGE','yith-woocommerce-compare');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Give a personal touch to the %1$scomparison table%2$s your users will see. Select an image, upload it from the option panel and show it ahead of the table to your users. ', 'yith-woocommerce-compare'), '<b>', '</b>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/04.png" alt="Shortcode" />
            </div>
        </div>
    </div>
    <div class="five section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/05.png" alt="Dynamic fields" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL?>assets/images/05-icon.png" alt="icon 05" />
                    <h2><?php _e('DYNAMIC FIELDS','yith-woocommerce-compare');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('With this precious feature, you will be able to show in the table only the fields that have information in at least one of the selected products.%3$sActivate the %1$s"Dynamic Attribute fields"%2$s option and there will be no more empty lines in your comparison table.','yith-woocommerce-compare'),'<b>','</b>','<br>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="six section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/06-icon.png" alt="icon 06" />
                    <h2><?php _e('SOCIAL NETWORK SHARING','yith-woocommerce-compare');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __( 'One of the most appealing features of the premium version of the plugin.Four social network sites for your users (Facebook, Twitter, Google + and Pinterest) and the email system %1$sto share%2$s the comparison table of the products they have selected.','yith-woocommerce-compare' ),'<b>','</b>' ) ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/06.png" alt="Social networks" />
            </div>
        </div>
    </div>
    <div class="seven section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/07.png" alt="Related products" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL?>assets/images/07-icon.png" alt="icon 05" />
                    <h2><?php _e('RELATED PRODUCTS','yith-woocommerce-compare');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('All the products that have common categories and/or tags with those in the comparison table will be showed in a slider, right under the comparison table.%3$sA completely %1$s"touch friendly"%2$s slider to encourage your users to discover the shop products of the shop related to those they are interested into.','yith-woocommerce-compare'),'<b>','</b>','<br>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="eight section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/08-icon.png" alt="icon 06" />
                    <h2><?php _e('CUSTOMIZABLE STYLE','yith-woocommerce-compare');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __( 'An advanced option panel that let you change all the colors of the plugin, so that you can adapt stylistically all its elements %1$sto the layout%2$s of your shop.We know it, looks also count... and we give you the right tools to get to the best result.','yith-woocommerce-compare' ),'<b>','</b>' ) ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/08.png" alt="Social networks" />
            </div>
        </div>
    </div>
    <div class="nine section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/09.png" alt="Related products" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL?>assets/images/09-icon.png" alt="icon 09" />
                    <h2><?php _e('CUSTOMIZED ATTRIBUTES','yith-woocommerce-compare');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Let you users compare products following every feature, and considering also all those %1$sattributes%2$s that can be manually created in within the product detail page. Every single detail will be included.','yith-woocommerce-compare'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="ten section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/10-icon.png" alt="icon 10" />
                    <h2><?php _e('A TAILORED TABLE','yith-woocommerce-compare');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __( 'Select the products you want to compare and the system will offer you the %1$sshortcode%2$s to generate a comparing table. Simple, rapid and useful.','yith-woocommerce-compare' ),'<b>','</b>' ) ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/10.png" alt="Social networks" />
            </div>
        </div>
    </div>
    <div class="eleven section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/11.png" alt="Widget" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL?>assets/images/11-icon.png" alt="icon 11" />
                    <h2><?php _e('Compare list - widget','yith-woocommerce-compare');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Thanks to YITH WooCommerce Comapre Widget users could verify at any time the complete list of products added to the compare tab and avoid to open the popup to check its existence. ','yith-woocommerce-compare'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="twelve section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/12-icon.png" alt="icon 12" />
                    <h2><?php _e('Compare counter - widget and shortcode','yith-woocommerce-compare');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __( 'Moreover, if you want to give your users the chance to see in a glance how many products they have added to the Compare list, feel free to use the widget or the shortcode included. And their Compare list is always no more than one click away.','yith-woocommerce-compare' ),'<b>','</b>' ) ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WOOCOMPARE_URL ?>assets/images/12.png" alt="Social networks" />
            </div>
        </div>
    </div>
    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="premium-cta">
                <p>
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Compare%2$s to benefit from all features!','yith-woocommerce-compare'),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-woocommerce-compare');?></span>
                    <span><?php _e('to the premium version','yith-woocommerce-compare');?></span>
                </a>
            </div>
        </div>
    </div>
</div>