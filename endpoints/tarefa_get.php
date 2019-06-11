<?php

function tarefa_scheme($slug){

  $post_id = get_tarefa_id_by_slug($slug);

  if($post_id) {
    $post_meta = get_post_meta($post_id);

    $response = array(
      'id' => $slug,
      'nome' => get_the_title($post_id),
      'status' => $post_meta['status'][0],
      'deadline' => $post_meta['deadline'][0],
      'usuario_id' => $post_meta['usuario_id'][0],
    );

  } else {
    $response = new WP_Error('naoexiste', 'Produto não encontrado.', array('status' => 404));
  }

  return $response;
}

function api_tarefa_get($request) {

  $user = wp_get_current_user();
  $user_id = $user->ID;

  if($user_id > 0) {
  
    $q = sanitize_text_field($request['q']) ?: '';
    $_page = sanitize_text_field($request['_page']) ?: 0;
    $_limit = sanitize_text_field($request['_limit']) ?: 9;
    $usuario_id = sanitize_text_field($request['usuario_id']);

    $usuario_id_query = null;
    if($usuario_id) {
      $usuario_id_query = array(
        'key' => 'usuario_id',
        'value' => $usuario_id,
        'compare' => '='
      );
    }

    // $vendido = array(
    //   'key' => 'vendido',
    //   'value' => 'false',
    //   'compare' => '='
    // );

    $query = array(
      'post_type' => 'tarefa',
      'posts_per_page' => $_limit,
      'paged' => $_page,
      's' => $q,
      'meta_query' => array(
        $usuario_id_query
      )
    );

    $loop = new WP_Query($query);
    $posts = $loop->posts;
    $total = $loop->found_posts;

    $produtos = array();
    foreach ($posts as $key => $value) {
      $produtos[] = tarefa_scheme($value->post_name);
    }

    $response = rest_ensure_response($produtos);
    $response->header('X-Total-Count', $total);

  }else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão.', array('status' => 401));
  }

  return $response;
}

function registrar_api_tarefa_get() {
  register_rest_route('api', '/tarefa', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_tarefa_get',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_tarefa_get');

?>