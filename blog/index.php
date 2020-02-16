<?php

include 'components.php';

$page = new Page();

$page->setScripts([
    'assets/js/scripts.js'
]);
$page->setStyles([
    'assets/css/min/blog.min.css'
]);

$page->addComponent(new Header());
$page->addComponent(new BlogList());
$page->addComponent(new Footer());

$page->addModel([
    "title" => "Robert Tamayo's Blog",
    "meta_description" => "Code blog for JavaScript, PHP, and Java. Web, Mobile, and Software Engineering",
]);

