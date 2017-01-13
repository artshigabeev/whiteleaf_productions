<?php
/**
 * Extension-Boilerplate
 *
 * @link https://github.com/ReduxFramework/extension-boilerplate
 *
 * Radium Importer - Modified For ReduxFramework
 * @link https://github.com/FrankM1/radium-one-click-demo-install
 *
 * @package     WBC_Importer - Extension for Importing demo content
 * @author      Webcreations907
 * @version     1.0.2
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'ReduxFramework_extension_wbc_importer' ) ) {

    class ReduxFramework_extension_wbc_importer {

        public static $instance;

        static $version = "1.0.2";

        protected $parent;

        private $filesystem = array();

        public $extension_url;

        public $extension_dir;

        public $demo_data_dir;

        public $wbc_import_files = array();

        public $active_import_id;

        public $active_import;
        
        public $_themeoption_name =  '';

        /**
         * Class Constructor
         *
         * @since       1.0
         * @access      public
         * @return      void
         */
        public function __construct( $parent ) {

            $this->parent = $parent;
            $this->_themeoption_name = $this->parent->args['opt_name'];
            if ( !is_admin() ) return;

            //Hides importer section if anything but true returned. Way to abort :)
            if ( true !== apply_filters( 'wbc_importer_abort', true ) ) {
                return;
            }

            if ( empty( $this->extension_dir ) ) {
               // var_dump(BEAU_PLUGIN_DIR);die();
                //$this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
               // $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
                $this->demo_data_dir = apply_filters( "wbc_importer_dir_path", BEAU_PLUGIN_DIR . '/demo-data/' );
            }

            //Delete saved options of imported demos, for dev/testing purpose
            // delete_option('wbc_imported_demos');

            $this->getImports();

            $this->field_name = 'wbc_importer';

            self::$instance = $this;

            add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array( &$this,
                    'overload_field_path'
                ) );

            add_action( 'wp_ajax_redux_wbc_importer', array(
                    $this,
                    'ajax_importer'
                ) );

            add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/wbc_importer_files', array(
                    $this,
                    'addImportFiles'
                ) );

            //Adds Importer section to panel
            $this->add_importer_section();
            
            // Add Class Master Slider import
            @include dirname( __FILE__ ) .'/classes/class-msp-importer.php';
        }

        /**
         * Get the demo folders/files
         * Provided fallback where some host require FTP info
         *
         * @return array list of files for demos
         */
        public function demoFiles() {

            $this->filesystem = $this->parent->filesystem->execute( 'object' );
            $dir_array = $this->filesystem->dirlist( $this->demo_data_dir, false, true );

            if ( !empty( $dir_array ) && is_array( $dir_array ) ) {
               
                uksort( $dir_array, 'strcasecmp' );
                return $dir_array;

            }else{

                $dir_array = array();

                $demo_directory = array_diff( scandir( $this->demo_data_dir ), array( '..', '.' ) );

                if ( !empty( $demo_directory ) && is_array( $demo_directory ) ) {
                    foreach ( $demo_directory as $key => $value ) {
                        if ( is_dir( $this->demo_data_dir.$value ) ) {

                            $dir_array[$value] = array( 'name' => $value, 'type' => 'd', 'files'=> array() );

                            $demo_content = array_diff( scandir( $this->demo_data_dir.$value ), array( '..', '.' ) );

                            foreach ( $demo_content as $d_key => $d_value ) {
                                if ( is_file( $this->demo_data_dir.$value.'/'.$d_value ) ) {
                                    $dir_array[$value]['files'][$d_value] = array( 'name'=> $d_value, 'type' => 'f' );
                                }
                            }
                        }
                    }

                    uksort( $dir_array, 'strcasecmp' );
                }
            }
            return $dir_array;
        }


        public function getImports() {

            if ( !empty( $this->wbc_import_files ) ) {
                return $this->wbc_import_files;
            }

            $imports = $this->demoFiles();

            $imported = get_option( 'wbc_imported_demos' );

            if ( !empty( $imports ) && is_array( $imports ) ) {
                $x = 1;
                foreach ( $imports as $import ) {

                    if ( !isset( $import['files'] ) || empty( $import['files'] ) ) {
                        continue;
                    }

                    if ( $import['type'] == "d" && !empty( $import['name'] ) ) {
                        $this->wbc_import_files['wbc-import-'.$x] = isset( $this->wbc_import_files['wbc-import-'.$x] ) ? $this->wbc_import_files['wbc-import-'.$x] : array();
                        $this->wbc_import_files['wbc-import-'.$x]['directory'] = $import['name'];

                        if ( !empty( $imported ) && is_array( $imported ) ) {
                            if ( array_key_exists( 'wbc-import-'.$x, $imported ) ) {
                                $this->wbc_import_files['wbc-import-'.$x]['imported'] = 'imported';
                            }
                        }

                        foreach ( $import['files'] as $file ) {
                            switch ( $file['name'] ) {
                            case 'content.xml':
                                $this->wbc_import_files['wbc-import-'.$x]['content_file'] = $file['name'];
                                break;

                            case 'theme-options.txt':
                            case 'theme-options.json':
                                $this->wbc_import_files['wbc-import-'.$x]['theme_options'] = $file['name'];
                                break;

                            case 'widgets.json':
                            case 'widgets.txt':
                                $this->wbc_import_files['wbc-import-'.$x]['widgets'] = $file['name'];
                                break;
                            case 'master-slider.json':
                                $this->wbc_import_files['wbc-import-'.$x]['master_slider'] = $file['name'];
                                break;    
                            case 'screen-image.png':
                            case 'screen-image.jpg':
                            case 'screen-image.gif':
                                $this->wbc_import_files['wbc-import-'.$x]['image'] = $file['name'];
                                break;
                            }

                        }

                        if ( !isset( $this->wbc_import_files['wbc-import-'.$x]['content_file'] ) ) {
                            unset( $this->wbc_import_files['wbc-import-'.$x] );
                            if ( $x > 1 ) $x--;
                        }

                    }

                    $x++;
                }

            }

        }

        public function addImportFiles( $wbc_import_files ) {

            if ( !is_array( $wbc_import_files ) || empty( $wbc_import_files ) ) {
                $wbc_import_files = array();
            }

            $wbc_import_files = wp_parse_args( $wbc_import_files, $this->wbc_import_files );

            return $wbc_import_files;
        }

        public function ajax_importer() {
            
            if ( !isset( $_REQUEST['nonce'] ) || !wp_verify_nonce( $_REQUEST['nonce'], "redux_{$this->parent->args['opt_name']}_wbc_importer" ) ) {
                die( 0 );
            }
            if ( isset( $_REQUEST['type'] ) && $_REQUEST['type'] == "import-demo-content" && array_key_exists( $_REQUEST['demo_import_id'], $this->wbc_import_files ) ) {

                $reimporting = false;

                if ( isset( $_REQUEST['wbc_import'] ) && $_REQUEST['wbc_import'] == 're-importing' ) {
                    $reimporting = true;
                }

                $this->active_import_id = $_REQUEST['demo_import_id'];

                $import_parts         = $this->wbc_import_files[$this->active_import_id];

                $this->active_import = array( $this->active_import_id => $import_parts );

                $content_file        = $import_parts['directory'];
                $demo_data_loc       = $this->demo_data_dir.$content_file;

                if ( file_exists( $demo_data_loc.'/'.$import_parts['content_file'] ) && is_file( $demo_data_loc.'/'.$import_parts['content_file'] ) ) {
                    if ( !isset( $import_parts['imported'] ) || true === $reimporting ) {
                        include $this->extension_dir.'inc/init-installer.php';
                        
                        $installer = new Radium_Theme_Demo_Data_Importer( $this, $this->parent );
                    }else {
                        echo esc_html__( "Demo Already Imported", 'framework' );
                    }
                }
                die();
            }
            die();
        }

        public static function get_instance() {
            return self::$instance;
        }

        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path( $field ) {
            return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
        }

        function add_importer_section() {
            // Checks to see if section was set in config of redux.
            for ( $n = 0; $n <= count( $this->parent->sections ); $n++ ) {
                if ( isset( $this->parent->sections[$n]['id'] ) && $this->parent->sections[$n]['id'] == 'wbc_importer_section' ) {
                    return;
                }
            }

            $wbc_importer_label = trim( esc_html( apply_filters( 'wbc_importer_label', __( 'Demo Importer', 'framework' ) ) ) );

            $wbc_importer_label = ( !empty( $wbc_importer_label ) ) ? $wbc_importer_label : __( 'Demo Importer', 'framework' );

			$warning_text = "<p class='warning-text'>SYSTEM CHECKING RESULT : </p>";
			$errors = checkSystem();
			if(empty($errors)){
				$warning_text .= "<p class='notice notice-success'>Your server config seems to be OK, let get start !</p>";
			} else {
				foreach($errors as $error) {
					$warning_text .= "<p class = 'notice notice-error'>".$error."</p>";
				}
			}
			
			
            $this->parent->sections[] = array(
                'id'     => 'wbc_importer_section',
                'title'  => $wbc_importer_label,
                'desc'   => $warning_text,
                'icon'   => 'el-icon-website',
                'fields' => array(
                    array(
                        'id'   => 'wbc_demo_importer',
                        'type' => 'wbc_importer'
                    )
                )
            );
        }
        
     /**
     * Import widget JSON data
     *
     * @since 2.2.0
     * @global array $wp_registered_sidebars
     * @param object  $data JSON widget data from .wie file
     * @return array Results array
     */
    public function import_widgets( $file ) {

      global $wp_registered_sidebars;
      $data = file_get_contents($file);
      $data = json_decode($data);
      // Have valid data?
      // If no data or could not decode
      if ( empty( $data ) || ! is_object( $data ) ) {
        return;
      }

      // Hook before import
      $data = apply_filters( 'radium_theme_import_widget_data', $data );

      // Get all available widgets site supports
      $available_widgets = $this->available_widgets();

      // Get all existing widget instances
      $widget_instances = array();
      foreach ( $available_widgets as $widget_data ) {
        $widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
      }

      // Begin results
      $results = array();

      // Loop import data's sidebars
      foreach ( $data as $sidebar_id => $widgets ) {

        // Skip inactive widgets
        // (should not be in export file)
        if ( 'wp_inactive_widgets' == $sidebar_id ) {
          continue;
        }

        // Check if sidebar is available on this site
        // Otherwise add widgets to inactive, and say so
        if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
          $sidebar_available = true;
          $use_sidebar_id = $sidebar_id;
          $sidebar_message_type = 'success';
          $sidebar_message = '';
        } else {
          $sidebar_available = false;
          $use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
          $sidebar_message_type = 'error';
          $sidebar_message = __( 'Sidebar does not exist in theme (using Inactive)', 'radium' );
        }

        // Result for sidebar
        $results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
        $results[$sidebar_id]['message_type'] = $sidebar_message_type;
        $results[$sidebar_id]['message'] = $sidebar_message;
        $results[$sidebar_id]['widgets'] = array();

        // Loop widgets
        foreach ( $widgets as $widget_instance_id => $widget ) {

          $fail = false;

          // Get id_base (remove -# from end) and instance ID number
          $id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
          $instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

          // Does site support this widget?
          if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
            $fail = true;
            $widget_message_type = 'error';
            $widget_message = __( 'Site does not support widget', 'radium' ); // explain why widget not imported
          }

          // Filter to modify settings before import
          // Do before identical check because changes may make it identical to end result (such as URL replacements)
          $widget = apply_filters( 'radium_theme_import_widget_settings', $widget );

          // Does widget with identical settings already exist in same sidebar?
          if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

            // Get existing widgets in this sidebar
            $sidebars_widgets = get_option( 'sidebars_widgets' );
            $sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

            // Loop widgets with ID base
            $single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
            foreach ( $single_widget_instances as $check_id => $check_widget ) {

              // Is widget in same sidebar and has identical settings?
              if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

                $fail = true;
                $widget_message_type = 'warning';
                $widget_message = __( 'Widget already exists', 'radium' ); // explain why widget not imported

                break;

              }

            }

          }

          // No failure
          if ( ! $fail ) {

            // Add widget instance
            $single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
            $single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
            $single_widget_instances[] = (array) $widget; // add it

            // Get the key it was given
            end( $single_widget_instances );
            $new_instance_id_number = key( $single_widget_instances );

            // If key is 0, make it 1
            // When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
            if ( '0' === strval( $new_instance_id_number ) ) {
              $new_instance_id_number = 1;
              $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
              unset( $single_widget_instances[0] );
            }

            // Move _multiwidget to end of array for uniformity
            if ( isset( $single_widget_instances['_multiwidget'] ) ) {
              $multiwidget = $single_widget_instances['_multiwidget'];
              unset( $single_widget_instances['_multiwidget'] );
              $single_widget_instances['_multiwidget'] = $multiwidget;
            }

            // Update option with new widget
            update_option( 'widget_' . $id_base, $single_widget_instances );

            // Assign widget instance to sidebar
            $sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
            $new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
            $sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
            update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

            // Success message
            if ( $sidebar_available ) {
              $widget_message_type = 'success';
              $widget_message = __( 'Imported', 'radium' );
            } else {
              $widget_message_type = 'warning';
              $widget_message = __( 'Imported to Inactive', 'radium' );
            }

          }

          // Result for widget instance
          $results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
          $results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = $widget->title ? $widget->title : __( 'No Title', 'radium' ); // show "No Title" if widget instance is untitled
          $results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
          $results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

        }

      }

      // Hook after import
      do_action( 'radium_theme_import_widget_after_import' );

      // Return results
      return apply_filters( 'radium_theme_import_widget_results', $results );

    }

    } // class
} // if
