<?php
class __CronkGridTemplateXmlParserInternalCacheContainer__ {
    public $data = array();
    public $fields = array();
    public $rewrite = null;
}
class CronkGridTemplateXmlParser {

    /**
     * @var DOMDocument
     */
    private $dom				= null;

    private $data				= array();

    private $fields				= array();

    private $ready				= false;

    private static $available	= array(
                                      'datasource', 'meta', 'option', 'fields'
                                  );
    private $filename           = "";
    /**
     * Object to replace some values
     * @var CronkGridTemplateXmlReplace
     */
    private $rewrite			= null;

    private $useCaching         = false;
    private $maxCacheTime       = 14400;
    private $cachedContent      = null;
    private $cacheHit           = false;
    private function initCaching() {
        $cfg = AgaviConfig::get('modules.cronks.templates');

        if (isset($cfg['use_caching'])) {
            $this->useCaching = $cfg['use_caching'];
        }

        if (isset($cfg['cache_dir'])) {
            $this->cache_dir = $cfg['cache_dir'];
        }

        if (!$this->cache_dir) {
            $this->useCaching = false;
        }

    }

    private function getCacheFilename($file) {
        $file = "template_".md5($file);

        $cached = $this->cache_dir.'/'.$file;

        if (!file_exists($this->cache_dir)) {
            AgaviToolkit::mkdir($this->cache_dir);
        }

        return $cached;
    }


    private function loadFromCache($file = null) {
        $this->initCaching();

        if ($file == null || !$this->useCaching) {
            return false;
        }

        $this->readCached($file);

        if (!$this->cachedContent instanceof __CronkGridTemplateXmlParserInternalCacheContainer__) {
            return false;
        }

        $this->data = $this->cachedContent->data;
        $this->fields = $this->cachedContent->fields;
        $this->cacheHit = true;

        return true;
    }

    private function readCached($file) {
        $cached = $this->getCacheFilename($file);

        if (file_exists($cached)  && is_readable($cached)) {
            // check cache date
            if (time()-filemtime($cached) > $this->maxCacheTime) {
                return null;
            }

            $this->cachedContent = unserialize(file_get_contents($cached));

        }

        return null;
    }

    private function cacheContent($file) {

        if (!$this->useCaching) {
            return false;
        }

        $cached = $this->getCacheFilename($file);
        $cacheDir = dirname($cached);

        if (file_exists($cached) && !is_writeable($cached)) {
            return;
        }

        if (!is_dir($cacheDir) || !is_writeable($cacheDir)) {
            return;
        }

        $container = new __CronkGridTemplateXmlParserInternalCacheContainer__();
        $container->data = $this->data;
        $container->fields = $this->fields;

        file_put_contents($cached,serialize($container));
    }



    /**
     * Generic constructor
     * @param string $file
     * @return CLASS
     */
    public function __construct($file = null) {
        if (!$this->loadFromCache($file)) {

            if (file_exists($file)) {
                $this->loadFile($file);
            }

            $this->rewrite = new CronkGridTemplateXmlReplace();
        }
    }

    /**
     * Inits the dom with a file
     * @param string $file
     * @return boolean
     */
    public function loadFile($file) {
        if (file_exists($file)) {
            $this->file = $file;
            return $this->loadXml(file_get_contents($file));
        }

        throw new CronkGridTemplateXmlParserException('File does not exist');
    }

    /**
     * inits the dom with a string of xml data
     * @param string $xml
     * @return boolean
     */
    public function loadXml($xml) {
        $this->resetState();
        $this->dom = new DOMDocument();
        $this->dom->preserveWhiteSpace = false;
        $this->dom->loadXML($xml);
        return true;
    }

    /**
     * Reset the parser state to an empty object
     * @return boolean
     */
    public function resetState() {
        $this->dom		= null;
        $this->data		= array();
        $this->fields	= array();
        $this->ready	= false;
        return true;
    }

    public function getFields() {
        return $this->fields;
    }

    public function getFieldKeys() {
        return array_keys($this->fields);
    }

    /**
     * Returns an parameter object from a field
     * @param string $name
     * @return AgaviParameterHolder
     */
    public function getFieldByName($name, $type=null) {
        if (array_key_exists($name, $this->fields)) {
            $arry =& $this->fields[$name];

            if ($type !== null) {
                if (array_key_exists($type, $arry)) {
                    $arry =& $arry[$type];
                } else {
                    throw new CronkGridTemplateXmlParserException('Type '. $type. ' does not exist!');
                }
            }

            return new AgaviParameterHolder($arry);
        }

        // Empty one
        return new AgaviParameterHolder(array());
    }

    /**
     * Return all template data as an array
     * @return array
     */
    public function getTemplateData() {
        return $this->data;
    }

    /**
     * Return a template section as an array
     * @param string $name
     * @return array
     */
    public function getSection($name) {
        return $this->data[ $name ];
    }

    /**
     * Return named sections as an array
     * @return array
     */
    public function getSections() {
        return array_keys($this->data);
    }

    /**
     * Returns a parameter object from section
     * @param $name
     * @return AgaviParameterHolder
     */
    public function getSectionParams($name) {
        return new AgaviParameterHolder($this->getSection($name));
    }

    /**
     * Start parsing the template
     * @return boolean
     */
    public function parseTemplate() {
        if ($this->cacheHit) {
            return true;
        }

        if (!$this->dom instanceof DOMDocument) {
            // throw new CronkGridTemplateXmlParserException('DOMDocument not ready!');
        }

        $storage = array();

        // Parse the template structure
        $this->parseDom($this->domRoot(), $storage);

        // Move the data to its place
        $this->fields = $storage['fields'];
        unset($storage['fields']);

        $this->data = $storage;
        unset($storage);

        // Check data
        if (count($this->fields) && count($this->data)) {
            $this->cacheContent($this->file);
            return true;
        }

        throw new CronkGridTemplateXmlParserException('Empty xml!');

        return false;
    }

    /**
     * Returns the root node
     * @return DOMElement
     */
    private function domRoot() {
        static $root = null;

        if ($root === null) {
            $root = $this->dom->getElementsByTagName('template')->item(0);
        }

        return $root;
    }

    private function elementHasElementChilds(DOMElement &$element) {
        if ($element->hasChildNodes()) {
            foreach($element->childNodes as $node) {
                if ($node->nodeType == XML_ELEMENT_NODE) {
                    return true;
                }
            }
        }
    }

    /**
     * Detects constants within parameter names and resolve values
     * @param string $name
     * @return mixed
     */
    private function rewriteParamName($name) {
        if (strstr($name, '::')) {

            if (defined($name)) {
                $name = AppKit::getConstant($name);
            }

        }

        return $name;
    }

    private function parseDom(DOMElement $element, array &$storage) {


        if ($element->hasChildNodes()) {
            foreach($element->childNodes as $child) {
                if ($child->nodeType == XML_ELEMENT_NODE) {
                    $index = '__BAD_INDEX';

                    if ($child->hasAttribute('name')) {
                        $index = $this->rewrite->replaceKey($child->getAttribute('name'));
                    }

                    elseif($child->nodeName == 'parameter') {
                        $index = count($storage);
                    }
                    else {
                        $index = $child->nodeName;
                    }

                    if ($this->elementHasElementChilds($child)) {
                        $storage [ $index ] = array();
                        $this->parseDom($child, $storage [ $index ]);
                    } else {

                        // Substitute boolean or numbers, ...
                        $storage [ $index ] = $this->rewrite->replaceValue($child->textContent);
                    }

                }
            }
        }
    }

    public function getHeaderArray() {
        $header = array();
        foreach($this->getFieldKeys() as $field) {
            $params = $this->getFieldByName($field, 'display');

            if ($params->getParameter('visible') == true) {
                $header[$field] = $params->getParameter('label', $field);
            }
        }
        return $header;
    }
}

class CronkGridTemplateXmlParserException extends AppKitException { }

?>
