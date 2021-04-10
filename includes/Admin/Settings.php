<?php

namespace Address\Book\Admin;

/**
 * Register address book settings
 */
class Settings {

    public function get_settings() {
        $settings = array(
            array(
                'option_group' => 'ab_settings',
                'option_name'  => 'ab_per_page',
            ),
            array(
                'option_group' => 'ab_settings',
                'option_name'  => 'ab_default_order',
            ),
            array(
                'option_group' => 'ab_settings',
                'option_name'  => 'ab_categories',
            ),
        );

        return $settings;
    }

    public function get_sections() {
        $sections = array(
            array(
                'id'       => 'ab_settings_section',
                'title'    => 'Address Book Settings', 
                'callback' => array($this, 'ab_settings_section_name'),
                'page'     => 'address-book'
            ),
        );

        return $sections;
    }

    public function get_feilds() {
        
        $feilds = array (
            array (
                'id'       => 'ab_per_page',
                'title'    => 'Show Items Per Page', 
                'callback' => array( $this, 'ab_settings_per_page' ),
                'page'     => 'address-book',
                'section'  => 'ab_settings_section'
            ),
            array (
                'id'       => 'ab_default_order',
                'title'    => 'Default Ordering', 
                'callback' => array( $this, 'ab_settings_default_order' ),
                'page'     => 'address-book',
                'section'  => 'ab_settings_section'
            ),
            array (
                'id'       => 'ab_categories',
                'title'    => 'Categories', 
                'callback' => array( $this, 'ab_settings_categories' ),
                'page'     => 'address-book',
                'section'  => 'ab_settings_section'
            ),
        );

        return $feilds;
    }

    /**
     * Register setting section & feilds
     * 
     * @return void
     */
    public function register() {
        $settings = $this->get_settings();
        $sections = $this->get_sections();
        $feilds = $this->get_feilds();

        foreach ($settings as $setting) {
            register_setting( $setting['option_group'], $setting['option_name'], isset( $setting['args'] ) ? $setting['args'] : '' );
        }
        foreach ($sections as $section) {
            add_settings_section( $section['id'], $section['title'], isset( $section['callback'] ) ? $section['callback'] : '', $section['page'] );
        }

        foreach ($feilds as $feild) {
            add_settings_field( $feild['id'], $feild['title'], $feild['callback'], $feild['page'], $feild['section'], isset( $feild['args'] ) ? $feild['args'] : '' );
        }
    
    }

    public function ab_settings_section_name() {
        echo "Set your personal preference";
    }

    public function ab_settings_per_page() {
        $per_page = get_option( 'ab_per_page', true );
        echo '<input type="text" name="ab_per_page" placeholder="eg: 10" value="'. $per_page .'" />';
    }

    public function ab_settings_default_order() {
        $options = array( 'ASC', 'DESC', 'RANDOM' );
        $all_options = "";
        $current_option = get_option( 'ab_default_order', true );

        foreach ($options as $option ) {
            $selected = '';
            if( $option == $current_option ){
                $selected = 'selected';
            }
            $all_options .= "<option value='$option' $selected> $option </option>";
        }

        echo '<select name="ab_default_order">'. $all_options .'</select>';
    }

    public function ab_settings_categories() {
        $options = array( 'LARAVEL', 'PHP', 'HTML' );
        $all_options = "";
        $current_options = get_option( 'ab_categories', true );

        foreach ($options as $option ) {
            $selected = '';
            if( in_array($option, $current_options) ){
                $selected = 'selected';
            }
            $all_options .= "<option value='$option' $selected> $option </option>";
        }

        echo '<select class="categories" name="ab_categories[]" multiple="multiple">'. $all_options .'</select>';
    }
}