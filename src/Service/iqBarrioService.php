<?php

namespace Drupal\iq_barrio_helper\Service;

class iqBarrioService {
  public function writeDefinitionsFile($stylingValues, $pathDefinitionTarget, $pathDefinitionSource = null) {
    $definitionSource = "";

    if( !$pathDefinitionSource ){
      $pathDefinitionSource = $pathDefinitionTarget.'.txt';
    }

		if(file_exists($pathDefinitionSource) ) {

			$definitionSource = file_get_contents($pathDefinitionSource);

			$definitionCompiled = preg_replace_callback('/\{{(\w+)}}/', function($match) use ($stylingValues){
				$matched = $match[0];
				$name = $match[1];
				return isset($stylingValues[$name]) ? $stylingValues[$name] : $matched;
			}, $definitionSource);

			file_put_contents($pathDefinitionTarget, $definitionCompiled);
		}
	}

  public function alterThemeSettingsForm(&$form, $formValues) {

    $form['#attached']['library'][] = 'iq_barrio/admin-style';

    $arr_colors = [
      'Colors' => [
        'primary' => t('Primary color'),
        'secondary' => t('Secondary color'),
        'tertiary' => t('Tertiary color'),
        'quaternary' => t('Quaternary color'),
      ],
      'Greyscales' => [
        'grey1' => t('Grey 1'),
        'grey2' => t('Grey 2'),
        'grey3' => t('Grey 3'),
        'grey4' => t('Grey 4'),
        'grey5' => t('Grey 5'),
      ],
      'Constants' => [
        'black' => t('Black'),
        'white' => t('White'),
      ],
    ];

    $arr_fontweights = [
      '100' => t('100 (lightest)'),
      '200' => '200',
      '300' => t('300'),
      '400' => t('400 (normal)'),
      '500' => t('500'),
      '600' => t('600'),
      '700' => t('700 (bold)'),
      '800' => t('800'),
      '900' => t('900 (boldest)'),
    ];

    $arr_fontstyles = [
      'normal' => t('Normal'),
      'italic' => t('Italic'),
    ];

    $arr_units = [
      'px'  => 'px',
      'em'  => 'em',
      'rem' => 'rem',
      '%'   => '%'
    ];

    $arr_fonts = array(
      'System Fonts' => array(
        'Arial' => t('Arial'),
        'Courier New' => t('Courier New'),
        'Helvetica' => t('Helvetica'),
        'Times New Roman' => t('Times New Roman'),
        'Verdana' => t('Verdana'),
        '\'Open Sans\', sans-serif' => t('Open Sans, sans-serif'),
      ),
    );

    $moduleHandler = \Drupal::service('module_handler');

    if ($moduleHandler->moduleExists('fontyourface')) {
      $fonts = \Drupal\fontyourface\Entity\Font::loadActivatedFonts();
      if (!empty($fonts)) {
        foreach ($fonts as $font) {
          $fontname = $font->name->value;
          $css_family = $font->css_family->value;
           $arr_google_fonts[$css_family] = $font->css_family->value;
        }
        $arr_fonts['Additional fonts'] = $arr_google_fonts;
      }
    }

    $form['iq_theme'] = [
      '#type' => 'vertical_tabs',
      '#title' => t('Custom Theme Settings'),
      '#attributes' => [
        'class' => ['iq-barrio-settings'],
      ]
    ];

    // section colors
    $form['color_definitions'] = [
      '#type' => 'details',
      '#title' => t('Colors'),
      '#open' => true, // Controls the HTML5 'open' attribute. Defaults to FALSE.
      '#group' => 'iq_theme',
      '#prefix' => '<div id="iq-barrio-target"></div><div id="iq-barrio-source">' . file_get_contents(DRUPAL_ROOT . '/' . drupal_get_path('theme', 'iq_barrio') . "/resources/sass/backend-styling-previes.css.txt") . '</div>'
    ];

    $form['color_definitions']['base_definitions'] = [
      '#type' => 'details',
      '#title' => t('Base color definitions'),
    ];

    $form['color_definitions']['base_definitions']['grey_holder'] = [
      '#type' => 'container',
      '#prefix' => '<strong>5 shades of grey</strong>',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field'],
      ]
    ];

    $form['color_definitions']['base_definitions']['grey_holder']['color_black'] = [
      '#type' => 'hidden',
      '#default_value' => $formValues['color_black'] ? $formValues['color_black'] : '#000000',
    ];

    $form['color_definitions']['base_definitions']['grey_holder']['color_white'] = [
      '#type' => 'hidden',
      '#default_value' => $formValues['color_white'] ? $formValues['color_white'] : '#FFFFFF',
    ];

    $form['color_definitions']['base_definitions']['grey_holder']['color_black'] = [
      '#type' => 'hidden',
      '#default_value' => $formValues['color_black'] ? $formValues['color_black'] : '#000000',
    ];

    $form['color_definitions']['base_definitions']['grey_holder']['color_white'] = [
      '#type' => 'hidden',
      '#default_value' => $formValues['color_white'] ? $formValues['color_white'] : '#FFFFFF',
    ];

    $form['color_definitions']['base_definitions']['grey_holder']['color_grey1'] = [
      '#type' => 'color',
      '#default_value' => $formValues['color_grey1'] ? $formValues['color_grey1'] : '#333333',
    ];

    $form['color_definitions']['base_definitions']['grey_holder']['color_grey2'] = [
      '#type' => 'color',
      '#default_value' => $formValues['color_grey2'] ? $formValues['color_grey2'] : '#666666',
    ];

    $form['color_definitions']['base_definitions']['grey_holder']['color_grey3'] = [
      '#type' => 'color',
      '#default_value' => $formValues['color_grey3'] ? $formValues['color_grey3'] : '#999999',
    ];

    $form['color_definitions']['base_definitions']['grey_holder']['color_grey4'] = [
      '#type' => 'color',
      '#default_value' => $formValues['color_grey4'] ? $formValues['color_grey4'] : '#cccccc',
    ];

    $form['color_definitions']['base_definitions']['grey_holder']['color_grey5'] = [
      '#type' => 'color',
      '#default_value' => $formValues['color_grey5'] ? $formValues['color_grey5'] : '#eeeeee',
    ];


    $form['color_definitions']['base_definitions']['color_primary'] = [
      '#type' => 'color',
      '#title' => t('Primary color'),
      '#default_value' => $formValues['color_primary'] ? $formValues['color_primary'] : '#e95e27',
    ];

    $form['color_definitions']['base_definitions']['color_secondary'] = [
      '#type' => 'color',
      '#title' => t('Secondary color'),
      '#default_value' => $formValues['color_secondary'] ? $formValues['color_secondary'] : '#009C82',
    ];

    $form['color_definitions']['base_definitions']['color_tertiary'] = [
      '#type' => 'color',
      '#title' => t('Tertiary color'),
      '#default_value' => $formValues['color_tertiary'] ? $formValues['color_tertiary'] : '#9C360D',
    ];

    $form['color_definitions']['base_definitions']['color_quaternary'] = [
      '#type' => 'color',
      '#title' => t('Quaternary color'),
      '#default_value' => $formValues['color_quaternary'] ? $formValues['color_quaternary'] : '#27E9C9',
    ];

    $form['color_definitions']['section_definitions'] = [
      '#type' => 'details',
      '#title' => t('Color assignments'),
    ];

    $form['color_definitions']['section_definitions']['color_page_background'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background'] ? $formValues['color_page_background'] : 'white',
      '#prefix' => '<div class="styling-row"><div class="styling-input"><div class="inline-input-holder separator"><strong>Page Background</strong>'
    ];

    $form['color_definitions']['section_definitions']['opacity_color_page_background'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_color_page_background'],
      '#suffix' => '</div>'
    ];

    $form['color_definitions']['section_definitions']['color_page_background_meta_header'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background_meta_header'] ? $formValues['color_page_background_meta_header'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>Meta header</strong>'
    ];

    $form['color_definitions']['section_definitions']['opacity_color_page_background_meta_header'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_color_page_background_meta_header'],
      '#suffix' => '</div>'
    ];

    $form['color_definitions']['section_definitions']['color_page_font_meta_header'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_meta_header'] ? $formValues['color_page_font_meta_header'] : 'grey3',
      '#prefix' => '<div class="inline-input-holder separator">'
    ];

    $form['color_definitions']['section_definitions']['opacity_color_page_font_meta_header'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_color_page_font_meta_header'],
      '#suffix' => '</div>'
    ];

    $form['color_definitions']['section_definitions']['color_page_background_header'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background_header'] ? $formValues['color_page_background_header'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>Header</strong>'
    ];

    $form['color_definitions']['section_definitions']['opacity_color_page_background_header'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_color_page_background_header'],
      '#suffix' => '</div>'
    ];

    $form['color_definitions']['section_definitions']['color_page_font_header'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_header'] ? $formValues['color_page_font_header'] : 'grey3',
      '#prefix' => '<div class="inline-input-holder separator">'
    ];

    $form['color_definitions']['section_definitions']['opacity_color_page_font_header'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_color_page_font_header'],
      '#suffix' => '</div>'
    ];

    $form['color_definitions']['section_definitions']['color_page_background_footer'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background_footer'] ? $formValues['color_page_background_footer'] : 'grey5',
      '#prefix' => '<div class="inline-input-holder"><strong>Footer</strong>'
    ];

    $form['color_definitions']['section_definitions']['opacity_color_page_background_footer'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_color_page_background_footer'],
      '#suffix' => '</div>'
    ];


    $form['color_definitions']['section_definitions']['color_page_font_footer'] = [
      '#type' => 'select',
      '#title' => t('Font color footer'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_footer'] ? $formValues['color_page_font_footer'] : 'grey3',
      '#prefix' => '<div class="inline-input-holder separator">'
    ];

    $form['color_definitions']['section_definitions']['opacity_color_page_font_footer'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_color_page_font_footer'],
      '#suffix' => '</div>'
    ];

    $form['color_definitions']['section_definitions']['color_page_background_elements'] = [
      '#type' => 'select',
      '#title' => t('Background color '),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background_elements'] ? $formValues['color_page_background_elements'] : 'secondary',
      '#prefix' => '<div class="inline-input-holder separator"><strong>Elements (legacy)</strong>',
    ];

    $form['color_definitions']['section_definitions']['opacity_color_page_background_elements'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_color_page_background_elements'],
      '#suffix' => '</div></div><div class="styling-preview page-layout"><div id="color-definitions-preview-page"><div id="color-definitions-preview-meta-header">Meta header</div><div id="color-definitions-preview-header">Header</div><div id="color-definitions-preview-content">Content</div><div id="color-definitions-preview-footer">Footer</div></div></div></div>'
    ];

    // Section Typo
    $form['typography'] = [
      '#type' => 'details',
      '#title' => t('Typography'),
      '#summary' => t('Typography definitions.'),
      '#group' => 'iq_theme',
    ];

    $form['typography']['headings'] = [
      '#type' => 'details',
      '#title' => t('Headings'),
    ];

    $headings = [
      'h1' => 'Heading 1',
      'h2' => 'Heading 2',
      'h3' => 'Heading 3',
      'h4' => 'Heading 4',
      'h5' => 'Heading 5',
      'h6' => 'Heading 6',
    ];

    foreach ($headings as $tag => $title) {
      $font_family_value = $formValues[$tag . '_font_family'] ? $formValues[$tag . '_font_family'] : '000000';
      $color_value = $formValues[$tag . '_color'] ? $formValues[$tag . '_color'] : 'primary';
      $opacity_value = $formValues[$tag.'_opacity'];

      $color_inverted_value = $formValues[$tag . '_color_inverted'] ? $formValues[$tag . '_color_inverted'] : 'white';
      $opacity_inverted_value = $formValues[$tag.'_opacity_inverted'];

      $font_size_value = $formValues[$tag . '_font_size'] ? $formValues[$tag . '_font_size'] : '2.5';
      $font_size_unit_value = $formValues[$tag . '_font_size_unit'] ? $formValues[$tag . '_font_size_unit'] : 'rem';


      $font_size_min_value = $formValues[$tag . '_font_size_min'] ? $formValues[$tag . '_font_size_min'] : '2';

      $line_height_value = $formValues[$tag . '_line_height'] ? $formValues[$tag . '_line_height'] : '1.2';
      $font_weight_value = $formValues[$tag . '_font_weight'] ? $formValues[$tag . '_font_weight'] : 'normal';
      $font_style_value = $formValues[$tag . '_font_style'] ? $formValues[$tag . '_font_style'] : 'normal';

      $margin_top_value = $formValues[$tag . '_margin_top'] ? $formValues[$tag . '_margin_top'] : '0';
      $margin_bottom_value = $formValues[$tag . '_margin_bottom'] ? $formValues[$tag . '_margin_bottom'] : '30';
      $margin_unit_value = $formValues[$tag . '_margin_unit'] ? $formValues[$tag . '_margin_unit'] : 'px';



      $form['typography']['headings'][$tag . '_color'] = [
        '#prefix' => '<div class="styling-row"><div class="styling-input"><div class="inline-input-holder"><strong>' . $title . '</strong>',
        '#type' => 'select',
        '#title' => t('Color'),
        '#options' => $arr_colors,
        '#default_value' => $color_value,
      ];


      $form['typography']['headings'][$tag . '_opacity'] = [
        '#type' => 'textfield',
        '#title' => t('Opacity'),
        '#default_value' => $opacity_value,
      ];


      $form['typography']['headings'][$tag . '_color_inverted'] = [
        '#type' => 'select',
        '#title' => t('Inverted color'),
        '#options' => $arr_colors,
        '#default_value' => $color_inverted_value,

      ];

      $form['typography']['headings'][$tag . '_opacity_inverted'] = [
        '#type' => 'textfield',
        '#title' => t('Opacity'),
        '#default_value' => $opacity_inverted_value,
        '#suffix' => '</div>'
      ];

      $form['typography']['headings'][$tag . '_font_family'] = [
        '#type' => 'select',
        '#title' => t('Font family'),
        '#options' => $arr_fonts,
        '#default_value' => $font_family_value,
        '#prefix' => '<div class="inline-input-holder">',
      ];

      $form['typography']['headings'][$tag . '_font_weight'] = [
        '#type' => 'select',
        '#options' => $arr_fontweights,
        '#title' => t('Font weight'),
        '#default_value' => $font_weight_value,
      ];

      $form['typography']['headings'][$tag . '_font_style'] = [
        '#type' => 'select',
        '#options' => $arr_fontstyles,
        '#title' => t('Font style'),
        '#default_value' => $font_style_value,
      ];

      $form['typography']['headings'][$tag . '_line_height'] = [
        '#type' => 'textfield',
        '#title' => t('Line height'),
        '#default_value' => $line_height_value,
        '#suffix' => '</div>'
      ];

      $form['typography']['headings'][$tag . '_font_size_min_holder'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['container-inline', 'unit-combo-field', 'separator'],
        ],
        '#prefix' => '<div class="inline-input-holder">',
      ];

      $form['typography']['headings'][$tag . '_font_size_min_holder'][$tag . '_font_size_min'] = [
        '#type' => 'textfield',
        '#title' => t('Font-size<br/>min'),
        '#default_value' => $font_size_min_value,
      ];

      $form['typography']['headings'][$tag . '_font_size_holder'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['container-inline', 'unit-combo-field', 'separator'],
        ]
      ];

      $form['typography']['headings'][$tag . '_font_size_holder'][$tag . '_font_size'] = [
        '#type' => 'textfield',
        '#title' => t('<br/>max'),
        '#default_value' => $font_size_value,
      ];

      $form['typography']['headings'][$tag . '_font_size_holder'][$tag . '_font_size_unit'] = [
        '#type' => 'select',
        '#options' => $arr_units,
        '#default_value' => $font_size_unit_value,
      ];

      $form['typography']['headings'][$tag . '_margin_holder'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['container-inline', 'unit-combo-field', 'multi-valule'],
        ],
      ];

      $form['typography']['headings'][$tag . '_margin_holder'][$tag . '_margin_top'] = [
        '#title' => t('Margin (top, bottom)'),
        '#type' => 'textfield',
        '#default_value' => $margin_top_value,
      ];

      $form['typography']['headings'][$tag . '_margin_holder'][$tag . '_margin_bottom'] = [
        '#type' => 'textfield',
        '#default_value' => $margin_bottom_value,
      ];

      $form['typography']['headings'][$tag . '_margin_holder'][$tag . '_margin_unit'] = [
        '#type' => 'textfield',
        '#type' => 'select',
        '#options' => $arr_units,
        '#default_value' => $margin_unit_value,
        '#suffix' => '</div>'
      ];

      $form['typography']['headings'][$tag . '_title'] = [
        '#prefix' => '</div><div class="styling-preview">',
        '#suffix' => '</div></div>',
        '#type' => 'item',
        '#markup' => '<' . $tag . ' >' . $title . '</' . $tag . '><p>Next element<br/>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet</p>',
      ];
    }

    $form['typography']['text_elements'] = [
      '#type' => 'details',
      '#title' => t('Text elements'),
    ];

    $text_elements = [
      'standard' => 'Standard text font',
      'small' => 'Small text font',
      'lead' => 'Lead text font',
      'deco_1' => 'Decoration font 1',
      'deco_2' => 'Decoration font 2',
      'deco_3' => 'Decoration font 3',
    ];

    foreach ($text_elements as $tag => $title) {
      $font_family_value = $formValues[$tag . '_font_family'] ? $formValues[$tag . '_font_family'] : '000000';
      $color_value = $formValues[$tag . '_color'] ? $formValues[$tag . '_color'] : 'grey3';
      $opacity_value = $formValues[$tag.'_opacity'];

      $color_inverted_value = $formValues[$tag . '_color_inverted'] ? $formValues[$tag . '_color_inverted'] : 'white';
      $opacity_inverted_value = $formValues[$tag.'_opacity_inverted'];


      $font_size_min_value = $formValues[$tag . '_font_size_min'] ? $formValues[$tag . '_font_size_min'] : '1';
      $font_size_value = $formValues[$tag . '_font_size'] ? $formValues[$tag . '_font_size'] : '1.5';
      $font_size_unit_value = $formValues[$tag . '_font_size_unit'] ? $formValues[$tag . '_font_size_unit'] : 'rem';
      $line_height_value = $formValues[$tag . '_line_height'] ? $formValues[$tag . '_line_height'] : '1.2';
      $font_weight_value = $formValues[$tag . '_font_weight'] ? $formValues[$tag . '_font_weight'] : '400';
      $font_style_value = $formValues[$tag . '_font_style'] ? $formValues[$tag . '_font_style'] : 'normal';

      $margin_top_value = $formValues[$tag . '_margin_top'] ? $formValues[$tag . '_margin_top'] : '0';
      $margin_bottom_value = $formValues[$tag . '_margin_bottom'] ? $formValues[$tag . '_margin_bottom'] : '0.5';
      $margin_unit_value = $formValues[$tag . '_margin_unit'] ? $formValues[$tag . '_margin_unit'] : 'rem';


      $form['typography']['text_elements'][$tag . '_color'] = [
        '#prefix' => '<div class="styling-row"><div class="styling-input"><div class="inline-input-holder"><strong>' . $title . '</strong>',
        '#type' => 'select',
        '#title' => t('Color'),
        '#options' => $arr_colors,
        '#default_value' => $color_value,
      ];

      $form['typography']['text_elements'][$tag . '_opacity'] = [
        '#type' => 'textfield',
        '#title' => t('Opacity'),
        '#default_value' => $opacity_value,
      ];

      $form['typography']['text_elements'][$tag . '_color_inverted'] = [
        '#type' => 'select',
        '#title' => t('Inverted color'),
        '#options' => $arr_colors,
        '#default_value' => $color_inverted_value,
      ];

      $form['typography']['text_elements'][$tag . '_opacity_inverted'] = [
        '#type' => 'textfield',
        '#title' => t('Opacity'),
        '#default_value' => $opacity_inverted_value,
        '#suffix' => '</div>'
      ];

      $form['typography']['text_elements'][$tag . '_font_family'] = [
        '#type' => 'select',
        '#title' => t('Font family'),
        '#options' => $arr_fonts,
        '#default_value' => $font_family_value,
        '#prefix' => '<div class="inline-input-holder">',
      ];

      $form['typography']['text_elements'][$tag . '_font_weight'] = [
        '#type' => 'select',
        '#options' => $arr_fontweights,
        '#title' => t('Font weight'),
        '#default_value' => $font_weight_value,
      ];

      $form['typography']['text_elements'][$tag . '_font_style'] = [
        '#type' => 'select',
        '#options' => $arr_fontstyles,
        '#title' => t('Font style'),
        '#default_value' => $font_style_value,
      ];

      $form['typography']['text_elements'][$tag . '_line_height'] = [
        '#type' => 'textfield',
        '#title' => t('Line height'),
        '#default_value' => $line_height_value,
        '#suffix' => '</div>'
      ];

      $form['typography']['text_elements'][$tag . '_font_size_min_holder'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['container-inline', 'unit-combo-field', 'separator'],
        ],
        '#prefix' => '<div class="inline-input-holder">',
      ];

      $form['typography']['text_elements'][$tag . '_font_size_min_holder'][$tag . '_font_size_min'] = [
        '#type' => 'textfield',
        '#title' => t('Font-size<br/>min'),
        '#default_value' => $font_size_min_value,
      ];

      $form['typography']['text_elements'][$tag . '_font_size_holder'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['container-inline', 'unit-combo-field', 'separator'],
        ]
      ];

      $form['typography']['text_elements'][$tag . '_font_size_holder'][$tag . '_font_size'] = [
        '#type' => 'textfield',
        '#title' => t('<br/>max'),
        '#default_value' => $font_size_value,
      ];

      $form['typography']['text_elements'][$tag . '_font_size_holder'][$tag . '_font_size_unit'] = [
        '#type' => 'select',
        '#options' => $arr_units,
        '#default_value' => $font_size_unit_value,
      ];

      $form['typography']['text_elements'][$tag . '_margin_holder'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['container-inline', 'unit-combo-field', 'multi-valule', 'separator'],
        ],
      ];

      $form['typography']['text_elements'][$tag . '_margin_holder'][$tag . '_margin_top'] = [
        '#title' => t('Margin (top, bottom)'),
        '#type' => 'textfield',
        '#default_value' => $margin_top_value,
      ];

      $form['typography']['text_elements'][$tag . '_margin_holder'][$tag . '_margin_bottom'] = [
        '#type' => 'textfield',
        '#default_value' => $margin_bottom_value,
      ];

      $form['typography']['text_elements'][$tag . '_margin_holder'][$tag . '_margin_unit'] = [
        '#type' => 'textfield',
        '#type' => 'select',
        '#options' => $arr_units,
        '#default_value' => $margin_unit_value,
        '#suffix' => '</div>'
      ];

      $form['typography']['text_elements'][$tag . '_title'] = [
        '#prefix' => '</div><div class="styling-preview">',
        '#suffix' => '</div></div>',
        '#type' => 'item',
        '#markup' => '<p class="' . $tag . '">' . $title . '<br/>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet</p>',
      ];
    }

    $form['typography']['links'] = [
      '#type' => 'details',
      '#title' => t('Links'),
    ];

    $link_color_value = $formValues['link_color'] ? $formValues['link_color'] : 'primary';
    $link_opacity_value = $formValues['link_opacity'];
    $link_color_inverted_value = $formValues['link_color_inverted'] ? $formValues['link_color_inverted'] : 'white';
    $link_opacity_inverted_value = $formValues['link_opacity_inverted'];

    $link_text_decoration_value = $formValues['link_text_decoration'] ? $formValues['link_text_decoration'] : 'underline';

    $link_color_hover_value = $formValues['link_color_hover'] ? $formValues['link_color_hover'] : 'primary';
    $link_opacity_hover_value = $formValues['link_opacity_hover'];
    $link_color_hover_inverted_value = $formValues['link_color_hover_inverted'] ? $formValues['link_color_hover_inverted'] : 'white';
    $link_opacity_hover_inverted_value = $formValues['link_opacity_hover_inverted'];

    $link_text_decoration_hover_value = $formValues['link_text_decoration_hover'] ? $formValues['link_text_decoration_hover'] : 'underline';

    $form['typography']['links']['link_color'] = [
      '#prefix' => '<div class="styling-row"><div class="styling-input"><div class="inline-input-holder separator"><strong>Standard</strong>',
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $link_color_value,
    ];

    $form['typography']['links']['link_opacity'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $link_opacity_value,
    ];

    $form['typography']['links']['link_color_inverted'] = [
      '#type' => 'select',
      '#title' => t('Inverted color'),
      '#options' => $arr_colors,
      '#default_value' => $link_color_inverted_value,
    ];

    $form['typography']['links']['link_opacity_inverted'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $link_opacity_inverted_value,
    ];

    $form['typography']['links']['link_text_decoration'] = [
      '#type' => 'select',
      '#title' => t('Text decoration'),
      '#options' => [
        'underline' => t('Underline'),
        'underline dotted' => t('Dotted'),
        'underline dashed' => t('Dashed'),
        'none' => t('none'),
      ],
      '#default_value' => $link_text_decoration_value,
      '#suffix' => '</div>'
    ];

    $form['typography']['links']['link_color_hover'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $link_color_hover_value,
      '#prefix' => '<div class="inline-input-holder separator"><strong>Hover</strong>',
    ];

    $form['typography']['links']['link_opacity_hover'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $link_opacity_hover_value,
    ];

    $form['typography']['links']['link_color_hover_inverted'] = [
      '#type' => 'select',
      '#title' => t('Inverted color'),
      '#options' => $arr_colors,
      '#default_value' => $link_color_hover_inverted_value,
    ];

    $form['typography']['links']['link_opacity_hover_inverted'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $link_opacity_hover_inverted_value,
    ];

    $form['typography']['links']['link_text_decoration_hover'] = [
      '#type' => 'select',
      '#title' => t('Text decoration'),
      '#options' => [
        'underline' => t('Underline'),
        'underline dotted' => t('Dotted'),
        'underline dashed' => t('Dashed'),
        'none' => t('none'),
      ],
      '#default_value' => $link_text_decoration_hover_value,
      '#suffix' => '</div>'
    ];

    $form['typography']['links']['link_title'] = [
      '#prefix' => '</div><div class="styling-preview">',
      '#suffix' => '</div></div>',
      '#type' => 'item',
      '#markup' => '<p>Links: </p><p><a href="#" class="preview-link-standard">Standard</a></p><p><a href="#" class="preview-link-hover">Hovered</a></p><p>Inline links within text: Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor <a href="#" class="preview-link-inline">invidunt</a> ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd <a href="#" class="preview-link-inline">gubergren, no sea takimata sanctus est</a> Lorem ipsum dolor sit amet</p>',
    ];

    // section navigation
    $form['navigation'] = [
      '#type' => 'details',
      '#title' => t('Navigation'),
      '#summary' => t('Navigation settings'),
      '#group' => 'iq_theme',
    ];

    $form['navigation']['navi_main'] = [
      '#type' => 'details',
      '#title' => t('Navigation Main'),
    ];

    $form['navigation']['navi_main']['navi_main_font_family'] = [
      '#prefix' => '<div class="styling-row"><div class="styling-input"><div class="inline-input-holder"><strong>Base styling</strong>',
      '#type' => 'select',
      '#title' => t('Font family'),
      '#options' => $arr_fonts,
      '#default_value' => $formValues['navi_main_font_family'],
    ];

    $form['navigation']['navi_main']['navi_main_font_weight'] = [
      '#type' => 'select',
      '#options' => $arr_fontweights,
      '#title' => t('Font weight'),
      '#default_value' => $formValues['navi_main_font_weight'],
    ];

    $form['navigation']['navi_main']['navi_main_font_style'] = [
      '#type' => 'select',
      '#options' => $arr_fontstyles,
      '#title' => t('Font style'),
      '#default_value' => $formValues['navi_main_font_style'],
    ];

     $form['navigation']['navi_main']['navi_main_line_height'] = [
      '#type' => 'textfield',
      '#title' => t('Line height'),
      '#default_value' => $formValues['navi_main_line_height'] ? $formValues['navi_main_line_height'] : 2,
      '#suffix' => '</div>'
    ];

    $form['navigation']['navi_main']['navi_main_font_size_holder'] = [
      '#type' => 'container',
      '#prefix' => '<div class="inline-input-holder separator">',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'separator'],
      ]
    ];

    $form['navigation']['navi_main']['navi_main_font_size_holder']['navi_main_font_size'] = [
      '#type' => 'textfield',
      '#title' => t('Font size'),
      '#default_value' => $formValues['navi_main_font_size'] ? $formValues['navi_main_font_size'] : 1,
    ];

    $form['navigation']['navi_main']['navi_main_font_size_holder']['navi_main_font_size_unit'] = [
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['navi_main_font_size_unit'] ? $formValues['navi_main_font_size_unit'] : 'rem',
    ];

    $form['navigation']['navi_main']['navi_main_margin_holder'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule', 'separator'],
      ],
    ];

    $form['navigation']['navi_main']['navi_main_margin_holder']['navi_main_margin_top'] = [
      '#title' => t('Margin (top, left/right, bottom)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['navi_main_margin_top'] ? $formValues['navi_main_margin_top'] : '0',
    ];

    $form['navigation']['navi_main']['navi_main_margin_holder']['navi_main_margin_left_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['navi_main_margin_left_right'] ? $formValues['navi_main_margin_left_right'] : '20',
    ];

    $form['navigation']['navi_main']['navi_main_margin_holder']['navi_main_margin_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['navi_main_margin_bottom'] ? $formValues['navi_main_margin_bottom'] : '0',
    ];

    $form['navigation']['navi_main']['navi_main_margin_holder']['navi_main_margin_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['navi_main_margin_unit'],
    ];

    $form['navigation']['navi_main']['navi_main_padding_holder'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule'],
      ],
      '#suffix' => '</div>'
    ];

    $form['navigation']['navi_main']['navi_main_padding_holder']['navi_main_padding_top'] = [
      '#title' => t('Padding (top, left/right, bottom)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['navi_main_padding_top'] ? $formValues['navi_main_padding_top'] : '5',
    ];

    $form['navigation']['navi_main']['navi_main_padding_holder']['navi_main_padding_left_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['navi_main_padding_left_right'] ? $formValues['navi_main_padding_left_right'] : '5',
    ];

    $form['navigation']['navi_main']['navi_main_padding_holder']['navi_main_padding_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['navi_main_padding_bottom'] ? $formValues['navi_main_padding_bottom'] : '5',
    ];

    $form['navigation']['navi_main']['navi_main_padding_holder']['navi_main_padding_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['navi_main_padding_unit'],
    ];

    $form['navigation']['navi_main']['color_page_background_navbar'] = [
      '#prefix' => '<div class="inline-input-holder"><strong>Standard</strong>',
      '#type' => 'select',
      '#title' => t('Background color '),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background_navbar'] ? $formValues['color_page_background_navbar'] : 'white',
    ];

    $form['navigation']['navi_main']['opacity_page_background_navbar'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_background_navbar'],
    ];

    $form['navigation']['navi_main']['color_page_font_navbar'] = [
      '#type' => 'select',
      '#title' => t('Font color '),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_navbar'] ? $formValues['color_page_font_navbar'] : 'grey3',
    ];

    $form['navigation']['navi_main']['opacity_page_font_navbar'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_font_navbar'],
      '#suffix' => '</div>'
    ];

    $form['navigation']['navi_main']['color_page_background_navbar_hover'] = [
      '#prefix' => '<div class="inline-input-holder"><strong>Navbar hover</strong>',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background_navbar_hover'] ? $formValues['color_page_background_navbar_hover'] : 'primary',
    ];

    $form['navigation']['navi_main']['opacity_page_background_navbar_hover'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_background_navbar_hover'],
    ];

    $form['navigation']['navi_main']['color_page_font_navbar_hover'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_navbar_hover'] ? $formValues['color_page_font_navbar_hover'] : 'white',
    ];

    $form['navigation']['navi_main']['opacity_page_font_navbar_hover'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_font_navbar_hover'],
      '#suffix' => '</div>'
    ];

    $form['navigation']['navi_main']['color_page_background_navbar_active'] = [
      '#prefix' => '<div class="inline-input-holder separator"><strong>Navbar active</strong>',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background_navbar_active'] ? $formValues['color_page_background_navbar_active'] : 'primary',
    ];

    $form['navigation']['navi_main']['opacity_page_background_navbar_active'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_background_navbar_active'],
    ];

    $form['navigation']['navi_main']['color_page_font_navbar_active'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_navbar_active'] ? $formValues['color_page_font_navbar_active'] : 'black',
    ];

    $form['navigation']['navi_main']['opacity_page_font_navbar_active'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_font_navbar_active'],
      '#suffix' => '</div>'
    ];

    $form['navigation']['navi_main']['color_page_background_nav_dropdown'] = [
      '#prefix' => '<div class="inline-input-holder"><strong>Dropdown standard</strong>',
      '#type' => 'select',
      '#title' => t('Background'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background_nav_dropdown'] ? $formValues['color_page_background_nav_dropdown'] : 'primary',
    ];

    $form['navigation']['navi_main']['opacity_page_background_nav_dropdown'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_background_nav_dropdown'],
    ];

    $form['navigation']['navi_main']['color_page_font_nav_dropdown'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_nav_dropdown'] ? $formValues['color_page_font_nav_dropdown'] : 'grey3',
    ];

    $form['navigation']['navi_main']['opacity_page_font_nav_dropdown'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_font_nav_dropdown'],
      '#suffix' => '</div>'
    ];

    $form['navigation']['navi_main']['color_page_background_nav_dropdown_hover'] = [
      '#prefix' => '<div class="inline-input-holder"><strong>Dropdown hover</strong>',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background_nav_dropdown_hover'] ? $formValues['color_page_background_nav_dropdown_hover'] : 'primary',
      '#suffix' => '<div class="line-break"></div>'
    ];

    $form['navigation']['navi_main']['opacity_page_background_nav_dropdown_hover'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_background_nav_dropdown_hover'],
    ];

    $form['navigation']['navi_main']['color_page_font_nav_dropdown_hover'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_nav_dropdown_hover'] ? $formValues['color_page_font_nav_dropdown_hover'] : 'grey3',
    ];

    $form['navigation']['navi_main']['opacity_page_font_nav_dropdown_hover'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_font_nav_dropdown_hover'],
      '#suffix' => '</div>'
    ];

    $form['navigation']['navi_main']['color_page_background_nav_dropdown_active'] = [
      '#prefix' => '<div class="inline-input-holder"><strong>Dropdown active</strong>',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background_nav_dropdown_active'] ? $formValues['color_page_background_nav_dropdown_active'] : 'primary',
      '#suffix' => '<div class="line-break"></div>'
    ];

    $form['navigation']['navi_main']['opacity_page_background_nav_dropdown_active'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_background_nav_dropdown_active'],
    ];

    $form['navigation']['navi_main']['color_page_font_nav_dropdown_active'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_nav_dropdown_active'] ? $formValues['color_page_font_nav_dropdown_active'] : 'grey3',
    ];

    $form['navigation']['navi_main']['opacity_page_font_nav_dropdown_active'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_font_nav_dropdown_active'],
      '#suffix' => '</div>'
    ];

    $form['navigation']['navi_main']['navi_main_title'] = [
      '#prefix' => '</div><div class="styling-preview">',
      '#suffix' => '</div></div>',
      '#type' => 'item',
      '#markup' => '<ul class="clearfix nav navbar-nav">
        <li class="nav-item">
          <a href="#" class="nav-link" >Main Nav 1</a>
        </li>
        <li class="nav-item menu-item--expanded active dropdown">
          <a href="#" class="nav-link active dropdown-toggle" >Main Nav 2 (active)</a>
          <ul class="dropdown-menu">
            <li class="dropdown-item">
              <a href="#" >Nav Dropdown 1</a>
            </li>
            <li class="dropdown-item active">
              <a href="#" class="active is-active" >Nav Dropdown 2 (active)</a>
            </li>
            <li class="dropdown-item">
              <a href="#" >Nav Dropdown 3</a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="/de/raster" class="nav-link" >Main Nav 3</a>
        </li>
        <li class="nav-item">
          <a href="/de/raster" class="nav-link" >Main Nav 4</a>
        </li>
      </ul>',
    ];

    $form['navigation']['navi_mobile'] = [
      '#type' => 'details',
      '#title' => t('Navigation Mobile'),
    ];

    $form['navigation']['navi_mobile']['color_page_background_nav_mobile'] = [
      '#prefix' => '<div class="styling-row"><div class="styling-input"><div class="inline-input-holder separator"><strong>Mobile navigation</strong>',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_background_nav_mobile'] ? $formValues['color_page_background_nav_mobile'] : 'white',
    ];

    $form['navigation']['navi_mobile']['opacity_page_background_nav_mobile'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_background_nav_mobile'],
    ];

    $form['navigation']['navi_mobile']['color_page_font_nav_mobile'] = [
      '#type' => 'select',
      '#title' => t('Font color '),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_nav_mobile'] ? $formValues['color_page_font_nav_mobile'] : 'grey3',
    ];

    $form['navigation']['navi_mobile']['opacity_page_font_nav_mobile'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_font_nav_mobile'],
      '#suffix' => '</div>'
    ];


    $form['navigation']['navi_mobile']['color_page_font_nav_mobile_hover'] = [
      '#prefix' => '<div class="inline-input-holder"><strong>Mobile navigation hover</strong>',
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_nav_mobile_hover'] ? $formValues['color_page_font_nav_mobile_hover'] : 'grey3',
    ];

    $form['navigation']['navi_mobile']['opacity_page_font_nav_mobile_hover'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_font_nav_mobile_hover'],
      '#suffix' => '</div>'
    ];

    $form['navigation']['navi_mobile']['navi_mobile_title'] = [
      '#prefix' => '</div><div class="styling-preview">',
      '#suffix' => '</div></div>',
      '#type' => 'item',
      '#markup' => 'Preview',
    ];

    $form['navigation']['navi_sidebar'] = [
      '#type' => 'details',
      '#title' => t('Navigation Sidebar'),
    ];

    $form['navigation']['navi_sidebar']['color_page_font_nav_sidebar'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_nav_sidebar'] ? $formValues['color_page_font_nav_sidebar'] : 'grey3',
      '#prefix' => '<div class="styling-row"><div class="styling-input"><div class="inline-input-holder separator"><strong>Sidebar standard</strong>',
    ];

    $form['navigation']['navi_sidebar']['opacity_page_font_nav_sidebar'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_font_nav_sidebar'],
      '#suffix' => '</div>'
    ];

    $form['navigation']['navi_sidebar']['color_page_font_nav_sidebar_hover'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_page_font_nav_sidebar_hover'] ? $formValues['color_page_font_nav_sidebar_hover'] : 'grey3',
      '#prefix' => '<div class="inline-input-holder separator"><strong>Sidebar hover</strong>',
    ];

    $form['navigation']['navi_sidebar']['opacity_page_font_nav_sidebar_hover'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_page_font_nav_sidebar_hover'],
      '#suffix' => '</div>'
    ];

    $form['navigation']['navi_sidebar']['navi_sidebar_title'] = [
      '#prefix' => '</div><div class="styling-preview">',
      '#suffix' => '</div></div>',
      '#type' => 'item',
      '#markup' => 'Preview',
    ];

    // section decorations
    $form['decorations'] = [
      '#type' => 'details',
      '#title' => t('Styling'),
      '#summary' => t('Borders, shadows etc.'),
      '#group' => 'iq_theme',
    ];

    $form['decorations']['border'] = [
      '#type' => 'details',
      '#title' => t('Border'),
    ];

    $form['decorations']['border']['border_width_holder'] = [
      '#prefix' => '<div class="styling-row"><div class="styling-input"><div class="inline-input-holder ">',
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'separator'],
      ]
    ];

    $form['decorations']['border']['border_width_holder']['border_width'] = [
      '#type' => 'textfield',
      '#title' => t('Width'),
      '#default_value' => $formValues['border_width'] ? $formValues['border_width'] : '1',
    ];

    $form['decorations']['border']['border_width_holder']['border_width_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['border_width_unit'] ? $formValues['border_width_unit'] : '1',
    ];

    $form['decorations']['border']['border_style'] = [
      '#type' => 'select',
      '#title' => t('Style'),
      '#options' => [
        'dotted' => 'Dotted',
        'dashed' => 'Dashed',
        'solid' => 'Solid',
        'double' => 'Double',
        'groove' => 'Groove',
        'ridge' => 'Ridge',
        'inset' => 'Inset',
        'outset' => 'Outset',
      ],
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#default_value' => $formValues['border_style'] ? $formValues['border_style'] : 'solid',
    ];

    $form['decorations']['border']['border_color'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['border_color'] ? $formValues['border_color'] : 'primary',
    ];

    $form['decorations']['border']['border_opacity'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['border_opacity'],
      '#suffix' => '</div>'
    ];

    $form['decorations']['border']['radius_holder'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field'],
      ]
    ];

    $form['decorations']['border']['radius_holder']['radius'] = [
      '#type' => 'textfield',
      '#title' => t('Border Radius'),
      '#default_value' => $formValues['radius'] ? $formValues['radius'] : '1',
    ];

    $form['decorations']['border']['radius_holder']['radius_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['radius_unit'] ? $formValues['radius_unit'] : '1',
      '#suffix' => '</div></div>'
    ];

    $form['decorations']['margins_paddings'] = [
      '#type' => 'details',
      '#title' => t('Margins & Paddings'),
    ];

    $form['decorations']['margins_paddings']['margin'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule'],
      ],
    ];

    $form['decorations']['margins_paddings']['margin']['margin_top'] = [
      '#title' => t('Margin (Top, right, bottom, left)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['margin_top'] ? $formValues['margin_top'] : '30',
    ];

    $form['decorations']['margins_paddings']['margin']['margin_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['margin_right'] ? $formValues['margin_right'] : '30',
    ];

    $form['decorations']['margins_paddings']['margin']['margin_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['margin_bottom'] ? $formValues['margin_bottom'] : '30',
    ];

    $form['decorations']['margins_paddings']['margin']['margin_left'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['margin_left'] ? $formValues['margin_left'] : '30',
    ];

    $form['decorations']['margins_paddings']['margin']['margin_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['margin_unit'] ? $formValues['margin_unit'] : '1',
    ];

    $form['decorations']['margins_paddings']['padding'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule'],
      ],
    ];

    $form['decorations']['margins_paddings']['padding']['padding_top'] = [
      '#title' => t('Padding (Top, right, bottom, left)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['padding_top'] ? $formValues['padding_top'] : '30',
    ];

    $form['decorations']['margins_paddings']['padding']['padding_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['padding_right'] ? $formValues['padding_right'] : '30',
    ];

    $form['decorations']['margins_paddings']['padding']['padding_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['padding_bottom'] ? $formValues['padding_bottom'] : '30',
    ];

    $form['decorations']['margins_paddings']['padding']['padding_left'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['padding_left'] ? $formValues['padding_left'] : '30',
    ];

    $form['decorations']['margins_paddings']['padding']['padding_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['padding_unit'] ? $formValues['padding_unit'] : '1',
    ];

    // section buttons
    $form['buttons'] = [
      '#type' => 'details',
      '#title' => t('Buttons'),
      '#summary' => t('Styling of buttons.'),
      '#group' => 'iq_theme',
    ];

    $form['buttons']['default'] = [
      '#type' => 'details',
      '#title' => t('Default Button'),
    ];

    $form['buttons']['default']['button_font_family'] = [
      '#prefix' => '<div class="styling-row"><div class="styling-input"><div class="inline-input-holder"><strong>Main button styling</strong>',
      '#type' => 'select',
      '#title' => t('Font family'),
      '#options' => $arr_fonts,
      '#default_value' => $formValues['button_font_family'],
    ];


    $form['buttons']['default']['button_font_weight'] = [
      '#type' => 'select',
      '#options' => $arr_fontweights,
      '#title' => t('Font weight'),
      '#default_value' => $formValues['button_font_weight'],
    ];

    $form['buttons']['default']['button_font_style'] = [
      '#type' => 'select',
      '#options' => $arr_fontstyles,
      '#title' => t('Font style'),
      '#default_value' => $formValues['button_font_style'],
    ];

    $form['buttons']['default']['button_line_height'] = [
      '#type' => 'textfield',
      '#title' => t('Line height'),
      '#default_value' => $formValues['button_line_height'] ? $formValues['button_line_height'] : '1.2',
      '#suffix' => '</div>'
    ];

    $form['buttons']['default']['button_font_size_holder'] = [
      '#type' => 'container',
      '#prefix' => '<div class="inline-input-holder">',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'separator'],
      ]
    ];

    $form['buttons']['default']['button_font_size_holder']['button_font_size'] = [
      '#type' => 'textfield',
      '#title' => t('Font size'),
      '#default_value' => $formValues['button_font_size'] ? $formValues['button_font_size'] : '1',
    ];

    $form['buttons']['default']['button_font_size_holder']['button_font_size_unit'] = [
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['button_font_size_unit'] ? $formValues['button_font_size_unit'] : 'rem',
    ];

    $form['buttons']['default']['button_border_width_holder'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule', 'separator'],
      ],
    ];

    $form['buttons']['default']['button_border_width_holder']['button_border_width_top'] = [
      '#title' => t('Border (top, right, bottom, left)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['button_border_width_top'] ? $formValues['button_border_width_top'] : '1',
    ];


    $form['buttons']['default']['button_border_width_holder']['button_border_width_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_border_width_right'] ? $formValues['button_border_width_right'] : '1',
    ];

    $form['buttons']['default']['button_border_width_holder']['button_border_width_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_border_width_bottom'] ? $formValues['button_border_width_bottom'] : '1',
    ];

    $form['buttons']['default']['button_border_width_holder']['button_border_width_left'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_border_width_left'] ? $formValues['button_border_width_left'] : '1',
    ];

    $form['buttons']['default']['button_border_width_holder']['button_border_width_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['button_border_width_unit'] ? $formValues['button_border_width_unit'] : '1',
    ];

    $form['buttons']['default']['button_border_radius_holder'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'separator'],
      ]
    ];

    $form['buttons']['default']['button_border_radius_holder']['button_border_radius'] = [
      '#type' => 'textfield',
      '#title' => t('Border radius'),
      '#default_value' => $formValues['button_border_radius'] ? $formValues['button_border_radius'] : '0',
    ];

    $form['buttons']['default']['button_border_radius_holder']['button_border_radius_unit'] = [
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['button_border_radius_unit'],
      '#suffix' => '</div>',
    ];

    $form['buttons']['default']['button_margin_holder'] = [
      '#type' => 'container',
      '#prefix' => '<div class="inline-input-holder">',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule', 'separator'],
      ],
    ];

    $form['buttons']['default']['button_margin_holder']['button_margin_top'] = [
      '#title' => t('Margin (top, right, bottom, left)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['button_margin_top'] ? $formValues['button_margin_top'] : '0',
    ];

    $form['buttons']['default']['button_margin_holder']['button_margin_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_margin_right'] ? $formValues['button_margin_right'] : '0',
    ];

    $form['buttons']['default']['button_margin_holder']['button_margin_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_margin_bottom'] ? $formValues['button_margin_bottom'] : '0',
    ];

    $form['buttons']['default']['button_margin_holder']['button_margin_left'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_margin_left'] ? $formValues['button_margin_left'] : '0',
    ];


    $form['buttons']['default']['button_margin_holder']['button_margin_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['button_margin_unit'],
    ];


    $form['buttons']['default']['button_padding_holder'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule'],
      ],
      '#suffix' => '</div>'
    ];

    $form['buttons']['default']['button_padding_holder']['button_padding_top'] = [
      '#title' => t('Padding (top, right, bottom, left)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['button_padding_top'] ? $formValues['button_padding_top'] : '5',
    ];

    $form['buttons']['default']['button_padding_holder']['button_padding_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_padding_right'] ? $formValues['button_padding_right'] : '5',
    ];

    $form['buttons']['default']['button_padding_holder']['button_padding_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_padding_bottom'] ? $formValues['button_padding_bottom'] : '5',
    ];

    $form['buttons']['default']['button_padding_holder']['button_padding_left'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_padding_left'] ? $formValues['button_padding_left'] : '5',
    ];


    $form['buttons']['default']['button_padding_holder']['button_padding_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['button_padding_unit'],
    ];



    $form['buttons']['default']['standard'] = [
      '#type' => 'details',
      '#title' => t('Standard color settings'),
    ];

    $form['buttons']['default']['standard']['button_font_color_standard'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_font_color_standard'] ? $formValues['button_font_color_standard'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>Standard</strong>',
    ];

    $form['buttons']['default']['standard']['button_font_opacity_standard'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_font_opacity_standard'],
      '#suffix' => '</div>'
    ];


    $form['buttons']['default']['standard']['button_background_color_standard'] = [
      '#prefix' => '<div class="inline-input-holder separator">',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_background_color_standard'] ? $formValues['button_background_color_standard'] : 'primary',
    ];

    $form['buttons']['default']['standard']['button_background_opacity_standard'] = [
      '#type' => 'textfield',
      '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_background_opacity_standard'],
    ];

    $form['buttons']['default']['standard']['button_border_color_standard'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_border_color_standard'] ? $formValues['button_background_color_standard'] : 'primary',
    ];

    $form['buttons']['default']['standard']['button_border_opacity_standard'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_border_opacity_standard'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['default']['standard']['button_font_color_hover'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_font_color_hover'] ? $formValues['button_font_color_hover'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>Hover</strong>',
    ];

    $form['buttons']['default']['standard']['button_font_opacity_hover'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_font_opacity_hover'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['default']['standard']['button_background_color_hover'] = [
      '#prefix' => '<div class="inline-input-holder separator">',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_background_color_hover'],
    ];

    $form['buttons']['default']['standard']['button_background_opacity_hover'] = [
      '#type' => 'textfield',
      '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_background_opacity_hover'],
    ];

    $form['buttons']['default']['standard']['button_border_color_hover'] = [
      '#type' => 'select',
      '#title' => t('Hover border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_border_color_hover'],
    ];

    $form['buttons']['default']['standard']['button_border_opacity_hover'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_border_opacity_hover'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['default']['standard']['button_font_color_active'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_font_color_active'],
      '#prefix' => '<div class="inline-input-holder"><strong>Active</strong>',
    ];

    $form['buttons']['default']['standard']['button_font_opacity_active'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_font_opacity_active'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['default']['standard']['button_background_color_active'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_background_color_active'],
      '#prefix' => '<div class="inline-input-holder separator">',
    ];

    $form['buttons']['default']['standard']['button_background_opacity_active'] = [
      '#type' => 'textfield',
      '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_background_opacity_active'],
    ];

    $form['buttons']['default']['standard']['button_border_color_active'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_border_color_active'],
    ];

    $form['buttons']['default']['standard']['button_border_opacity_active'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_border_opacity_active'],
      '#suffix' => '</div>'
    ];





    $form['buttons']['default']['inverted'] = [
      '#type' => 'details',
      '#title' => t('Inverted color settings'),
    ];

    $form['buttons']['default']['inverted']['button_font_color_inverted'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_font_color_inverted'] ? $formValues['button_font_color_inverted'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>Standard</strong>',
    ];

    $form['buttons']['default']['inverted']['button_font_opacity_inverted'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_font_opacity_inverted'],
      '#suffix' => '</div>'
    ];


    $form['buttons']['default']['inverted']['button_background_color_inverted'] = [
      '#prefix' => '<div class="inline-input-holder separator">',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_background_color_inverted'] ? $formValues['button_background_color_inverted'] : 'primary',
    ];

    $form['buttons']['default']['inverted']['button_background_opacity_inverted'] = [
      '#type' => 'textfield',
      '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_background_opacity_inverted'],
    ];

    $form['buttons']['default']['inverted']['button_border_color_inverted'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_border_color_inverted'] ? $formValues['button_background_color_inverted'] : 'primary',
    ];

    $form['buttons']['default']['inverted']['button_border_opacity_inverted'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_border_opacity_inverted'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['default']['inverted']['button_font_color_inverted_hover'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_font_color_inverted_hover'] ? $formValues['button_font_color_inverted_hover'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>Hover</strong>',
    ];

    $form['buttons']['default']['inverted']['button_font_opacity_inverted_hover'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_font_opacity_inverted_hover'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['default']['inverted']['button_background_color_inverted_hover'] = [
      '#prefix' => '<div class="inline-input-holder separator">',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_background_color_inverted_hover'],
    ];

    $form['buttons']['default']['inverted']['button_background_opacity_inverted_hover'] = [
      '#type' => 'textfield',
      '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_background_opacity_inverted_hover'],
    ];

    $form['buttons']['default']['inverted']['button_border_color_inverted_hover'] = [
      '#type' => 'select',
      '#title' => t('Hover border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_border_color_inverted_hover'],
    ];

    $form['buttons']['default']['inverted']['button_border_opacity_inverted_hover'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_border_opacity_inverted_hover'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['default']['inverted']['button_font_color_inverted_active'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_font_color_inverted_active'],
      '#prefix' => '<div class="inline-input-holder"><strong>Active</strong>',
    ];

    $form['buttons']['default']['inverted']['button_font_opacity_inverted_active'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_font_opacity_inverted_active'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['default']['inverted']['button_background_color_inverted_active'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_background_color_inverted_active'],
      '#prefix' => '<div class="inline-input-holder separator">',
    ];

    $form['buttons']['default']['inverted']['button_background_opacity_inverted_active'] = [
      '#type' => 'textfield',
      '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_background_opacity_inverted_active'],
    ];

    $form['buttons']['default']['inverted']['button_border_color_inverted_active'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_border_color_inverted_active'],
    ];

    $form['buttons']['default']['inverted']['button_border_opacity_inverted_active'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_border_opacity_inverted_active'],
      '#suffix' => '</div>'
    ];


    $form['buttons']['default']['button_title'] = [
      '#prefix' => '</div><div class="styling-preview">',
      '#suffix' => '</div></div>',
      '#type' => 'item',
      '#markup' => '<div class="preview-button standard">Standard</div><div class="preview-button hover">Hover</div><div class="preview-button active">Active</div><p>Buttons within text: Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor <a href="#" class="preview-button inline">invidunt</a> ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. <br/><a href="#" class="preview-button inline">gubergren, no sea takimata sanctus est</a> <br/>Lorem ipsum dolor sit amet</p>
      <div class="preview-inverted"><div class="preview-button standard">Standard</div><div class="preview-button hover">Hover</div><div class="preview-button active">Active</div><p>Buttons within text: Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor <a href="#" class="preview-button inline">invidunt</a> ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. <br/><a href="#" class="preview-button inline">gubergren, no sea takimata sanctus est</a> <br/>Lorem ipsum dolor sit amet</p></div>
      ',
    ];

    // section alternate button
    $form['buttons']['alternate'] = [
      '#type' => 'details',
      '#title' => t('Alterante Button'),
    ];

    $form['buttons']['alternate']['button_alternate_font_family'] = [
      '#prefix' => '<div class="styling-row"><div class="styling-input"><div class="inline-input-holder"><strong>Main button styling</strong>',
      '#type' => 'select',
      '#title' => t('Font family'),
      '#options' => $arr_fonts,
      '#default_value' => $formValues['button_alternate_font_family'],
    ];


    $form['buttons']['alternate']['button_alternate_font_weight'] = [
      '#type' => 'select',
      '#options' => $arr_fontweights,
      '#title' => t('Font weight'),
      '#default_value' => $formValues['button_alternate_font_weight'],
    ];

    $form['buttons']['alternate']['button_alternate_font_style'] = [
      '#type' => 'select',
      '#options' => $arr_fontstyles,
      '#title' => t('Font style'),
      '#default_value' => $formValues['button_alternate_font_style'],
    ];

    $form['buttons']['alternate']['button_alternate_line_height'] = [
      '#type' => 'textfield',
      '#title' => t('Line height'),
      '#default_value' => $formValues['button_alternate_line_height'] ? $formValues['button_alternate_line_height'] : '1,2',
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['button_alternate_font_size_holder'] = [
      '#type' => 'container',
      '#prefix' => '<div class="inline-input-holder">',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'separator'],
      ]
    ];

    $form['buttons']['alternate']['button_alternate_font_size_holder']['button_alternate_font_size'] = [
      '#type' => 'textfield',
      '#title' => t('Font size'),
      '#default_value' => $formValues['button_alternate_font_size'] ? $formValues['button_alternate_font_size'] : '1',
    ];

    $form['buttons']['alternate']['button_alternate_font_size_holder']['button_alternate_font_size_unit'] = [
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['button_alternate_font_size_unit'] ? $formValues['button_alternate_font_size_unit'] : 'rem',
    ];

    $form['buttons']['alternate']['button_alternate_border_width_holder'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule', 'separator'],
      ],
    ];

    $form['buttons']['alternate']['button_alternate_border_width_holder']['button_alternate_border_width_top'] = [
      '#title' => t('Border (top, right, bottom, left)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_border_width_top'] ? $formValues['button_alternate_border_width_top'] : '1',
    ];

    $form['buttons']['alternate']['button_alternate_border_width_holder']['button_alternate_border_width_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_border_width_right'] ? $formValues['button_alternate_border_width_right'] : '1',
    ];

    $form['buttons']['alternate']['button_alternate_border_width_holder']['button_alternate_border_width_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_border_width_bottom'] ? $formValues['button_alternate_border_width_bottom'] : '1',
    ];

    $form['buttons']['alternate']['button_alternate_border_width_holder']['button_alternate_border_width_left'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_border_width_left'] ? $formValues['button_alternate_border_width_left'] : '1',
    ];

    $form['buttons']['alternate']['button_alternate_border_width_holder']['button_alternate_border_width_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['button_alternate_border_width_unit'],
    ];

    $form['buttons']['alternate']['button_alternate_border_radius_holder'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'separator'],
      ]
    ];

    $form['buttons']['alternate']['button_alternate_border_radius_holder']['button_alternate_border_radius'] = [
      '#type' => 'textfield',
      '#title' => t('Border radius'),
      '#default_value' => $formValues['button_alternate_border_radius'] ? $formValues['button_alternate_border_radius'] : '0',
    ];

    $form['buttons']['alternate']['button_alternate_border_radius_holder']['button_alternate_border_radius_unit'] = [
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['button_alternate_border_radius_unit'],
      '#suffix' => '</div>',
    ];

    $form['buttons']['alternate']['button_alternate_margin_holder'] = [
      '#type' => 'container',
      '#prefix' => '<div class="inline-input-holder">',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule', 'separator'],
      ],
    ];

    $form['buttons']['alternate']['button_alternate_margin_holder']['button_alternate_margin_top'] = [
      '#title' => t('Margin (top, right, bottom, left)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_margin_top'] ? $formValues['button_alternate_margin_top'] : '0',
    ];

    $form['buttons']['alternate']['button_alternate_margin_holder']['button_alternate_margin_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_margin_right'] ? $formValues['button_alternate_margin_right'] : '0',
    ];

    $form['buttons']['alternate']['button_alternate_margin_holder']['button_alternate_margin_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_margin_bottom'] ? $formValues['button_alternate_margin_bottom'] : '0',
    ];

    $form['buttons']['alternate']['button_alternate_margin_holder']['button_alternate_margin_left'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_margin_left'] ? $formValues['button_alternate_margin_left'] : '0',
    ];


    $form['buttons']['alternate']['button_alternate_margin_holder']['button_alternate_margin_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['button_alternate_margin_unit'],
    ];


    $form['buttons']['alternate']['button_alternate_padding_holder'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule'],
      ],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['button_alternate_padding_holder']['button_alternate_padding_top'] = [
      '#title' => t('Padding (top, right, bottom, left)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_padding_top'] ? $formValues['button_alternate_padding_top'] : '5',
    ];

    $form['buttons']['alternate']['button_alternate_padding_holder']['button_alternate_padding_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_padding_right'] ? $formValues['button_alternate_padding_right'] : '5',
    ];

    $form['buttons']['alternate']['button_alternate_padding_holder']['button_alternate_padding_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_padding_bottom'] ? $formValues['button_alternate_padding_bottom'] : '5',
    ];

    $form['buttons']['alternate']['button_alternate_padding_holder']['button_alternate_padding_left'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['button_alternate_padding_left'] ? $formValues['button_alternate_padding_left'] : '5',
    ];


    $form['buttons']['alternate']['button_alternate_padding_holder']['button_alternate_padding_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['button_alternate_padding_unit'],
    ];

    $form['buttons']['alternate']['standard'] = [
      '#type' => 'details',
      '#title' => t('Standard color settings'),
    ];

    $form['buttons']['alternate']['standard']['button_alternate_font_color_standard'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_font_color_standard'] ? $formValues['button_alternate_font_color_standard'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>Standard</strong>',
    ];

    $form['buttons']['alternate']['standard']['button_alternate_font_opacity_standard'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_font_opacity_standard'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['standard']['button_alternate_background_color_standard'] = [
      '#prefix' => '<div class="inline-input-holder separator">',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_background_color_standard'] ? $formValues['button_alternate_background_color_standard'] : 'primary',
    ];

    $form['buttons']['alternate']['standard']['button_alternate_background_opacity_standard'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_background_opacity_standard'],
    ];

    $form['buttons']['alternate']['standard']['button_alternate_border_color_standard'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_border_color_standard'] ? $formValues['button_alternate_background_color_standard'] : 'primary',
    ];

    $form['buttons']['alternate']['standard']['button_alternate_border_opacity_standard'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_border_opacity_standard'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['standard']['button_alternate_font_color_hover'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_font_color_hover'] ? $formValues['button_alternate_font_color_hover'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>Hover</strong>',
    ];

    $form['buttons']['alternate']['standard']['button_alternate_font_opacity_hover'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_font_opacity_hover'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['standard']['button_alternate_background_color_hover'] = [
      '#prefix' => '<div class="inline-input-holder separator">',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_background_color_hover'],
    ];

    $form['buttons']['alternate']['standard']['button_alternate_background_opacity_hover'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_background_opacity_hover'],
    ];

    $form['buttons']['alternate']['standard']['button_alternate_border_color_hover'] = [
      '#type' => 'select',
      '#title' => t('Hover border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_border_color_hover'],
    ];

    $form['buttons']['alternate']['standard']['button_alternate_border_opacity_hover'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_border_opacity_hover'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['standard']['button_alternate_font_color_active'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_font_color_active'],
      '#prefix' => '<div class="inline-input-holder"><strong>Active</strong>',
    ];

    $form['buttons']['alternate']['standard']['button_alternate_font_opacity_active'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_font_opacity_active'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['standard']['button_alternate_background_color_active'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_background_color_active'],
      '#prefix' => '<div class="inline-input-holder separator">',
    ];

    $form['buttons']['alternate']['standard']['button_alternate_background_opacity_active'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_background_opacity_active'],
    ];

    $form['buttons']['alternate']['standard']['button_alternate_border_color_active'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_border_color_active'],
    ];

    $form['buttons']['alternate']['standard']['button_alternate_border_opacity_active'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_border_opacity_active'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['inverted'] = [
      '#type' => 'details',
      '#title' => t('Inverted color settings'),
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_font_color_inverted'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_font_color_inverted'] ? $formValues['button_alternate_font_color_inverted'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>inverted</strong>',
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_font_opacity_inverted'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_font_opacity_inverted'],
      '#suffix' => '</div>'
    ];


    $form['buttons']['alternate']['inverted']['button_alternate_background_color_inverted'] = [
      '#prefix' => '<div class="inline-input-holder separator">',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_background_color_inverted'] ? $formValues['button_alternate_background_color_inverted'] : 'primary',
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_background_opacity_inverted'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_background_opacity_inverted'],
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_border_color_inverted'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_border_color_inverted'] ? $formValues['button_alternate_background_color_inverted'] : 'primary',
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_border_opacity_inverted'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_border_opacity_inverted'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_font_color_inverted_hover'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_font_color_inverted_hover'] ? $formValues['button_alternate_font_color_inverted_hover'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>Hover</strong>',
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_font_opacity_inverted_hover'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_font_opacity_inverted_hover'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_background_color_inverted_hover'] = [
      '#prefix' => '<div class="inline-input-holder separator">',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_background_color_inverted_hover'],
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_background_opacity_inverted_hover'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_background_opacity_inverted_hover'],
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_border_color_inverted_hover'] = [
      '#type' => 'select',
      '#title' => t('Hover border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_border_color_inverted_hover'],
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_border_opacity_inverted_hover'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_border_opacity_inverted_hover'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_font_color_inverted_active'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_font_color_inverted_active'],
      '#prefix' => '<div class="inline-input-holder"><strong>Active</strong>',
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_font_opacity_inverted_active'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_font_opacity_inverted_active'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_background_color_inverted_active'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_background_color_inverted_active'],
      '#prefix' => '<div class="inline-input-holder separator">',
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_background_opacity_inverted_active'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_background_opacity_inverted_active'],
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_border_color_inverted_active'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['button_alternate_border_color_inverted_active'],
    ];

    $form['buttons']['alternate']['inverted']['button_alternate_border_opacity_inverted_active'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['button_alternate_border_opacity_inverted_active'],
      '#suffix' => '</div>'
    ];

    $form['buttons']['alternate']['button_alternate_title'] = [
      '#prefix' => '</div><div class="styling-preview button-alternate">',
      '#suffix' => '</div></div>',
      '#type' => 'item',
      '#markup' => '<div class="preview-button  standard">Standard</div><div class="preview-button hover">Hover</div><div class="preview-button active">Active</div><p>Buttons within text: Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor <a href="#" class="preview-button inline">invidunt</a> ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. <br/><a href="#" class="preview-button inline">gubergren, no sea takimata sanctus est</a> <br/>Lorem ipsum dolor sit amet</p>
      <div class="preview-inverted"><div class="preview-button standard">Standard</div><div class="preview-button hover">Hover</div><div class="preview-button active">Active</div><p>Buttons within text: Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor <a href="#" class="preview-button inline">invidunt</a> ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. <br/><a href="#" class="preview-button inline">gubergren, no sea takimata sanctus est</a> <br/>Lorem ipsum dolor sit amet</p></div>
      ',
    ];

    // section patterns

    $form['patterns'] = [
      '#type' => 'details',
      '#title' => t('Patterns'),
      '#summary' => t('Styling of patterns.'),
      '#group' => 'iq_theme',
    ];



    $form['patterns']['slider'] = [
      '#type' => 'details',
      '#title' => t('Slider'),
    ];

    $form['patterns']['slider']['slider_arrow_size_holder'] = [
      '#prefix' => '<div class="styling-row"><div class="styling-input full"><div class="inline-input-holder "><strong>Arrows</strong>',
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'separator'],
      ]
    ];

    $form['patterns']['slider']['slider_arrow_size_holder']['slider_arrow_size'] = [
      '#type' => 'textfield',
      '#title' => t('Size'),
      '#default_value' => $formValues['slider_arrow_size'] ? $formValues['slider_arrow_size'] : '1',
    ];

    $form['patterns']['slider']['slider_arrow_size_holder']['slider_arrow_size_unit'] = [
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['slider_arrow_size_unit'] ? $formValues['slider_arrow_size_unit'] : 'rem',
    ];

    $form['patterns']['slider']['slider_arrow_color'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['slider_arrow_color'] ? $formValues['slider_arrow_color'] : 'grey3',
    ];

    $form['patterns']['slider']['slider_arrow_opacity'] = [
      '#type' => 'textfield',
      '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
        'class' => [ 'separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['slider_arrow_opacity'],
    ];

    $form['patterns']['slider']['slider_arrow_backgroundcolor'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['slider_arrow_backgroundcolor'] ? $formValues['slider_arrow_backgroundcolor'] : 'white',
    ];

    $form['patterns']['slider']['slider_arrow_background_opacity'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['slider_arrow_background_opacity'],
      '#suffix' => '</div>'
    ];




    $form['patterns']['slider']['slider_dot_size_holder'] = [
      '#type' => 'container',
      '#prefix' => '<div class="inline-input-holder "><strong>Dots</strong>',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'separator'],
      ]
    ];

    $form['patterns']['slider']['slider_dot_size_holder']['slider_dot_size'] = [
      '#type' => 'textfield',
      '#title' => t('Size'),
      '#default_value' => $formValues['slider_dot_size'] ? $formValues['slider_dot_size'] : '1',
    ];

    $form['patterns']['slider']['slider_dot_size_holder']['slider_dot_size_unit'] = [
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['slider_dot_size_unit'] ? $formValues['slider_dot_size_unit'] : 'rem',
    ];

    $form['patterns']['slider']['slider_dot_color'] = [
      '#type' => 'select',
      '#title' => t('Colo standard'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['slider_dot_color'] ? $formValues['slider_dot_color'] : 'grey3',
    ];

    $form['patterns']['slider']['slider_dot_opacity'] = [
      '#type' => 'textfield',
      '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
        'class' => [ 'separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['slider_dot_opacity'],
    ];

    $form['patterns']['slider']['slider_dot_color_active'] = [
      '#type' => 'select',
      '#title' => t('Color active'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['slider_dot_color_active'] ? $formValues['slider_dot_color_active'] : 'grey1',
    ];

    $form['patterns']['slider']['slider_dot_opacity_active'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['slider_dot_opacity_active'],
      '#suffix' => '</div></div></div>'
    ];



    $form['patterns']['quote'] = [
      '#type' => 'details',
      '#title' => t('Quote'),
    ];

    $form['patterns']['quote']['quote_highlight_color'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['quote_highlight_color'] ? $formValues['quote_highlight_color'] : 'grey3',
      '#prefix' => '<div class="styling-row"><div class="styling-input full"><div class="inline-input-holder "><strong>Highlights (quote marks / lines)</strong>',
    ];

    $form['patterns']['quote']['quote_highlight_opacity'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['quote_highlight_opacity'],
      '#suffix' => '</div></div></div>'
    ];



    $form['patterns']['icons'] = [
      '#type' => 'details',
      '#title' => t('Icons'),
    ];


    $form['patterns']['icons']['default'] = [
      '#type' => 'details',
      '#title' => t('Default icon'),
    ];



    $form['patterns']['icons']['default']['color_icons'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons'] ? $formValues['color_icons'] : 'primary',
      '#prefix' => '<div class="styling-row"><div class="styling-input full"><div class="inline-input-holder "><strong>Standard</strong>',
    ];

    $form['patterns']['icons']['default']['opacity_icons'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['icons']['default']['color_icons_hover'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons_hover'] ? $formValues['color_icons_hover'] : 'primary',
      '#prefix' => '<div class="inline-input-holder "><strong>Hover</strong>',
    ];

    $form['patterns']['icons']['default']['opacity_icons_hover'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons_hover'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['icons']['default']['color_icons_active'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons_active'] ? $formValues['color_icons_active'] : 'primary',
      '#prefix' => '<div class="inline-input-holder separator"><strong>Active</strong>',
    ];

    $form['patterns']['icons']['default']['opacity_icons_active'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons_active'],
      '#suffix' => '</div>'
    ];







    $form['patterns']['icons']['default']['color_icons_inverted'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons_inverted'] ? $formValues['color_icons_inverted'] : 'primary',
      '#prefix' => '<div class="styling-row"><div class="styling-input full"><div class="inline-input-holder "><strong>Inverted</strong>',
    ];

    $form['patterns']['icons']['default']['opacity_icons_inverted'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons_inverted'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['icons']['default']['color_icons_inverted_hover'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons_inverted_hover'] ? $formValues['color_icons_inverted_hover'] : 'primary',
      '#prefix' => '<div class="inline-input-holder "><strong>Inverted hover</strong>',
    ];

    $form['patterns']['icons']['default']['opacity_icons_inverted_hover'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons_inverted_hover'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['icons']['default']['color_icons_inverted_active'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons_inverted_active'] ? $formValues['color_icons_inverted_active'] : 'primary',
      '#prefix' => '<div class="inline-input-holder"><strong>Inverted active</strong>',
    ];

    $form['patterns']['icons']['default']['opacity_icons_inverted_active'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons_inverted_active'],
      '#suffix' => '</div></div></div>'
    ];







    $form['patterns']['icons']['alternate'] = [
      '#type' => 'details',
      '#title' => t('Alternate icon'),
    ];




    $form['patterns']['icons']['alternate']['color_icons_alternate'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons_alternate'] ? $formValues['color_icons_alternate'] : 'primary',
      '#prefix' => '<div class="styling-row"><div class="styling-input full"><div class="inline-input-holder "><strong>Standard</strong>',
    ];

    $form['patterns']['icons']['alternate']['opacity_icons_alternate'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons_alternate'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['icons']['alternate']['color_icons_alternate_hover'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons_alternate_hover'] ? $formValues['color_icons_alternate_hover'] : 'primary',
      '#prefix' => '<div class="inline-input-holder "><strong>Hover</strong>',
    ];

    $form['patterns']['icons']['alternate']['opacity_icons_alternate_hover'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons_alternate_hover'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['icons']['alternate']['color_icons_alternate_active'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons_alternate_active'] ? $formValues['color_icons_alternate_active'] : 'primary',
      '#prefix' => '<div class="inline-input-holder separator"><strong>Active</strong>',
    ];

    $form['patterns']['icons']['alternate']['opacity_icons_alternate_active'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons_alternate_active'],
      '#suffix' => '</div>'
    ];







    $form['patterns']['icons']['alternate']['color_icons_alternate_inverted'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons_alternate_inverted'] ? $formValues['color_icons_alternate_inverted'] : 'primary',
      '#prefix' => '<div class="styling-row"><div class="styling-input full"><div class="inline-input-holder "><strong>Inverted</strong>',
    ];

    $form['patterns']['icons']['alternate']['opacity_icons_alternate_inverted'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons_alternate_inverted'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['icons']['alternate']['color_icons_alternate_inverted_hover'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons_alternate_inverted_hover'] ? $formValues['color_icons_alternate_inverted_hover'] : 'primary',
      '#prefix' => '<div class="inline-input-holder "><strong>Inverted hover</strong>',
    ];

    $form['patterns']['icons']['alternate']['opacity_icons_alternate_inverted_hover'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons_alternate_inverted_hover'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['icons']['alternate']['color_icons_alternate_inverted_active'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['color_icons_alternate_inverted_active'] ? $formValues['color_icons_alternate_inverted_active'] : 'primary',
      '#prefix' => '<div class="inline-input-holder"><strong>Inverted active</strong>',
    ];

    $form['patterns']['icons']['alternate']['opacity_icons_alternate_inverted_active'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['opacity_icons_alternate_inverted_active'],
      '#suffix' => '</div></div></div>'
    ];

















    $form['patterns']['social'] = [
      '#type' => 'details',
      '#title' => t('Social'),
    ];

    $form['patterns']['social']['social_icon_color'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['social_icon_color'] ? $formValues['social_icon_color'] : 'grey3',
      '#prefix' => '<div class="styling-row"><div class="styling-input full"><div class="inline-input-holder "><strong>Social icons</strong>',
    ];

    $form['patterns']['social']['social_icon_opacity'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['social_icon_opacity'],
      '#suffix' => '</div></div></div>'
    ];



    $form['patterns']['tabs'] = [
      '#type' => 'details',
      '#title' => t('Tabbed content'),
    ];

    $form['patterns']['tabs']['tab_color'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['tab_color'] ? $formValues['tab_color'] : 'primary',
      '#prefix' => '<div class="styling-row"><div class="styling-input full"><div class="inline-input-holder "><strong>Tab standard</strong>',
    ];


    $form['patterns']['tabs']['tab_opacity'] = [
      '#type' => 'textfield',
      '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
        'class' => [ 'separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['tab_opacity'],
    ];

    $form['patterns']['tabs']['tab_font_color'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['tab_font_color'] ? $formValues['tab_font_color'] : 'grey1',
    ];

    $form['patterns']['tabs']['tab_font_opacity'] = [
      '#type' => 'textfield',
      // '#attributes' => [
      //   'min' => 0,
      //   'max' => 1,
      //   'step' => 0.01,
      // ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['tab_font_opacity'],
      '#suffix' => '</div>'
    ];





    $form['patterns']['tabs']['tab_color_active'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['tab_color_active'] ? $formValues['tab_color_active'] : 'grey3',
      '#prefix' => '<div class="inline-input-holder "><strong>Tab Active</strong>',
    ];

    $form['patterns']['tabs']['tab_opacity_active'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => [ 'separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['tab_opacity_active'],
    ];

    $form['patterns']['tabs']['tab_font_color_active'] = [
      '#type' => 'select',
      '#title' => t('Tab font color active'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['tab_font_color_active'] ? $formValues['tab_font_color_active'] : 'grey1',
    ];

    $form['patterns']['tabs']['tab_font_opacity_active'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['tab_font_opacity_active'],
      '#suffix' => '</div></div></div>'
    ];

    $form['patterns']['toggler'] = [
      '#type' => 'details',
      '#title' => t('Toggler'),
    ];

    $form['patterns']['toggler']['toggler_color'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['toggler_color'] ? $formValues['toggler_color'] : 'grey3',
      '#prefix' => '<div class="styling-row"><div class="styling-input full"><div class="inline-input-holder "><strong>Standard</strong>',
    ];

    $form['patterns']['toggler']['toggler_opacity'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['toggler_opacity'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['toggler']['toggler_color_hover'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['toggler_color_hover'] ? $formValues['toggler_color_hover'] : 'grey3',
      '#prefix' => '<div class="styling-row"><div class="styling-input full"><div class="inline-input-holder "><strong>Hover</strong>',
    ];

    $form['patterns']['toggler']['toggler_opacity_hover'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['toggler_opacity_hover'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['toggler']['toggler_color_active'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['toggler_color_active'] ? $formValues['toggler_color_active'] : 'grey3',
      '#prefix' => '<div class="styling-row"><div class="styling-input full"><div class="inline-input-holder "><strong>Active</strong>',
    ];

    $form['patterns']['toggler']['toggler_opacity_active'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['toggler_opacity_active'],
      '#suffix' => '</div></div></div>'
    ];

    // section anchornavitation
    $form['patterns']['anchornavigation'] = [
      '#type' => 'details',
      '#title' => t('Anchor navigation'),
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_family'] = [
      '#prefix' => '<div class="styling-row"><div class="styling-input"><div class="inline-input-holder"><strong>Main styling</strong>',
      '#type' => 'select',
      '#title' => t('Font family'),
      '#options' => $arr_fonts,
      '#default_value' => $formValues['anchornavigation_font_family'],
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_weight'] = [
      '#type' => 'select',
      '#options' => $arr_fontweights,
      '#title' => t('Font weight'),
      '#default_value' => $formValues['anchornavigation_font_weight'],
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_style'] = [
      '#type' => 'select',
      '#options' => $arr_fontstyles,
      '#title' => t('Font style'),
      '#default_value' => $formValues['anchornavigation_font_style'],
    ];

    $form['patterns']['anchornavigation']['anchornavigation_line_height'] = [
      '#type' => 'textfield',
      '#title' => t('Line height'),
      '#default_value' => $formValues['anchornavigation_line_height'] ? $formValues['anchornavigation_line_height'] : '1.2',
      '#suffix' => '</div>'
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_size_holder'] = [
      '#type' => 'container',
      '#prefix' => '<div class="inline-input-holder">',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'separator'],
      ]
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_size_holder']['anchornavigation_font_size'] = [
      '#type' => 'textfield',
      '#title' => t('Font size'),
      '#default_value' => $formValues['anchornavigation_font_size'] ? $formValues['anchornavigation_font_size'] : '1.2',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_size_holder']['anchornavigation_font_size_unit'] = [
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['anchornavigation_font_size_unit'] ? $formValues['anchornavigation_font_size_unit'] : 'rem',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_margin_holder'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule', 'separator'],
      ],
    ];

    $form['patterns']['anchornavigation']['anchornavigation_margin_holder']['anchornavigation_margin_top'] = [
      '#title' => t('Margin (top, right, bottom, left)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['anchornavigation_margin_top'] ? $formValues['anchornavigation_margin_top'] : '0',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_margin_holder']['anchornavigation_margin_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['anchornavigation_margin_right'] ? $formValues['anchornavigation_margin_right'] : '0',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_margin_holder']['anchornavigation_margin_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['anchornavigation_margin_bottom'] ? $formValues['anchornavigation_margin_bottom'] : '0',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_margin_holder']['anchornavigation_margin_left'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['anchornavigation_margin_left'] ? $formValues['anchornavigation_margin_left'] : '0',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_margin_holder']['anchornavigation_margin_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['anchornavigation_margin_unit'],
    ];

    $form['patterns']['anchornavigation']['anchornavigation_padding_holder'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline', 'unit-combo-field', 'multi-valule'],
      ],
      '#suffix' => '</div>'
    ];

    $form['patterns']['anchornavigation']['anchornavigation_padding_holder']['anchornavigation_padding_top'] = [
      '#title' => t('Padding (top, right, bottom, left)'),
      '#type' => 'textfield',
      '#default_value' => $formValues['anchornavigation_padding_top'] ? $formValues['anchornavigation_padding_top'] : '5',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_padding_holder']['anchornavigation_padding_right'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['anchornavigation_padding_right'] ? $formValues['anchornavigation_padding_right'] : '5',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_padding_holder']['anchornavigation_padding_bottom'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['anchornavigation_padding_bottom'] ? $formValues['anchornavigation_padding_bottom'] : '5',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_padding_holder']['anchornavigation_padding_left'] = [
      '#type' => 'textfield',
      '#default_value' => $formValues['anchornavigation_padding_left'] ? $formValues['anchornavigation_padding_left'] : '5',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_padding_holder']['anchornavigation_padding_unit'] = [
      '#type' => 'textfield',
      '#type' => 'select',
      '#options' => $arr_units,
      '#default_value' => $formValues['anchornavigation_padding_unit'],
    ];

    $form['patterns']['anchornavigation']['anchornavigation_wrapper_background_color'] = [
      '#type' => 'select',
      '#title' => t('Wrapper background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['anchornavigation_wrapper_background_color'] ? $formValues['anchornavigation_wrapper_background_color'] : 'white',
      '#prefix' => '<div class="inline-input-holder separator"><strong>Color settings</strong>',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_wrapper_background_opacity'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['anchornavigation_wrapper_background_opacity'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_color_standard'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['anchornavigation_font_color_standard'] ? $formValues['anchornavigation_font_color_standard'] : 'grey1',
      '#prefix' => '<div class="inline-input-holder"><strong>Standard</strong>',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_opacity_standard'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['anchornavigation_font_opacity_standard'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['anchornavigation']['anchornavigation_background_color_standard'] = [
      '#prefix' => '<div class="inline-input-holder separator">',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['anchornavigation_background_color_standard'] ? $formValues['anchornavigation_background_color_standard'] : 'white',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_background_opacity_standard'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['anchornavigation_background_opacity_standard'],
    ];

    $form['patterns']['anchornavigation']['anchornavigation_border_color_standard'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['anchornavigation_border_color_standard'] ? $formValues['anchornavigation_background_color_standard'] : 'white',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_border_opacity_standard'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['anchornavigation_border_opacity_standard'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_color_hover'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['anchornavigation_font_color_hover'] ? $formValues['anchornavigation_font_color_hover'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>Hover</strong>',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_opacity_hover'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['anchornavigation_font_opacity_hover'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['anchornavigation']['anchornavigation_background_color_hover'] = [
      '#prefix' => '<div class="inline-input-holder separator">',
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['anchornavigation_background_color_hover'] ? $formValues['anchornavigation_background_color_hover'] : 'primary',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_background_opacity_hover'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['anchornavigation_background_opacity_hover'],
    ];

    $form['patterns']['anchornavigation']['anchornavigation_border_color_hover'] = [
      '#type' => 'select',
      '#title' => t('Hover border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['anchornavigation_border_color_hover'] ? $formValues['anchornavigation_border_color_hover'] : 'primary',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_border_opacity_hover'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['anchornavigation_border_opacity_hover'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_color_active'] = [
      '#type' => 'select',
      '#title' => t('Font color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['anchornavigation_font_color_active'] ? $formValues['anchornavigation_font_color_active'] : 'white',
      '#prefix' => '<div class="inline-input-holder"><strong>Active</strong>',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_font_opacity_active'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['anchornavigation_font_opacity_active'],
      '#suffix' => '</div>'
    ];

    $form['patterns']['anchornavigation']['anchornavigation_background_color_active'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['anchornavigation_background_color_active'] ? $formValues['anchornavigation_background_color_active'] : 'primary',
      '#prefix' => '<div class="inline-input-holder separator">',
    ];

    $form['patterns']['anchornavigation']['anchornavigation_background_opacity_active'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['separator'],
      ],
      '#title' => t('Opacity'),
      '#default_value' => $formValues['anchornavigation_background_opacity_active'],
    ];

    $form['patterns']['anchornavigation']['anchornavigation_border_color_active'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#options' => $arr_colors,
      '#default_value' => $formValues['anchornavigation_border_color_active'],
    ];

    $form['patterns']['anchornavigation']['anchornavigation_border_opacity_active'] = [
      '#type' => 'textfield',
      '#title' => t('Opacity'),
      '#default_value' => $formValues['anchornavigation_border_opacity_active'],
      '#suffix' => '</div>'
    ];
  }

}
