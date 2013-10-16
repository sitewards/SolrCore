<?php
/**
 * Sitewards_SolrCore_Model_Adapter_HttpStream
 * Core HttpStream for the Search adapter
 *
 * @category    Sitewards
 * @package     Sitewards_SolrCore
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 */
class Sitewards_SolrCore_Model_Adapter_HttpStream extends Enterprise_Search_Model_Adapter_HttpStream {
    /**
     * An array of search results
     *
     * @var array
     */
    private $_aResults = array();
    /**
     * String of search queries formatted for Solr
     *
     * @var string
     */
    private $_sSearchConditions = null;

    /**
     * Dispatch an event after calling the parent constructor
     *
     * @param array $aOptions
     */
    public function __construct ($aOptions = array()) {
        parent::__construct($aOptions);
        Mage::dispatchEvent('sitewards_solr_extend_index_fields', array('indexer_context' => $this));
    }

    /**
     * Simple Search interface
     *
     * @param string $sQuery  The raw query string
     * @param array  $aParams Parameters for the search
     * @return array
     */
    protected function _search ($sQuery, $aParams = array()) {
        Mage::dispatchEvent(
            'sitewards_solr_search_interface', array(
                                                    'search_context' => $this, 'query' => $sQuery,
                                                    'params'         => $aParams));
        if (is_null($this->_aResults)) {
            $this->setResults(parent::_search($sQuery, $aParams));
        }
        return $this->_aResults;
    }

    /**
     * Dispatch an event after calling the parent function
     *
     * @see Enterprise_Search_Model_Adapter_Solr_Abstract::prepareSearchConditions()
     * @return string
     */
    protected function prepareSearchConditions ($mQuery) {
        $this->setSearchConditions(parent::prepareSearchConditions($mQuery));
        Mage::dispatchEvent('sitewards_solr_search_conditions', array('search_context' => $this, 'query' => $mQuery));
        return $this->_sSearchConditions;
    }

    /**
     * Assign a string for field name to the _usedFields options
     *
     * @param string $sField
     */
    public function addUsedField ($sField) {
        $this->_usedFields[] = $sField;
    }

    /**
     * This function will allow the observers to call a parent search
     *
     * @param string $sQuery
     * @param array  $aParams
     * @return array
     */
    public function doParentSearch ($sQuery, $aParams) {
        return parent::_search($sQuery, $aParams);
    }

    /**
     * Assign results from the observing classes
     *
     * @param array $aResults
     */
    public function setResults ($aResults) {
        $this->_aResults = $aResults;
    }

    /**
     * Set search conditions
     *
     * @param string $sSearchConditions
     */
    public function setSearchConditions ($sSearchConditions) {
        $this->_sSearchConditions = $sSearchConditions;
    }

    /**
     * Create an array for multiple search conditions
     *
     * @param array $aSearchConditions
     */
    public function addSearchConditions ($aSearchConditions) {
        if (isset($this->_sSearchConditions)) {
            $aSearchConditions[] = $this->_sSearchConditions;
        }
        $this->_sSearchConditions = implode(' OR ', $aSearchConditions);
    }
}