<?php
/**
 * ClassRegistryTest file.
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
 * @since         CakePHP(tm) v 1.2.0.5432
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', 'ClassRegistry');

/**
 * ClassRegisterModel class.
 */
class ClassRegisterModel extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;
}

/**
 * RegisterArticle class.
 */
class RegisterArticle extends ClassRegisterModel
{
    /**
     * name property.
     *
     * @var string 'RegisterArticle'
     */
    public $name = 'RegisterArticle';
}

/**
 * RegisterArticleFeatured class.
 */
class RegisterArticleFeatured extends ClassRegisterModel
{
    /**
     * name property.
     *
     * @var string 'RegisterArticleFeatured'
     */
    public $name = 'RegisterArticleFeatured';
}

/**
 * RegisterArticleTag class.
 */
class RegisterArticleTag extends ClassRegisterModel
{
    /**
     * name property.
     *
     * @var string 'RegisterArticleTag'
     */
    public $name = 'RegisterArticleTag';
}

/**
 * RegistryPluginAppModel class.
 */
class RegistryPluginAppModel extends ClassRegisterModel
{
    /**
     * tablePrefix property.
     *
     * @var string 'something_'
     */
    public $tablePrefix = 'something_';
}

/**
 * TestRegistryPluginModel class.
 */
class TestRegistryPluginModel extends RegistryPluginAppModel
{
    /**
     * name property.
     *
     * @var string 'TestRegistryPluginModel'
     */
    public $name = 'TestRegistryPluginModel';
}

/**
 * RegisterCategory class.
 */
class RegisterCategory extends ClassRegisterModel
{
    /**
     * name property.
     *
     * @var string 'RegisterCategory'
     */
    public $name = 'RegisterCategory';
}

/**
 * ClassRegistryTest class.
 */
class ClassRegistryTest extends CakeTestCase
{
    /**
     * testAddModel method.
     */
    public function testAddModel()
    {
        if (PHP5) {
            $Tag = ClassRegistry::init('RegisterArticleTag');
        } else {
            $Tag = &ClassRegistry::init('RegisterArticleTag');
        }
        $this->assertTrue(is_a($Tag, 'RegisterArticleTag'));

        $TagCopy = ClassRegistry::isKeySet('RegisterArticleTag');
        $this->assertTrue($TagCopy);

        $Tag->name = 'SomeNewName';

        if (PHP5) {
            $TagCopy = ClassRegistry::getObject('RegisterArticleTag');
        } else {
            $TagCopy = &ClassRegistry::getObject('RegisterArticleTag');
        }

        $this->assertTrue(is_a($TagCopy, 'RegisterArticleTag'));
        $this->assertIdentical($Tag, $TagCopy);

        if (PHP5) {
            $NewTag = ClassRegistry::init(['class' => 'RegisterArticleTag', 'alias' => 'NewTag']);
        } else {
            $NewTag = &ClassRegistry::init(['class' => 'RegisterArticleTag', 'alias' => 'NewTag']);
        }
        $this->assertTrue(is_a($Tag, 'RegisterArticleTag'));

        if (PHP5) {
            $NewTagCopy = ClassRegistry::init(['class' => 'RegisterArticleTag', 'alias' => 'NewTag']);
        } else {
            $NewTagCopy = &ClassRegistry::init(['class' => 'RegisterArticleTag', 'alias' => 'NewTag']);
        }

        $this->assertNotIdentical($Tag, $NewTag);
        $this->assertIdentical($NewTag, $NewTagCopy);

        $NewTag->name = 'SomeOtherName';
        $this->assertNotIdentical($Tag, $NewTag);
        $this->assertIdentical($NewTag, $NewTagCopy);

        $Tag->name = 'SomeOtherName';
        $this->assertNotIdentical($Tag, $NewTag);

        $this->assertTrue('SomeOtherName' === $TagCopy->name);

        if (PHP5) {
            $User = ClassRegistry::init(['class' => 'RegisterUser', 'alias' => 'User', 'table' => false]);
        } else {
            $User = &ClassRegistry::init(['class' => 'RegisterUser', 'alias' => 'User', 'table' => false]);
        }
        $this->assertTrue(is_a($User, 'AppModel'));

        if (PHP5) {
            $UserCopy = ClassRegistry::init(['class' => 'RegisterUser', 'alias' => 'User', 'table' => false]);
        } else {
            $UserCopy = &ClassRegistry::init(['class' => 'RegisterUser', 'alias' => 'User', 'table' => false]);
        }
        $this->assertTrue(is_a($UserCopy, 'AppModel'));
        $this->assertIdentical($User, $UserCopy);

        if (PHP5) {
            $Category = ClassRegistry::init(['class' => 'RegisterCategory']);
        } else {
            $Category = &ClassRegistry::init(['class' => 'RegisterCategory']);
        }
        $this->assertTrue(is_a($Category, 'RegisterCategory'));

        if (PHP5) {
            $ParentCategory = ClassRegistry::init(['class' => 'RegisterCategory', 'alias' => 'ParentCategory']);
        } else {
            $ParentCategory = &ClassRegistry::init(['class' => 'RegisterCategory', 'alias' => 'ParentCategory']);
        }
        $this->assertTrue(is_a($ParentCategory, 'RegisterCategory'));
        $this->assertNotIdentical($Category, $ParentCategory);

        $this->assertNotEqual($Category->alias, $ParentCategory->alias);
        $this->assertEqual('RegisterCategory', $Category->alias);
        $this->assertEqual('ParentCategory', $ParentCategory->alias);
    }

    /**
     * testClassRegistryFlush method.
     */
    public function testClassRegistryFlush()
    {
        $ArticleTag = ClassRegistry::getObject('RegisterArticleTag');
        $this->assertTrue(is_a($ArticleTag, 'RegisterArticleTag'));
        ClassRegistry::flush();

        $NoArticleTag = ClassRegistry::isKeySet('RegisterArticleTag');
        $this->assertFalse($NoArticleTag);
        $this->assertTrue(is_a($ArticleTag, 'RegisterArticleTag'));
    }

    /**
     * testAddMultipleModels method.
     */
    public function testAddMultipleModels()
    {
        $Article = ClassRegistry::isKeySet('Article');
        $this->assertFalse($Article);

        $Featured = ClassRegistry::isKeySet('Featured');
        $this->assertFalse($Featured);

        $Tag = ClassRegistry::isKeySet('Tag');
        $this->assertFalse($Tag);

        $models = [['class' => 'RegisterArticle', 'alias' => 'Article'],
                ['class' => 'RegisterArticleFeatured', 'alias' => 'Featured'],
                ['class' => 'RegisterArticleTag', 'alias' => 'Tag'], ];

        $added = ClassRegistry::init($models);
        $this->assertTrue($added);

        $Article = ClassRegistry::isKeySet('Article');
        $this->assertTrue($Article);

        $Featured = ClassRegistry::isKeySet('Featured');
        $this->assertTrue($Featured);

        $Tag = ClassRegistry::isKeySet('Tag');
        $this->assertTrue($Tag);

        $Article = ClassRegistry::getObject('Article');
        $this->assertTrue(is_a($Article, 'RegisterArticle'));

        $Featured = ClassRegistry::getObject('Featured');
        $this->assertTrue(is_a($Featured, 'RegisterArticleFeatured'));

        $Tag = ClassRegistry::getObject('Tag');
        $this->assertTrue(is_a($Tag, 'RegisterArticleTag'));
    }

    /**
     * testPluginAppModel method.
     */
    public function testPluginAppModel()
    {
        $TestRegistryPluginModel = ClassRegistry::isKeySet('TestRegistryPluginModel');
        $this->assertFalse($TestRegistryPluginModel);

        $TestRegistryPluginModel = ClassRegistry::init('RegistryPlugin.TestRegistryPluginModel');
        $this->assertTrue(is_a($TestRegistryPluginModel, 'TestRegistryPluginModel'));

        $this->assertEqual($TestRegistryPluginModel->tablePrefix, 'something_');

        if (PHP5) {
            $PluginUser = ClassRegistry::init(['class' => 'RegistryPlugin.RegisterUser', 'alias' => 'RegistryPluginUser', 'table' => false]);
        } else {
            $PluginUser = &ClassRegistry::init(['class' => 'RegistryPlugin.RegisterUser', 'alias' => 'RegistryPluginUser', 'table' => false]);
        }
        $this->assertTrue(is_a($PluginUser, 'RegistryPluginAppModel'));

        if (PHP5) {
            $PluginUserCopy = ClassRegistry::getObject('RegistryPluginUser');
        } else {
            $PluginUserCopy = &ClassRegistry::getObject('RegistryPluginUser');
        }
        $this->assertTrue(is_a($PluginUserCopy, 'RegistryPluginAppModel'));
        $this->assertIdentical($PluginUser, $PluginUserCopy);
    }
}
