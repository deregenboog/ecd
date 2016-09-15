<?php 

App::import('Core', 'Controller');
App::import('Component', 'Email');

class SendEmailShell extends Shell {

    public $Controller;
    var $uses = array('QueueTask', 'Groepsactiviteit', 'IzDeelnemer', 'Medewerker');

    public $Email;
 
    public $defaults = array(
        'to' => array('gabor@azavista.com'),
        'subject' => 'test email',
        'charset' => 'UTF-8',
        'from' => 'info@deregenboog.org',
        'replyTo' => 'info@deregenboog.org',
        'layout' => 'default',
        'sendAs' => 'html',
        'template' => 'mass_email',
        'debug' => false,
        'additionalParams' => '',
        'layout' => 'default',
        'attachments' => null,
        
    );
 
    public function initialize() {
        $this->Controller = new Controller();
        $this->Email = new EmailComponent(null);
        $this->Email->initialize($this->Controller);
        parent::initialize();
    }
 
    public function send($settings = array()) {
        $this->settings($settings);
        return $this->Email->send();
    }
 
    public function set($name, $data) {
        $this->Controller->set($name, $data);
    }
 
    function settings($settings = array()) {
        $this->Email->_set($this->defaults = array_filter(am($this->defaults, $settings)));
    }

    public function send_email($person, $subject, $medewerker, $params, $task) {

       $this->set('person', $person);

        $params = $this->defaults;

        $this->Email->subject = $subject;
        $this->Email->replyTo = $medewerker['email'];
        $this->Email->from = $medewerker['email'];

        $this->Email->to = array($person['email']);

        $this->Email->template = $params['template'];

        $this->Email->sendAs = $params['sendAs'];

        $this->Email->layout = $params['layout'];

        $attachments = array();
        
        foreach ($task['Document'] as $attachment) {
        	
            $filePath = MEDIA . $attachment['dirname'] . '/' . $attachment['basename'];

            $title = $originalTitle = $attachment['title'];
            $titleCnt = 2;
            while (array_key_exists($title, $attachments)) {
                $title = $titleCnt . '_' . $originalTitle;
                $titleCnt++;
            }

            $attachments[ $title ] = $filePath;
        }

        $this->Email->attachments = !empty($attachments) ? $attachments : null;
        $res=false;
        $res = $this->Email->send();
        return $res;
    }

    function main() {

        $params = array(
            'conditions' => array(
                'status' => STATUS_PENDING,
                'action' => 'mass_email'
            ),
        );
        $tasks = $this->QueueTask->find('all', $params);

        $numberOfTasks = count($tasks);
        $this->out('Number of tasks to execute: ' . $numberOfTasks);

        $taskCnt = 1;
        foreach ($tasks as $task) {
            $this->out('Executing task ' . $taskCnt . ' of ' . $numberOfTasks . '. QueueTask id: ' . $task['QueueTask']['id']);
            $medewerker = $this->Medewerker->getById($task['QueueTask']['foreign_key']);

            $task['QueueTask']['status'] = STATUS_PROCESSING;

            $selectie = $task['QueueTask']['data']['selectie'];
            
            $model = 'Groepsactiviteit';
            if(!empty($task['QueueTask']['data']['model'])) {
                $model = $task['QueueTask']['data']['model'];
            }
            $function = 'get_personen';
            if(!empty($task['QueueTask']['data']['function'])) {
                $function = $task['QueueTask']['data']['function'];
            }
            echo "model: {$model} action: {$function}\n";
            $personen = $this->{$model}->{$function}($selectie);
            
            $subject = $task['QueueTask']['data']['email'][$model]['onderwerp'];
            $this->set('email_content', $task['QueueTask']['data']['email'][$model]['text']);

            $output = array();

            foreach ($personen as $person) {
                if ( empty($person['email']) || !filter_var($person['email'], FILTER_VALIDATE_EMAIL) ) {
                    $output[ $person['klant_nummer'] ] = array(
                        'has_email' => false,
                        'sent' => false,
                    );
                } else {

                    $res = $this->send_email($person, $subject, $medewerker, $params, $task);

                    $output[ $person['klant_nummer'] ] = array(
                        'has_email' => $person['email'],
                        'sent' => $res,
                    );

                }

                $this->out( json_encode( array($person['klant_nummer'] => $output[ $person['klant_nummer'] ]) ) );

            }
            
            $person=array(
                'email' => $medewerker['email'],
            );
            
            echo "Send to sender";
            
            $res = $this->send_email($person, $subject, $medewerker, $params, $task);

            $task['QueueTask']['output'] = $output;
            $task['QueueTask']['status'] = STATUS_DONE;
            $task['QueueTask']['executed'] = date('Y-m-d H:i:s');

            $this->QueueTask->save($task);

            $this->out('Done.');

        }
        
    }
 
}
    
?>
