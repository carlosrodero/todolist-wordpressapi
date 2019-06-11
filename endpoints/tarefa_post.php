<?php

function api_tarefa_post($request) {

  $user = wp_get_current_user();
  $user_id = $user->ID;

  if($user_id > 0) {

    $name = sanitize_text_field($request['task']);
    $deadline = sanitize_text_field($request['deadline']);
    $usuario_id = $user->user_login;

    $response = array(
      'post_author' => $user_id,
      'post_type' => 'tarefa',
      'post_title' => $name,
      'post_status' => 'publish',
      'meta_input' => array(
        'deadline' => $deadline,
        'usuario_id' => $usuario_id,
        'status' => 'false',
      ),
    );

    $tarefa_id = wp_insert_post($response);
    $response['id'] = get_post_field('post_name', $tarefa_id);

  }else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão.', array('status' => 401));
  }
  return rest_ensure_response($response);
}

function registrar_api_tarefa_post() {
  register_rest_route('api', '/tarefa', array(
    array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'api_tarefa_post',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_tarefa_post');

?>