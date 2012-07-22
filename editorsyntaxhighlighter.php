<?php
/*
Plugin Name: Editor Syntax Highlighter
Plugin URI: http://kaneandre.ws
Description: Syntax highlighting for both theme and plugin editor in the admin panel.
Version: 1.0
Author: Kane Andrews
Author URI: http://kaneandre.ws
License: GPL2
*/

add_action('load-theme-editor.php', 'codemirror_register');
add_action('load-plugin-editor.php', 'codemirror_register');
 
function codemirror_register() {
       
        wp_register_script('codemirror', plugins_url('lib/codemirror.js', __FILE__));
        wp_register_style('codemirror', plugins_url('lib/codemirror.css', __FILE__));
       
        wp_register_style('blackboard', plugins_url('theme/blackboard.css', __FILE__));
       
        wp_register_script('xml', plugins_url('mode/xml/xml.js', __FILE__));
        wp_register_script('javascript', plugins_url('mode/javascript/javascript.js', __FILE__));
        wp_register_script('css', plugins_url('mode/css/css.js', __FILE__));   
        wp_register_script('php', plugins_url('mode/php/php.js', __FILE__));
        wp_register_script('clike', plugins_url('mode/clike/clike.js', __FILE__));
       
        add_action('admin_enqueue_scripts', 'codemirror_enqueue_scripts');
        add_action('admin_head', 'codemirror_control_js');
}
 
function codemirror_enqueue_scripts() {
        wp_enqueue_script('codemirror');
        wp_enqueue_style('codemirror');
       
        wp_enqueue_style('blackboard');
       
        wp_enqueue_script('xml');
        wp_enqueue_script('javascript');
        wp_enqueue_script('css');
        wp_enqueue_script('php');
        wp_enqueue_script('clike');
}
 
function codemirror_control_js() {
        if (isset($_GET['file'])) {
                $filename_to_edit = end(explode("/", $_GET['file']));
                $file = substr($filename_to_edit, stripos($filename_to_edit, '.')+1);
                switch ($file) {
                        case "php": $file = "application/x-httpd-php"; break;
                        case "js" : $file = "text/javascript"; break;
                        case "css": $file = "text/css"; break;
                }      
        }
        else {
                $file_script = $_SERVER['SCRIPT_NAME'];
                $file_script = end(explode('/', $file_script));
                if ($file_script == 'theme-editor.php')
                        $file = "text/css";
                else
                        $file = "application/x-httpd-php";
        }
               
?>
        <script type="text/javascript">
                jQuery(document).ready(function() {
                        var editor = CodeMirror.fromTextArea(document.getElementById("newcontent"), {
                        lineNumbers: true,
                        matchBrackets: true,
                                mode: "<?php echo $file ;?>",
                        indentUnit: 4,
                        indentWithTabs: true,
                        enterMode: "keep",
                        tabMode: "shift",
                                theme: "blackboard"
                        });
                })
        </script>
<?php
} ?>