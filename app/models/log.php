<?php

class Log extends AppModel
{
    /** http://bakery.cakephp.org/articles/view/logablebehavior */

    /**
     * This behavior is created to be a plug-and-play database changes log
     * that will work out of the box as using the created and modified fields
     * does in cake core. It is NOT version control, undo or meant to be used as
     * part of the public application. It's intent is to easily let you (the
     * developer) log users activities that relates to database modifications
     * (ie, add, edit and delete). If you just want to see what your users are
     * doing or need to be able to say "That is not a bug, I can see from my log
     * that you deleted the post yesterday." and don't want to spend more time
     * that it takes to do "var $actsAs = array('Logable');" then this behavior
     * is for you.
     */
    public $order = 'created DESC';

    public function getLogs($limit, $models = null, $foreignKeys = null, $userIds = null, $actions = null)
    {
        if (empty($limit)) {
            $limit = 1;
        }
        $this->recursive = -1;
        $options = array('limit' => $limit);
        $options['conditions'] = array(
            'model' => $models,
            'foreign_key' => $foreignKeys,
            'action' => $actions,
            'user_id' => $userIds,
        );
        $options['conditions'] = array_filter($options['conditions']);
        $results = $this->find('all', $options);

        return $results;
    }
}
