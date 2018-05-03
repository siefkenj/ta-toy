<?php
function arr_get($array, $key, $default = null){
    return isset($array[$key]) ? $array[$key] : $default;
}

header('Content-type: application/json');

$course = arr_get($_GET, 'course', '');
$ta = arr_get($_GET, 'ta', '');

// list of courses
if ($course == '') {
    print('{ "TYPE": "courses", "DATA": ["MAT135H1F", "CSC236H1S", "MAT223H1S"] }');
    exit(0);
}

// list of TAs by course
if ($course == 'MAT135H1F' && $ta == '') {
    print('{ "TYPE": "tas", "DATA": ["Jenny Grover", "Martha", "Tim"] }');
    exit(0);
}

if ($course == 'CSC236H1S' && $ta == '') {
    print('{ "TYPE": "tas", "DATA": ["Sam Tobble", "Jacob", "Roger"] }');
    exit(0);
}

if ($course == 'MAT223H1S' && $ta == '') {
    print('{ "TYPE": "tas", "DATA": ["Wanda Zhu"] }');
    exit(0);
}

// list of sections by (course, TA)
if ($course == 'MAT135H1F' && $ta == 'Jenny Grover') {
    print('{ "TYPE": "sections", "DATA": ["TUT0401", "TUT5401"] }');
    exit(0);
}

if ($course == 'MAT135H1F' && $ta == 'Martha') {
    print('{ "TYPE": "sections", "DATA": ["TUT0301", "TUT0201"] }');
    exit(0);
}

if ($course == 'MAT135H1F' && $ta == 'Tim') {
    print('{ "TYPE": "sections", "DATA": ["TUT0101"] }');
    exit(0);
}

if ($course == 'CSC236H1S' && $ta == 'Sam Tobble') {
    print('{ "TYPE": "sections", "DATA": ["TUT0301", "TUT0201"] }');
    exit(0);
}

if ($course == 'CSC236H1S' && $ta == 'Jacob') {
    print('{ "TYPE": "sections", "DATA": ["TUT0101", "TUT5701", "TUT0202", "TUT0123"] }');
    exit(0);
}

if ($course == 'CSC236H1S' && $ta == 'Roger') {
    print('{ "TYPE": "sections", "DATA": ["TUT0203", "TUT0124"] }');
    exit(0);
}

if ($course == 'MAT223H1S' && $ta == 'Wanda Zhu') {
    print('{ "TYPE": "sections", "DATA": ["TUT0203", "LEC0101"] }');
    exit(0);
}

    print('{ "TYPE": "error" }');
?> 
