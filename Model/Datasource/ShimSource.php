<?php
/**
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/utility/blob/master/license.md
 * @link        http://milesj.me/code/cakephp/utility
 */

App::uses('DataSource', 'Model/Datasource');

/**
 * A Model DataSource that does nothing and is used to trick the model layer for specific functionality.
 * Is used by the CacheableBehavior.
 *
 * {{{
 *        public $shim = array('datasource' => 'Utility.ShimSource');
 * }}}
 *
 * @version		1.0.0
 * @copyright	Copyright 2006-2012, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/cakephp/utility
 */

App::uses('DataSource', 'Model/Datasource');

class ShimSource extends DataSource {

    /**
     * Return the Model schema.
     *
     * @param Model|string $model
     * @return array
     */
    public function describe($model) {
        return $model->schema();
    }

    /**
     * Return $data else the query will fail.
     *
     * @param mixed $data
     * @return array|null
     */
    public function listSources($data = null) {
        return $data;
    }

}