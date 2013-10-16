<?php
/**
 * Sitewards_SolrCore_Model_Query
 *
 * @category    Sitewards
 * @package     Sitewards_SolrCore
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 */
class Sitewards_SolrCore_Model_Query extends Mage_CatalogSearch_Model_Query {
    /**
     * Collection object for use in the suggest
     *
     * @var object
     */
    protected $_oCollection;
    protected $_oProductCollection;

    /**
     * Dispatch an event when the suggest collection is requested
     *
     * @see Mage_CatalogSearch_Model_Query::getSuggestCollection()
     */
    public function getSuggestCollection () {
        Mage::dispatchEvent('sitewards_solr_get_suggest_collection', array('query' => $this));
        if (is_null($this->_oCollection)) {
            $this->setSuggestCollection(parent::getSuggestCollection());
        }
        return $this->_oCollection;
    }

    public function getSuggestProductCollection () {
        return $this->_oProductCollection;
    }

    /**
     * Set the suggest collection
     *
     * @param object $oSuggestCollection
     */
    public function setSuggestCollection ($oSuggestCollection) {
        $this->_oCollection = $oSuggestCollection;
    }

    public function setSuggestProductCollection ($oSuggestCollection) {
        $this->_oProductCollection = $oSuggestCollection;
    }
}