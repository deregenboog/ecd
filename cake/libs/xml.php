<?php
/**
 * XML handling for Cake.
 *
 * The methods in these classes enable the datasources that use XML to work.
 *
 * PHP versions 4 and 5
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
 * @since         CakePHP v .0.10.3.1400
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Core', 'Set');

/**
 * XML node.
 *
 * Single XML node in an XML tree.
 *
 * @since         CakePHP v .0.10.3.1400
 */
class XmlNode extends Object
{
    /**
     * Name of node.
     *
     * @var string
     */
    public $name = null;

    /**
     * Node namespace.
     *
     * @var string
     */
    public $namespace = null;

    /**
     * Namespaces defined for this node and all child nodes.
     *
     * @var array
     */
    public $namespaces = [];

    /**
     * Value of node.
     *
     * @var string
     */
    public $value;

    /**
     * Attributes on this node.
     *
     * @var array
     */
    public $attributes = [];

    /**
     * This node's children.
     *
     * @var array
     */
    public $children = [];

    /**
     * Reference to parent node.
     *
     * @var XmlNode
     */
    public $__parent = null;

    /**
     * Constructor.
     *
     * @param string $name       Node name
     * @param array  $attributes Node attributes
     * @param mixed  $value      Node contents (text)
     * @param array  $children   Node children
     */
    public function __construct($name = null, $value = null, $namespace = null)
    {
        if (false !== strpos($name, ':')) {
            list($prefix, $name) = explode(':', $name);
            if (!$namespace) {
                $namespace = $prefix;
            }
        }
        $this->name = $name;
        if ($namespace) {
            $this->namespace = $namespace;
        }

        if (is_array($value) || is_object($value)) {
            $this->normalize($value);
        } elseif (!empty($value) || 0 === $value || '0' === $value) {
            $this->createTextNode($value);
        }
    }

    /**
     * Adds a namespace to the current node.
     *
     * @param string $prefix The namespace prefix
     * @param string $url    The namespace DTD URL
     */
    public function addNamespace($prefix, $url)
    {
        if ($ns = Xml::addGlobalNs($prefix, $url)) {
            $this->namespaces = array_merge($this->namespaces, $ns);

            return true;
        }

        return false;
    }

    /**
     * Adds a namespace to the current node.
     *
     * @param string $prefix The namespace prefix
     * @param string $url    The namespace DTD URL
     */
    public function removeNamespace($prefix)
    {
        if (Xml::removeGlobalNs($prefix)) {
            return true;
        }

        return false;
    }

    /**
     * Creates an XmlNode object that can be appended to this document or a node in it.
     *
     * @param string $name      Node name
     * @param string $value     Node value
     * @param string $namespace Node namespace
     *
     * @return object XmlNode
     */
    public function &createNode($name = null, $value = null, $namespace = false)
    {
        $node = new self($name, $value, $namespace);
        $node->setParent($this);

        return $node;
    }

    /**
     * Creates an XmlElement object that can be appended to this document or a node in it.
     *
     * @param string $name       Element name
     * @param string $value      Element value
     * @param array  $attributes Element attributes
     * @param string $namespace  Node namespace
     *
     * @return object XmlElement
     */
    public function &createElement($name = null, $value = null, $attributes = [], $namespace = false)
    {
        $element = new XmlElement($name, $value, $attributes, $namespace);
        $element->setParent($this);

        return $element;
    }

    /**
     * Creates an XmlTextNode object that can be appended to this document or a node in it.
     *
     * @param string $value Node value
     *
     * @return object XmlTextNode
     */
    public function &createTextNode($value = null)
    {
        $node = new XmlTextNode($value);
        $node->setParent($this);

        return $node;
    }

    /**
     * Gets the XML element properties from an object.
     *
     * @param object $object Object to get properties from
     *
     * @return array Properties from object
     */
    public function normalize($object, $keyName = null, $options = [])
    {
        if (is_a($object, 'XmlNode')) {
            return $object;
        }
        $name = null;
        $options += ['format' => 'attributes'];

        if (null !== $keyName && !is_numeric($keyName)) {
            $name = $keyName;
        } elseif (!empty($object->_name_)) {
            $name = $object->_name_;
        } elseif (isset($object->name)) {
            $name = $object->name;
        } elseif ('attributes' == $options['format']) {
            $name = get_class($object);
        }

        $tagOpts = $this->__tagOptions($name);

        if (false === $tagOpts) {
            return;
        }

        if (isset($tagOpts['name'])) {
            $name = $tagOpts['name'];
        } elseif ($name != strtolower($name) && false !== $options['slug']) {
            $name = Inflector::slug(Inflector::underscore($name));
        }

        if (!empty($name)) {
            $node = &$this->createElement($name);
        } else {
            $node = &$this;
        }

        $namespace = [];
        $attributes = [];
        $children = [];
        $chldObjs = [];

        if (is_object($object)) {
            $chldObjs = get_object_vars($object);
        } elseif (is_array($object)) {
            $chldObjs = $object;
        } elseif (!empty($object) || 0 === $object || '0' === $object) {
            $node->createTextNode($object);
        }
        $attr = [];

        if (isset($tagOpts['attributes'])) {
            $attr = $tagOpts['attributes'];
        }
        if (isset($tagOpts['value']) && isset($chldObjs[$tagOpts['value']])) {
            $node->createTextNode($chldObjs[$tagOpts['value']]);
            unset($chldObjs[$tagOpts['value']]);
        }

        $n = $name;
        if (isset($chldObjs['_name_'])) {
            $n = null;
            unset($chldObjs['_name_']);
        }
        $c = 0;

        foreach ($chldObjs as $key => $val) {
            if (in_array($key, $attr) && !is_object($val) && !is_array($val)) {
                $attributes[$key] = $val;
            } else {
                if (!isset($tagOpts['children']) || $tagOpts['children'] === [] || (is_array($tagOpts['children']) && in_array($key, $tagOpts['children']))) {
                    if (!is_numeric($key)) {
                        $n = $key;
                    }
                    if (is_array($val)) {
                        foreach ($val as $n2 => $obj2) {
                            if (is_numeric($n2)) {
                                $n2 = $n;
                            }
                            $node->normalize($obj2, $n2, $options);
                        }
                    } else {
                        if (is_object($val)) {
                            $node->normalize($val, $n, $options);
                        } elseif ('tags' == $options['format'] && false !== $this->__tagOptions($key)) {
                            if (true == $options['slug']) {
                                $key = Inflector::slug(Inflector::underscore($key));
                            }
                            $tmp = &$node->createElement($key);
                            if (!empty($val) || 0 === $val || '0' === $val) {
                                $tmp->createTextNode($val);
                            }
                        } elseif ('attributes' == $options['format']) {
                            $node->addAttribute($key, $val);
                        }
                    }
                }
            }
            ++$c;
        }
        if (!empty($name)) {
            return $node;
        }

        return $children;
    }

    /**
     * Gets the tag-specific options for the given node name.
     *
     * @param string $name   XML tag name
     * @param string $option The specific option to query.  Omit for all options
     *
     * @return mixed A specific option value if $option is specified, otherwise an array of all options
     */
    public function __tagOptions($name, $option = null)
    {
        if (isset($this->__tags[$name])) {
            $tagOpts = $this->__tags[$name];
        } elseif (isset($this->__tags[strtolower($name)])) {
            $tagOpts = $this->__tags[strtolower($name)];
        } else {
            return null;
        }
        if (false === $tagOpts) {
            return false;
        }
        if (empty($option)) {
            return $tagOpts;
        }
        if (isset($tagOpts[$option])) {
            return $tagOpts[$option];
        }

        return null;
    }

    /**
     * Returns the fully-qualified XML node name, with namespace.
     */
    public function name()
    {
        if (!empty($this->namespace)) {
            $_this = &XmlManager::getInstance();
            if (!isset($_this->options['verifyNs']) || !$_this->options['verifyNs'] || in_array($this->namespace, array_keys($_this->namespaces))) {
                return $this->namespace.':'.$this->name;
            }
        }

        return $this->name;
    }

    /**
     * Sets the parent node of this XmlNode.
     */
    public function setParent(&$parent)
    {
        if ('xml' == strtolower(get_class($this))) {
            return;
        }
        if (isset($this->__parent) && is_object($this->__parent)) {
            if ($this->__parent->compare($parent)) {
                return;
            }
            foreach ($this->__parent->children as $i => $child) {
                if ($this->compare($child)) {
                    array_splice($this->__parent->children, $i, 1);
                    break;
                }
            }
        }
        if (null == $parent) {
            unset($this->__parent);
        } else {
            $parent->children[] = &$this;
            $this->__parent = &$parent;
        }
    }

    /**
     * Returns a copy of self.
     *
     * @return object Cloned instance
     */
    public function cloneNode()
    {
        return clone $this;
    }

    /**
     * Compares $node to this XmlNode object.
     *
     * @param object An XmlNode or subclass instance
     *
     * @return bool True if the nodes match, false otherwise
     */
    public function compare($node)
    {
        $keys = [get_object_vars($this), get_object_vars($node)];

        return $keys[0] === $keys[1];
    }

    /**
     * Append given node as a child.
     *
     * @param object $child   XmlNode with appended child
     * @param array  $options XML generator options for objects and arrays
     *
     * @return object A reference to the appended child node
     */
    public function &append(&$child, $options = [])
    {
        if (empty($child)) {
            $return = false;

            return $return;
        }

        if (is_object($child)) {
            if ($this->compare($child)) {
                trigger_error(__('Cannot append a node to itself.', true));
                $return = false;

                return $return;
            }
        } elseif (is_array($child)) {
            $child = Set::map($child);
            if (is_array($child)) {
                if (!is_a(current($child), 'XmlNode')) {
                    foreach ($child as $i => $childNode) {
                        $child[$i] = $this->normalize($childNode, null, $options);
                    }
                } else {
                    foreach ($child as $childNode) {
                        $this->append($childNode, $options);
                    }
                }

                return $child;
            }
        } else {
            $attributes = [];
            if (func_num_args() >= 2) {
                $attributes = func_get_arg(1);
            }
            $child = &$this->createNode($child, null, $attributes);
        }

        $child = $this->normalize($child, null, $options);

        if (empty($child->namespace) && !empty($this->namespace)) {
            $child->namespace = $this->namespace;
        }

        if (is_a($child, 'XmlNode')) {
            $child->setParent($this);
        }

        return $child;
    }

    /**
     * Returns first child node, or null if empty.
     *
     * @return object First XmlNode
     */
    public function &first()
    {
        if (isset($this->children[0])) {
            return $this->children[0];
        } else {
            $return = null;

            return $return;
        }
    }

    /**
     * Returns last child node, or null if empty.
     *
     * @return object Last XmlNode
     */
    public function &last()
    {
        if (count($this->children) > 0) {
            return $this->children[count($this->children) - 1];
        } else {
            $return = null;

            return $return;
        }
    }

    /**
     * Returns child node with given ID.
     *
     * @param string $id Name of child node
     *
     * @return object Child XmlNode
     */
    public function &child($id)
    {
        $null = null;

        if (is_int($id)) {
            if (isset($this->children[$id])) {
                return $this->children[$id];
            } else {
                return null;
            }
        } elseif (is_string($id)) {
            for ($i = 0; $i < count($this->children); ++$i) {
                if ($this->children[$i]->name == $id) {
                    return $this->children[$i];
                }
            }
        }

        return $null;
    }

    /**
     * Gets a list of childnodes with the given tag name.
     *
     * @param string $name Tag name of child nodes
     *
     * @return array An array of XmlNodes with the given tag name
     */
    public function children($name)
    {
        $nodes = [];
        $count = count($this->children);
        for ($i = 0; $i < $count; ++$i) {
            if ($this->children[$i]->name == $name) {
                $nodes[] = &$this->children[$i];
            }
        }

        return $nodes;
    }

    /**
     * Gets a reference to the next child node in the list of this node's parent.
     *
     * @return object A reference to the XmlNode object
     */
    public function &nextSibling()
    {
        $null = null;
        $count = count($this->__parent->children);
        for ($i = 0; $i < $count; ++$i) {
            if ($this->__parent->children[$i] == $this) {
                if ($i >= $count - 1 || !isset($this->__parent->children[$i + 1])) {
                    return $null;
                }

                return $this->__parent->children[$i + 1];
            }
        }

        return $null;
    }

    /**
     * Gets a reference to the previous child node in the list of this node's parent.
     *
     * @return object A reference to the XmlNode object
     */
    public function &previousSibling()
    {
        $null = null;
        $count = count($this->__parent->children);
        for ($i = 0; $i < $count; ++$i) {
            if ($this->__parent->children[$i] == $this) {
                if (0 == $i || !isset($this->__parent->children[$i - 1])) {
                    return $null;
                }

                return $this->__parent->children[$i - 1];
            }
        }

        return $null;
    }

    /**
     * Returns parent node.
     *
     * @return object Parent XmlNode
     */
    public function &parent()
    {
        return $this->__parent;
    }

    /**
     * Returns the XML document to which this node belongs.
     *
     * @return object Parent XML object
     */
    public function &document()
    {
        $document = &$this;
        while (true) {
            if ('Xml' == get_class($document) || null == $document) {
                break;
            }
            $document = &$document->parent();
        }

        return $document;
    }

    /**
     * Returns true if this structure has child nodes.
     *
     * @return bool
     */
    public function hasChildren()
    {
        if (is_array($this->children) && !empty($this->children)) {
            return true;
        }

        return false;
    }

    /**
     * Returns this XML structure as a string.
     *
     * @return string string representation of the XML structure
     */
    public function toString($options = [], $depth = 0)
    {
        if (is_int($options)) {
            $depth = $options;
            $options = [];
        }
        $defaults = ['cdata' => true, 'whitespace' => false, 'convertEntities' => false, 'showEmpty' => true, 'leaveOpen' => false];
        $options = array_merge($defaults, Xml::options(), $options);
        $tag = !(0 === strpos($this->name, '#'));
        $d = '';

        if ($tag) {
            if ($options['whitespace']) {
                $d .= str_repeat("\t", $depth);
            }

            $d .= '<'.$this->name();
            if (!empty($this->namespaces) > 0) {
                foreach ($this->namespaces as $key => $val) {
                    $val = str_replace('"', '\"', $val);
                    $d .= ' xmlns:'.$key.'="'.$val.'"';
                }
            }

            $parent = &$this->parent();
            if ('#document' === $parent->name && !empty($parent->namespaces)) {
                foreach ($parent->namespaces as $key => $val) {
                    $val = str_replace('"', '\"', $val);
                    $d .= ' xmlns:'.$key.'="'.$val.'"';
                }
            }

            if (is_array($this->attributes) && !empty($this->attributes)) {
                foreach ($this->attributes as $key => $val) {
                    if (is_bool($val) && false === $val) {
                        $val = 0;
                    }
                    $d .= ' '.$key.'="'.htmlspecialchars($val, ENT_QUOTES, Configure::read('App.encoding')).'"';
                }
            }
        }

        if (!$this->hasChildren() && empty($this->value) && 0 !== $this->value && $tag) {
            if (!$options['leaveOpen']) {
                $d .= ' />';
            }
            if ($options['whitespace']) {
                $d .= "\n";
            }
        } elseif ($tag || $this->hasChildren()) {
            if ($tag) {
                $d .= '>';
            }
            if ($this->hasChildren()) {
                if ($options['whitespace']) {
                    $d .= "\n";
                }
                $count = count($this->children);
                $cDepth = $depth + 1;
                for ($i = 0; $i < $count; ++$i) {
                    $d .= $this->children[$i]->toString($options, $cDepth);
                }
                if ($tag) {
                    if ($options['whitespace'] && $tag) {
                        $d .= str_repeat("\t", $depth);
                    }
                    if (!$options['leaveOpen']) {
                        $d .= '</'.$this->name().'>';
                    }
                    if ($options['whitespace']) {
                        $d .= "\n";
                    }
                }
            }
        }

        return $d;
    }

    /**
     * Return array representation of current object.
     *
     * @param bool $camelize true will camelize child nodes, false will not alter node names
     *
     * @return array Array representation
     */
    public function toArray($camelize = true)
    {
        $out = $this->attributes;

        foreach ($this->children as $child) {
            $key = $camelize ? Inflector::camelize($child->name) : $child->name;

            $leaf = false;
            if (is_a($child, 'XmlTextNode')) {
                $out['value'] = $child->value;
                continue;
            } elseif (isset($child->children[0]) && is_a($child->children[0], 'XmlTextNode')) {
                $value = $child->children[0]->value;
                if ($child->attributes) {
                    $value = array_merge(['value' => $value], $child->attributes);
                }
                if (1 == count($child->children)) {
                    $leaf = true;
                }
            } elseif (0 === count($child->children) && '' == $child->value) {
                $value = $child->attributes;
                if (empty($value)) {
                    $leaf = true;
                }
            } else {
                $value = $child->toArray($camelize);
            }

            if (isset($out[$key])) {
                if (!isset($out[$key][0]) || !is_array($out[$key]) || !is_int(key($out[$key]))) {
                    $out[$key] = [$out[$key]];
                }
                $out[$key][] = $value;
            } elseif (isset($out[$child->name])) {
                $t = $out[$child->name];
                unset($out[$child->name]);
                $out[$key] = [$t];
                $out[$key][] = $value;
            } elseif ($leaf) {
                $out[$child->name] = $value;
            } else {
                $out[$key] = $value;
            }
        }

        return $out;
    }

    /**
     * Returns data from toString when this object is converted to a string.
     *
     * @return string string representation of this structure
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Debug method. Deletes the parent. Also deletes this node's children,
     * if given the $recursive parameter.
     *
     * @param bool $recursive recursively delete elements
     */
    public function _killParent($recursive = true)
    {
        unset($this->__parent, $this->_log);
        if ($recursive && $this->hasChildren()) {
            for ($i = 0; $i < count($this->children); ++$i) {
                $this->children[$i]->_killParent(true);
            }
        }
    }
}

/**
 * Main XML class.
 *
 * Parses and stores XML data, representing the root of an XML document
 *
 * @since         CakePHP v .0.10.3.1400
 */
class Xml extends XmlNode
{
    /**
     * Resource handle to XML parser.
     *
     * @var resource
     */
    public $__parser;

    /**
     * File handle to XML indata file.
     *
     * @var resource
     */
    public $__file;

    /**
     * Raw XML string data (for loading purposes).
     *
     * @var string
     */
    public $__rawData = null;

    /**
     * XML document header.
     *
     * @var string
     */
    public $__header = null;

    /**
     * Default array keys/object properties to use as tag names when converting objects or array
     * structures to XML. Set by passing $options['tags'] to this object's constructor.
     *
     * @var array
     */
    public $__tags = [];

    /**
     * XML document version.
     *
     * @var string
     */
    public $version = '1.0';

    /**
     * XML document encoding.
     *
     * @var string
     */
    public $encoding = 'UTF-8';

    /**
     * Constructor.  Sets up the XML parser with options, gives it this object as
     * its XML object, and sets some variables.
     *
     * ### Options
     * - 'root': The name of the root element, defaults to '#document'
     * - 'version': The XML version, defaults to '1.0'
     * - 'encoding': Document encoding, defaults to 'UTF-8'
     * - 'namespaces': An array of namespaces (as strings) used in this document
     * - 'format': Specifies the format this document converts to when parsed or
     *    rendered out as text, either 'attributes' or 'tags', defaults to 'attributes'
     * - 'tags': An array specifying any tag-specific formatting options, indexed
     *    by tag name.  See XmlNode::normalize().
     * - 'slug':  A boolean to indicate whether or not you want the string version of the XML document
     *   to have its tags run through Inflector::slug().  Defaults to true
     *
     * @param mixed $input   The content with which this XML document should be initialized.  Can be a
     *                       string, array or object.  If a string is specified, it may be a literal XML
     *                       document, or a URL or file path to read from.
     * @param array $options Options to set up with, for valid options see above:
     *
     * @see XmlNode::normalize()
     */
    public function __construct($input = null, $options = [])
    {
        $defaults = [
            'root' => '#document', 'tags' => [], 'namespaces' => [],
            'version' => '1.0', 'encoding' => 'UTF-8', 'format' => 'attributes',
            'slug' => true,
        ];
        $options = array_merge($defaults, self::options(), $options);

        foreach (['version', 'encoding', 'namespaces'] as $key) {
            $this->{$key} = $options[$key];
        }
        $this->__tags = $options['tags'];
        parent::__construct('#document');

        if ('#document' !== $options['root']) {
            $Root = &$this->createNode($options['root']);
        } else {
            $Root = &$this;
        }

        if (!empty($input)) {
            if (is_string($input)) {
                $Root->load($input);
            } elseif (is_array($input) || is_object($input)) {
                $Root->append($input, $options);
            }
        }
    }

    /**
     * Initialize XML object from a given XML string. Returns false on error.
     *
     * @param string $input XML string, a path to a file, or an HTTP resource to load
     *
     * @return bool Success
     */
    public function load($input)
    {
        if (!is_string($input)) {
            return false;
        }
        $this->__rawData = null;
        $this->__header = null;

        if (strstr($input, '<')) {
            $this->__rawData = $input;
        } elseif (0 === strpos($input, 'http://') || 0 === strpos($input, 'https://')) {
            App::import('Core', 'HttpSocket');
            $socket = new HttpSocket();
            $this->__rawData = $socket->get($input);
        } elseif (file_exists($input)) {
            $this->__rawData = file_get_contents($input);
        } else {
            trigger_error(__('XML cannot be read', true));

            return false;
        }

        return $this->parse();
    }

    /**
     * Parses and creates XML nodes from the __rawData property.
     *
     * @return bool Success
     *
     * @see Xml::load()
     *
     * @todo figure out how to link attributes and namespaces
     */
    public function parse()
    {
        $this->__initParser();
        $this->__rawData = trim($this->__rawData);
        $this->__header = trim(str_replace(
            ['<'.'?', '?'.'>'],
            ['', ''],
            substr($this->__rawData, 0, strpos($this->__rawData, '?'.'>'))
        ));

        xml_parse_into_struct($this->__parser, $this->__rawData, $vals);
        $xml = &$this;
        $count = count($vals);

        for ($i = 0; $i < $count; ++$i) {
            $data = $vals[$i];
            $data += ['tag' => null, 'value' => null, 'attributes' => []];
            switch ($data['type']) {
                case 'open':
                    $xml = &$xml->createElement($data['tag'], $data['value'], $data['attributes']);
                break;
                case 'close':
                    $xml = &$xml->parent();
                break;
                case 'complete':
                    $xml->createElement($data['tag'], $data['value'], $data['attributes']);
                break;
                case 'cdata':
                    $xml->createTextNode($data['value']);
                break;
            }
        }
        xml_parser_free($this->__parser);
        $this->__parser = null;

        return true;
    }

    /**
     * Initializes the XML parser resource.
     */
    public function __initParser()
    {
        if (empty($this->__parser)) {
            $this->__parser = xml_parser_create();
            xml_set_object($this->__parser, $this);
            xml_parser_set_option($this->__parser, XML_OPTION_CASE_FOLDING, 0);
            xml_parser_set_option($this->__parser, XML_OPTION_SKIP_WHITE, 1);
        }
    }

    /**
     * Returns a string representation of the XML object.
     *
     * @param mixed $options If boolean: whether to include the XML header with the document
     *                       (defaults to true); if an array, overrides the default XML generation options
     *
     * @return string XML data
     *
     * @deprecated
     * @see Xml::toString()
     */
    public function compose($options = [])
    {
        return $this->toString($options);
    }

    /**
     * If debug mode is on, this method echoes an error message.
     *
     * @param string $msg  Error message
     * @param int    $code Error code
     * @param int    $line Line in file
     */
    public function error($msg, $code = 0, $line = 0)
    {
        if (Configure::read('debug')) {
            echo $msg.' '.$code.' '.$line;
        }
    }

    /**
     * Returns a string with a textual description of the error code, or FALSE if no description was found.
     *
     * @param int $code Error code
     *
     * @return string Error message
     */
    public function getError($code)
    {
        $r = @xml_error_string($code);

        return $r;
    }

    // Overridden functions from superclass

    /**
     * Get next element. NOT implemented.
     *
     * @return object
     */
    public function &next()
    {
        $return = null;

        return $return;
    }

    /**
     * Get previous element. NOT implemented.
     *
     * @return object
     */
    public function &previous()
    {
        $return = null;

        return $return;
    }

    /**
     * Get parent element. NOT implemented.
     *
     * @return object
     */
    public function &parent()
    {
        $return = null;

        return $return;
    }

    /**
     * Adds a namespace to the current document.
     *
     * @param string $prefix The namespace prefix
     * @param string $url    The namespace DTD URL
     */
    public function addNamespace($prefix, $url)
    {
        if ($count = count($this->children)) {
            for ($i = 0; $i < $count; ++$i) {
                $this->children[$i]->addNamespace($prefix, $url);
            }

            return true;
        }

        return parent::addNamespace($prefix, $url);
    }

    /**
     * Removes a namespace to the current document.
     *
     * @param string $prefix The namespace prefix
     */
    public function removeNamespace($prefix)
    {
        if ($count = count($this->children)) {
            for ($i = 0; $i < $count; ++$i) {
                $this->children[$i]->removeNamespace($prefix);
            }

            return true;
        }

        return parent::removeNamespace($prefix);
    }

    /**
     * Return string representation of current object.
     *
     * @return string String representation
     */
    public function toString($options = [])
    {
        if (is_bool($options)) {
            $options = ['header' => $options];
        }

        $defaults = ['header' => false, 'encoding' => $this->encoding];
        $options = array_merge($defaults, self::options(), $options);
        $data = parent::toString($options, 0);

        if ($options['header']) {
            if (!empty($this->__header)) {
                return $this->header($this->__header)."\n".$data;
            }

            return $this->header()."\n".$data;
        }

        return $data;
    }

    /**
     * Return a header used on the first line of the xml file.
     *
     * @param mixed $attrib attributes of the header element
     *
     * @return string formated header
     */
    public function header($attrib = [])
    {
        $header = 'xml';
        if (is_string($attrib)) {
            $header = $attrib;
        } else {
            $attrib = array_merge(['version' => $this->version, 'encoding' => $this->encoding], $attrib);
            foreach ($attrib as $key => $val) {
                $header .= ' '.$key.'="'.$val.'"';
            }
        }

        return '<'.'?'.$header.' ?'.'>';
    }

    /**
     * Destructor, used to free resources.
     */
    public function __destruct()
    {
        $this->_killParent(true);
    }

    /**
     * Adds a namespace to any XML documents generated or parsed.
     *
     * @param string $name The namespace name
     * @param string $url  The namespace URI; can be empty if in the default namespace map
     *
     * @return bool False if no URL is specified, and the namespace does not exist
     *              default namespace map, otherwise true
     * @static
     */
    public function addGlobalNs($name, $url = null)
    {
        $_this = &XmlManager::getInstance();
        if ($ns = self::resolveNamespace($name, $url)) {
            $_this->namespaces = array_merge($_this->namespaces, $ns);

            return $ns;
        }

        return false;
    }

    /**
     * Resolves current namespace.
     *
     * @param string $name
     * @param string $url
     *
     * @return array
     */
    public function resolveNamespace($name, $url)
    {
        $_this = &XmlManager::getInstance();
        if (null == $url && isset($_this->defaultNamespaceMap[$name])) {
            $url = $_this->defaultNamespaceMap[$name];
        } elseif (null == $url) {
            return false;
        }

        if (!strpos($url, '://') && isset($_this->defaultNamespaceMap[$name])) {
            $_url = $_this->defaultNamespaceMap[$name];
            $name = $url;
            $url = $_url;
        }

        return [$name => $url];
    }

    /**
     * Alias to Xml::addNs.
     *
     * @static
     */
    public function addGlobalNamespace($name, $url = null)
    {
        return self::addGlobalNs($name, $url);
    }

    /**
     * Removes a namespace added in addNs().
     *
     * @param string $name The namespace name or URI
     * @static
     */
    public function removeGlobalNs($name)
    {
        $_this = &XmlManager::getInstance();
        if (isset($_this->namespaces[$name])) {
            unset($_this->namespaces[$name]);
            unset($this->namespaces[$name]);

            return true;
        } elseif (in_array($name, $_this->namespaces)) {
            $keys = array_keys($_this->namespaces);
            $count = count($keys);
            for ($i = 0; $i < $count; ++$i) {
                if ($_this->namespaces[$keys[$i]] == $name) {
                    unset($_this->namespaces[$keys[$i]]);
                    unset($this->namespaces[$keys[$i]]);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Alias to Xml::removeNs.
     *
     * @static
     */
    public function removeGlobalNamespace($name)
    {
        return self::removeGlobalNs($name);
    }

    /**
     * Sets/gets global XML options.
     *
     * @param array $options
     *
     * @return array
     * @static
     */
    public function options($options = [])
    {
        $_this = &XmlManager::getInstance();
        $_this->options = array_merge($_this->options, $options);

        return $_this->options;
    }
}

/**
 * The XML Element.
 */
class XmlElement extends XmlNode
{
    /**
     * Construct an Xml element.
     *
     * @param string $name       name of the node
     * @param string $value      value of the node
     * @param array  $attributes
     * @param string $namespace
     *
     * @return string A copy of $data in XML format
     */
    public function __construct($name = null, $value = null, $attributes = [], $namespace = false)
    {
        parent::__construct($name, $value, $namespace);
        $this->addAttribute($attributes);
    }

    /**
     * Get all the attributes for this element.
     *
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Add attributes to this element.
     *
     * @param string $name  name of the node
     * @param string $value value of the node
     *
     * @return bool
     */
    public function addAttribute($name, $val = null)
    {
        if (is_object($name)) {
            $name = get_object_vars($name);
        }
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->addAttribute($key, $val);
            }

            return true;
        }
        if (is_numeric($name)) {
            $name = $val;
            $val = null;
        }
        if (!empty($name)) {
            if (0 === strpos($name, 'xmlns')) {
                if ('xmlns' == $name) {
                    $this->namespace = $val;
                } else {
                    list($pre, $prefix) = explode(':', $name);
                    $this->addNamespace($prefix, $val);

                    return true;
                }
            }
            $this->attributes[$name] = $val;

            return true;
        }

        return false;
    }

    /**
     * Remove attributes to this element.
     *
     * @param string $name name of the node
     *
     * @return bool
     */
    public function removeAttribute($attr)
    {
        if (array_key_exists($attr, $this->attributes)) {
            unset($this->attributes[$attr]);

            return true;
        }

        return false;
    }
}

/**
 * XML text or CDATA node.
 *
 * Stores XML text data according to the encoding of the parent document
 *
 * @since         CakePHP v .1.2.6000
 */
class XmlTextNode extends XmlNode
{
    /**
     * Harcoded XML node name, represents this object as a text node.
     *
     * @var string
     */
    public $name = '#text';

    /**
     * The text/data value which this node contains.
     *
     * @var string
     */
    public $value = null;

    /**
     * Construct text node with the given parent object and data.
     *
     * @param object $parent Parent XmlNode/XmlElement object
     * @param mixed  $value  Node value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * Looks for child nodes in this element.
     *
     * @return bool False - not supported
     */
    public function hasChildren()
    {
        return false;
    }

    /**
     * Append an XML node: XmlTextNode does not support this operation.
     *
     * @return bool False - not supported
     *
     * @todo make convertEntities work without mb support, convert entities to number entities
     */
    public function append()
    {
        return false;
    }

    /**
     * Return string representation of current text node object.
     *
     * @return string String representation
     */
    public function toString($options = [], $depth = 0)
    {
        if (is_int($options)) {
            $depth = $options;
            $options = [];
        }

        $defaults = ['cdata' => true, 'whitespace' => false, 'convertEntities' => false];
        $options = array_merge($defaults, Xml::options(), $options);
        $val = $this->value;

        if ($options['convertEntities'] && function_exists('mb_convert_encoding')) {
            $val = mb_convert_encoding($val, 'UTF-8', 'HTML-ENTITIES');
        }

        if (true === $options['cdata'] && !is_numeric($val)) {
            $val = '<![CDATA['.$val.']]>';
        }

        if ($options['whitespace']) {
            return str_repeat("\t", $depth).$val."\n";
        }

        return $val;
    }
}

/**
 * Manages application-wide namespaces and XML parsing/generation settings.
 * Private class, used exclusively within scope of XML class.
 */
class XmlManager
{
    /**
     * Global XML namespaces.  Used in all XML documents processed by this application.
     *
     * @var array
     */
    public $namespaces = [];

    /**
     * Global XML document parsing/generation settings.
     *
     * @var array
     */
    public $options = [];

    /**
     * Map of common namespace URIs.
     *
     * @var array
     */
    public $defaultNamespaceMap = [
        'dc' => 'http://purl.org/dc/elements/1.1/',					// Dublin Core
        'dct' => 'http://purl.org/dc/terms/',						// Dublin Core Terms
        'g' => 'http://base.google.com/ns/1.0',					// Google Base
        'rc' => 'http://purl.org/rss/1.0/modules/content/',		// RSS 1.0 Content Module
        'wf' => 'http://wellformedweb.org/CommentAPI/',			// Well-Formed Web Comment API
        'fb' => 'http://rssnamespace.org/feedburner/ext/1.0',	// FeedBurner extensions
        'lj' => 'http://www.livejournal.org/rss/lj/1.0/',		// Live Journal
        'itunes' => 'http://www.itunes.com/dtds/podcast-1.0.dtd',	// iTunes
        'xhtml' => 'http://www.w3.org/1999/xhtml',					// XHTML,
        'atom' => 'http://www.w3.org/2005/Atom',					// Atom
    ];

    /**
     * Returns a reference to the global XML object that manages app-wide XML settings.
     *
     * @return object
     */
    public function &getInstance()
    {
        static $instance = [];

        if (!$instance) {
            $instance[0] = new self();
        }

        return $instance[0];
    }
}
