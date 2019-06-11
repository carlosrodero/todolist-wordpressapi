<?php

function registrar_cpt_tarefa() {
  register_post_type('tarefa', array(
    'label' => 'Tarefa',
    'description' => 'Tarefa',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'rewrite' => array('slug' => 'tarefa', 'with_front' => true),
    'query_var' => true,
    'supports' => array('custom-fields', 'author', 'title'),
    'publicly_queryable' => true
  ));
}
add_action('init', 'registrar_cpt_tarefa');

?>