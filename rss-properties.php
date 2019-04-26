<?php
/**
 * Template Name: Legacy Communities Custom RSS Feed
 */

  // NOTE: The first thing you need to do is register a custom rss feed inside of your functions.php file.
  // https://codex.wordpress.org/Rewrite_API/add_feed
  /*
   * Example
   * 
   * function propertiesRSS() {
   *  add_feed('properties', $function);
   * }
   * add_action('init', 'propertiesRSS');
   * 
   * function propertiesFeed() {
   *  get_template_part('rss', 'properties');
   * }
   */

  // Set the header to return XML
  header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);

  // Get properties
  $data = new WP_Query([
    'post_type'       => 'properties',
    'posts_per_page'  => -1,
    'no_found_rows'   => true
  ]);

  // Define data
  $community = 'AV';
  $street = '7807 E. Main Street';
  $city = 'Mesa';
  $state = 'AZ';
  $zipcode = '85207';
?>
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <Community>
    <LGID><?= $community; ?></LGID>
    <Name><?= get_bloginfo('name'); ?></Name>
    <StreetAddress><?= $street; ?></StreetAddress>
    <City><?= $city; ?></City>
    <State><?= $state; ?></State>
    <Zip><?= $zipcode; ?></Zip>
    <CommunityPhone>480-986-5451</CommunityPhone>
    <Description><![CDATA[Agave Village Mobile Home community and RV Park located in Mesa Arizona. We offer mobile homes for sale and year around RV Park stay. Call us today at 480-986-5451.]]></Description>
    <Picture1>https://www.agavevillagemhrv.com/app/uploads/2018/05/agave-mhc-pool.jpg</Picture1>
    <Picture2>https://www.agavevillagemhrv.com/app/uploads/2018/05/hero1.jpg</Picture2>
    <Picture3>https://www.agavevillagemhrv.com/app/uploads/2018/05/pickleball-final.jpg</Picture3>
    <Picture4>https://www.agavevillagemhrv.com/app/uploads/2018/05/front-shot-final.jpg</Picture4>
  </Community>
  <?php if ( $data->have_posts() ) { ?>
    <?php while ( $data->have_posts() ) : $data->the_post(); ?>
      <?php 
        // Define data
        // NOTE: This data will likely change based on the ACF fields used
        $site = get_field('property_lot_number', $post->ID) ? get_field('property_lot_number', $post->ID) : null;
        $price = get_field('property_price_listed', $post->ID) ? get_field('property_price_listed', $post->ID) : null;
        $built = get_field('property_year_built', $post->ID) ? get_field('property_year_built', $post->ID) : null;
        $size = get_field('property_square_feet', $post->ID) ? get_field('property_square_feet', $post->ID) : null;
        $bedrooms = get_field('bedrooms', $post->ID) ? preg_replace('/[^0-9]/', '', get_field('bedrooms', $post->ID)) : null;
        $bathrooms = get_field('bathrooms', $post->ID) ? preg_replace('/[^0-9]/', '', get_field('bathrooms', $post->ID)) : null;
        $description = $post->post_content ? $post->post_content : null;
        $modified = get_the_modified_date('Y-n-j', $post->ID) ? get_the_modified_date('Y-n-j', $post->ID) : null;
        $name = get_field('property_contact_name', $post->ID) ? get_field('property_contact_name', $post->ID) : null;
        $email = get_field('property_contact_email', $post->ID) ? get_field('property_contact_email', $post->ID) : null;
        $phone = get_field('property_contact_phone', $post->ID) ? get_field('property_contact_phone', $post->ID) : null;
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full')[0] ? wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full')[0] : null;
        $images = [];

        // This field will change depending on the ACF field
        if ( have_rows('property_image_slider') ) {
          while ( have_rows('property_image_slider') ) : the_row();
            $images[] = get_sub_field('property_image_slide')['sizes']['large'];
          endwhile; 
        }
      ?>
      <Listing>
        <LGID><?= $community; ?></LGID>
        <LGLID><?= $post->ID ?></LGLID>
        <Site><?= $site; ?></Site>
        <StreetAddress><?= $street; ?></StreetAddress>
        <City><?= $city; ?></City>
        <State><?= $state; ?></State>
        <Zip><?= $zipcode; ?></Zip>
        <Price><?= $price; ?></Price>
        <isForRent>0</isForRent>
        <RentalPrice>0</RentalPrice>
        <YearBuilt><?= $built; ?></YearBuilt>
        <HomeSize><?= $size; ?></HomeSize>
        <Bedrooms><?= $bedrooms; ?></Bedrooms>
        <Bathrooms><?= $bathrooms; ?></Bathrooms>
        <Picture1><?= $image; ?></Picture1>
        <?php if ( $images ) { ?>
          <Picture2><?= $images[0]; ?></Picture2>
          <Picture3><?= $images[1]; ?></Picture3>
          <Picture4><?= $images[2]; ?></Picture4>
          <Picture5><?= $images[3]; ?></Picture5>
        <?php } ?>
        <Remarks><![CDATA[<?= $description; ?>]]></Remarks>
        <AdditionFeatures><![CDATA[<?= $description; ?>]]></AdditionFeatures>
        <Phone><?= $phone; ?></Phone>
        <ContactName><?= $name; ?></ContactName>
        <ContactEmail><?= $email; ?></ContactEmail>
        <HomeLastModifiedDate><?= $modified; ?></HomeLastModifiedDate>
      </Listing>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
  <?php } ?>
</root>