<?php
/*
Plugin Name: AI Content Enhancer
Description: A WordPress plugin to enhance content using AI models.
Version: 1.0.0
Author: Ali Kiani
*/

// Define security keys for encryption
define('SECURE_KEY', 'your-secure-key-here');
define('SECURE_IV', 'your-secure-iv-here');

// Include required files
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';

// Function to handle AI API requests
function ai_content_enhancer_fetch_ai_response($content) {
    $encrypted_key = get_option('ai_content_enhancer_api_key');
    $api_key = $encrypted_key ? openssl_decrypt($encrypted_key, 'AES-128-CBC', SECURE_KEY, 0, SECURE_IV) : null;
    $model = get_option('ai_content_enhancer_model', 'gpt-4');

    if (!$api_key || !$model) {
        return 'Error: API Key or Model Name is not set.';
    }

    // Handle Perplexity
    if ($model === 'perplexity') {
        // Custom logic for perplexity calculation
        return 'Perplexity analysis is currently under development.';
    }

    // General API call for text completion
    $response = wp_remote_post('https://api.openai.com/v1/completions', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type'  => 'application/json',
        ),
        'body'    => json_encode(array(
            'model'       => $model,
            'prompt'      => $content,
            'max_tokens'  => 200,
        )),
    ));

    if (is_wp_error($response)) {
        return 'Error: ' . $response->get_error_message();
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return $data['choices'][0]['text'] ?? 'No response generated.';
}

