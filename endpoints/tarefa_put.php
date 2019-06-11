<?php

function api_tarefa_put($request) {

  $slug = $request['slug'];

  $tarefa_id = get_tarefa_id_by_slug($slug);
  $user = wp_get_current_user();

  $author_id = (int) get_post_field('post_author', $tarefa_id);
  $user_id = (int) $user->ID;

  if($user_id === $author_id) {

    $status_current = get_post_meta($tarefa_id ,'status')[0];
    if($status_current === "true") {
      $newStatus = "false";
    }else{ 
      $newStatus = "true";
    }

    $response = array(
      'ID' => $tarefa_id,
      'meta_input' => array(
        'status' => $newStatus
      )
    );
    wp_update_post($response);

  }else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão.', array('status' => 401));
  }
  return rest_ensure_response($response);
}

function registrar_api_tarefa_put() {
  register_rest_route('api', '/tarefa/(?P<slug>[-\w]+)', array(
    array(
      'methods' => WP_REST_Server::EDITABLE,
      'callback' => 'api_tarefa_put',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_tarefa_put');

?>