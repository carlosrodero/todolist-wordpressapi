<?php

function api_usuario_get($request) {

  $user = wp_get_current_user();
  $user_id = $user->ID;

  if($user_id > 0) {
    $user_meta = get_user_meta($user_id);

    $response = array(
      "id" => $user->user_login,
      "nome" => $user->display_name,
      "email" => $user->user_email,
    );
  }else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão', array('status' => 401));
  }

  return rest_ensure_response($response);
}

function registrar_api_usuario_get() {
  register_rest_route('api', '/usuario', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_usuario_get',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_usuario_get');

?>