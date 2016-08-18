<?php

function filter_posts($posts) {
  // Should contain all the posts fetched from the DB.
  // Are we in admin area?
  if (is_admin()) return $posts;
  // If the user isn't logged in, then let wp-members handle it.
  // wp-members is installed to control which posts are available to unregistered users.
  // TODO : Remove this and update the code below to handle unregistered users too.
  if (!is_user_logged_in()) return $posts;

  // Get the Auth0 Details from the logged in user.
  $Auth0Data = json_decode(get_user_meta(get_current_user_id(), "wp_auth0_obj", true));

  // What category slug names do we let any logged in user view?
  $allowedCategories= array("free");

  // Will contain the posts that we let WordPress then render/consume.
  $allowedPosts= array();

  // if they don't have Auth0 details, then no access?
  if ($Auth0Data== "")
  {
    // Do we want to do anything here?
  } else if (!isset($Auth0Data->blogCategories)) {
    // The user has Auth0 data, but no Blog Categories defined... want to do anything?
  } else {
    // grab the WordPress category slugs defined in the Auth0 user and add them to the array.
    $allowedCategories= array_merge($allowedCategories, $Auth0Data->blogCategories);
  }

  // Run through each post in the array and see if the post has any categories that are allowed against this user.
  for ($loopPosts= 0; $loopPosts<= count($posts); $loopPosts++) {
    $post_categories= wp_get_post_categories($posts[$loopPosts]->ID);
    $postAllowed= false;

    foreach ($post_categories as $post_category) {
      $category= get_category($post_category);
      // Compare the slug with the roles we get back from Auth0!!!!!!
      if (in_array($category->slug, $allowedCategories))
      {
        $postAllowed= true;
        // We should break here...why keep going?
      }
    }
    if ($postAllowed) {
      // Could consider just giving back the extracts when the post is denied?g
      array_push($allowedPosts, $posts[$loopPosts]);
    }
  }

  // Send back the list of posts...
  return $allowedPosts;
}  // End of filter_posts

// Add our function as a hook, every time WordPress queries a list of posts.
add_action('the_posts', 'filter_posts' );

// I created this work within a child theme's functions.php .
// The function below is to make sure that the child theme inherits properly...
function my_theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

?>
