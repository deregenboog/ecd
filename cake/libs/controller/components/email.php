<?php
/**
 * Email Component.
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
 * @since         CakePHP(tm) v 1.2.0.3467
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Core', 'Multibyte');
App::import('Core', 'Str');

/**
 * EmailComponent.
 *
 * This component is used for handling Internet Message Format based
 * based on the standard outlined in http://www.rfc-editor.org/rfc/rfc2822.txt
 *
 * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Email.html
 */
class EmailComponent extends Object
{
    /**
     * Recipient of the email.
     *
     * @var string
     */
    public $to = null;

    /**
     * The mail which the email is sent from.
     *
     * @var string
     */
    public $from = null;

    /**
     * The email the recipient will reply to.
     *
     * @var string
     */
    public $replyTo = null;

    /**
     * The read receipt email.
     *
     * @var string
     */
    public $readReceipt = null;

    /**
     * The mail that will be used in case of any errors like
     * - Remote mailserver down
     * - Remote user has exceeded his quota
     * - Unknown user.
     *
     * @var string
     */
    public $return = null;

    /**
     * Carbon Copy.
     *
     * List of email's that should receive a copy of the email.
     * The Recipient WILL be able to see this list
     *
     * @var array
     */
    public $cc = [];

    /**
     * Blind Carbon Copy.
     *
     * List of email's that should receive a copy of the email.
     * The Recipient WILL NOT be able to see this list
     *
     * @var array
     */
    public $bcc = [];

    /**
     * The date to put in the Date: header.  This should be a date
     * conformant with the RFC2822 standard.  Leave null, to have
     * today's date generated.
     *
     * @var string
     */
    public $date = null;

    /**
     * The subject of the email.
     *
     * @var string
     */
    public $subject = null;

    /**
     * Associative array of a user defined headers
     * Keys will be prefixed 'X-' as per RFC2822 Section 4.7.5.
     *
     * @var array
     */
    public $headers = [];

    /**
     * List of additional headers.
     *
     * These will NOT be used if you are using safemode and mail()
     *
     * @var string
     */
    public $additionalParams = null;

    /**
     * Layout for the View.
     *
     * @var string
     */
    public $layout = 'default';

    /**
     * Template for the view.
     *
     * @var string
     */
    public $template = null;

    /**
     * as per RFC2822 Section 2.1.1.
     *
     * @var int
     */
    public $lineLength = 70;

    /**
     * Line feed character(s) to be used when sending using mail() function
     * By default PHP_EOL is used.
     * RFC2822 requires it to be CRLF but some Unix
     * mail transfer agents replace LF by CRLF automatically
     * (which leads to doubling CR if CRLF is used).
     *
     * @var string
     */
    public $lineFeed = PHP_EOL;

    /**
     * @deprecated see lineLength
     */
    public $_lineLength = null;

    /**
     * What format should the email be sent in.
     *
     * Supported formats:
     * - text
     * - html
     * - both
     *
     * @var string
     */
    public $sendAs = 'text';

    /**
     * What method should the email be sent by.
     *
     * Supported methods:
     * - mail
     * - smtp
     * - debug
     *
     * @var string
     */
    public $delivery = 'mail';

    /**
     * charset the email is sent in.
     *
     * @var string
     */
    public $charset = 'utf-8';

    /**
     * List of files that should be attached to the email.
     *
     * Can be both absolute and relative paths
     *
     * @var array
     */
    public $attachments = [];

    /**
     * What mailer should EmailComponent identify itself as.
     *
     * @var string
     */
    public $xMailer = 'CakePHP Email Component';

    /**
     * The list of paths to search if an attachment isnt absolute.
     *
     * @var array
     */
    public $filePaths = [];

    /**
     * List of options to use for smtp mail method.
     *
     * Options is:
     * - port
     * - host
     * - timeout
     * - username
     * - password
     * - client
     *
     * @var array
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Email.html#sending-a-message-using-smtp
     */
    public $smtpOptions = [];

    /**
     * Placeholder for any errors that might happen with the
     * smtp mail methods.
     *
     * @var string
     */
    public $smtpError = null;

    /**
     * Contains the rendered plain text message if one was sent.
     *
     * @var string
     */
    public $textMessage = null;

    /**
     * Contains the rendered HTML message if one was sent.
     *
     * @var string
     */
    public $htmlMessage = null;

    /**
     * Whether to generate a Message-ID header for the
     * e-mail. True to generate a Message-ID, False to let
     * it be handled by sendmail (or similar) or a string
     * to completely override the Message-ID.
     *
     * If you are sending Email from a shell, be sure to set this value.  As you
     * could encounter delivery issues if you do not.
     *
     * @var mixed
     */
    public $messageId = true;

    /**
     * Temporary store of message header lines.
     *
     * @var array
     */
    public $__header = [];

    /**
     * If set, boundary to use for multipart mime messages.
     *
     * @var string
     */
    public $__boundary = null;

    /**
     * Temporary store of message lines.
     *
     * @var array
     */
    public $__message = [];

    /**
     * Variable that holds SMTP connection.
     *
     * @var resource
     */
    public $__smtpConnection = null;

    /**
     * Initialize component.
     *
     * @param object $controller Instantiating controller
     */
    public function initialize(&$controller, $settings = [])
    {
        $this->Controller = &$controller;
        if (null !== Configure::read('App.encoding')) {
            $this->charset = Configure::read('App.encoding');
        }
        $this->_set($settings);
    }

    /**
     * Startup component.
     *
     * @param object $controller Instantiating controller
     */
    public function startup(&$controller)
    {
    }

    /**
     * Send an email using the specified content, template and layout.
     *
     * @param mixed  $content  Either an array of text lines, or a string with contents
     *                         If you are rendering a template this variable will be sent to the templates as `$content`
     * @param string $template Template to use when sending email
     * @param string $layout   Layout to use to enclose email body
     *
     * @return bool Success
     */
    public function send($content = null, $template = null, $layout = null)
    {
        $this->_createHeader();

        if ($template) {
            $this->template = $template;
        }

        if ($layout) {
            $this->layout = $layout;
        }

        if (is_array($content)) {
            $content = implode("\n", $content)."\n";
        }

        $this->htmlMessage = $this->textMessage = null;
        if ($content) {
            if ('html' === $this->sendAs) {
                $this->htmlMessage = $content;
            } elseif ('text' === $this->sendAs) {
                $this->textMessage = $content;
            } else {
                $this->htmlMessage = $this->textMessage = $content;
            }
        }

        if ('text' === $this->sendAs) {
            $message = $this->_wrap($content);
        } else {
            $message = $this->_wrap($content, 998);
        }

        if (null === $this->template) {
            $message = $this->_formatMessage($message);
        } else {
            $message = $this->_render($message);
        }

        $message[] = '';
        $this->__message = $message;

        if (!empty($this->attachments)) {
            $this->_attachFiles();
        }

        if (!empty($this->attachments)) {
            $this->__message[] = '';
            $this->__message[] = '--'.$this->__boundary.'--';
            $this->__message[] = '';
        }

        $_method = '_'.$this->delivery;
        $sent = $this->$_method();

        $this->__header = [];
        $this->__message = [];

        return $sent;
    }

    /**
     * Reset all EmailComponent internal variables to be able to send out a new email.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Email.html#sending-multiple-emails-in-a-loop
     */
    public function reset()
    {
        $this->template = null;
        $this->to = [];
        $this->from = null;
        $this->replyTo = null;
        $this->return = null;
        $this->cc = [];
        $this->bcc = [];
        $this->headers = [];
        $this->subject = null;
        $this->additionalParams = null;
        $this->date = null;
        $this->smtpError = null;
        $this->attachments = [];
        $this->htmlMessage = null;
        $this->textMessage = null;
        $this->messageId = true;
        $this->delivery = 'mail';
        $this->__header = [];
        $this->__boundary = null;
        $this->__message = [];
    }

    /**
     * Render the contents using the current layout and template.
     *
     * @param string $content Content to render
     *
     * @return array Email ready to be sent
     */
    public function _render($content)
    {
        $viewClass = $this->Controller->view;

        if ('View' != $viewClass) {
            list($plugin, $viewClass) = pluginSplit($viewClass);
            $viewClass = $viewClass.'View';
            App::import('View', $this->Controller->view);
        }

        $View = new $viewClass($this->Controller);
        $View->layout = $this->layout;
        $msg = [];

        $content = implode("\n", $content);

        if ('both' === $this->sendAs) {
            $htmlContent = $content;
            if (!empty($this->attachments)) {
                $msg[] = '--'.$this->__boundary;
                $msg[] = 'Content-Type: multipart/alternative; boundary="alt-'.$this->__boundary.'"';
                $msg[] = '';
            }
            $msg[] = '--alt-'.$this->__boundary;
            $msg[] = 'Content-Type: text/plain; charset='.$this->charset;
            $msg[] = 'Content-Transfer-Encoding: 7bit';
            $msg[] = '';

            $content = $View->element('email'.DS.'text'.DS.$this->template, ['content' => $content], true);
            $View->layoutPath = 'email'.DS.'text';
            $content = explode("\n", $this->textMessage = str_replace(["\r\n", "\r"], "\n", $View->renderLayout($content)));

            $msg = array_merge($msg, $content);

            $msg[] = '';
            $msg[] = '--alt-'.$this->__boundary;
            $msg[] = 'Content-Type: text/html; charset='.$this->charset;
            $msg[] = 'Content-Transfer-Encoding: 7bit';
            $msg[] = '';

            $htmlContent = $View->element('email'.DS.'html'.DS.$this->template, ['content' => $htmlContent], true);
            $View->layoutPath = 'email'.DS.'html';
            $htmlContent = explode("\n", $this->htmlMessage = str_replace(["\r\n", "\r"], "\n", $View->renderLayout($htmlContent)));

            $msg = array_merge($msg, $htmlContent);

            $msg[] = '';
            $msg[] = '--alt-'.$this->__boundary.'--';
            $msg[] = '';

            ClassRegistry::removeObject('view');

            return $msg;
        }

        if (!empty($this->attachments)) {
            if ('html' === $this->sendAs) {
                $msg[] = '';
                $msg[] = '--'.$this->__boundary;
                $msg[] = 'Content-Type: text/html; charset='.$this->charset;
                $msg[] = 'Content-Transfer-Encoding: 7bit';
                $msg[] = '';
            } else {
                $msg[] = '--'.$this->__boundary;
                $msg[] = 'Content-Type: text/plain; charset='.$this->charset;
                $msg[] = 'Content-Transfer-Encoding: 7bit';
                $msg[] = '';
            }
        }

        $content = $View->element('email'.DS.$this->sendAs.DS.$this->template, ['content' => $content], true);
        $View->layoutPath = 'email'.DS.$this->sendAs;
        $content = explode("\n", $rendered = str_replace(["\r\n", "\r"], "\n", $View->renderLayout($content)));

        if ('html' === $this->sendAs) {
            $this->htmlMessage = $rendered;
        } else {
            $this->textMessage = $rendered;
        }

        $msg = array_merge($msg, $content);
        ClassRegistry::removeObject('view');

        return $msg;
    }

    /**
     * Create unique boundary identifier.
     */
    public function _createboundary()
    {
        $this->__boundary = md5(uniqid(time()));
    }

    /**
     * Sets headers for the message.
     *
     * @param array associative array containing headers to be set
     */
    public function header($headers)
    {
        foreach ($headers as $header => $value) {
            $this->__header[] = sprintf('%s: %s', trim($header), trim($value));
        }
    }

    /**
     * Create emails headers including (but not limited to) from email address, reply to,
     * bcc and cc.
     */
    public function _createHeader()
    {
        $headers = [];

        if ('smtp' == $this->delivery) {
            $headers['To'] = implode(', ', array_map([$this, '_formatAddress'], (array) $this->to));
        }
        $headers['From'] = $this->_formatAddress($this->from);

        if (!empty($this->replyTo)) {
            $headers['Reply-To'] = $this->_formatAddress($this->replyTo);
        }
        if (!empty($this->return)) {
            $headers['Return-Path'] = $this->_formatAddress($this->return);
        }
        if (!empty($this->readReceipt)) {
            $headers['Disposition-Notification-To'] = $this->_formatAddress($this->readReceipt);
        }

        if (!empty($this->cc)) {
            $headers['Cc'] = implode(', ', array_map([$this, '_formatAddress'], (array) $this->cc));
        }

        if (!empty($this->bcc) && 'smtp' != $this->delivery) {
            $headers['Bcc'] = implode(', ', array_map([$this, '_formatAddress'], (array) $this->bcc));
        }
        if ('smtp' == $this->delivery) {
            $headers['Subject'] = $this->_encode($this->subject);
        }

        if (false !== $this->messageId) {
            if (true === $this->messageId) {
                $headers['Message-ID'] = '<'.str_replace('-', '', Str::uuid()).'@'.env('HTTP_HOST').'>';
            } else {
                $headers['Message-ID'] = $this->messageId;
            }
        }

        $date = $this->date;
        if (false == $date) {
            $date = date(DATE_RFC2822);
        }
        $headers['Date'] = $date;

        $headers['X-Mailer'] = $this->xMailer;

        if (!empty($this->headers)) {
            foreach ($this->headers as $key => $val) {
                $headers['X-'.$key] = $val;
            }
        }

        if (!empty($this->attachments) || 'both' === $this->sendAs) {
            $this->_createBoundary();
        }

        if (!empty($this->attachments)) {
            $headers['Content-Type'] = 'multipart/mixed; boundary="'.$this->__boundary.'"';
        } elseif ('text' === $this->sendAs) {
            $headers['Content-Type'] = 'text/plain; charset='.$this->charset;
        } elseif ('html' === $this->sendAs) {
            $headers['Content-Type'] = 'text/html; charset='.$this->charset;
        } elseif ('both' === $this->sendAs) {
            $headers['Content-Type'] = 'multipart/alternative; boundary="alt-'.$this->__boundary.'"';
        }

        $headers['MIME-Version'] = '1.0';
        $headers['Content-Transfer-Encoding'] = '7bit';

        $this->header($headers);
    }

    /**
     * Format the message by seeing if it has attachments.
     *
     * @param string $message Message to format
     */
    public function _formatMessage($message)
    {
        if (!empty($this->attachments)) {
            $prefix = ['--'.$this->__boundary];
            if ('text' === $this->sendAs) {
                $prefix[] = 'Content-Type: text/plain; charset='.$this->charset;
            } elseif ('html' === $this->sendAs) {
                $prefix[] = 'Content-Type: text/html; charset='.$this->charset;
            } elseif ('both' === $this->sendAs) {
                $prefix[] = 'Content-Type: multipart/alternative; boundary="alt-'.$this->__boundary.'"';
            }
            $prefix[] = 'Content-Transfer-Encoding: 7bit';
            $prefix[] = '';
            $message = array_merge($prefix, $message);
        }

        return $message;
    }

    /**
     * Attach files by adding file contents inside boundaries.
     *
     * @TODO: modify to use the core File class?
     */
    public function _attachFiles()
    {
        $files = [];
        foreach ($this->attachments as $filename => $attachment) {
            $file = $this->_findFiles($attachment);
            if (!empty($file)) {
                if (is_int($filename)) {
                    $filename = basename($file);
                }
                $files[$filename] = $file;
            }
        }

        foreach ($files as $filename => $file) {
            $handle = fopen($file, 'rb');
            $data = fread($handle, filesize($file));
            $data = chunk_split(base64_encode($data));
            fclose($handle);

            $this->__message[] = '--'.$this->__boundary;
            $this->__message[] = 'Content-Type: application/octet-stream';
            $this->__message[] = 'Content-Transfer-Encoding: base64';
            $this->__message[] = 'Content-Disposition: attachment; filename="'.basename($filename).'"';
            $this->__message[] = '';
            $this->__message[] = $data;
            $this->__message[] = '';
        }
    }

    /**
     * Find the specified attachment in the list of file paths.
     *
     * @param string $attachment Attachment file name to find
     *
     * @return string Path to located file
     */
    public function _findFiles($attachment)
    {
        if (file_exists($attachment)) {
            return $attachment;
        }
        foreach ($this->filePaths as $path) {
            if (file_exists($path.DS.$attachment)) {
                $file = $path.DS.$attachment;

                return $file;
            }
        }

        return null;
    }

    /**
     * Wrap the message using EmailComponent::$lineLength.
     *
     * @param string $message    Message to wrap
     * @param int    $lineLength Max length of line
     *
     * @return array Wrapped message
     */
    public function _wrap($message, $lineLength = null)
    {
        $message = $this->_strip($message, true);
        $message = str_replace(["\r\n", "\r"], "\n", $message);
        $lines = explode("\n", $message);
        $formatted = [];

        if (null !== $this->_lineLength) {
            trigger_error(__('_lineLength cannot be accessed please use lineLength', true), E_USER_WARNING);
            $this->lineLength = $this->_lineLength;
        }

        if (!$lineLength) {
            $lineLength = $this->lineLength;
        }

        foreach ($lines as $line) {
            if ('.' == substr($line, 0, 1)) {
                $line = '.'.$line;
            }
            $formatted = array_merge($formatted, explode("\n", wordwrap($line, $lineLength, "\n", true)));
        }
        $formatted[] = '';

        return $formatted;
    }

    /**
     * Encode the specified string using the current charset.
     *
     * @param string $subject String to encode
     *
     * @return string Encoded string
     */
    public function _encode($subject)
    {
        $subject = $this->_strip($subject);

        $nl = "\r\n";
        if ('mail' == $this->delivery) {
            $nl = '';
        }
        $internalEncoding = function_exists('mb_internal_encoding');
        if ($internalEncoding) {
            $restore = mb_internal_encoding();
            mb_internal_encoding($this->charset);
        }
        $return = mb_encode_mimeheader($subject, $this->charset, 'B', $nl);
        if ($internalEncoding) {
            mb_internal_encoding($restore);
        }

        return $return;
    }

    /**
     * Format a string as an email address.
     *
     * @param string $string String representing an email address
     *
     * @return string Email address suitable for email headers or smtp pipe
     */
    public function _formatAddress($string, $smtp = false)
    {
        $hasAlias = preg_match('/((.*))?\s?<(.+)>/', $string, $matches);
        if ($smtp && $hasAlias) {
            return $this->_strip('<'.$matches[3].'>');
        } elseif ($smtp) {
            return $this->_strip('<'.$string.'>');
        }

        if ($hasAlias && !empty($matches[2])) {
            return $this->_encode(trim($matches[2])).$this->_strip(' <'.$matches[3].'>');
        }

        return $this->_strip($string);
    }

    /**
     * Remove certain elements (such as bcc:, to:, %0a) from given value.
     * Helps prevent header injection / mainipulation on user content.
     *
     * @param string $value   Value to strip
     * @param bool   $message Set to true to indicate main message content
     *
     * @return string Stripped value
     */
    public function _strip($value, $message = false)
    {
        $search = '%0a|%0d|Content-(?:Type|Transfer-Encoding)\:';
        $search .= '|charset\=|mime-version\:|multipart/mixed|(?:[\n\r]+to|b?cc)\:.*';

        if (true !== $message) {
            $search .= '|\r|\n';
        }
        $search = '#(?:'.$search.')#i';
        while (preg_match($search, $value)) {
            $value = preg_replace($search, '', $value);
        }

        return $value;
    }

    /**
     * Wrapper for PHP mail function used for sending out emails.
     *
     * @return bool Success
     */
    public function _mail()
    {
        $header = implode($this->lineFeed, $this->__header);
        $message = implode($this->lineFeed, $this->__message);
        if (is_array($this->to)) {
            $to = implode(', ', array_map([$this, '_formatAddress'], $this->to));
        } else {
            $to = $this->to;
        }
        if (ini_get('safe_mode')) {
            return @mail($to, $this->_encode($this->subject), $message, $header);
        }

        return @mail($to, $this->_encode($this->subject), $message, $header, $this->additionalParams);
    }

    /**
     * Helper method to get socket, overridden in tests.
     *
     * @param array $config config data for the socket
     */
    public function _getSocket($config)
    {
        $this->__smtpConnection = new CakeSocket($config);
    }

    /**
     * Sends out email via SMTP.
     *
     * @return bool Success
     */
    public function _smtp()
    {
        App::import('Core', ['CakeSocket']);

        $defaults = [
            'host' => 'localhost',
            'port' => 25,
            'protocol' => 'smtp',
            'timeout' => 30,
        ];
        $this->smtpOptions = array_merge($defaults, $this->smtpOptions);
        $this->_getSocket($this->smtpOptions);

        if (!$this->__smtpConnection->connect()) {
            $this->smtpError = $this->__smtpConnection->lastError();

            return false;
        } elseif (!$this->_smtpSend(null, '220')) {
            return false;
        }

        $httpHost = env('HTTP_HOST');

        if (isset($this->smtpOptions['client'])) {
            $host = $this->smtpOptions['client'];
        } elseif (!empty($httpHost)) {
            list($host) = explode(':', $httpHost);
        } else {
            $host = 'localhost';
        }

        if (!$this->_smtpSend("EHLO {$host}", '250') && !$this->_smtpSend("HELO {$host}", '250')) {
            return false;
        }

        if (isset($this->smtpOptions['username']) && isset($this->smtpOptions['password'])) {
            $authRequired = $this->_smtpSend('AUTH LOGIN', '334|503');
            if ('334' == $authRequired) {
                if (!$this->_smtpSend(base64_encode($this->smtpOptions['username']), '334')) {
                    return false;
                }
                if (!$this->_smtpSend(base64_encode($this->smtpOptions['password']), '235')) {
                    return false;
                }
            } elseif ('503' != $authRequired) {
                return false;
            }
        }

        if (!$this->_smtpSend('MAIL FROM: '.$this->_formatAddress($this->from, true))) {
            return false;
        }

        if (!is_array($this->to)) {
            $tos = array_map('trim', explode(',', $this->to));
        } else {
            $tos = $this->to;
        }
        foreach ($tos as $to) {
            if (!$this->_smtpSend('RCPT TO: '.$this->_formatAddress($to, true))) {
                return false;
            }
        }

        foreach ($this->cc as $cc) {
            if (!$this->_smtpSend('RCPT TO: '.$this->_formatAddress($cc, true))) {
                return false;
            }
        }
        foreach ($this->bcc as $bcc) {
            if (!$this->_smtpSend('RCPT TO: '.$this->_formatAddress($bcc, true))) {
                return false;
            }
        }

        if (!$this->_smtpSend('DATA', '354')) {
            return false;
        }

        $header = implode("\r\n", $this->__header);
        $message = implode("\r\n", $this->__message);
        if (!$this->_smtpSend($header."\r\n\r\n".$message."\r\n\r\n\r\n.")) {
            return false;
        }
        $this->_smtpSend('QUIT', false);

        $this->__smtpConnection->disconnect();

        return true;
    }

    /**
     * Protected method for sending data to SMTP connection.
     *
     * @param string $data      data to be sent to SMTP server
     * @param mixed  $checkCode code to check for in server response, false to skip
     *
     * @return bool Success
     */
    public function _smtpSend($data, $checkCode = '250')
    {
        if (!is_null($data)) {
            $this->__smtpConnection->write($data."\r\n");
        }
        while (false !== $checkCode) {
            $response = '';
            $startTime = time();
            while ("\r\n" !== substr($response, -2) && ((time() - $startTime) < $this->smtpOptions['timeout'])) {
                $response .= $this->__smtpConnection->read();
            }
            if ("\r\n" !== substr($response, -2)) {
                $this->smtpError = 'timeout';

                return false;
            }
            $response = end(explode("\r\n", rtrim($response, "\r\n")));

            if (preg_match('/^('.$checkCode.')(.)/', $response, $code)) {
                if ('-' === $code[2]) {
                    continue;
                }

                return $code[1];
            }
            $this->smtpError = $response;

            return false;
        }

        return true;
    }

    /**
     * Set as controller flash message a debug message showing current settings in component.
     *
     * @return bool Success
     */
    public function _debug()
    {
        $nl = "\n";
        $header = implode($nl, $this->__header);
        $message = implode($nl, $this->__message);
        $fm = '<pre>';

        if (is_array($this->to)) {
            $to = implode(', ', array_map([$this, '_formatAddress'], $this->to));
        } else {
            $to = $this->to;
        }
        $fm .= sprintf('%s %s%s', 'To:', $to, $nl);
        $fm .= sprintf('%s %s%s', 'From:', $this->from, $nl);
        $fm .= sprintf('%s %s%s', 'Subject:', $this->_encode($this->subject), $nl);
        $fm .= sprintf('%s%3$s%3$s%s', 'Header:', $header, $nl);
        $fm .= sprintf('%s%3$s%3$s%s', 'Parameters:', $this->additionalParams, $nl);
        $fm .= sprintf('%s%3$s%3$s%s', 'Message:', $message, $nl);
        $fm .= '</pre>';

        if (isset($this->Controller->Session)) {
            $this->Controller->Session->setFlash($fm, 'default', null, 'email');

            return true;
        }

        return $fm;
    }
}
