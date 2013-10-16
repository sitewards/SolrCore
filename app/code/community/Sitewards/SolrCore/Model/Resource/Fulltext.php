<?php
/**
 * Sitewards_SolrCore_Model_Resource_Fulltext
 *
 * @category    Sitewards
 * @package     Sitewards_SolrCore
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 */
class Sitewards_SolrCore_Model_Resource_Fulltext extends Mage_CatalogSearch_Model_Resource_Fulltext {
    /**
     * An array of search results
     *
     * @var array
     */
    private $_aResult = array();

    /**
     * Dispatch an event on _prepareProductIndex allowing for other modules to extend this class
     *
     * @see Mage_CatalogSearch_Model_Resource_Fulltext::_prepareProductIndex()
     * @param array   $aIndexData
     * @param array   $aProductData
     * @param integer $iStoreId
     * @return array
     */
    protected function _prepareProductIndex ($aIndexData, $aProductData, $iStoreId) {
        $this->_aResult = parent::_prepareProductIndex($aIndexData, $aProductData, $iStoreId);
        Mage::dispatchEvent(
            'sitewards_solr_prepare_product_index',
            array(
                 'fulltext'   => $this,
                 'product_id' => $aProductData['entity_id'],
                 'store_id'   => $iStoreId,
            )
        );
        return $this->_aResult;
    }

    /**
     * Set a key value pair on the resutls array
     *
     * @param string $sKey
     * @param array  $aValues
     */
    public function addResult ($sKey, $aValues) {
        $this->_aResult[$sKey] = $aValues;
    }

    /**
     * Set the results array to an empty array
     */
    public function removeResult () {
        $this->_aResult = array();
    }

    /**
     * Return the set results array
     *
     * @return array
     */
    public function getResults () {
        return $this->_aResult;
    }
}