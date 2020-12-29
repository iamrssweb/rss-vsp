<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://itjustdoes.co.uk
 * @since      1.0.0
 *
 * @package    Rss_Vsp
 * @subpackage Rss_Vsp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rss_Vsp
 * @subpackage Rss_Vsp/public
 * @author     It Just Does <richard@itjustdoes.co.uk>
 */

require_once plugin_dir_path(dirname(__FILE__)) . 'includes/SmartDOMDocument.php';

class Rss_Vsp_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * Unique(ish) identifier for prepending to funciton names
     *
     * @since    1.0.0
     * @access    private
     * @var        string    $unique_name    The 'name' of this plugin
     */
    private $unique_name = 'rss_vsp';

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The options that the user has set on the admin page
     *
     * @since   1.0.0
     * @access  private
     * @var     array   $options        The options
     */
    private $options;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Rss_Vsp_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Rss_Vsp_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/rss-vsp-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * An instance of this class should be passed to the run() function
         * defined in Rss_Vsp_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Rss_Vsp_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/rss-vsp-public.js', array('jquery'), $this->version, true);

        /**
         * Build the options that get passed to the javascript, and then pass them
         * accross using localize
         */
        $this->options = $this->get_the_options();
        wp_localize_script($this->plugin_name, 'options', $this->options);
    }

    /**
     * Create the public (HTML) content: this is a shortcoce callback, so returns the content, it does not
     * create it 'directly'
     *
     * What it does
     * Get the options
     * Use a custom query to get the posts, putting the contents into an array
     * Filter the array to only include those elemens the user has selected
     *
     * @since   1.0.0
     */
    public function the_content_cb($atts, $content, $shortcode_tag)
    {
        // get options from the admin side, and set defaults if not present
        $this->get_the_options();

        // get the concatenated contents for the recent posts of the chosen category
        $local_content = $this->get_the_content($this->options['category'], $this->options['mostrecent']);

        // filter the results to only include those items chosen by the user
        //$local_content = $this->filter_the_content($local_content); //, array('no'=>'no') );

        // return the filtered results
        return '<div id="rss-vsp-public">' . $local_content . '</div>';
    }

    /**
     * get_the_options
     *
     * @since    1.0.0
     */
    private function get_the_options()
    {

        $this->options = get_option($this->unique_name . '_options');

        if (!isset($this->options['category'])) {
            $this->options['category'] = '1';
        }
        if (!isset($this->options['mostrecent'])) {
            $this->options['mostrecent'] = '3';
        }
        if (!isset($this->options['speed'])) {
            $this->options['speed'] = '15';
        }
        if (!isset($this->options['lines'])) {
            $this->options['lines'] = '6';
        }

        return $this->options;
    }

    /**
     * get_the_content
     * Uses a custom loop to fetch mostrecent number of posts for category
     * and returns only the content of the posts, all concatenated
     *
     * @since    1.0.0
     * @param   number  category    Fetch posts of this category ID
     * @param   number  mostrecent  Maximum number of posts to fetch, in reverse chrono order (normal WP order)
     */
    private function get_the_content($category, $mostrecent)
    {

        $args = array(
            'cat' => absint($category),
            'posts_per_page' => absint($mostrecent),
            'post_type' => 'post',
            'orderby' => 'modified',
        );

        $query = new WP_Query($args);
        $this_content = '';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $this_content .= $this->filter_the_content(get_the_content());
            }
        } else {
            $this_content = '<p>No content</p>';
        }

        wp_reset_postdata();

        return $this_content;
    }

    /**
     * filter_the_content
     * Removes anything that isn't in HTML tags that have been requested by the user to be included (admin setting)
     *
     * @since   1.0.0
     */
    private function filter_the_content($content)
    {

        $dom = new \archon810\SmartDOMDocument;
        $dom->loadHTML($content);
        $xpath = new DOMXPath($dom);

        // paragraphs
        if (!isset($this->options['paragraph'])) {
            foreach ($xpath->query('//p') as $n) {
                $n->parentNode->removeChild($n);
            }
        }
        // headings
        if (!isset($this->options['header'])) {
            foreach ($xpath->query('//h1') as $n) {
                $n->parentNode->removeChild($n);
            }
            foreach ($xpath->query('//h2') as $n) {
                $n->parentNode->removeChild($n);
            }
            foreach ($xpath->query('//h3') as $n) {
                $n->parentNode->removeChild($n);
            }
            foreach ($xpath->query('//h4') as $n) {
                $n->parentNode->removeChild($n);
            }
            foreach ($xpath->query('//h5') as $n) {
                $n->parentNode->removeChild($n);
            }
            foreach ($xpath->query('//h6') as $n) {
                $n->parentNode->removeChild($n);
            }
        } 
        // images
        if (!isset($this->options['image'])) {
            foreach ($xpath->query('//img') as $n) {
                $n->parentNode->removeChild($n);
            }
        }

        // comments, to keep the HTML short
        foreach ($xpath->query('//comment()') as $comment) {
            $comment->parentNode->removeChild($comment);
        }

        return $dom->saveHTMLExact();
    }
}
