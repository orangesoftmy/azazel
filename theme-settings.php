<?php

function azazel_settings($saved_settings) {

  // Get the default values from the .info file.
  $defaults = azazel_theme_get_default_settings('azazel');

  // Merge the saved variables and their default values.
  $settings = array_merge($defaults, $saved_settings);
  
  $form['azazel_block_editing'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Show block editing on hover'),
    '#description'   => t('When hovering over a block, privileged users will see block editing links.'),
    '#default_value' => $settings['azazel_block_editing'],
    '#prefix'        => '<strong>' . t('Block Edit Links:') . '</strong>',
  );
  $form['azazel_breadcrumb'] = array(
    '#type'          => 'fieldset',
    '#title'         => t('Breadcrumb settings'),
    '#collapsible' => TRUE, 
    '#collapsed' => TRUE,
    '#attributes'    => array('id' => 'azazel-breadcrumb'),
  );
  $form['azazel_breadcrumb']['azazel_breadcrumb'] = array(
    '#type'          => 'select',
    '#title'         => t('Display breadcrumb'),
    '#default_value' => $settings['azazel_breadcrumb'],
    '#options'       => array(
                          'yes'   => t('Yes'),
                          'admin' => t('Only in admin section'),
                          'no'    => t('No'),
                        ),
  );
  $form['azazel_breadcrumb']['azazel_breadcrumb_separator'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Breadcrumb separator'),
    '#description'   => t('Text only. Don’t forget to include spaces.'),
    '#default_value' => $settings['azazel_breadcrumb_separator'],
    '#size'          => 5,
    '#maxlength'     => 10,
    '#prefix'        => '<div id="div-azazel-breadcrumb-collapse">', // jquery hook to show/hide optional widgets
  );
  $form['azazel_breadcrumb']['azazel_breadcrumb_home'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Show home page link in breadcrumb'),
    '#default_value' => $settings['azazel_breadcrumb_home'],
  );
  $form['azazel_breadcrumb']['azazel_breadcrumb_trailing'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Append a separator to the end of the breadcrumb'),
    '#default_value' => $settings['azazel_breadcrumb_trailing'],
    '#description'   => t('Useful when the breadcrumb is placed just before the title.'),
  );
  $form['azazel_breadcrumb']['azazel_breadcrumb_title'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Append the content title to the end of the breadcrumb'),
    '#default_value' => $settings['azazel_breadcrumb_title'],
    '#description'   => t('Useful when the breadcrumb is not placed just before the title.'),
    '#suffix'        => '</div>', // #div-zen-breadcrumb
  );
  $form['azazel_dropdown'] = array(
    '#type'          => 'fieldset',
    '#title'         => t('Sooperfish Menu'),
    '#collapsible' => TRUE, 
    '#collapsed' => TRUE,
    '#attributes'    => array('id' => 'azazel-dropdown'),
  );
  $form['azazel_dropdown']['azazel_dropdown'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Use Sooperfish Menu'),
  	'#default_value' => $settings['azazel_dropdown'],
  );
  $form['azazel_dropdown']['azazel_dropdown_2_col'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Double Column'),
    '#description'   => t('If a submenu has at least this many items it will be divided in 2 columns'),
    '#default_value' => $settings['azazel_dropdown_2_col'],
    '#size'          => 10,
    '#maxlength'     => 5,
    //'#prefix'        => '<div id="div-azazel-breadcrumb-collapse">', // jquery hook to show/hide optional widgets
  );
  $form['azazel_dropdown']['azazel_dropdown_3_col'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Triple Column'),
    '#description'   => t('If a submenu has at least this many items it will be divided in 3 columns'),
    '#default_value' => $settings['azazel_dropdown_3_col'],
    '#size'          => 10,
    '#maxlength'     => 5,
    //'#prefix'        => '<div id="div-azazel-breadcrumb-collapse">', // jquery hook to show/hide optional widgets
  );
  $form['azazel_dropdown']['azazel_dropdown_delay'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Delay'),
    '#description'   => t('Delay before menu disappear, in ms.'),
    '#default_value' => $settings['azazel_dropdown_delay'],
    '#size'          => 10,
    '#maxlength'     => 5,
    //'#prefix'        => '<div id="div-azazel-breadcrumb-collapse">', // jquery hook to show/hide optional widgets
  );
  $form['azazel_dropdown']['azazel_dropdown_speed_show'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Fade in speed'),
    '#description'   => t('Sub menu fade in speed, in ms.'),
    '#default_value' => $settings['azazel_dropdown_speed_show'],
    '#size'          => 10,
    '#maxlength'     => 5,
    //'#prefix'        => '<div id="div-azazel-breadcrumb-collapse">', // jquery hook to show/hide optional widgets
  );
  $form['azazel_dropdown']['azazel_dropdown_speed_hide'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Fade out speed'),
    '#description'   => t('Sub menu fade out speed, in ms.'),
    '#default_value' => $settings['azazel_dropdown_speed_hide'],
    '#size'          => 10,
    '#maxlength'     => 5,
    //'#prefix'        => '<div id="div-azazel-breadcrumb-collapse">', // jquery hook to show/hide optional widgets
  );
  return $form;

}

function azazel_theme_get_default_settings($theme) {
  $themes = list_themes();

  // Get the default values from the .info file.
  $defaults = !empty($themes[$theme]->info['settings']) ? $themes[$theme]->info['settings'] : array();

  if (!empty($defaults)) {
    // Get the theme settings saved in the database.
    $settings = theme_get_settings($theme);
    // Don't save the toggle_node_info_ variables.
    if (module_exists('node')) {
      foreach (node_get_types() as $type => $name) {
        unset($settings['toggle_node_info_' . $type]);
      }
    }
    // Save default theme settings.
    variable_set(
      str_replace('/', '_', 'theme_' . $theme . '_settings'),
      array_merge($defaults, $settings)
    );
    // If the active theme has been loaded, force refresh of Drupal internals.
    if (!empty($GLOBALS['theme_key'])) {
      theme_get_setting('', TRUE);
    }
  }

  // Return the default settings.
  return $defaults;
}