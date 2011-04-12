<?php

$app['basename'] = 'process_viewer';
$app['version'] = '6.0';
$app['release'] = '0.2';
$app['vendor'] = 'ClearFoundation';
$app['packager'] = 'ClearFoundation';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['summary'] = 'System live process viewer'; // FIXME: translate 
$app['description'] = 'Displays processes running on your system.'; // FIXME: translate

$app['name'] = lang('process_viewer_process_viewer');
$app['category'] = lang('base_category_system');
$app['subcategory'] = lang('base_subcategory_settings');

// Packaging
$app['core_dependencies'] = array('app-base-core');
