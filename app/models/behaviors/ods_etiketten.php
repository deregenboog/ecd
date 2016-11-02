<?php

function OdsWarningHandler($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}

class OdsEtikettenBehavior extends ModelBehavior
{
    public $settings = array();
    public $model = null;
    public $_defaults = array();

    public $template_dir = null;
    public $template_name = null;

    public $errors = array();
    public $error = null;

    public function setup(&$model, $config)
    {
        $this->model        = & $model;

        $this->source_path  = APP . WEBROOT_DIR . DS . "etiketten" . DS;
        $this->source_name  = "";

        $this->dest_path    = APP . 'tmp' . DS. 'etiketten'. DS ;
        $this->dest_name    = "";
        $this->dest_filename    = "";
        
        if (! is_dir($this->dest_path)) {
            mkdir($this->dest_path);
        }

        $this->pdf_path        = $this->dest_path;
        $this->pdf_name        = "";
        $this->pdf_filename        = "";

        $this->merge_errors = false;

        $this->settings     = array_merge($this->_defaults, $config);
    }

    public function getOdsEtikettenFilename(&$model, $type = 'pdf')
    {
        switch ($type) {
            case 'pdf':
                return $this->pdf_filename;
                break;
            case 'odt':
                return $this->dest_filename;
                break;
        }
        return $this->source_filename;
    }

    public function getOdsEtikettenErrors(&$model)
    {
        return $this->errors;
    }
    
    public function printOdsEtikettenErrorsInDocument()
    {
        if (count($this->errors) == 0) {
            return;
        }
        return;
        $query="//office:text";
        $elements = $this->xpath->query($query);
        if ($elements->length == 0) {
            return false;
        }
        array_unshift($this->errors, 'Following errors have been found merging the document:');
        foreach ($this->errors as $error) {
            $element = $this->doc->createElement('text:p', $error);
            $element->setAttribute('text:style-name', 'Standard');
            $elements->item(0)->appendChild($element);
        }
        return true;
    }
    
    public function saveOdsEtikettenData(&$model)
    {
        if ($this->merge_errors) {
            $this->print_errors_in_document();
        }
    
        $this->zip->deleteName('content.xml');
        $this->zip->addFromString('content.xml', $this->doc->saveXML());
        $this->zip->close();
    }
    
    public function getOdsEtikettenXML(&$model)
    {
        return $this->doc->saveXML();
    }

    public function setOdsEtikettenTemplate(&$model, $template = 'etiketten')
    {
        $this->errors = array();
            
        $this->zip = null;
        $this->doc = null;
        $this->xpath = null;

        $file = $this->source_path . DS . $template . ".odt";

        if (!file_exists($file)) {
            $this->errors[]="template file {$template} does not exist";
            return false;
        }

        $this->source_dir = dirname($file);
        $this->source_name = basename($file);
        $this->source_filename = $file;

        $id = date("U");
        $id = "";
        $this->dest_name = $this->source_name;
        $this->dest_filename = $this->dest_path . $id . $this->dest_name ;
        
        $this->pdf_name = preg_replace('/\.odt$/', '.pdf', $this->dest_name);
        $this->pdf_filename = $this->pdf_path . $id . $this->pdf_name;
        
        $ret =  $this->load_document($model);
        if (!$ret) {
            $this->errors[] = 'error load_document';
        }
        return $ret;
    }
    

    public function setOdsEtikettenData(&$model, $data)
    {
        $rows =$this->xpath->query('//office:body/office:text/draw:frame');
        $len = count($data);
        $lastrow = $rows->length;
        if ($lastrow != 24) {
            $this->errors[] = 'geen 24 etiketten per pagina';
            return false;
        }
        $insertnode = $this->doc->createElement('XXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
        $parent = $rows->item(0)->parentNode;
        $parent->insertBefore($insertnode, $rows->item(0));
        $framecnt = 1;
        $pagecnt = 1;
        for ($cnt = 0 ; $cnt < $len ; $cnt++) {
            $row = $rows->item($framecnt - 1)->cloneNode(true);
            $row->setAttribute('text:anchor-page-number', $pagecnt);
            $parent->insertBefore($row, $insertnode);

            $this->setNextRow($row, $data[$cnt]);
            $framecnt ++ ;
            if ($framecnt > 24) {
                $framecnt = 1;
                $pagecnt++;
            }
            $this->current++;
        }
        $parent = $insertnode->parentNode;
        $parent->removeChild($insertnode);
        for ($cnt = 0 ; $cnt < $lastrow ; $cnt++) {
            $parent = $rows->item($cnt)->parentNode;
            $parent->removeChild($rows->item($cnt));
        }
    }
    
    private function addValue($row, $attribute, $value)
    {
        $l = $this->xpath->query(".//text:database-display[@text:column-name='{$attribute}']", $row);
        if ($l->length != 1) {
            return;
        }
        $this->nodeValue($l->item(0), $value);
    }
    private function setNextRow($row, $rowdata)
    {
        foreach ($rowdata as $attribute => $value) {
            $this->addValue($row, $attribute, $value);
        }
    }
    
    private function nodeValue($node, $value)
    {
        set_error_handler("OdsWarningHandler", E_WARNING);
         
        $value=preg_replace('/&nbsp;/', ' ', $value);
        try {
            $node->nodeValue = $value;
        } catch (Exception $e) {
            $len = strlen($node->nodeValue);
            if (substr($value, $len, 1) == '&') {
                $str = $node->nodeValue."&amp;".substr($value, $len+1);
                $this->nodevalue($node, $str);
            }
        }
        
        restore_error_handler();
    }

    private function xxfilter(&$node, $text)
    {
        set_error_handler("OdsWarningHandler", E_WARNING);
         
        $text=preg_replace('/&nbsp;/', ' ', $text);
        $node->nodeValue = "";
        $this->nodevalue($node, $text);

        restore_error_handler();
    }

    private function load_document(&$model)
    {
        $this->zip = null;
        $this->doc = null;
        $this->xpath = null;

        $source = $this->source_filename;
        $dest = $this->dest_filename;

        if (file_exists($dest)) {
            unlink($dest);
        }
        if (!copy($source, $dest)) {
            $this->log("    (merge_document) Cannot copy {$source} to {$dest}", 'queue');
            $this->error = "Cannot copy {$source} to {$dest}";
            return false;
        }

        $this->zip = new ZipArchive;
        
        try {
            set_error_handler("OdsWarningHandler", E_WARNING);
            
            if (!$this->zip->open($dest)) {
                $this->log("   (merge_document) Cannot open {$dest}", 'queue');
                $this->error = "Cannot open {$dest}";
                restore_error_handler();
                return false;
            }

            $this->content = $this->zip->getFromName('content.xml');
            if (!$this->content) {
                $this->log("    (merge_document) Cannot find content.xml in {$dest}", 'queue');
                $this->error = "Cannot find content.xml in {$dest}";
                restore_error_handler();
                return false;
            }
            restore_error_handler();
        } catch (Exception $e) {
            restore_error_handler();
            $this->log("Caught exception merge document {$dest}: ".  $e->getMessage(). "\n", 'queue');
            $this->error = "Caught exception merge document {$dest}: ".  $e->getMessage(). "\n";
            return false;
        }
        
        
        $this->doc = new DOMDocument();
        $this->doc->loadXML($this->content);
        $this->xpath = new DOMXpath($this->doc);


        return true;
    }

    private function saveTmpContent()
    {
        $fh = fopen('/tmp/content.xml', "w");
        fwrite($fh, $this->doc->saveXML());
        fclose($fh);
    }

    private function convert_to_pdf()
    {
        $script = Configure::read('SOffice.convertScript');
        if (!$script) {
            throw new Exception('SOffice.convertScript is not configured');
        }
        $pdf = $this->pdf_filename;
        $odt = $this->dest_filename;

        $command = sprintf($script, $odt, $pdf);

        $this->log('  (merge_document)   ' . $command, 'queue');

        $retval = 0;
        exec($command, $output, $retval);

        $res = implode("            \n", $output);
        $this->log('   (merge_document)  Output: ' . $res, 'queue');

        if ($retval != 0) {
            $this->log('   (merge_document)  Retcode: '.$retval, 'queue');
            $this->error = "conversion to pdf fails: ".$retval;
            return false;
        }

        if (file_exists($this->dest_filename)) {
            //unlink($this->dest_filename);
        }
        return true;
    }
}
