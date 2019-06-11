<?php

function api_usuario_put($request) {

  $user = wp_get_current_user();
  $user_id = $user->ID;

  if($user_id > 0) {

    $email = sanitize_email($request['email']);
    $senha = sanitize_text_field($request['senha']);
    $nome = sanitize_text_field($request['nome']);

    $email_exists = email_exists($email);

    if(!$email_exists || $email_exists === $user_id) {

      $response = array(
        'ID' => $user_id,
        'user_pass' => $senha,
        'user_email' => $email,
        'display_name' => $nome,
        'first_name' => $nome,
      );
      wp_update_user($response);

    }else {
      $response = new WP_Error('email', 'E-mail já cadastrado.', array('status' => 403));
    }

  }else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão.', array('status' => 401));
  }
  return rest_ensure_response($response);
}

function registrar_api_usuario_put() {
  register_rest_route('api', '/usuario', array(
    array(
      'methods' => WP_REST_Server::EDITABLE,
      'callback' => 'api_usuario_put',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_usuario_put');

?>