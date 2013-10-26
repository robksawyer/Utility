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
     */
    public function index() {
        $models = App::objects('Model');
        $sitemap = array();

        // Fetch sitemap data
        foreach ($models as $model) {

            // Don't load AppModel's, Model's who can't be found
            if ( strpos($controller, 'AppModel') !== false ) {
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