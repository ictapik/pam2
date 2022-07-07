<?php

$data = array();

$list = (array(
    "A", "B", "C"
));

foreach ($list as $list) {
    $row = array();
    $row[] = "A";
    $row[] = "B";
    $row[] = "C";

    $data[] = $row;
}

$output = array(
    "draw" => 1,
    "recordsTotal" => 1,
    "recordsFiltered" => 1,
    "data" => $data,
);

echo json_encode($output);
