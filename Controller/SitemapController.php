<?php
/**
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/utility/blob/master/license.md
 * @link        http://milesj.me/code/cakephp/utility
 */

App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Utility');

/**
 * Handles sitemap generation for search engines.
 */
class SitemapController extends AppController {

    /**
     * Components.
     *
     * @type array
     */
    public $components = array('RequestHandler');

    /**
     * Loop through active models and generate sitemap data.
     * When using a model, you should update your routes to look something like:
     * Router::connect('/sitemap-places', array('plugin' => 'utility', 'controller' => 'sitemap', 'action' => 'index', 'ext' => 'json', 'Place'));
     * @param string $model The model to generate a sitemap for
     * @param boolean $imageheader Whether or not to use the image xml header
     * @return void 
     */
    public function index($model = null, $imageheader = false) {
        $sitemap = array();

        if($imageheader){
            $this->set(compact('imageheader'));
        }

        if( empty( $model ) ){
            
            $models = App::objects('Model');

            // Fetch sitemap data for all models
            foreach ($models as $model) {

                // Don't load AppModel's, Model's who can't be found
                if ( strpos($model, 'AppModel') !== false ) {
                    continue;
                }

                $instance = ClassRegistry::init($model);

                if ( method_exists($instance, '_generateSitemap') ) {
                    if ($data = $instance->_generateSitemap()) {
                        $sitemap = array_merge($sitemap, $data);
                    }
                }
            }

        } else {

             // Don't load AppModel's, Model's who can't be found
            if ( strpos($model, 'AppModel') !== false ) {
                continue;
            }

            $instance = ClassRegistry::init($model);

            if ( method_exists($instance, '_generateSitemap') ) {
                if ($data = $instance->_generateSitemap()) {
                    $sitemap = array_merge($sitemap, $data);
                }
            }

        }

        // Cleanup sitemap
        if ($sitemap) {
            foreach ($sitemap as &$item) {

                if (is_array($item['loc'])) {
                    if (!isset($item['loc']['plugin'])) {
                        $item['loc']['plugin'] = false;
                    }

                    $item['loc'] = h(Router::url($item['loc'], true));
                }

                if (array_key_exists('lastmod', $item)) {
                    if (!$item['lastmod']) {
                        unset($item['lastmod']);
                    } else {
                        $item['lastmod'] = CakeTime::format(DateTime::W3C, $item['lastmod']);
                    }
                }
            }
        }

        // Render view and don't use specific view engines
        $this->RequestHandler->respondAs($this->request->params['ext']);

        $this->set('sitemap', $sitemap);
    }

}