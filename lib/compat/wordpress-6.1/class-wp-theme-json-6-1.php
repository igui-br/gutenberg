<?php
/**
 * WP_Theme_JSON_6_1 class
 *
 * @package gutenberg
 */

/**
 * Class that encapsulates the processing of structures that adhere to the theme.json spec.
 *
 * This class is for internal core usage and is not supposed to be used by extenders (plugins and/or themes).
 * This is a low-level API that may need to do breaking changes. Please,
 * use get_global_settings, get_global_styles, and get_global_stylesheet instead.
 *
 * @access private
 */
class WP_Theme_JSON_6_1 extends WP_Theme_JSON_6_0 {
	/**
	 * Metadata for style properties.
	 *
	 * Each element is a direct mapping from the CSS property name to the
	 * path to the value in theme.json & block attributes.
	 */
	const PROPERTIES_METADATA = array(
		'background'                 => array( 'color', 'gradient' ),
		'background-color'           => array( 'color', 'background' ),
		'border-radius'              => array( 'border', 'radius' ),
		'border-top-left-radius'     => array( 'border', 'radius', 'topLeft' ),
		'border-top-right-radius'    => array( 'border', 'radius', 'topRight' ),
		'border-bottom-left-radius'  => array( 'border', 'radius', 'bottomLeft' ),
		'border-bottom-right-radius' => array( 'border', 'radius', 'bottomRight' ),
		'border-color'               => array( 'border', 'color' ),
		'border-width'               => array( 'border', 'width' ),
		'border-style'               => array( 'border', 'style' ),
		'border-top-color'           => array( 'border', 'top', 'color' ),
		'border-top-width'           => array( 'border', 'top', 'width' ),
		'border-top-style'           => array( 'border', 'top', 'style' ),
		'border-right-color'         => array( 'border', 'right', 'color' ),
		'border-right-width'         => array( 'border', 'right', 'width' ),
		'border-right-style'         => array( 'border', 'right', 'style' ),
		'border-bottom-color'        => array( 'border', 'bottom', 'color' ),
		'border-bottom-width'        => array( 'border', 'bottom', 'width' ),
		'border-bottom-style'        => array( 'border', 'bottom', 'style' ),
		'border-left-color'          => array( 'border', 'left', 'color' ),
		'border-left-width'          => array( 'border', 'left', 'width' ),
		'border-left-style'          => array( 'border', 'left', 'style' ),
		'color'                      => array( 'color', 'text' ),
		'font-family'                => array( 'typography', 'fontFamily' ),
		'font-size'                  => array( 'typography', 'fontSize' ),
		'font-style'                 => array( 'typography', 'fontStyle' ),
		'font-weight'                => array( 'typography', 'fontWeight' ),
		'letter-spacing'             => array( 'typography', 'letterSpacing' ),
		'line-height'                => array( 'typography', 'lineHeight' ),
		'margin'                     => array( 'spacing', 'margin' ),
		'margin-top'                 => array( 'spacing', 'margin', 'top' ),
		'margin-right'               => array( 'spacing', 'margin', 'right' ),
		'margin-bottom'              => array( 'spacing', 'margin', 'bottom' ),
		'margin-left'                => array( 'spacing', 'margin', 'left' ),
		'padding'                    => array( 'spacing', 'padding' ),
		'padding-top'                => array( 'spacing', 'padding', 'top' ),
		'padding-right'              => array( 'spacing', 'padding', 'right' ),
		'padding-bottom'             => array( 'spacing', 'padding', 'bottom' ),
		'padding-left'               => array( 'spacing', 'padding', 'left' ),
		'text-decoration'            => array( 'typography', 'textDecoration' ),
		'text-transform'             => array( 'typography', 'textTransform' ),
		'filter'                     => array( 'filter', 'duotone' ),
	);

	const ELEMENTS = array(
		'link'   => 'a',
		'h1'     => 'h1',
		'h2'     => 'h2',
		'h3'     => 'h3',
		'h4'     => 'h4',
		'h5'     => 'h5',
		'h6'     => 'h6',
		'button' => '.wp-element-button, .wp-block-button__link', // We have the .wp-block-button__link class so that this will target older buttons that have been serialized.
	);

	const __EXPERIMENTAL_ELEMENT_CLASS_NAMES = array(
		'button' => 'wp-element-button',
	);

	/**
	 * Given an element name, returns a class name.
	 *
	 * @param string $element The name of the element.
	 *
	 * @return string The name of the class.
	 *
	 * @since 6.1.0
	 */
	public static function get_element_class_name( $element ) {
		return array_key_exists( $element, static::__EXPERIMENTAL_ELEMENT_CLASS_NAMES ) ? static::__EXPERIMENTAL_ELEMENT_CLASS_NAMES[ $element ] : '';
	}

	/**
	 * The valid properties under the settings key.
	 *
	 * @var array
	 */
	const VALID_SETTINGS = array(
		'appearanceTools' => null,
		'border'          => array(
			'color'  => null,
			'radius' => null,
			'style'  => null,
			'width'  => null,
		),
		'color'           => array(
			'background'       => null,
			'custom'           => null,
			'customDuotone'    => null,
			'customGradient'   => null,
			'defaultDuotone'   => null,
			'defaultGradients' => null,
			'defaultPalette'   => null,
			'duotone'          => null,
			'gradients'        => null,
			'link'             => null,
			'palette'          => null,
			'text'             => null,
		),
		'custom'          => null,
		'layout'          => array(
			'contentSize' => null,
			'definitions' => null,
			'wideSize'    => null,
		),
		'spacing'         => array(
			'blockGap' => null,
			'margin'   => null,
			'padding'  => null,
			'units'    => null,
		),
		'typography'      => array(
			'customFontSize' => null,
			'dropCap'        => null,
			'fontFamilies'   => null,
			'fontSizes'      => null,
			'fontStyle'      => null,
			'fontWeight'     => null,
			'letterSpacing'  => null,
			'lineHeight'     => null,
			'textDecoration' => null,
			'textTransform'  => null,
		),
	);

	/**
	 * The valid properties under the styles key.
	 *
	 * @var array
	 */
	const VALID_STYLES = array(
		'border'     => array(
			'color'  => null,
			'radius' => null,
			'style'  => null,
			'width'  => null,
			'top'    => null,
			'right'  => null,
			'bottom' => null,
			'left'   => null,
		),
		'color'      => array(
			'background' => null,
			'gradient'   => null,
			'text'       => null,
		),
		'filter'     => array(
			'duotone' => null,
		),
		'spacing'    => array(
			'margin'   => null,
			'padding'  => null,
			'blockGap' => null,
		),
		'typography' => array(
			'fontFamily'     => null,
			'fontSize'       => null,
			'fontStyle'      => null,
			'fontWeight'     => null,
			'letterSpacing'  => null,
			'lineHeight'     => null,
			'textDecoration' => null,
			'textTransform'  => null,
		),
	);

	/**
	 * Returns the metadata for each block.
	 *
	 * Example:
	 *
	 *     {
	 *       'core/paragraph': {
	 *         'selector': 'p',
	 *         'elements': {
	 *           'link' => 'link selector',
	 *           'etc'  => 'element selector'
	 *         }
	 *       },
	 *       'core/heading': {
	 *         'selector': 'h1',
	 *         'elements': {}
	 *       },
	 *       'core/image': {
	 *         'selector': '.wp-block-image',
	 *         'duotone': 'img',
	 *         'elements': {}
	 *       }
	 *     }
	 *
	 * @return array Block metadata.
	 */
	protected static function get_blocks_metadata() {
		if ( null !== static::$blocks_metadata ) {
			return static::$blocks_metadata;
		}

		static::$blocks_metadata = array();

		$registry = WP_Block_Type_Registry::get_instance();
		$blocks   = $registry->get_all_registered();
		foreach ( $blocks as $block_name => $block_type ) {
			if (
				isset( $block_type->supports['__experimentalSelector'] ) &&
				is_string( $block_type->supports['__experimentalSelector'] )
			) {
				static::$blocks_metadata[ $block_name ]['selector'] = $block_type->supports['__experimentalSelector'];
			} else {
				static::$blocks_metadata[ $block_name ]['selector'] = '.wp-block-' . str_replace( '/', '-', str_replace( 'core/', '', $block_name ) );
			}

			if (
				isset( $block_type->supports['color']['__experimentalDuotone'] ) &&
				is_string( $block_type->supports['color']['__experimentalDuotone'] )
			) {
				static::$blocks_metadata[ $block_name ]['duotone'] = $block_type->supports['color']['__experimentalDuotone'];
			}

			// Assign defaults, then overwrite those that the block sets by itself.
			// If the block selector is compounded, will append the element to each
			// individual block selector.
			$block_selectors = explode( ',', static::$blocks_metadata[ $block_name ]['selector'] );
			foreach ( static::ELEMENTS as $el_name => $el_selector ) {
				$element_selector = array();
				foreach ( $block_selectors as $selector ) {
					if ( $selector === $el_selector ) {
						$element_selector = array( $el_selector );
						break;
					}

					$element_selector[] = $selector . ' ' . $el_selector;
				}
				static::$blocks_metadata[ $block_name ]['elements'][ $el_name ] = implode( ',', $element_selector );
			}
		}

		return static::$blocks_metadata;
	}

	/**
	 * Builds metadata for the style nodes, which returns in the form of:
	 *
	 *     [
	 *       [
	 *         'path'     => [ 'path', 'to', 'some', 'node' ],
	 *         'selector' => 'CSS selector for some node',
	 *         'duotone'  => 'CSS selector for duotone for some node'
	 *       ],
	 *       [
	 *         'path'     => ['path', 'to', 'other', 'node' ],
	 *         'selector' => 'CSS selector for other node',
	 *         'duotone'  => null
	 *       ],
	 *     ]
	 *
	 * @since 5.8.0
	 *
	 * @param array $theme_json The tree to extract style nodes from.
	 * @param array $selectors  List of selectors per block.
	 * @return array
	 */
	protected static function get_style_nodes( $theme_json, $selectors = array() ) {
		$nodes = array();
		if ( ! isset( $theme_json['styles'] ) ) {
			return $nodes;
		}

		// Top-level.
		$nodes[] = array(
			'path'     => array( 'styles' ),
			'selector' => static::ROOT_BLOCK_SELECTOR,
		);

		if ( isset( $theme_json['styles']['elements'] ) ) {
			foreach ( $theme_json['styles']['elements'] as $element => $node ) {
				$nodes[] = array(
					'path'     => array( 'styles', 'elements', $element ),
					'selector' => static::ELEMENTS[ $element ],
				);
			}
		}

		// Blocks.
		if ( ! isset( $theme_json['styles']['blocks'] ) ) {
			return $nodes;
		}

		$nodes = array_merge( $nodes, static::get_block_nodes( $theme_json, $selectors ) );

		// This filter allows us to modify the output of WP_Theme_JSON so that we can do things like loading block CSS independently.
		return apply_filters( 'gutenberg_get_style_nodes', $nodes );
	}

	/**
	 * A public helper to get the block nodes from a theme.json file.
	 *
	 * @return array The block nodes in theme.json.
	 */
	public function get_styles_block_nodes() {
		return static::get_block_nodes( $this->theme_json );
	}

	/**
	 * An internal method to get the block nodes from a theme.json file.
	 *
	 * @param array $theme_json The theme.json converted to an array.
	 * @param array $selectors  Optional list of selectors per block.
	 *
	 * @return array The block nodes in theme.json.
	 */
	private static function get_block_nodes( $theme_json, $selectors = array() ) {
		$selectors = empty( $selectors ) ? static::get_blocks_metadata() : $selectors;
		$nodes     = array();
		if ( ! isset( $theme_json['styles'] ) ) {
			return $nodes;
		}

		// Blocks.
		if ( ! isset( $theme_json['styles']['blocks'] ) ) {
			return $nodes;
		}

		foreach ( $theme_json['styles']['blocks'] as $name => $node ) {
			$selector = null;
			if ( isset( $selectors[ $name ]['selector'] ) ) {
				$selector = $selectors[ $name ]['selector'];
			}

			$duotone_selector = null;
			if ( isset( $selectors[ $name ]['duotone'] ) ) {
				$duotone_selector = $selectors[ $name ]['duotone'];
			}

			$nodes[] = array(
				'name'     => $name,
				'path'     => array( 'styles', 'blocks', $name ),
				'selector' => $selector,
				'duotone'  => $duotone_selector,
			);

			if ( isset( $theme_json['styles']['blocks'][ $name ]['elements'] ) ) {
				foreach ( $theme_json['styles']['blocks'][ $name ]['elements'] as $element => $node ) {
					$nodes[] = array(
						'path'     => array( 'styles', 'blocks', $name, 'elements', $element ),
						'selector' => $selectors[ $name ]['elements'][ $element ],
					);
				}
			}
		}

		return $nodes;
	}

	/**
	 * Returns the stylesheet that results of processing
	 * the theme.json structure this object represents.
	 *
	 * @param array $types    Types of styles to load. Will load all by default. It accepts:
	 *                         'variables': only the CSS Custom Properties for presets & custom ones.
	 *                         'styles': only the styles section in theme.json.
	 *                         'presets': only the classes for the presets.
	 * @param array $origins A list of origins to include. By default it includes VALID_ORIGINS.
	 * @return string Stylesheet.
	 */
	public function get_stylesheet( $types = array( 'variables', 'styles', 'presets' ), $origins = null ) {
		if ( null === $origins ) {
			$origins = static::VALID_ORIGINS;
		}

		if ( is_string( $types ) ) {
			// Dispatch error and map old arguments to new ones.
			_deprecated_argument( __FUNCTION__, '5.9' );
			if ( 'block_styles' === $types ) {
				$types = array( 'styles', 'presets' );
			} elseif ( 'css_variables' === $types ) {
				$types = array( 'variables' );
			} else {
				$types = array( 'variables', 'styles', 'presets' );
			}
		}

		$blocks_metadata = static::get_blocks_metadata();
		$style_nodes     = static::get_style_nodes( $this->theme_json, $blocks_metadata );
		$setting_nodes   = static::get_setting_nodes( $this->theme_json, $blocks_metadata );

		$stylesheet = '';

		if ( in_array( 'variables', $types, true ) ) {
			$stylesheet .= $this->get_css_variables( $setting_nodes, $origins );
		}

		if ( in_array( 'styles', $types, true ) ) {
			$stylesheet .= $this->get_block_classes( $style_nodes );
		} elseif ( in_array( 'base-layout-styles', $types, true ) ) {
			// Base layout styles are provided as part of `styles`, so only output separately if explicitly requested.
			// For backwards compatibility, the Columns block is explicitly included, to support a different default gap value.
			$base_styles_nodes = array(
				array(
					'path'     => array( 'styles' ),
					'selector' => static::ROOT_BLOCK_SELECTOR,
				),
				array(
					'path'     => array( 'styles', 'blocks', 'core/columns' ),
					'selector' => '.wp-block-columns',
					'name'     => 'core/columns',
				),
			);

			foreach ( $base_styles_nodes as $base_style_node ) {
				$stylesheet .= $this->get_layout_styles( $base_style_node );
			}
		}

		if ( in_array( 'presets', $types, true ) ) {
			$stylesheet .= $this->get_preset_classes( $setting_nodes, $origins );
		}

		return $stylesheet;
	}

	/**
	 * Gets the CSS rules for a particular block from theme.json.
	 *
	 * @param array $block_metadata Metadata about the block to get styles for.
	 *
	 * @return string Styles for the block.
	 */
	public function get_styles_for_block( $block_metadata ) {
		$node         = _wp_array_get( $this->theme_json, $block_metadata['path'], array() );
		$selector     = $block_metadata['selector'];
		$settings     = _wp_array_get( $this->theme_json, array( 'settings' ) );
		$declarations = static::compute_style_properties( $node, $settings );
		$block_rules  = '';

		// 1. Separate the ones who use the general selector
		// and the ones who use the duotone selector.
		$declarations_duotone = array();
		foreach ( $declarations as $index => $declaration ) {
			if ( 'filter' === $declaration['name'] ) {
				unset( $declarations[ $index ] );
				$declarations_duotone[] = $declaration;
			}
		}

		/*
		 * Reset default browser margin on the root body element.
		 * This is set on the root selector **before** generating the ruleset
		 * from the `theme.json`. This is to ensure that if the `theme.json` declares
		 * `margin` in its `spacing` declaration for the `body` element then these
		 * user-generated values take precedence in the CSS cascade.
		 * @link https://github.com/WordPress/gutenberg/issues/36147.
		 */
		if ( static::ROOT_BLOCK_SELECTOR === $selector ) {
			$block_rules .= 'body { margin: 0; }';
		}

		// 2. Generate the rules that use the general selector.
		$block_rules .= static::to_ruleset( $selector, $declarations );

		// 3. Generate the rules that use the duotone selector.
		if ( isset( $block_metadata['duotone'] ) && ! empty( $declarations_duotone ) ) {
			$selector_duotone = static::scope_selector( $block_metadata['selector'], $block_metadata['duotone'] );
			$block_rules     .= static::to_ruleset( $selector_duotone, $declarations_duotone );
		}

		// 4. Generate Layout block gap styles.
		$has_block_gap_support = _wp_array_get( $this->theme_json, array( 'settings', 'spacing', 'blockGap' ) ) !== null;
		$has_block_gap_value   = _wp_array_get( $node, array( 'spacing', 'blockGap' ), false );

		if (
			static::ROOT_BLOCK_SELECTOR !== $selector &&
			$has_block_gap_support &&
			$has_block_gap_value &&
			! empty( $block_metadata['name'] )
		) {
			$block_rules .= $this->get_layout_styles( $block_metadata );
		}

		if ( static::ROOT_BLOCK_SELECTOR === $selector ) {
			$block_gap_value = _wp_array_get( $this->theme_json, array( 'styles', 'spacing', 'blockGap' ), '0.5em' );

			$block_rules .= '.wp-site-blocks > .alignleft { float: left; margin-right: 2em; }';
			$block_rules .= '.wp-site-blocks > .alignright { float: right; margin-left: 2em; }';
			$block_rules .= '.wp-site-blocks > .aligncenter { justify-content: center; margin-left: auto; margin-right: auto; }';

			if ( $has_block_gap_support ) {
				$block_rules .= '.wp-site-blocks > * { margin-block-start: 0; margin-block-end: 0; }';
				$block_rules .= ".wp-site-blocks > * + * { margin-block-start: $block_gap_value; }";
				// For backwards compatibility, ensure the legacy block gap CSS variable is still available.
				$block_rules .= "$selector { --wp--style--block-gap: $block_gap_value; }";
			}
			$block_rules .= $this->get_layout_styles( $block_metadata );
		}

		return $block_rules;
	}

	/**
	 * Converts each style section into a list of rulesets
	 * containing the block styles to be appended to the stylesheet.
	 *
	 * See glossary at https://developer.mozilla.org/en-US/docs/Web/CSS/Syntax
	 *
	 * For each section this creates a new ruleset such as:
	 *
	 *   block-selector {
	 *     style-property-one: value;
	 *   }
	 *
	 * @param array $style_nodes Nodes with styles.
	 * @return string The new stylesheet.
	 */
	protected function get_block_classes( $style_nodes ) {
		$block_rules = '';

		foreach ( $style_nodes as $metadata ) {
			if ( null === $metadata['selector'] ) {
				continue;
			}
			$block_rules .= static::get_styles_for_block( $metadata );
		}

		return $block_rules;
	}

	/**
	 * Get the CSS layout rules for a particular block from theme.json layout definitions.
	 *
	 * @param array   $block_metadata      Metadata about the block to get styles for.
	 * @param boolean $skip_default_layout Whether to skip outputting the default layout, and force outputting remaining layout styles.
	 *
	 * @return string Layout styles for the block.
	 */
	protected function get_layout_styles( $block_metadata ) {
		$block_rules              = '';
		$selector                 = isset( $block_metadata['selector'] ) ? $block_metadata['selector'] : '';
		$has_block_gap_support    = _wp_array_get( $this->theme_json, array( 'settings', 'spacing', 'blockGap' ) ) !== null;
		$has_block_styles_support = current_theme_supports( 'wp-block-styles' );
		$node                     = _wp_array_get( $this->theme_json, $block_metadata['path'], array() );
		$layout_definitions       = _wp_array_get( $this->theme_json, array( 'settings', 'layout', 'definitions' ), array() );

		// Gap styles will only be output if the theme has block gap support, or supports `wp-block-styles`.
		// In this way, we tie the concept of gap styles to the styles that ship with core blocks.
		// Default layout gap styles will be skipped for themes that do not explicitly opt-in to blockGap with a `true` or `false` value.
		if ( $has_block_gap_support || $has_block_styles_support ) {
			$block_gap_value = null;
			// Use a fallback gap value if block gap support is not available.
			if ( ! $has_block_gap_support ) {
				$block_gap_value = '0.5em';
				if ( isset( $block_metadata['name'] ) ) {
					$block_type      = WP_Block_Type_Registry::get_instance()->get_registered( $block_metadata['name'] );
					$block_gap_value = _wp_array_get( $block_type->supports, array( 'spacing', 'blockGap', '__experimentalDefault' ), '0.5em' );
				}
			} else {
				$block_gap_value = _wp_array_get( $node, array( 'spacing', 'blockGap' ), null );
			}

			// Support split row / column values and concatenate to a shorthand value.
			if ( is_array( $block_gap_value ) ) {
				if ( isset( $block_gap_value['top'] ) && isset( $block_gap_value['left'] ) ) {
					$gap_row         = $block_gap_value['top'];
					$gap_column      = $block_gap_value['left'];
					$block_gap_value = $gap_row === $gap_column ? $gap_row : $gap_row . ' ' . $gap_column;
				} else {
					// Skip outputting gap value if not all sides are provided.
					$block_gap_value = null;
				}
			}

			if ( $block_gap_value ) {
				foreach ( $layout_definitions as $layout_definition_key => $layout_definition ) {
					// Allow skipping default layout, for example, so that classic themes can still output flex gap styles.
					if ( ! $has_block_gap_support && 'default' === $layout_definition_key ) {
						continue;
					}

					$class_name      = _wp_array_get( $layout_definition, array( 'className' ), false );
					$block_gap_rules = _wp_array_get( $layout_definition, array( 'blockGapStyles' ), array() );

					if (
						is_string( $class_name ) &&
						! empty( $block_gap_rules )
					) {
						foreach ( $block_gap_rules as $block_gap_rule ) {
							$declarations = array();
							if ( isset( $block_gap_rule['selector'] ) && ! empty( $block_gap_rule['rules'] ) ) {
								// Iterate over each of the styling rules and substitute non-string values such as `null` with the real `blockGap` value.
								foreach ( $block_gap_rule['rules'] as $css_property => $css_value ) {
									$declarations[] = array(
										'name'  => $css_property,
										'value' => is_string( $css_value ) ? $css_value : $block_gap_value,
									);
								}

								$format          = static::ROOT_BLOCK_SELECTOR === $selector ? '%s .%s%s' : '%s.%s%s';
								$layout_selector = sprintf(
									$format,
									$selector,
									$class_name,
									$block_gap_rule['selector']
								);
								$block_rules    .= static::to_ruleset( $layout_selector, $declarations );
							}
						}
					}
				}
			}
		}

		// Output base styles. These can be skipped by themes that remove both `blockGap` and `wp-block-styles` support.
		// This ensures that classic themes have a mechanism to opt-out of layout styles if need be.
		if (
			static::ROOT_BLOCK_SELECTOR === $selector &&
			( $has_block_gap_support || $has_block_styles_support )
		) {
			foreach ( $layout_definitions as $layout_definition ) {
				$class_name       = _wp_array_get( $layout_definition, array( 'className' ), false );
				$base_style_rules = _wp_array_get( $layout_definition, array( 'baseStyles' ), array() );

				if (
					is_string( $class_name ) &&
					! empty( $base_style_rules )
				) {
					foreach ( $base_style_rules as $base_style_rule ) {
						$declarations = array();

						if ( isset( $base_style_rule['selector'] ) && ! empty( $base_style_rule['rules'] ) ) {
							foreach ( $base_style_rule['rules'] as $css_property => $css_value ) {
								$declarations[] = array(
									'name'  => $css_property,
									'value' => $css_value,
								);
							}

							$format          = static::ROOT_BLOCK_SELECTOR === $selector ? '%s .%s%s' : '%s.%s%s';
							$layout_selector = sprintf(
								$format,
								$selector,
								$class_name,
								$base_style_rule['selector']
							);
							$block_rules    .= static::to_ruleset( $layout_selector, $declarations );
						}
					}
				}
			}
		}
		return $block_rules;
	}
}
