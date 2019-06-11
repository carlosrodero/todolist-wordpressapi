<?php

$template_directory = get_template_directory();

require_once($template_directory . '/custom-post-type/tarefa.php');

require_once($template_directory . '/endpoints/usuario_post.php');
require_once($template_directory . '/endpoints/usuario_get.php');
require_once($template_directory . '/endpoints/usuario_put.php');

require_once($template_directory . '/endpoints/tarefa_post.php');
require_once($template_directory . '/endpoints/tarefa_get.php');
require_once($template_directory . '/endpoints/tarefa_put.php');

function get_tarefa_id_by_slug($slug) {
  $query = new WP_Query(array(
    'name' => $slug,
    'post_type' => 'tarefa',
    'numberposts' => 1,
    'fields' => 'ids'
  ));
  $posts = $query->get_posts();
  return array_shift($posts);
}

add_action('rest_pre_serve_request', function() {
  header('Access-Control-Expose-Headers: X-Total-Count');
});

function expire_token() {
  return time() + (60 * 60 * 24);
}
add_action('jwt_auth_expire', 'expire_token');


function my_login_screen() { ?>
  <style type="text/css">
  #login h1 a {
    background-image: none;
  }
  #backtoblog {
    display: none;
  }
  </style>
  <?php }
  add_action('login_enqueue_scripts', 'my_login_screen');