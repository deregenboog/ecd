<?php
/**
 * XML Helper class file.
 *
 * Simplifies the output of XML documents.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.2
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Core', ['Xml', 'Set']);

/**
 * XML Helper class for easy output of XML structures.
 *
 * XmlHelper encloses all methods needed while working with XML documents.
 *
 * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/XML.html#XML
 */
class XmlHelper extends AppHelper
{
    /**
     * Default document encoding.
     *
     * @var string
     */
    public $encoding = 'UTF-8';

    public $Xml;
    public $XmlElement;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->Xml = new Xml();
        $this->Xml->options(['verifyNs' => false]);
    }

    /**
     * Returns an XML document header.
     *
     * @param array $attrib Header tag attributes
     *
     * @return string XML header
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/XML.html#header
     */
    public function header($attrib = [])
    {
        if (null !== Configure::read('App.encoding')) {
            $this->encoding = Configure::read('App.encoding');
        }

        if (is_array($attrib)) {
            $attrib = array_merge(['encoding' => $this->encoding], $attrib);
        }
        if (is_string($attrib) && 0 !== strpos($attrib, 'xml')) {
            $attrib = 'xml '.$attrib;
        }

        return $this->Xml->header($attrib);
    }

    /**
     * Adds a namespace to any documents generated.
     *
     * @param string $name The namespace name
     * @param string $url  The namespace URI; can be empty if in the default namespace map
     *
     * @return bool False if no URL is specified, and the namespace does not exist
     *              default namespace map, otherwise true
     *
     * @deprecated
     * @see Xml::addNs()
     */
    public function addNs($name, $url = null)
    {
        return $this->Xml->addNamespace($name, $url);
    }

    /**
     * Removes a namespace added in addNs().
     *
     * @param string $name The namespace name or URI
     *
     * @deprecated
     * @see Xml::removeNs()
     */
    public function removeNs($name)
    {
        return $this->Xml->removeGlobalNamespace($name);
    }

    /**
     * Generates an XML element.
     *
     * @param string $name    The name of the XML element
     * @param array  $attrib  The attributes of the XML element
     * @param mixed  $content XML element content
     * @param bool   $endTag  Whether the end tag of the element should be printed
     *
     * @return string XML
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/XML.html#elem
     */
    public function elem($name, $attrib = [], $content = null, $endTag = true)
    {
        $namespace = null;
        if (isset($attrib['namespace'])) {
            $namespace = $attrib['namespace'];
            unset($attrib['namespace']);
        }
        $cdata = false;
        if (is_array($content) && isset($content['cdata'])) {
            $cdata = true;
            unset($content['cdata']);
        }
        if (is_array($content) && array_key_exists('value', $content)) {
            $content = $content['value'];
        }
        $children = [];
        if (is_array($content)) {
            $children = $content;
            $content = null;
        }

        $elem = &$this->Xml->createElement($name, $content, $attrib, $namespace);
        foreach ($children as $child) {
            $elem->createElement($child);
        }
        $out = $elem->toString(['cdata' => $cdata, 'leaveOpen' => !$endTag]);

        if (!$endTag) {
            $this->XmlElement = &$elem;
        }

        return $out;
    }

    /**
     * Create closing tag for current element.
     *
     * @return string
     */
    public function closeElem()
    {
        $elem = (empty($this->XmlElement)) ? $this->Xml : $this->XmlElement;
        $name = $elem->name();
        if ($parent = &$elem->parent()) {
            $this->XmlElement = &$parent;
        }

        return '</'.$name.'>';
    }

    /**
     * Serializes a model resultset into XML.
     *
     * @param mixed $data    The content to be converted to XML
     * @param array $options The data formatting options.  For a list of valid options, see
     *                       Xml::__construct().
     *
     * @return string A copy of $data in XML format
     *
     * @see Xml::__construct()
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/XML.html#serialize
     */
    public function serialize($data, $options = [])
    {
        $options += ['attributes' => false, 'format' => 'attributes'];
        $data = new Xml($data, $options);

        return $data->toString($options + ['header' => false]);
    }
}
