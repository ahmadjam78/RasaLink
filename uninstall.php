<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('rasa_api_key');
