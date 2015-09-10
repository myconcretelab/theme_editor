<?php
namespace Concrete\Package\ThemeEditor\Block\ThemeEditor;

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Loader;
use Asset;
use AssetList;
use \Concrete\Core\Http\ResponseAssetGroup;
use StdClass;
use Environment;
use Core;
use Less_Parser;
use Less_Cache;
use Less_Tree_Rule;
use Concrete\Package\ThemeEditor\Controller\Tools\ThemeEditorTools;
use PageTheme;
use Page;
use Config;

// use Concrete\Package\ThemeSuperMint\Models\ThemeSuperMintOptions;


class Controller extends BlockController
{
    protected $btTable = 'btThemeEditor';
    protected $btInterfaceWidth = "600";
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceHeight = "465";
    protected $btCacheBlockRecord = false;
    protected $btExportFileColumns = array('fID');
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputOnPost = false;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btSupportsInlineEdit = false;
    protected $btSupportsInlineAdd = false;
    protected $btDefaultSet = '';

    // var $themeHandle = 'vedana';
    // var $packageHandle = 'theme_vedana';

    protected $optionTabs = array();

    public function getBlockTypeDescription()
    {
        return t("");
    }

    public function getBlockTypeName()
    {
        return t("");
    }

    public function on_start (){
        $c = $_REQUEST['cID'] ? Page::getByID($_REQUEST['cID']) : Page::getCurrentPage();
        $this->currentPage = $c;
        $this->cID = $c->getCollectionID();
        $this->pt = $c->getCollectionThemeObject();
        $this->packageHandle = $this->pt->getPackageHandle();
        $this->themeHandle = $this->pt->getThemeHandle();
        $this->bannedVars = array('slider','footer');

    }

    public function add() {
        $this->setAssetEdit();
        $this->set('options', $this->getOptionsJson());        
    }

    public function edit()
    {
        $this->setAssetEdit();
        $this->set('options', $this->getOptionsJson());
    }
    public function setAssetEdit () {

        $this->requireAsset('javascript', 'theme_editor-edit');

    }
    function getFilesIds () { return explode(',', $this->fIDs); }
    
    function getOptionsJson ()  { 
        if ($this->isValueEmpty()) :
            $options = new StdClass();
        else:
            $options = json_decode($this->options);
        endif;
        return $options;
    }


    public function registerViewAssets()
    {
        $this->requireAsset('css','theme_editor-view');        
        $this->requireAsset('css','hint');
        // $this->requireAsset('javascript', 'less');
        $this->requireAsset('core/colorpicker'); 
        $this->requireAsset('javascript', 'underscore');       
    }

    public function view() {

        $c = Page::getCurrentPage();
        $customStyleObject = $c->getCustomStyleObject();
        $activePresetHandle = is_object($customStyleObject) ? $customStyleObject->getPresetHandle() : 'defaults';

        $this->set('activePresetHandle', $activePresetHandle);
        $this->set('themePresets', $this->getThemeStylePresets());
        $this->set('stylesheetUrl', $this->getStyleSheetUrl());
        $this->set('ColorsVariables', $this->getColorsVariables($activePresetHandle));
        $this->set('themeHandle', $this->themeHandle);
        // die();
    }


    public function composer() {
        $this->setAssetEdit();
    }

    public function isValueEmpty() {
        if ($this->bID)
            return false;
        else 
            return true;
    }


    public function save($data)
    {
        $options = $data;
        $data['options'] = json_encode($options);
        
        parent::save($data);
    }   

    public function getStyleSheetUrl () {

        $env = Environment::get();
        $stylesheet = 'main.less';
        $place = DIRNAME_THEMES . '/' . $this->themeHandle . '/' . DIRNAME_CSS . '/' . $stylesheet;

        $r = $env->getRecord($place, $this->packageHandle); // Return the URL
        return $r->url . $file;
    }    
    function getStyleSheetPath () {
        $stylesheet = 'main.less';
        $path = DIR_PACKAGES . '/' . $this->packageHandle . '/' . DIRNAME_THEMES . '/' . $this->themeHandle . '/' . DIRNAME_CSS . '/' . $stylesheet;
        return $path;
    } 
    function getColorsVariables ($presetHandle = 'defaults') {

        if ($_POST['presetHandle']) $presetHandle = $_POST['presetHandle'];
        
        $themePreset = $this->pt->getThemeCustomizablePreset($presetHandle);
        if (is_object($themePreset)):
            $vl = $themePreset->getStyleValueList();
        else :
            $customStyleObject = $this->currentPage->getCustomStyleObject();
            $vl = $customStyleObject->getValueList();
        endif;        

        $values =array();

        $return = new stdClass();

        foreach ($vl->getValues() as $key => $value) :
            $valueArray = $value->toLessVariablesArray();
            $v = array_values($valueArray)[0];
            $k = array_keys($valueArray)[0];
            
            if(strpos(get_class($value),'Color')) :
                // Now we exclude all value that are like rgb( , , 5)
                // this come when a color is calculated by less on a other color
                if(strlen($v) > 11) :
                    foreach($this->bannedVars as $ban) {
                        $place = strpos($k, $ban);
                        if ($place !== false) {
                            continue 2;       
                        }
                    }                    
                    $o = new stdClass();
                    $o->variable = '@' . $k;
                    $o->val = $v;
                    $values[] = $o;
                    $less['@' . $k] = $v;
                endif;
            elseif($k):
                $less['@' . $k] = $v;
            endif;
        endforeach;
        if (count($values)) :
            $return->values = $values;
            $return->less = $less;
            if ($_POST['presetHandle']) :
                Loader::helper('ajax')->sendResult($return);
                exit();
            else :
                return $return;
            endif;
        endif;
    
    }  
    function getThemeStylePresets () {
        
        return $this->pt->getThemeCustomizableStylePresets();

    }

    function getNewCss () {

        $options = array('cache_dir'=>Config::get('concrete.cache.directory') . '/css');
        $l = new Less_Parser($options);
        $parser = $l->parseFile($this->getStyleSheetPath(),  DIRNAME_PACKAGES . '/' . $this->packageHandle . '/' . DIRNAME_THEMES . '/' . $this->themeHandle . '/' . DIRNAME_CSS . '/'  );
        $parser->ModifyVars($this->post());
        $css = $parser->getCss();
        print($css);
        exit();
    }


}