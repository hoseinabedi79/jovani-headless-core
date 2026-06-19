<?php
/*
Plugin Name: Jovani Headless Core
Description: Custom backend logic and GraphQL fields for Jovani Headless Store.
Version: 1.0.0
Author: Hossein Abedi
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// 1. Load the Composer Autoloader
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// 2. Initialize the core plugin class
if ( class_exists( 'Jovani\HeadlessCore\Core' ) ) {
    $jovani_core = new Jovani\HeadlessCore\Core();
    $jovani_core->init();
}