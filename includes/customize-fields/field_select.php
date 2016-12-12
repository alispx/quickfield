<?php
/**
 * Class Select Control
 *
 * @class     Qf_Customize_Select_Control
 * @package   Quick_Field/Customize_Field
 * @category  Class
 * @author    vutuan.sw
 * @license   GPLv2 or later
 * @version   1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) ):

	/**
	 * Qf_Customize_Select_Control Class
	 */
	class Qf_Customize_Select_Control extends WP_Customize_Control {

		public $type = 'qf_select';

		/**
		 * Maximum number of options the user will be able to select.
		 * Set to 1 for single-select.
		 *
		 * @access public
		 * @var int
		 */
		public $multiple = 1;

		public function to_json() {

			// Call parent to_json() method to get the core defaults like "label", "description", etc.
			parent::to_json();

			// The setting value.
			$this->json['value'] = $this->value();

			// The control choices.
			$this->json['choices'] = $this->choices;

			// The data link.
			$this->json['link'] = $this->get_link();
			
			//The data multiple
			$this->json['multiple'] = $this->multiple;
		}
		
		public function content_template() {
			?>
			<# if ( ! data.choices ) return; #>

			<label>
				<# if ( data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
				<# } #>
				<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
				<# } #>
				<select {{{ data.inputAttrs }}} {{{ data.link }}} data-multiple="{{ data.multiple }}"<# if (data.multiple ) { #> multiple<# } #>>
					
					<# if ( data.multiple && data.value ) { #>
					
					<# for ( key in data.value ) { #>
					<option value="{{ data.value[ key ] }}" selected>{{ data.choices[ data.value[ key ] ] }}</option>
					<# } #>
					
					<# for ( key in data.choices ) { #>
					<# if ( data.value[ key ] in data.value ) { #>
					<# } else { #>
					<option value="{{ key }}">{{ data.choices[ key ] }}</option>
					<# } #>
					<# } #>
					<# } else { #>
					<# for ( key in data.choices ) { #>
					<option value="{{ key }}"<# if ( key === data.value ) { #>selected<# } #>>{{ data.choices[ key ] }}</option>
					<# } #>
					<# } #>
				</select>
			</label>
			<?php
		}

	}

	
endif;