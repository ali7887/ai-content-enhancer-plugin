<?php

// Add the menu item for the plugin
function ai_content_enhancer_menu() {
    add_menu_page(
        'AI Content Enhancer',          // Page title
        'AI Enhancer',                  // Menu title
        'manage_options',               // Capability
        'ai-content-enhancer',          // Menu slug
        'ai_content_enhancer_page',     // Callback function
        'dashicons-admin-generic',      // Icon
        20                              // Position
    );
}
add_action('admin_menu', 'ai_content_enhancer_menu');

// The main admin page for the plugin
function ai_content_enhancer_page() {
    ?>
    <div class="ai-content-enhancer-wrapper">
        <h1>AI Content Enhancer</h1>
        <form method="post" action="options.php">
            <?php
            // Display settings fields and sections
            settings_fields('ai_content_enhancer_options');
            do_settings_sections('ai-content-enhancer');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

// Register settings and fields
function ai_content_enhancer_settings() {
    // Register the options
    register_setting('ai_content_enhancer_options', 'ai_content_enhancer_api_key');
    register_setting('ai_content_enhancer_options', 'ai_content_enhancer_model');

    // Add settings section
    add_settings_section(
        'ai_content_enhancer_main_section',   // ID
        'API Configuration',                 // Title
        null,                                // Callback (no description needed)
        'ai-content-enhancer'                // Page
    );

    // Add API Key field
    add_settings_field(
        'ai_content_enhancer_api_key',       // ID
        'API Key',                           // Title
        'ai_content_enhancer_api_key_field', // Callback function
        'ai-content-enhancer',               // Page
        'ai_content_enhancer_main_section'   // Section
    );

    // Add Model Name field
    add_settings_field(
        'ai_content_enhancer_model',         // ID
        'Model Name',                        // Title
        'ai_content_enhancer_model_field',   // Callback function
        'ai-content-enhancer',               // Page
        'ai_content_enhancer_main_section'   // Section
    );
}
add_action('admin_init', 'ai_content_enhancer_settings');

// Callback for API Key field
function ai_content_enhancer_api_key_field() {
    $encrypted_key = get_option('ai_content_enhancer_api_key');
    $api_key = $encrypted_key ? openssl_decrypt($encrypted_key, 'AES-128-CBC', SECURE_KEY, 0, SECURE_IV) : '';
    echo '<input type="password" name="ai_content_enhancer_api_key" value="' . esc_attr($api_key) . '" placeholder="Enter your API key" />';
}

// Callback for Model Name field
function ai_content_enhancer_model_field() {
    $current_model = get_option('ai_content_enhancer_model', 'gpt-4');

    // Expanded model list
    $models = array(
        'text-davinci-003' => 'Text Davinci 003 (High Creativity)',
        'text-curie-001'   => 'Text Curie 001 (Balanced)',
        'text-babbage-001' => 'Text Babbage 001 (Fast, Lower Cost)',
        'text-ada-001'     => 'Text Ada 001 (Basic Tasks)',
        'gpt-3.5-turbo'    => 'GPT-3.5 Turbo (Conversational AI)',
        'gpt-4'            => 'GPT-4 (Advanced Reasoning)',
        'bloom'            => 'Bloom (Multilingual, Open Source)',
        'mGPT'             => 'mGPT (Persian Language Model)',
        't5'               => 'T5 (Multilingual, General Purpose)',
        'perplexity'       => 'Perplexity Calculation (Evaluate Predictions)',
    );

    echo '<select name="ai_content_enhancer_model">';
    foreach ($models as $value => $label) {
        $selected = ($current_model === $value) ? 'selected' : '';
        echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';

    echo '<p class="description">Select a model based on your needs. For perplexity calculations, the plugin will analyze predictive accuracy.</p>';
}

