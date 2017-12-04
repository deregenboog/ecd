<?php
/**
 * L10nTest file.
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
App::import('Core', 'l10n');

/**
 * L10nTest class.
 */
class L10nTest extends CakeTestCase
{
    /**
     * testGet method.
     */
    public function testGet()
    {
        $l10n = new L10n();

        // Catalog Entry
        $l10n->get('en');

        $this->assertEqual($l10n->language, 'English');
        $this->assertEqual($l10n->languagePath, ['eng', 'eng']);
        $this->assertEqual($l10n->locale, 'eng');

        // Map Entry
        $l10n->get('eng');

        $this->assertEqual($l10n->language, 'English');
        $this->assertEqual($l10n->languagePath, ['eng', 'eng']);
        $this->assertEqual($l10n->locale, 'eng');

        // Catalog Entry
        $l10n->get('en-ca');

        $this->assertEqual($l10n->language, 'English (Canadian)');
        $this->assertEqual($l10n->languagePath, ['en_ca', 'eng']);
        $this->assertEqual($l10n->locale, 'en_ca');

        // Default Entry
        define('DEFAULT_LANGUAGE', 'en-us');

        $l10n->get('use_default');

        $this->assertEqual($l10n->language, 'English (United States)');
        $this->assertEqual($l10n->languagePath, ['en_us', 'eng']);
        $this->assertEqual($l10n->locale, 'en_us');

        $l10n->get('es');
        $l10n->get('');
        $this->assertEqual($l10n->lang, 'en-us');

        // Using $this->default
        $l10n = new L10n();

        $l10n->get('use_default');
        $this->assertEqual($l10n->language, 'English (United States)');
        $this->assertEqual($l10n->languagePath, ['en_us', 'eng', 'eng']);
        $this->assertEqual($l10n->locale, 'en_us');
    }

    /**
     * testGetAutoLanguage method.
     */
    public function testGetAutoLanguage()
    {
        $__SERVER = $_SERVER;
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'inexistent,en-ca';

        $l10n = new L10n();
        $l10n->get();

        $this->assertEqual($l10n->language, 'English (Canadian)');
        $this->assertEqual($l10n->languagePath, ['en_ca', 'eng', 'eng']);
        $this->assertEqual($l10n->locale, 'en_ca');

        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'es_mx';
        $l10n->get();

        $this->assertEqual($l10n->language, 'Spanish (Mexican)');
        $this->assertEqual($l10n->languagePath, ['es_mx', 'spa', 'eng']);
        $this->assertEqual($l10n->locale, 'es_mx');

        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en_xy,en_ca';
        $l10n->get();

        $this->assertEqual($l10n->language, 'English');
        $this->assertEqual($l10n->languagePath, ['eng', 'eng', 'eng']);
        $this->assertEqual($l10n->locale, 'eng');

        $_SERVER = $__SERVER;
    }

    /**
     * testMap method.
     */
    public function testMap()
    {
        $l10n = new L10n();

        $result = $l10n->map(['afr', 'af']);
        $expected = ['afr' => 'af', 'af' => 'afr'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['alb', 'sq']);
        $expected = ['alb' => 'sq', 'sq' => 'alb'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['ara', 'ar']);
        $expected = ['ara' => 'ar', 'ar' => 'ara'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['hye', 'hy']);
        $expected = ['hye' => 'hy', 'hy' => 'hye'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['baq', 'eu']);
        $expected = ['baq' => 'eu', 'eu' => 'baq'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['baq', 'eu']);
        $expected = ['baq' => 'eu', 'eu' => 'baq'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['bos', 'bs']);
        $expected = ['bos' => 'bs', 'bs' => 'bos'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['bul', 'bg']);
        $expected = ['bul' => 'bg', 'bg' => 'bul'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['bel', 'be']);
        $expected = ['bel' => 'be', 'be' => 'bel'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['cat', 'ca']);
        $expected = ['cat' => 'ca', 'ca' => 'cat'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['chi', 'zh']);
        $expected = ['chi' => 'zh', 'zh' => 'chi'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['zho', 'zh']);
        $expected = ['zho' => 'zh', 'zh' => 'chi'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['hrv', 'hr']);
        $expected = ['hrv' => 'hr', 'hr' => 'hrv'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['ces', 'cs']);
        $expected = ['ces' => 'cs', 'cs' => 'cze'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['cze', 'cs']);
        $expected = ['cze' => 'cs', 'cs' => 'cze'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['dan', 'da']);
        $expected = ['dan' => 'da', 'da' => 'dan'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['dut', 'nl']);
        $expected = ['dut' => 'nl', 'nl' => 'dut'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['nld', 'nl']);
        $expected = ['nld' => 'nl', 'nl' => 'dut'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['nld']);
        $expected = ['nld' => 'nl'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['eng', 'en']);
        $expected = ['eng' => 'en', 'en' => 'eng'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['est', 'et']);
        $expected = ['est' => 'et', 'et' => 'est'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['fao', 'fo']);
        $expected = ['fao' => 'fo', 'fo' => 'fao'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['fas', 'fa']);
        $expected = ['fas' => 'fa', 'fa' => 'fas'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['per', 'fa']);
        $expected = ['per' => 'fa', 'fa' => 'fas'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['fin', 'fi']);
        $expected = ['fin' => 'fi', 'fi' => 'fin'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['fra', 'fr']);
        $expected = ['fra' => 'fr', 'fr' => 'fre'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['fre', 'fr']);
        $expected = ['fre' => 'fr', 'fr' => 'fre'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['gla', 'gd']);
        $expected = ['gla' => 'gd', 'gd' => 'gla'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['glg', 'gl']);
        $expected = ['glg' => 'gl', 'gl' => 'glg'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['deu', 'de']);
        $expected = ['deu' => 'de', 'de' => 'deu'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['ger', 'de']);
        $expected = ['ger' => 'de', 'de' => 'deu'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['ell', 'el']);
        $expected = ['ell' => 'el', 'el' => 'gre'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['gre', 'el']);
        $expected = ['gre' => 'el', 'el' => 'gre'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['heb', 'he']);
        $expected = ['heb' => 'he', 'he' => 'heb'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['hin', 'hi']);
        $expected = ['hin' => 'hi', 'hi' => 'hin'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['hun', 'hu']);
        $expected = ['hun' => 'hu', 'hu' => 'hun'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['ice', 'is']);
        $expected = ['ice' => 'is', 'is' => 'ice'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['isl', 'is']);
        $expected = ['isl' => 'is', 'is' => 'ice'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['ind', 'id']);
        $expected = ['ind' => 'id', 'id' => 'ind'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['gle', 'ga']);
        $expected = ['gle' => 'ga', 'ga' => 'gle'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['ita', 'it']);
        $expected = ['ita' => 'it', 'it' => 'ita'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['jpn', 'ja']);
        $expected = ['jpn' => 'ja', 'ja' => 'jpn'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['kor', 'ko']);
        $expected = ['kor' => 'ko', 'ko' => 'kor'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['lav', 'lv']);
        $expected = ['lav' => 'lv', 'lv' => 'lav'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['lit', 'lt']);
        $expected = ['lit' => 'lt', 'lt' => 'lit'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['mac', 'mk']);
        $expected = ['mac' => 'mk', 'mk' => 'mac'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['mkd', 'mk']);
        $expected = ['mkd' => 'mk', 'mk' => 'mac'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['may', 'ms']);
        $expected = ['may' => 'ms', 'ms' => 'may'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['msa', 'ms']);
        $expected = ['msa' => 'ms', 'ms' => 'may'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['mlt', 'mt']);
        $expected = ['mlt' => 'mt', 'mt' => 'mlt'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['nor', 'no']);
        $expected = ['nor' => 'no', 'no' => 'nor'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['nob', 'nb']);
        $expected = ['nob' => 'nb', 'nb' => 'nob'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['nno', 'nn']);
        $expected = ['nno' => 'nn', 'nn' => 'nno'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['pol', 'pl']);
        $expected = ['pol' => 'pl', 'pl' => 'pol'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['por', 'pt']);
        $expected = ['por' => 'pt', 'pt' => 'por'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['roh', 'rm']);
        $expected = ['roh' => 'rm', 'rm' => 'roh'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['ron', 'ro']);
        $expected = ['ron' => 'ro', 'ro' => 'rum'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['rum', 'ro']);
        $expected = ['rum' => 'ro', 'ro' => 'rum'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['rus', 'ru']);
        $expected = ['rus' => 'ru', 'ru' => 'rus'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['smi', 'sz']);
        $expected = ['smi' => 'sz', 'sz' => 'smi'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['scc', 'sr']);
        $expected = ['scc' => 'sr', 'sr' => 'scc'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['srp', 'sr']);
        $expected = ['srp' => 'sr', 'sr' => 'scc'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['slk', 'sk']);
        $expected = ['slk' => 'sk', 'sk' => 'slo'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['slo', 'sk']);
        $expected = ['slo' => 'sk', 'sk' => 'slo'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['slv', 'sl']);
        $expected = ['slv' => 'sl', 'sl' => 'slv'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['wen', 'sb']);
        $expected = ['wen' => 'sb', 'sb' => 'wen'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['spa', 'es']);
        $expected = ['spa' => 'es', 'es' => 'spa'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['swe', 'sv']);
        $expected = ['swe' => 'sv', 'sv' => 'swe'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['tha', 'th']);
        $expected = ['tha' => 'th', 'th' => 'tha'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['tso', 'ts']);
        $expected = ['tso' => 'ts', 'ts' => 'tso'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['tsn', 'tn']);
        $expected = ['tsn' => 'tn', 'tn' => 'tsn'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['tur', 'tr']);
        $expected = ['tur' => 'tr', 'tr' => 'tur'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['ukr', 'uk']);
        $expected = ['ukr' => 'uk', 'uk' => 'ukr'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['urd', 'ur']);
        $expected = ['urd' => 'ur', 'ur' => 'urd'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['ven', 've']);
        $expected = ['ven' => 've', 've' => 'ven'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['vie', 'vi']);
        $expected = ['vie' => 'vi', 'vi' => 'vie'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['xho', 'xh']);
        $expected = ['xho' => 'xh', 'xh' => 'xho'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['cy', 'cym']);
        $expected = ['cym' => 'cy', 'cy' => 'cym'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['yid', 'yi']);
        $expected = ['yid' => 'yi', 'yi' => 'yid'];
        $this->assertEqual($result, $expected);

        $result = $l10n->map(['zul', 'zu']);
        $expected = ['zul' => 'zu', 'zu' => 'zul'];
        $this->assertEqual($result, $expected);
    }

    /**
     * testCatalog method.
     */
    public function testCatalog()
    {
        $l10n = new L10n();

        $result = $l10n->catalog(['af']);
        $expected = [
            'af' => ['language' => 'Afrikaans', 'locale' => 'afr', 'localeFallback' => 'afr', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['ar', 'ar-ae', 'ar-bh', 'ar-dz', 'ar-eg', 'ar-iq', 'ar-jo', 'ar-kw', 'ar-lb', 'ar-ly', 'ar-ma',
            'ar-om', 'ar-qa', 'ar-sa', 'ar-sy', 'ar-tn', 'ar-ye', ]);
        $expected = [
            'ar' => ['language' => 'Arabic', 'locale' => 'ara', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-ae' => ['language' => 'Arabic (U.A.E.)', 'locale' => 'ar_ae', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-bh' => ['language' => 'Arabic (Bahrain)', 'locale' => 'ar_bh', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-dz' => ['language' => 'Arabic (Algeria)', 'locale' => 'ar_dz', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-eg' => ['language' => 'Arabic (Egypt)', 'locale' => 'ar_eg', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-iq' => ['language' => 'Arabic (Iraq)', 'locale' => 'ar_iq', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-jo' => ['language' => 'Arabic (Jordan)', 'locale' => 'ar_jo', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-kw' => ['language' => 'Arabic (Kuwait)', 'locale' => 'ar_kw', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-lb' => ['language' => 'Arabic (Lebanon)', 'locale' => 'ar_lb', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-ly' => ['language' => 'Arabic (Libya)', 'locale' => 'ar_ly', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-ma' => ['language' => 'Arabic (Morocco)', 'locale' => 'ar_ma', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-om' => ['language' => 'Arabic (Oman)', 'locale' => 'ar_om', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-qa' => ['language' => 'Arabic (Qatar)', 'locale' => 'ar_qa', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-sa' => ['language' => 'Arabic (Saudi Arabia)', 'locale' => 'ar_sa', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-sy' => ['language' => 'Arabic (Syria)', 'locale' => 'ar_sy', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-tn' => ['language' => 'Arabic (Tunisia)', 'locale' => 'ar_tn', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'ar-ye' => ['language' => 'Arabic (Yemen)', 'locale' => 'ar_ye', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['be']);
        $expected = [
            'be' => ['language' => 'Byelorussian', 'locale' => 'bel', 'localeFallback' => 'bel', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['bg']);
        $expected = [
            'bg' => ['language' => 'Bulgarian', 'locale' => 'bul', 'localeFallback' => 'bul', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['bs']);
        $expected = [
            'bs' => ['language' => 'Bosnian', 'locale' => 'bos', 'localeFallback' => 'bos', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['ca']);
        $expected = [
            'ca' => ['language' => 'Catalan', 'locale' => 'cat', 'localeFallback' => 'cat', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['cs']);
        $expected = [
            'cs' => ['language' => 'Czech', 'locale' => 'cze', 'localeFallback' => 'cze', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['da']);
        $expected = [
            'da' => ['language' => 'Danish', 'locale' => 'dan', 'localeFallback' => 'dan', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['de', 'de-at', 'de-ch', 'de-de', 'de-li', 'de-lu']);
        $expected = [
            'de' => ['language' => 'German (Standard)', 'locale' => 'deu', 'localeFallback' => 'deu', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'de-at' => ['language' => 'German (Austria)', 'locale' => 'de_at', 'localeFallback' => 'deu', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'de-ch' => ['language' => 'German (Swiss)', 'locale' => 'de_ch', 'localeFallback' => 'deu', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'de-de' => ['language' => 'German (Germany)', 'locale' => 'de_de', 'localeFallback' => 'deu', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'de-li' => ['language' => 'German (Liechtenstein)', 'locale' => 'de_li', 'localeFallback' => 'deu', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'de-lu' => ['language' => 'German (Luxembourg)', 'locale' => 'de_lu', 'localeFallback' => 'deu', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['e', 'el']);
        $expected = [
            'e' => ['language' => 'Greek', 'locale' => 'gre', 'localeFallback' => 'gre', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'el' => ['language' => 'Greek', 'locale' => 'gre', 'localeFallback' => 'gre', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['en', 'en-au', 'en-bz', 'en-ca', 'en-gb', 'en-ie', 'en-jm', 'en-nz', 'en-tt', 'en-us', 'en-za']);
        $expected = [
            'en' => ['language' => 'English', 'locale' => 'eng', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'en-au' => ['language' => 'English (Australian)', 'locale' => 'en_au', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'en-bz' => ['language' => 'English (Belize)', 'locale' => 'en_bz', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'en-ca' => ['language' => 'English (Canadian)', 'locale' => 'en_ca', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'en-gb' => ['language' => 'English (British)', 'locale' => 'en_gb', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'en-ie' => ['language' => 'English (Ireland)', 'locale' => 'en_ie', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'en-jm' => ['language' => 'English (Jamaica)', 'locale' => 'en_jm', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'en-nz' => ['language' => 'English (New Zealand)', 'locale' => 'en_nz', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'en-tt' => ['language' => 'English (Trinidad)', 'locale' => 'en_tt', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'en-us' => ['language' => 'English (United States)', 'locale' => 'en_us', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'en-za' => ['language' => 'English (South Africa)', 'locale' => 'en_za', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['es', 'es-ar', 'es-bo', 'es-cl', 'es-co', 'es-cr', 'es-do', 'es-ec', 'es-es', 'es-gt', 'es-hn',
            'es-mx', 'es-ni', 'es-pa', 'es-pe', 'es-pr', 'es-py', 'es-sv', 'es-uy', 'es-ve', ]);
        $expected = [
            'es' => ['language' => 'Spanish (Spain - Traditional)', 'locale' => 'spa', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-ar' => ['language' => 'Spanish (Argentina)', 'locale' => 'es_ar', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-bo' => ['language' => 'Spanish (Bolivia)', 'locale' => 'es_bo', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-cl' => ['language' => 'Spanish (Chile)', 'locale' => 'es_cl', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-co' => ['language' => 'Spanish (Colombia)', 'locale' => 'es_co', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-cr' => ['language' => 'Spanish (Costa Rica)', 'locale' => 'es_cr', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-do' => ['language' => 'Spanish (Dominican Republic)', 'locale' => 'es_do', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-ec' => ['language' => 'Spanish (Ecuador)', 'locale' => 'es_ec', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-es' => ['language' => 'Spanish (Spain)', 'locale' => 'es_es', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-gt' => ['language' => 'Spanish (Guatemala)', 'locale' => 'es_gt', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-hn' => ['language' => 'Spanish (Honduras)', 'locale' => 'es_hn', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-mx' => ['language' => 'Spanish (Mexican)', 'locale' => 'es_mx', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-ni' => ['language' => 'Spanish (Nicaragua)', 'locale' => 'es_ni', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-pa' => ['language' => 'Spanish (Panama)', 'locale' => 'es_pa', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-pe' => ['language' => 'Spanish (Peru)', 'locale' => 'es_pe', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-pr' => ['language' => 'Spanish (Puerto Rico)', 'locale' => 'es_pr', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-py' => ['language' => 'Spanish (Paraguay)', 'locale' => 'es_py', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-sv' => ['language' => 'Spanish (El Salvador)', 'locale' => 'es_sv', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-uy' => ['language' => 'Spanish (Uruguay)', 'locale' => 'es_uy', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-ve' => ['language' => 'Spanish (Venezuela)', 'locale' => 'es_ve', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['et']);
        $expected = [
            'et' => ['language' => 'Estonian', 'locale' => 'est', 'localeFallback' => 'est', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['eu']);
        $expected = [
            'eu' => ['language' => 'Basque', 'locale' => 'baq', 'localeFallback' => 'baq', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['fa']);
        $expected = [
            'fa' => ['language' => 'Farsi', 'locale' => 'per', 'localeFallback' => 'per', 'charset' => 'utf-8', 'direction' => 'rtl'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['fi']);
        $expected = [
            'fi' => ['language' => 'Finnish', 'locale' => 'fin', 'localeFallback' => 'fin', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['fo']);
        $expected = [
            'fo' => ['language' => 'Faeroese', 'locale' => 'fao', 'localeFallback' => 'fao', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['fr', 'fr-be', 'fr-ca', 'fr-ch', 'fr-fr', 'fr-lu']);
        $expected = [
            'fr' => ['language' => 'French (Standard)', 'locale' => 'fre', 'localeFallback' => 'fre', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'fr-be' => ['language' => 'French (Belgium)', 'locale' => 'fr_be', 'localeFallback' => 'fre', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'fr-ca' => ['language' => 'French (Canadian)', 'locale' => 'fr_ca', 'localeFallback' => 'fre', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'fr-ch' => ['language' => 'French (Swiss)', 'locale' => 'fr_ch', 'localeFallback' => 'fre', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'fr-fr' => ['language' => 'French (France)', 'locale' => 'fr_fr', 'localeFallback' => 'fre', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'fr-lu' => ['language' => 'French (Luxembourg)', 'locale' => 'fr_lu', 'localeFallback' => 'fre', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['ga']);
        $expected = [
            'ga' => ['language' => 'Irish', 'locale' => 'gle', 'localeFallback' => 'gle', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['gd', 'gd-ie']);
        $expected = [
            'gd' => ['language' => 'Gaelic (Scots)', 'locale' => 'gla', 'localeFallback' => 'gla', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'gd-ie' => ['language' => 'Gaelic (Irish)', 'locale' => 'gd_ie', 'localeFallback' => 'gla', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['gl']);
        $expected = [
            'gl' => ['language' => 'Galician', 'locale' => 'glg', 'localeFallback' => 'glg', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['he']);
        $expected = [
            'he' => ['language' => 'Hebrew', 'locale' => 'heb', 'localeFallback' => 'heb', 'charset' => 'utf-8', 'direction' => 'rtl'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['hi']);
        $expected = [
            'hi' => ['language' => 'Hindi', 'locale' => 'hin', 'localeFallback' => 'hin', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['hr']);
        $expected = [
            'hr' => ['language' => 'Croatian', 'locale' => 'hrv', 'localeFallback' => 'hrv', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['hu']);
        $expected = [
            'hu' => ['language' => 'Hungarian', 'locale' => 'hun', 'localeFallback' => 'hun', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['hy']);
        $expected = [
            'hy' => ['language' => 'Armenian - Armenia', 'locale' => 'hye', 'localeFallback' => 'hye', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['id', 'in']);
        $expected = [
            'id' => ['language' => 'Indonesian', 'locale' => 'ind', 'localeFallback' => 'ind', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'in' => ['language' => 'Indonesian', 'locale' => 'ind', 'localeFallback' => 'ind', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['is']);
        $expected = [
            'is' => ['language' => 'Icelandic', 'locale' => 'ice', 'localeFallback' => 'ice', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['it', 'it-ch']);
        $expected = [
            'it' => ['language' => 'Italian', 'locale' => 'ita', 'localeFallback' => 'ita', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'it-ch' => ['language' => 'Italian (Swiss) ', 'locale' => 'it_ch', 'localeFallback' => 'ita', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['ja']);
        $expected = [
            'ja' => ['language' => 'Japanese', 'locale' => 'jpn', 'localeFallback' => 'jpn', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['ko', 'ko-kp', 'ko-kr']);
        $expected = [
            'ko' => ['language' => 'Korean', 'locale' => 'kor', 'localeFallback' => 'kor', 'charset' => 'kr', 'direction' => 'ltr'],
            'ko-kp' => ['language' => 'Korea (North)', 'locale' => 'ko_kp', 'localeFallback' => 'kor', 'charset' => 'kr', 'direction' => 'ltr'],
            'ko-kr' => ['language' => 'Korea (South)', 'locale' => 'ko_kr', 'localeFallback' => 'kor', 'charset' => 'kr', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['koi8-r', 'ru', 'ru-mo']);
        $expected = [
            'koi8-r' => ['language' => 'Russian', 'locale' => 'koi8_r', 'localeFallback' => 'rus', 'charset' => 'koi8-r', 'direction' => 'ltr'],
            'ru' => ['language' => 'Russian', 'locale' => 'rus', 'localeFallback' => 'rus', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'ru-mo' => ['language' => 'Russian (Moldavia)', 'locale' => 'ru_mo', 'localeFallback' => 'rus', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['lt']);
        $expected = [
            'lt' => ['language' => 'Lithuanian', 'locale' => 'lit', 'localeFallback' => 'lit', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['lv']);
        $expected = [
            'lv' => ['language' => 'Latvian', 'locale' => 'lav', 'localeFallback' => 'lav', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['mk', 'mk-mk']);
        $expected = [
            'mk' => ['language' => 'FYRO Macedonian', 'locale' => 'mk', 'localeFallback' => 'mac', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'mk-mk' => ['language' => 'Macedonian', 'locale' => 'mk_mk', 'localeFallback' => 'mac', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['ms']);
        $expected = [
            'ms' => ['language' => 'Malaysian', 'locale' => 'may', 'localeFallback' => 'may', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['mt']);
        $expected = [
            'mt' => ['language' => 'Maltese', 'locale' => 'mlt', 'localeFallback' => 'mlt', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['n', 'nl', 'nl-be']);
        $expected = [
            'n' => ['language' => 'Dutch (Standard)', 'locale' => 'dut', 'localeFallback' => 'dut', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'nl' => ['language' => 'Dutch (Standard)', 'locale' => 'dut', 'localeFallback' => 'dut', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'nl-be' => ['language' => 'Dutch (Belgium)', 'locale' => 'nl_be', 'localeFallback' => 'dut', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog('nl');
        $expected = ['language' => 'Dutch (Standard)', 'locale' => 'dut', 'localeFallback' => 'dut', 'charset' => 'utf-8', 'direction' => 'ltr'];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog('nld');
        $expected = ['language' => 'Dutch (Standard)', 'locale' => 'dut', 'localeFallback' => 'dut', 'charset' => 'utf-8', 'direction' => 'ltr'];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog('dut');
        $expected = ['language' => 'Dutch (Standard)', 'locale' => 'dut', 'localeFallback' => 'dut', 'charset' => 'utf-8', 'direction' => 'ltr'];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['nb']);
        $expected = [
            'nb' => ['language' => 'Norwegian Bokmal', 'locale' => 'nob', 'localeFallback' => 'nor', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['nn', 'no']);
        $expected = [
            'nn' => ['language' => 'Norwegian Nynorsk', 'locale' => 'nno', 'localeFallback' => 'nor', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'no' => ['language' => 'Norwegian', 'locale' => 'nor', 'localeFallback' => 'nor', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['p', 'pl']);
        $expected = [
            'p' => ['language' => 'Polish', 'locale' => 'pol', 'localeFallback' => 'pol', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'pl' => ['language' => 'Polish', 'locale' => 'pol', 'localeFallback' => 'pol', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['pt', 'pt-br']);
        $expected = [
            'pt' => ['language' => 'Portuguese (Portugal)', 'locale' => 'por', 'localeFallback' => 'por', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'pt-br' => ['language' => 'Portuguese (Brazil)', 'locale' => 'pt_br', 'localeFallback' => 'por', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['rm']);
        $expected = [
            'rm' => ['language' => 'Rhaeto-Romanic', 'locale' => 'roh', 'localeFallback' => 'roh', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['ro', 'ro-mo']);
        $expected = [
            'ro' => ['language' => 'Romanian', 'locale' => 'rum', 'localeFallback' => 'rum', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'ro-mo' => ['language' => 'Romanian (Moldavia)', 'locale' => 'ro_mo', 'localeFallback' => 'rum', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['sb']);
        $expected = [
            'sb' => ['language' => 'Sorbian', 'locale' => 'wen', 'localeFallback' => 'wen', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['sk']);
        $expected = [
            'sk' => ['language' => 'Slovak', 'locale' => 'slo', 'localeFallback' => 'slo', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['sl']);
        $expected = [
            'sl' => ['language' => 'Slovenian', 'locale' => 'slv', 'localeFallback' => 'slv', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['sq']);
        $expected = [
            'sq' => ['language' => 'Albanian', 'locale' => 'alb', 'localeFallback' => 'alb', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['sr']);
        $expected = [
            'sr' => ['language' => 'Serbian', 'locale' => 'scc', 'localeFallback' => 'scc', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['sv', 'sv-fi']);
        $expected = [
            'sv' => ['language' => 'Swedish', 'locale' => 'swe', 'localeFallback' => 'swe', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'sv-fi' => ['language' => 'Swedish (Finland)', 'locale' => 'sv_fi', 'localeFallback' => 'swe', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['sx']);
        $expected = [
            'sx' => ['language' => 'Sutu', 'locale' => 'sx', 'localeFallback' => 'sx', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['sz']);
        $expected = [
            'sz' => ['language' => 'Sami (Lappish)', 'locale' => 'smi', 'localeFallback' => 'smi', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['th']);
        $expected = [
            'th' => ['language' => 'Thai', 'locale' => 'tha', 'localeFallback' => 'tha', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['tn']);
        $expected = [
            'tn' => ['language' => 'Tswana', 'locale' => 'tsn', 'localeFallback' => 'tsn', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['tr']);
        $expected = [
            'tr' => ['language' => 'Turkish', 'locale' => 'tur', 'localeFallback' => 'tur', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['ts']);
        $expected = [
            'ts' => ['language' => 'Tsonga', 'locale' => 'tso', 'localeFallback' => 'tso', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['uk']);
        $expected = [
            'uk' => ['language' => 'Ukrainian', 'locale' => 'ukr', 'localeFallback' => 'ukr', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['ur']);
        $expected = [
            'ur' => ['language' => 'Urdu', 'locale' => 'urd', 'localeFallback' => 'urd', 'charset' => 'utf-8', 'direction' => 'rtl'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['ve']);
        $expected = [
            've' => ['language' => 'Venda', 'locale' => 'ven', 'localeFallback' => 'ven', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['vi']);
        $expected = [
            'vi' => ['language' => 'Vietnamese', 'locale' => 'vie', 'localeFallback' => 'vie', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['cy']);
        $expected = [
            'cy' => ['language' => 'Welsh', 'locale' => 'cym', 'localeFallback' => 'cym', 'charset' => 'utf-8',
'direction' => 'ltr', ],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['xh']);
        $expected = [
            'xh' => ['language' => 'Xhosa', 'locale' => 'xho', 'localeFallback' => 'xho', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['yi']);
        $expected = [
            'yi' => ['language' => 'Yiddish', 'locale' => 'yid', 'localeFallback' => 'yid', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['zh', 'zh-cn', 'zh-hk', 'zh-sg', 'zh-tw']);
        $expected = [
            'zh' => ['language' => 'Chinese', 'locale' => 'chi', 'localeFallback' => 'chi', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'zh-cn' => ['language' => 'Chinese (PRC)', 'locale' => 'zh_cn', 'localeFallback' => 'chi', 'charset' => 'GB2312', 'direction' => 'ltr'],
            'zh-hk' => ['language' => 'Chinese (Hong Kong)', 'locale' => 'zh_hk', 'localeFallback' => 'chi', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'zh-sg' => ['language' => 'Chinese (Singapore)', 'locale' => 'zh_sg', 'localeFallback' => 'chi', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'zh-tw' => ['language' => 'Chinese (Taiwan)', 'locale' => 'zh_tw', 'localeFallback' => 'chi', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['zu']);
        $expected = [
            'zu' => ['language' => 'Zulu', 'locale' => 'zul', 'localeFallback' => 'zul', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['en-nz', 'es-do', 'sz', 'ar-lb', 'zh-hk', 'pt-br']);
        $expected = [
            'en-nz' => ['language' => 'English (New Zealand)', 'locale' => 'en_nz', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'es-do' => ['language' => 'Spanish (Dominican Republic)', 'locale' => 'es_do', 'localeFallback' => 'spa', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'sz' => ['language' => 'Sami (Lappish)', 'locale' => 'smi', 'localeFallback' => 'smi', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'ar-lb' => ['language' => 'Arabic (Lebanon)', 'locale' => 'ar_lb', 'localeFallback' => 'ara', 'charset' => 'utf-8', 'direction' => 'rtl'],
            'zh-hk' => ['language' => 'Chinese (Hong Kong)', 'locale' => 'zh_hk', 'localeFallback' => 'chi', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'pt-br' => ['language' => 'Portuguese (Brazil)', 'locale' => 'pt_br', 'localeFallback' => 'por', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);

        $result = $l10n->catalog(['eng', 'deu', 'zho', 'rum', 'zul', 'yid']);
        $expected = [
            'eng' => ['language' => 'English', 'locale' => 'eng', 'localeFallback' => 'eng', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'deu' => ['language' => 'German (Standard)', 'locale' => 'deu', 'localeFallback' => 'deu', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'zho' => ['language' => 'Chinese', 'locale' => 'chi', 'localeFallback' => 'chi', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'rum' => ['language' => 'Romanian', 'locale' => 'rum', 'localeFallback' => 'rum', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'zul' => ['language' => 'Zulu', 'locale' => 'zul', 'localeFallback' => 'zul', 'charset' => 'utf-8', 'direction' => 'ltr'],
            'yid' => ['language' => 'Yiddish', 'locale' => 'yid', 'localeFallback' => 'yid', 'charset' => 'utf-8', 'direction' => 'ltr'],
        ];
        $this->assertEqual($result, $expected);
    }
}
