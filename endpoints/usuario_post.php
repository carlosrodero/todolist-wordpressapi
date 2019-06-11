<?php

function api_usuario_post($request) {

  $email = sanitize_email($request['email']);
  $senha = sanitize_text_field($request['senha']);
  $nome = sanitize_text_field($request['nome']);

  $user_exists = username_exists($email);
  $email_exists = email_exists($email);

  if(!$user_exists && !$email_exists && $email && $senha) {
    $user_id = wp_create_user($email, $senha, $email);

    $response = array(
      'ID' => $user_id,
      'display_name' => $nome,
      'first_name' => $nome,
      'role' => 'subscriber',
    );
    wp_update_user($response);

  }else {
    $response = new WP_Error('email', 'E-mail já cadastrado.', array('status' => 403));
  }

  return rest_ensure_response($response);
}

function registrar_api_usuario_post() {
  register_rest_route('api', '/usuario', array(
    array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'api_usuario_post',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_usuario_post');

?>