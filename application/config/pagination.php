<?php
$config['per_page'] = 1;
$config["uri_segment"] = 3;

//$config['full_tag_open'] = '<div class="pagination">';
$config['full_tag_open'] = '<div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate"><ul class="pagination">';
$config['full_tag_close'] = '</ul></div>';

$config['first_link'] = 'Primera';
$config['first_tag_open'] = '<li class="paginate_button previous" aria-controls="dataTable" tabindex="0" id="dataTable_previous">';
$config['first_tag_close'] = '</li>';

$config['last_link'] = 'Ultima';
$config['last_tag_open'] = '<li class="paginate_button next" aria-controls="dataTable" tabindex="0" id="dataTable_next">';
$config['last_tag_close'] = '</li>';

$config['next_link'] = 'Siguiente';
//$config['next_tag_open'] = '<span class="nextlink">';
//$config['next_tag_close'] = '</span>';
$config['next_tag_open'] = '<li class="paginate_button next" aria-controls="dataTable" tabindex="0" id="dataTable_next">';
$config['next_tag_close'] = '</li>';

$config['prev_link'] = 'Anterior';
//$config['prev_tag_open'] = '<span class="prevlink">';
//$config['prev_tag_close'] = '</span>';

$config['prev_tag_open'] = '<li class="paginate_button previous" aria-controls="dataTable" tabindex="0" id="dataTable_previous">';
$config['prev_tag_close'] = '</li>';


//$config['cur_tag_open'] = '<span class="curlink">';
//$config['cur_tag_close'] = '</span>';

$config['cur_tag_open'] = '<li class="paginate_button current" aria-controls="dataTable" tabindex="0">';
$config['cur_tag_close'] = '</li>';


//$config['num_tag_open'] = '<span class="numlink">';
//$config['num_tag_close'] = '</span>';

$config['num_tag_open'] = '<li class="paginate_button " aria-controls="dataTable" tabindex="0">';
$config['num_tag_close'] = '</li>';
