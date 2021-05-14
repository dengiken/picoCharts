<?php
class PicoCharts extends AbstractPicoPlugin
{
    /**
     * API version used by this plugin
     *
     * @var int
     */
    const API_VERSION = 3;

    /**
     * Pico Charts Plugin
     *
     * Show charts or graphs dynamicaly and easly from Graphviz DOT files.
     *
     * This plugin needs Graphviz(https://graphviz.org/)
     *
     * example:
     *      ![alt](yourchart.dot)
     *      <img src="sub/yourchart.dot" alt="alt">
     *      http://yourdomain/yourchart.dot
     * 
     * @author Dengiken.net
     * @link https://github.com/dengiken/picoCharts
     * @license http://opensource.org/licenses/MIT The MIT License
     * @version 1.0.0
     */
    
    /**
     * This plugin is desabled by default.
     *
     * @see AbstractPicoPlugin::$enabled
     * @var bool|null
     */
    protected $enabled = false;

    /**
     * This plugin depends on {@link ...}.
     *
     * @see AbstractPicoPlugin::$dependsOn
     * @var string[]
     */
    protected $dependsOn = array();

    
    /**
     * content directory
     *
     * @var string
     */
    protected $contentDirectory = "";

    /**
     * Triggered after Pico has read its configuration
     *
     * @see Pico::getConfig()
     *
     * @param array &$config array of config variables
     */
    public function onConfigLoaded($config)
    {
        $this->contentDirectory = $config["content_dir"];
    }

    /**
     * Triggered after Pico has evaluated the request URL
     *
     * @see Pico::getRequestUrl()
     *
     * @param string &$url part of the URL describing the requested contents
     */
    public function onRequestUrl(&$url)
    {
        // is match .dot?
        if (!preg_match("/\.dot$/", $url)) {
            return;
        }

        // set .dot file path
        $dotFile = "{$this->contentDirectory}{$url}";

        // is file?
        if (!is_file($dotFile)) {
           return;
        }

        // get PNG binary data by graphviz (dot command)
        $output = shell_exec("/usr/bin/env dot -Tpng " . escapeshellarg($dotFile));
        
        // set HTTP headers.
        header("Content-Type: image/png");
        header("Content-Length: " . strlen($output));
        
        // output PNG data
        print $output;
        
        exit();
    }

}
