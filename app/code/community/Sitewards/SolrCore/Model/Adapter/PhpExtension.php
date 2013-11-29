<?php
/**
 * Sitewards_SolrCore_Model_Adapter_PhpExtension
 * Core PhpExtension for the Search adapter
 *
 * @category    Sitewards
 * @package     Sitewards_SolrCore
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 */
class Sitewards_SolrCore_Model_Adapter_PhpExtension extends Enterprise_Search_Model_Adapter_PhpExtension {
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
     * Assign a string for field name to the _usedFields options
     *
     * @param string $sField
     */
    public function addUsedField ($sField) {
        $this->_usedFields[] = $sField;
    }

    /**
     * Simple Search interface
     *
     * @param string $sQuery  The raw query string
     * @param array  $aParams Parameters for the search
     * @return array
     */
    protected function _search ($sQuery, $aParams = array()) {
        $this->setResults(parent::_search($sQuery, $aParams));
        Mage::dispatchEvent(
            'sitewards_solr_search_interface', array(
                                                    'search_context' => $this, 'query' => $sQuery,
                                                    'params'         => $aParams));
        return $this->_aResults;
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
}