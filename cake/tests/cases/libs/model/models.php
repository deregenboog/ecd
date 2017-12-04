<?php
/**
 * Mock models file.
 *
 * Mock classes for use in Model and related test cases
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html>
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html CakePHP(tm) Tests
 * @since         CakePHP(tm) v 1.2.0.6464
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
    define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
}

/**
 * Test class.
 */
class Test extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * name property.
     *
     * @var string 'Test'
     */
    public $name = 'Test';

    /**
     * schema property.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => '', 'default' => '1', 'length' => '8', 'key' => 'primary'],
        'name' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'email' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'],
        'notes' => ['type' => 'text', 'null' => '1', 'default' => 'write some notes here', 'length' => ''],
        'created' => ['type' => 'date', 'null' => '1', 'default' => '', 'length' => ''],
        'updated' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
    ];
}

/**
 * TestAlias class.
 */
class TestAlias extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * name property.
     *
     * @var string 'TestAlias'
     */
    public $name = 'TestAlias';

    /**
     * alias property.
     *
     * @var string 'TestAlias'
     */
    public $alias = 'TestAlias';

    /**
     * schema property.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => '', 'default' => '1', 'length' => '8', 'key' => 'primary'],
        'name' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'email' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'],
        'notes' => ['type' => 'text', 'null' => '1', 'default' => 'write some notes here', 'length' => ''],
        'created' => ['type' => 'date', 'null' => '1', 'default' => '', 'length' => ''],
        'updated' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
    ];
}

/**
 * TestValidate class.
 */
class TestValidate extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * name property.
     *
     * @var string 'TestValidate'
     */
    public $name = 'TestValidate';

    /**
     * schema property.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'title' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'body' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => ''],
        'number' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'created' => ['type' => 'date', 'null' => '1', 'default' => '', 'length' => ''],
        'modified' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
    ];

    /**
     * validateNumber method.
     *
     * @param mixed $value
     * @param mixed $options
     */
    public function validateNumber($value, $options)
    {
        $options = array_merge(['min' => 0, 'max' => 100], $options);
        $valid = ($value['number'] >= $options['min'] && $value['number'] <= $options['max']);

        return $valid;
    }

    /**
     * validateTitle method.
     *
     * @param mixed $value
     */
    public function validateTitle($value)
    {
        return !empty($value) && 0 === strpos(low($value['title']), 'title-');
    }
}

/**
 * User class.
 */
class User extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'User'
     */
    public $name = 'User';

    /**
     * validate property.
     *
     * @var array
     */
    public $validate = ['user' => 'notEmpty', 'password' => 'notEmpty'];
}

/**
 * Article class.
 */
class Article extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Article'
     */
    public $name = 'Article';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['User'];

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['Comment' => ['dependent' => true]];

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Tag'];

    /**
     * validate property.
     *
     * @var array
     */
    public $validate = ['user_id' => 'numeric', 'title' => ['allowEmpty' => false, 'rule' => 'notEmpty'], 'body' => 'notEmpty'];

    /**
     * beforeSaveReturn property.
     *
     * @var bool true
     */
    public $beforeSaveReturn = true;

    /**
     * beforeSave method.
     */
    public function beforeSave()
    {
        return $this->beforeSaveReturn;
    }

    /**
     * titleDuplicate method.
     *
     * @param mixed $title
     */
    public function titleDuplicate($title)
    {
        if ('My Article Title' === $title) {
            return false;
        }

        return true;
    }
}

/**
 * Model stub for beforeDelete testing.
 *
 * @see #250
 */
class BeforeDeleteComment extends CakeTestModel
{
    public $name = 'BeforeDeleteComment';

    public $useTable = 'comments';

    public function beforeDelete($cascade = true)
    {
        $db = &$this->getDataSource();
        $db->delete($this, [$this->alias.'.'.$this->primaryKey => [1, 3]]);

        return true;
    }
}

/**
 * NumericArticle class.
 */
class NumericArticle extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'NumericArticle'
     */
    public $name = 'NumericArticle';

    /**
     * useTable property.
     *
     * @var string 'numeric_articles'
     */
    public $useTable = 'numeric_articles';
}

/**
 * Article10 class.
 */
class Article10 extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Article10'
     */
    public $name = 'Article10';

    /**
     * useTable property.
     *
     * @var string 'articles'
     */
    public $useTable = 'articles';

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['Comment' => ['dependent' => true, 'exclusive' => true]];
}

/**
 * ArticleFeatured class.
 */
class ArticleFeatured extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ArticleFeatured'
     */
    public $name = 'ArticleFeatured';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['User', 'Category'];

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = ['Featured'];

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['Comment' => ['className' => 'Comment', 'dependent' => true]];

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Tag'];

    /**
     * validate property.
     *
     * @var array
     */
    public $validate = ['user_id' => 'numeric', 'title' => 'notEmpty', 'body' => 'notEmpty'];
}

/**
 * Featured class.
 */
class Featured extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Featured'
     */
    public $name = 'Featured';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['ArticleFeatured', 'Category'];
}

/**
 * Tag class.
 */
class Tag extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Tag'
     */
    public $name = 'Tag';
}

/**
 * ArticlesTag class.
 */
class ArticlesTag extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ArticlesTag'
     */
    public $name = 'ArticlesTag';
}

/**
 * ArticleFeaturedsTag class.
 */
class ArticleFeaturedsTag extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ArticleFeaturedsTag'
     */
    public $name = 'ArticleFeaturedsTag';
}

/**
 * Comment class.
 */
class Comment extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Comment'
     */
    public $name = 'Comment';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Article', 'User'];

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = ['Attachment' => ['dependent' => true]];
}

/**
 * Modified Comment Class has afterFind Callback.
 */
class ModifiedComment extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Comment'
     */
    public $name = 'Comment';

    /**
     * useTable property.
     *
     * @var string 'comments'
     */
    public $useTable = 'comments';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Article'];

    /**
     * afterFind callback.
     */
    public function afterFind($results)
    {
        if (isset($results[0])) {
            $results[0]['Comment']['callback'] = 'Fire';
        }

        return $results;
    }
}

/**
 * Modified Comment Class has afterFind Callback.
 */
class AgainModifiedComment extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Comment'
     */
    public $name = 'Comment';

    /**
     * useTable property.
     *
     * @var string 'comments'
     */
    public $useTable = 'comments';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Article'];

    /**
     * afterFind callback.
     */
    public function afterFind($results)
    {
        if (isset($results[0])) {
            $results[0]['Comment']['querytype'] = $this->findQueryType;
        }

        return $results;
    }
}

/**
 * MergeVarPluginAppModel class.
 */
class MergeVarPluginAppModel extends AppModel
{
    /**
     * actsAs parameter.
     *
     * @var array
     */
    public $actsAs = [
        'Containable',
    ];
}

/**
 * MergeVarPluginPost class.
 */
class MergeVarPluginPost extends MergeVarPluginAppModel
{
    /**
     * actsAs parameter.
     *
     * @var array
     */
    public $actsAs = [
        'Tree',
    ];

    /**
     * useTable parameter.
     *
     * @var string
     */
    public $useTable = 'posts';
}

/**
 * MergeVarPluginComment class.
 */
class MergeVarPluginComment extends MergeVarPluginAppModel
{
    /**
     * actsAs parameter.
     *
     * @var array
     */
    public $actsAs = [
        'Containable' => ['some_settings'],
    ];

    /**
     * useTable parameter.
     *
     * @var string
     */
    public $useTable = 'comments';
}

/**
 * Attachment class.
 */
class Attachment extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Attachment'
     */
    public $name = 'Attachment';
}

/**
 * Category class.
 */
class Category extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Category'
     */
    public $name = 'Category';
}

/**
 * CategoryThread class.
 */
class CategoryThread extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'CategoryThread'
     */
    public $name = 'CategoryThread';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['ParentCategory' => ['className' => 'CategoryThread', 'foreignKey' => 'parent_id']];
}

/**
 * Apple class.
 */
class Apple extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Apple'
     */
    public $name = 'Apple';

    /**
     * validate property.
     *
     * @var array
     */
    public $validate = ['name' => 'notEmpty'];

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = ['Sample'];

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['Child' => ['className' => 'Apple', 'dependent' => true]];

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Parent' => ['className' => 'Apple', 'foreignKey' => 'apple_id']];
}

/**
 * Sample class.
 */
class Sample extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Sample'
     */
    public $name = 'Sample';

    /**
     * belongsTo property.
     *
     * @var string 'Apple'
     */
    public $belongsTo = 'Apple';
}

/**
 * AnotherArticle class.
 */
class AnotherArticle extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'AnotherArticle'
     */
    public $name = 'AnotherArticle';

    /**
     * hasMany property.
     *
     * @var string 'Home'
     */
    public $hasMany = 'Home';
}

/**
 * Advertisement class.
 */
class Advertisement extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Advertisement'
     */
    public $name = 'Advertisement';

    /**
     * hasMany property.
     *
     * @var string 'Home'
     */
    public $hasMany = 'Home';
}

/**
 * Home class.
 */
class Home extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Home'
     */
    public $name = 'Home';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['AnotherArticle', 'Advertisement'];
}

/**
 * Post class.
 */
class Post extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Post'
     */
    public $name = 'Post';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Author'];

    public function beforeFind($queryData)
    {
        if (isset($queryData['connection'])) {
            $this->useDbConfig = $queryData['connection'];
        }

        return true;
    }

    public function afterFind($results)
    {
        $this->useDbConfig = 'test_suite';

        return $results;
    }
}

/**
 * Author class.
 */
class Author extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Author'
     */
    public $name = 'Author';

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['Post'];

    /**
     * afterFind method.
     *
     * @param mixed $results
     */
    public function afterFind($results)
    {
        $results[0]['Author']['test'] = 'working';

        return $results;
    }
}

/**
 * ModifiedAuthor class.
 */
class ModifiedAuthor extends Author
{
    /**
     * name property.
     *
     * @var string 'Author'
     */
    public $name = 'Author';

    /**
     * afterFind method.
     *
     * @param mixed $results
     */
    public function afterFind($results)
    {
        foreach ($results as $index => $result) {
            $results[$index]['Author']['user'] .= ' (CakePHP)';
        }

        return $results;
    }
}

/**
 * Project class.
 */
class Project extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Project'
     */
    public $name = 'Project';

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['Thread'];
}

/**
 * Thread class.
 */
class Thread extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Thread'
     */
    public $name = 'Thread';

    /**
     * hasMany property.
     *
     * @var array
     */
    public $belongsTo = ['Project'];

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['Message'];
}

/**
 * Message class.
 */
class Message extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Message'
     */
    public $name = 'Message';

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = ['Bid'];
}

/**
 * Bid class.
 */
class Bid extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Bid'
     */
    public $name = 'Bid';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Message'];
}

/**
 * BiddingMessage class.
 */
class BiddingMessage extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'BiddingMessage'
     */
    public $name = 'BiddingMessage';

    /**
     * primaryKey property.
     *
     * @var string 'bidding'
     */
    public $primaryKey = 'bidding';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = [
        'Bidding' => [
            'foreignKey' => false,
            'conditions' => ['BiddingMessage.bidding = Bidding.bid'],
        ],
    ];
}

/**
 * Bidding class.
 */
class Bidding extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Bidding'
     */
    public $name = 'Bidding';

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = [
        'BiddingMessage' => [
            'foreignKey' => false,
            'conditions' => ['BiddingMessage.bidding = Bidding.bid'],
            'dependent' => true,
        ],
    ];
}

/**
 * NodeAfterFind class.
 */
class NodeAfterFind extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'NodeAfterFind'
     */
    public $name = 'NodeAfterFind';

    /**
     * validate property.
     *
     * @var array
     */
    public $validate = ['name' => 'notEmpty'];

    /**
     * useTable property.
     *
     * @var string 'apples'
     */
    public $useTable = 'apples';

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = ['Sample' => ['className' => 'NodeAfterFindSample']];

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['Child' => ['className' => 'NodeAfterFind', 'dependent' => true]];

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Parent' => ['className' => 'NodeAfterFind', 'foreignKey' => 'apple_id']];

    /**
     * afterFind method.
     *
     * @param mixed $results
     */
    public function afterFind($results)
    {
        return $results;
    }
}

/**
 * NodeAfterFindSample class.
 */
class NodeAfterFindSample extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'NodeAfterFindSample'
     */
    public $name = 'NodeAfterFindSample';

    /**
     * useTable property.
     *
     * @var string 'samples'
     */
    public $useTable = 'samples';

    /**
     * belongsTo property.
     *
     * @var string 'NodeAfterFind'
     */
    public $belongsTo = 'NodeAfterFind';
}

/**
 * NodeNoAfterFind class.
 */
class NodeNoAfterFind extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'NodeAfterFind'
     */
    public $name = 'NodeAfterFind';

    /**
     * validate property.
     *
     * @var array
     */
    public $validate = ['name' => 'notEmpty'];

    /**
     * useTable property.
     *
     * @var string 'apples'
     */
    public $useTable = 'apples';

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = ['Sample' => ['className' => 'NodeAfterFindSample']];

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['Child' => ['className' => 'NodeAfterFind', 'dependent' => true]];

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Parent' => ['className' => 'NodeAfterFind', 'foreignKey' => 'apple_id']];
}

/**
 * Node class.
 */
class Node extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Node'
     */
    public $name = 'Node';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = [
        'ParentNode' => [
            'className' => 'Node',
            'joinTable' => 'dependency',
            'with' => 'Dependency',
            'foreignKey' => 'child_id',
            'associationForeignKey' => 'parent_id',
        ],
    ];
}

/**
 * Dependency class.
 */
class Dependency extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Dependency'
     */
    public $name = 'Dependency';
}

/**
 * ModelA class.
 */
class ModelA extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ModelA'
     */
    public $name = 'ModelA';

    /**
     * useTable property.
     *
     * @var string 'apples'
     */
    public $useTable = 'apples';

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['ModelB', 'ModelC'];
}

/**
 * ModelB class.
 */
class ModelB extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ModelB'
     */
    public $name = 'ModelB';

    /**
     * useTable property.
     *
     * @var string 'messages'
     */
    public $useTable = 'messages';

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['ModelD'];
}

/**
 * ModelC class.
 */
class ModelC extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ModelC'
     */
    public $name = 'ModelC';

    /**
     * useTable property.
     *
     * @var string 'bids'
     */
    public $useTable = 'bids';

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['ModelD'];
}

/**
 * ModelD class.
 */
class ModelD extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ModelD'
     */
    public $name = 'ModelD';

    /**
     * useTable property.
     *
     * @var string 'threads'
     */
    public $useTable = 'threads';
}

/**
 * Something class.
 */
class Something extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Something'
     */
    public $name = 'Something';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['SomethingElse' => ['with' => ['JoinThing' => ['doomed']]]];
}

/**
 * SomethingElse class.
 */
class SomethingElse extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'SomethingElse'
     */
    public $name = 'SomethingElse';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Something' => ['with' => 'JoinThing']];
}

/**
 * JoinThing class.
 */
class JoinThing extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'JoinThing'
     */
    public $name = 'JoinThing';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Something', 'SomethingElse'];
}

/**
 * Portfolio class.
 */
class Portfolio extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Portfolio'
     */
    public $name = 'Portfolio';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Item'];
}

/**
 * Item class.
 */
class Item extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Item'
     */
    public $name = 'Item';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Syfile' => ['counterCache' => true]];

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Portfolio' => ['unique' => false]];
}

/**
 * ItemsPortfolio class.
 */
class ItemsPortfolio extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ItemsPortfolio'
     */
    public $name = 'ItemsPortfolio';
}

/**
 * Syfile class.
 */
class Syfile extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Syfile'
     */
    public $name = 'Syfile';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Image'];
}

/**
 * Image class.
 */
class Image extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Image'
     */
    public $name = 'Image';
}

/**
 * DeviceType class.
 */
class DeviceType extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'DeviceType'
     */
    public $name = 'DeviceType';

    /**
     * order property.
     *
     * @var array
     */
    public $order = ['DeviceType.order' => 'ASC'];

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = [
        'DeviceTypeCategory', 'FeatureSet', 'ExteriorTypeCategory',
        'Image' => ['className' => 'Document'],
        'Extra1' => ['className' => 'Document'],
        'Extra2' => ['className' => 'Document'], ];

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['Device' => ['order' => ['Device.id' => 'ASC']]];
}

/**
 * DeviceTypeCategory class.
 */
class DeviceTypeCategory extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'DeviceTypeCategory'
     */
    public $name = 'DeviceTypeCategory';
}

/**
 * FeatureSet class.
 */
class FeatureSet extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'FeatureSet'
     */
    public $name = 'FeatureSet';
}

/**
 * ExteriorTypeCategory class.
 */
class ExteriorTypeCategory extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ExteriorTypeCategory'
     */
    public $name = 'ExteriorTypeCategory';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Image' => ['className' => 'Device']];
}

/**
 * Document class.
 */
class Document extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Document'
     */
    public $name = 'Document';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['DocumentDirectory'];
}

/**
 * Device class.
 */
class Device extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Device'
     */
    public $name = 'Device';
}

/**
 * DocumentDirectory class.
 */
class DocumentDirectory extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'DocumentDirectory'
     */
    public $name = 'DocumentDirectory';
}

/**
 * PrimaryModel class.
 */
class PrimaryModel extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'PrimaryModel'
     */
    public $name = 'PrimaryModel';
}

/**
 * SecondaryModel class.
 */
class SecondaryModel extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'SecondaryModel'
     */
    public $name = 'SecondaryModel';
}

/**
 * JoinA class.
 */
class JoinA extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'JoinA'
     */
    public $name = 'JoinA';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['JoinB', 'JoinC'];
}

/**
 * JoinB class.
 */
class JoinB extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'JoinB'
     */
    public $name = 'JoinB';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['JoinA'];
}

/**
 * JoinC class.
 */
class JoinC extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'JoinC'
     */
    public $name = 'JoinC';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['JoinA'];
}

/**
 * ThePaper class.
 */
class ThePaper extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ThePaper'
     */
    public $name = 'ThePaper';

    /**
     * useTable property.
     *
     * @var string 'apples'
     */
    public $useTable = 'apples';

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = ['Itself' => ['className' => 'ThePaper', 'foreignKey' => 'apple_id']];

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Monkey' => ['joinTable' => 'the_paper_monkies', 'order' => 'id']];
}

/**
 * Monkey class.
 */
class Monkey extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Monkey'
     */
    public $name = 'Monkey';

    /**
     * useTable property.
     *
     * @var string 'devices'
     */
    public $useTable = 'devices';
}

/**
 * AssociationTest1 class.
 */
class AssociationTest1 extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var string 'join_as'
     */
    public $useTable = 'join_as';

    /**
     * name property.
     *
     * @var string 'AssociationTest1'
     */
    public $name = 'AssociationTest1';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['AssociationTest2' => [
        'unique' => false, 'joinTable' => 'join_as_join_bs', 'foreignKey' => false,
    ]];
}

/**
 * AssociationTest2 class.
 */
class AssociationTest2 extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var string 'join_bs'
     */
    public $useTable = 'join_bs';

    /**
     * name property.
     *
     * @var string 'AssociationTest2'
     */
    public $name = 'AssociationTest2';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['AssociationTest1' => [
        'unique' => false, 'joinTable' => 'join_as_join_bs',
    ]];
}

/**
 * Callback class.
 */
class Callback extends CakeTestModel
{
}
/**
 * CallbackPostTestModel class.
 */
class CallbackPostTestModel extends CakeTestModel
{
    public $useTable = 'posts';
    /**
     * variable to control return of beforeValidate.
     *
     * @var string
     */
    public $beforeValidateReturn = true;
    /**
     * variable to control return of beforeSave.
     *
     * @var string
     */
    public $beforeSaveReturn = true;
    /**
     * variable to control return of beforeDelete.
     *
     * @var string
     */
    public $beforeDeleteReturn = true;

    /**
     * beforeSave callback.
     */
    public function beforeSave($options)
    {
        return $this->beforeSaveReturn;
    }

    /**
     * beforeValidate callback.
     */
    public function beforeValidate($options)
    {
        return $this->beforeValidateReturn;
    }

    /**
     * beforeDelete callback.
     */
    public function beforeDelete($cascade = true)
    {
        return $this->beforeDeleteReturn;
    }
}

/**
 * Uuid class.
 */
class Uuid extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Uuid'
     */
    public $name = 'Uuid';
}

/**
 * DataTest class.
 */
class DataTest extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'DataTest'
     */
    public $name = 'DataTest';
}

/**
 * TheVoid class.
 */
class TheVoid extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'TheVoid'
     */
    public $name = 'TheVoid';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;
}

/**
 * ValidationTest1 class.
 */
class ValidationTest1 extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ValidationTest'
     */
    public $name = 'ValidationTest1';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * schema property.
     *
     * @var array
     */
    public $_schema = [];

    /**
     * validate property.
     *
     * @var array
     */
    public $validate = [
        'title' => 'notEmpty',
        'published' => 'customValidationMethod',
        'body' => [
            'notEmpty',
            '/^.{5,}$/s' => 'no matchy',
            '/^[0-9A-Za-z \\.]{1,}$/s',
        ],
    ];

    /**
     * customValidationMethod method.
     *
     * @param mixed $data
     */
    public function customValidationMethod($data)
    {
        return 1 === $data;
    }

    /**
     * Custom validator with parameters + default values.
     *
     * @return array
     */
    public function customValidatorWithParams($data, $validator, $or = true, $ignore_on_same = 'id')
    {
        $this->validatorParams = get_defined_vars();
        unset($this->validatorParams['this']);

        return true;
    }

    /**
     * Custom validator with messaage.
     *
     * @return array
     */
    public function customValidatorWithMessage($data)
    {
        return 'This field will *never* validate! Muhahaha!';
    }

    /**
     * Test validation with many parameters.
     */
    public function customValidatorWithSixParams($data, $one = 1, $two = 2, $three = 3, $four = 4, $five = 5, $six = 6)
    {
        $this->validatorParams = get_defined_vars();
        unset($this->validatorParams['this']);

        return true;
    }
}

/**
 * ValidationTest2 class.
 */
class ValidationTest2 extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ValidationTest2'
     */
    public $name = 'ValidationTest2';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * validate property.
     *
     * @var array
     */
    public $validate = [
        'title' => 'notEmpty',
        'published' => 'customValidationMethod',
        'body' => [
            'notEmpty',
            '/^.{5,}$/s' => 'no matchy',
            '/^[0-9A-Za-z \\.]{1,}$/s',
        ],
    ];

    /**
     * customValidationMethod method.
     *
     * @param mixed $data
     */
    public function customValidationMethod($data)
    {
        return 1 === $data;
    }

    /**
     * schema method.
     */
    public function schema()
    {
        return [];
    }
}

/**
 * Person class.
 */
class Person extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Person'
     */
    public $name = 'Person';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = [
            'Mother' => [
                'className' => 'Person',
                'foreignKey' => 'mother_id', ],
            'Father' => [
                'className' => 'Person',
                'foreignKey' => 'father_id', ], ];
}

/**
 * UnderscoreField class.
 */
class UnderscoreField extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'UnderscoreField'
     */
    public $name = 'UnderscoreField';
}

/**
 * Product class.
 */
class Product extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Product'
     */
    public $name = 'Product';
}

/**
 * Story class.
 */
class Story extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Story'
     */
    public $name = 'Story';

    /**
     * primaryKey property.
     *
     * @var string 'story'
     */
    public $primaryKey = 'story';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Tag' => ['foreignKey' => 'story']];

    /**
     * validate property.
     *
     * @var array
     */
    public $validate = ['title' => 'notEmpty'];
}

/**
 * Cd class.
 */
class Cd extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Cd'
     */
    public $name = 'Cd';

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = ['OverallFavorite' => ['foreignKey' => 'model_id', 'dependent' => true, 'conditions' => ['model_type' => 'Cd']]];
}

/**
 * Book class.
 */
class Book extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Book'
     */
    public $name = 'Book';

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = ['OverallFavorite' => ['foreignKey' => 'model_id', 'dependent' => true, 'conditions' => 'OverallFavorite.model_type = \'Book\'']];
}

/**
 * OverallFavorite class.
 */
class OverallFavorite extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'OverallFavorite'
     */
    public $name = 'OverallFavorite';
}

/**
 * MyUser class.
 */
class MyUser extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'MyUser'
     */
    public $name = 'MyUser';

    /**
     * undocumented variable.
     *
     * @var string
     */
    public $hasAndBelongsToMany = ['MyCategory'];
}

/**
 * MyCategory class.
 */
class MyCategory extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'MyCategory'
     */
    public $name = 'MyCategory';

    /**
     * undocumented variable.
     *
     * @var string
     */
    public $hasAndBelongsToMany = ['MyProduct', 'MyUser'];
}

/**
 * MyProduct class.
 */
class MyProduct extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'MyProduct'
     */
    public $name = 'MyProduct';

    /**
     * undocumented variable.
     *
     * @var string
     */
    public $hasAndBelongsToMany = ['MyCategory'];
}

/**
 * MyCategoriesMyUser class.
 */
class MyCategoriesMyUser extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'MyCategoriesMyUser'
     */
    public $name = 'MyCategoriesMyUser';
}

/**
 * MyCategoriesMyProduct class.
 */
class MyCategoriesMyProduct extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'MyCategoriesMyProduct'
     */
    public $name = 'MyCategoriesMyProduct';
}

/**
 * I18nModel class.
 */
class I18nModel extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'I18nModel'
     */
    public $name = 'I18nModel';

    /**
     * useTable property.
     *
     * @var string 'i18n'
     */
    public $useTable = 'i18n';

    /**
     * displayField property.
     *
     * @var string 'field'
     */
    public $displayField = 'field';
}

/**
 * NumberTree class.
 */
class NumberTree extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'NumberTree'
     */
    public $name = 'NumberTree';

    /**
     * actsAs property.
     *
     * @var array
     */
    public $actsAs = ['Tree'];

    /**
     * initialize method.
     *
     * @param int    $levelLimit
     * @param int    $childLimit
     * @param mixed  $currentLevel
     * @param mixed  $parent_id
     * @param string $prefix
     * @param bool   $hierachial
     */
    public function initialize($levelLimit = 3, $childLimit = 3, $currentLevel = null, $parent_id = null, $prefix = '1', $hierachial = true)
    {
        if (!$parent_id) {
            $db = &ConnectionManager::getDataSource($this->useDbConfig);
            $db->truncate($this->table);
            $this->save([$this->name => ['name' => '1. Root']]);
            $this->initialize($levelLimit, $childLimit, 1, $this->id, '1', $hierachial);
            $this->create([]);
        }

        if (!$currentLevel || $currentLevel > $levelLimit) {
            return;
        }

        for ($i = 1; $i <= $childLimit; ++$i) {
            $name = $prefix.'.'.$i;
            $data = [$this->name => ['name' => $name]];
            $this->create($data);

            if ($hierachial) {
                if ('UnconventionalTree' == $this->name) {
                    $data[$this->name]['join'] = $parent_id;
                } else {
                    $data[$this->name]['parent_id'] = $parent_id;
                }
            }
            $this->save($data);
            $this->initialize($levelLimit, $childLimit, $currentLevel + 1, $this->id, $name, $hierachial);
        }
    }
}

/**
 * NumberTreeTwo class.
 */
class NumberTreeTwo extends NumberTree
{
    /**
     * name property.
     *
     * @var string 'NumberTree'
     */
    public $name = 'NumberTreeTwo';

    /**
     * actsAs property.
     *
     * @var array
     */
    public $actsAs = [];
}

/**
 * FlagTree class.
 */
class FlagTree extends NumberTree
{
    /**
     * name property.
     *
     * @var string 'FlagTree'
     */
    public $name = 'FlagTree';
}

/**
 * UnconventionalTree class.
 */
class UnconventionalTree extends NumberTree
{
    /**
     * name property.
     *
     * @var string 'FlagTree'
     */
    public $name = 'UnconventionalTree';
    public $actsAs = [
        'Tree' => [
            'parent' => 'join',
            'left' => 'left',
            'right' => 'right',
        ],
    ];
}

/**
 * UuidTree class.
 */
class UuidTree extends NumberTree
{
    /**
     * name property.
     *
     * @var string 'FlagTree'
     */
    public $name = 'UuidTree';
}

/**
 * Campaign class.
 */
class Campaign extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Campaign'
     */
    public $name = 'Campaign';

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['Ad' => ['fields' => ['id', 'campaign_id', 'name']]];
}

/**
 * Ad class.
 */
class Ad extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Ad'
     */
    public $name = 'Ad';

    /**
     * actsAs property.
     *
     * @var array
     */
    public $actsAs = ['Tree'];

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['Campaign'];
}

/**
 * AfterTree class.
 */
class AfterTree extends NumberTree
{
    /**
     * name property.
     *
     * @var string 'AfterTree'
     */
    public $name = 'AfterTree';

    /**
     * actsAs property.
     *
     * @var array
     */
    public $actsAs = ['Tree'];

    public function afterSave($created)
    {
        if ($created && isset($this->data['AfterTree'])) {
            $this->data['AfterTree']['name'] = 'Six and One Half Changed in AfterTree::afterSave() but not in database';
        }
    }
}

/**
 * Nonconformant Content class.
 */
class Content extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Content'
     */
    public $name = 'Content';

    /**
     * useTable property.
     *
     * @var string 'Content'
     */
    public $useTable = 'Content';

    /**
     * primaryKey property.
     *
     * @var string 'iContentId'
     */
    public $primaryKey = 'iContentId';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Account' => ['className' => 'Account', 'with' => 'ContentAccount', 'joinTable' => 'ContentAccounts', 'foreignKey' => 'iContentId', 'associationForeignKey', 'iAccountId']];
}

/**
 * Nonconformant Account class.
 */
class Account extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Account'
     */
    public $name = 'Account';

    /**
     * useTable property.
     *
     * @var string 'Account'
     */
    public $useTable = 'Accounts';

    /**
     * primaryKey property.
     *
     * @var string 'iAccountId'
     */
    public $primaryKey = 'iAccountId';
}

/**
 * Nonconformant ContentAccount class.
 */
class ContentAccount extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Account'
     */
    public $name = 'ContentAccount';

    /**
     * useTable property.
     *
     * @var string 'Account'
     */
    public $useTable = 'ContentAccounts';

    /**
     * primaryKey property.
     *
     * @var string 'iAccountId'
     */
    public $primaryKey = 'iContentAccountsId';
}

/**
 * FilmFile class.
 */
class FilmFile extends CakeTestModel
{
    public $name = 'FilmFile';
}

/**
 * Basket test model.
 */
class Basket extends CakeTestModel
{
    public $name = 'Basket';

    public $belongsTo = [
        'FilmFile' => [
            'className' => 'FilmFile',
            'foreignKey' => 'object_id',
            'conditions' => "Basket.type = 'file'",
            'fields' => '',
            'order' => '',
        ],
    ];
}

/**
 * TestPluginArticle class.
 */
class TestPluginArticle extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'TestPluginArticle'
     */
    public $name = 'TestPluginArticle';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['User'];

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = [
        'TestPluginComment' => [
            'className' => 'TestPlugin.TestPluginComment',
            'foreignKey' => 'article_id',
            'dependent' => true,
        ],
    ];
}

/**
 * TestPluginComment class.
 */
class TestPluginComment extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'TestPluginComment'
     */
    public $name = 'TestPluginComment';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = [
        'TestPluginArticle' => [
            'className' => 'TestPlugin.TestPluginArticle',
            'foreignKey' => 'article_id',
        ],
        'User',
    ];
}

/**
 * Uuidportfolio class.
 */
class Uuidportfolio extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Uuidportfolio'
     */
    public $name = 'Uuidportfolio';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Uuiditem'];
}

/**
 * Uuiditem class.
 */
class Uuiditem extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Item'
     */
    public $name = 'Uuiditem';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Uuidportfolio' => ['with' => 'UuiditemsUuidportfolioNumericid']];
}

/**
 * UuiditemsPortfolio class.
 */
class UuiditemsUuidportfolio extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ItemsPortfolio'
     */
    public $name = 'UuiditemsUuidportfolio';
}

/**
 * UuiditemsPortfolioNumericid class.
 */
class UuiditemsUuidportfolioNumericid extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string
     */
    public $name = 'UuiditemsUuidportfolioNumericid';
}

/**
 * TranslateTestModel class.
 */
class TranslateTestModel extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'TranslateTestModel'
     */
    public $name = 'TranslateTestModel';

    /**
     * useTable property.
     *
     * @var string 'i18n'
     */
    public $useTable = 'i18n';

    /**
     * displayField property.
     *
     * @var string 'field'
     */
    public $displayField = 'field';
}

/**
 * TranslateTestModel class.
 */
class TranslateWithPrefix extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'TranslateTestModel'
     */
    public $name = 'TranslateWithPrefix';
    /**
     * tablePrefix property.
     *
     * @var string 'i18n'
     */
    public $tablePrefix = 'i18n_';
    /**
     * displayField property.
     *
     * @var string 'field'
     */
    public $displayField = 'field';
}
/**
 * TranslatedItem class.
 */
class TranslatedItem extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'TranslatedItem'
     */
    public $name = 'TranslatedItem';

    /**
     * cacheQueries property.
     *
     * @var bool false
     */
    public $cacheQueries = false;

    /**
     * actsAs property.
     *
     * @var array
     */
    public $actsAs = ['Translate' => ['content', 'title']];

    /**
     * translateModel property.
     *
     * @var string 'TranslateTestModel'
     */
    public $translateModel = 'TranslateTestModel';
}

/**
 * TranslatedItem class.
 */
class TranslatedItem2 extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'TranslatedItem'
     */
    public $name = 'TranslatedItem';
    /**
     * cacheQueries property.
     *
     * @var bool false
     */
    public $cacheQueries = false;
    /**
     * actsAs property.
     *
     * @var array
     */
    public $actsAs = ['Translate' => ['content', 'title']];
    /**
     * translateModel property.
     *
     * @var string 'TranslateTestModel'
     */
    public $translateModel = 'TranslateWithPrefix';
}
/**
 * TranslatedItemWithTable class.
 */
class TranslatedItemWithTable extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'TranslatedItemWithTable'
     */
    public $name = 'TranslatedItemWithTable';

    /**
     * useTable property.
     *
     * @var string 'translated_items'
     */
    public $useTable = 'translated_items';

    /**
     * cacheQueries property.
     *
     * @var bool false
     */
    public $cacheQueries = false;

    /**
     * actsAs property.
     *
     * @var array
     */
    public $actsAs = ['Translate' => ['content', 'title']];

    /**
     * translateModel property.
     *
     * @var string 'TranslateTestModel'
     */
    public $translateModel = 'TranslateTestModel';

    /**
     * translateTable property.
     *
     * @var string 'another_i18n'
     */
    public $translateTable = 'another_i18n';
}

/**
 * TranslateArticleModel class.
 */
class TranslateArticleModel extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'TranslateArticleModel'
     */
    public $name = 'TranslateArticleModel';

    /**
     * useTable property.
     *
     * @var string 'article_i18n'
     */
    public $useTable = 'article_i18n';

    /**
     * displayField property.
     *
     * @var string 'field'
     */
    public $displayField = 'field';
}

/**
 * TranslatedArticle class.
 */
class TranslatedArticle extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'TranslatedArticle'
     */
    public $name = 'TranslatedArticle';

    /**
     * cacheQueries property.
     *
     * @var bool false
     */
    public $cacheQueries = false;

    /**
     * actsAs property.
     *
     * @var array
     */
    public $actsAs = ['Translate' => ['title', 'body']];

    /**
     * translateModel property.
     *
     * @var string 'TranslateArticleModel'
     */
    public $translateModel = 'TranslateArticleModel';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['User'];

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['TranslatedItem'];
}

class CounterCacheUser extends CakeTestModel
{
    public $name = 'CounterCacheUser';
    public $alias = 'User';

    public $hasMany = ['Post' => [
        'className' => 'CounterCachePost',
        'foreignKey' => 'user_id',
    ]];
}

class CounterCachePost extends CakeTestModel
{
    public $name = 'CounterCachePost';
    public $alias = 'Post';

    public $belongsTo = ['User' => [
        'className' => 'CounterCacheUser',
        'foreignKey' => 'user_id',
        'counterCache' => true,
    ]];
}

class CounterCacheUserNonstandardPrimaryKey extends CakeTestModel
{
    public $name = 'CounterCacheUserNonstandardPrimaryKey';
    public $alias = 'User';
    public $primaryKey = 'uid';

    public $hasMany = ['Post' => [
        'className' => 'CounterCachePostNonstandardPrimaryKey',
        'foreignKey' => 'uid',
    ]];
}

class CounterCachePostNonstandardPrimaryKey extends CakeTestModel
{
    public $name = 'CounterCachePostNonstandardPrimaryKey';
    public $alias = 'Post';
    public $primaryKey = 'pid';

    public $belongsTo = ['User' => [
        'className' => 'CounterCacheUserNonstandardPrimaryKey',
        'foreignKey' => 'uid',
        'counterCache' => true,
    ]];
}

class ArticleB extends CakeTestModel
{
    public $name = 'ArticleB';
    public $useTable = 'articles';
    public $hasAndBelongsToMany = [
        'TagB' => [
            'className' => 'TagB',
            'joinTable' => 'articles_tags',
            'foreignKey' => 'article_id',
            'associationForeignKey' => 'tag_id',
        ],
    ];
}

class TagB extends CakeTestModel
{
    public $name = 'TagB';
    public $useTable = 'tags';
    public $hasAndBelongsToMany = [
        'ArticleB' => [
            'className' => 'ArticleB',
            'joinTable' => 'articles_tags',
            'foreignKey' => 'tag_id',
            'associationForeignKey' => 'article_id',
        ],
    ];
}

class Fruit extends CakeTestModel
{
    public $name = 'Fruit';
    public $hasAndBelongsToMany = [
        'UuidTag' => [
            'className' => 'UuidTag',
            'joinTable' => 'fruits_uuid_tags',
            'foreignKey' => 'fruit_id',
            'associationForeignKey' => 'uuid_tag_id',
            'with' => 'FruitsUuidTag',
        ],
    ];
}

class FruitsUuidTag extends CakeTestModel
{
    public $name = 'FruitsUuidTag';
    public $primaryKey = false;
    public $belongsTo = [
        'UuidTag' => [
            'className' => 'UuidTag',
            'foreignKey' => 'uuid_tag_id',
        ],
        'Fruit' => [
            'className' => 'Fruit',
            'foreignKey' => 'fruit_id',
        ],
    ];
}

class UuidTag extends CakeTestModel
{
    public $name = 'UuidTag';
    public $hasAndBelongsToMany = [
        'Fruit' => [
            'className' => 'Fruit',
            'joinTable' => 'fruits_uuid_tags',
            'foreign_key' => 'uuid_tag_id',
            'associationForeignKey' => 'fruit_id',
            'with' => 'FruitsUuidTag',
        ],
    ];
}

class FruitNoWith extends CakeTestModel
{
    public $name = 'Fruit';
    public $useTable = 'fruits';
    public $hasAndBelongsToMany = [
        'UuidTag' => [
            'className' => 'UuidTagNoWith',
            'joinTable' => 'fruits_uuid_tags',
            'foreignKey' => 'fruit_id',
            'associationForeignKey' => 'uuid_tag_id',
        ],
    ];
}

class UuidTagNoWith extends CakeTestModel
{
    public $name = 'UuidTag';
    public $useTable = 'uuid_tags';
    public $hasAndBelongsToMany = [
        'Fruit' => [
            'className' => 'FruitNoWith',
            'joinTable' => 'fruits_uuid_tags',
            'foreign_key' => 'uuid_tag_id',
            'associationForeignKey' => 'fruit_id',
        ],
    ];
}

class ProductUpdateAll extends CakeTestModel
{
    public $name = 'ProductUpdateAll';
    public $useTable = 'product_update_all';
}

class GroupUpdateAll extends CakeTestModel
{
    public $name = 'GroupUpdateAll';
    public $useTable = 'group_update_all';
}

class TransactionTestModel extends CakeTestModel
{
    public $name = 'TransactionTestModel';
    public $useTable = 'samples';

    public function afterSave($created)
    {
        $data = [
            ['apple_id' => 1, 'name' => 'sample6'],
        ];
        $this->saveAll($data, ['atomic' => true, 'callbacks' => false]);
    }
}

/**
 * Test model for datasource prefixes.
 */
class PrefixTestModel extends CakeTestModel
{
}
class PrefixTestUseTableModel extends CakeTestModel
{
    public $name = 'PrefixTest';
    public $useTable = 'prefix_tests';
}
