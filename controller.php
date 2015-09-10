<?php
namespace Concrete\Package\ThemeEditor;

defined('C5_EXECUTE') or die('Access Denied.');

use Asset;
use AssetList;
use BlockType;
use Package;
use Route;

class Controller extends Package {

    protected $pkgHandle = 'theme_editor';
    protected $appVersionRequired = '5.7.3';
    protected $pkgVersion = '0.2';
    protected $btDefaultSet = '';
    protected $pkg;
    
    public function getPackageDescription() {
        return t("Theme Editor");
    }
    
    public function getPackageName() {
        return t("Theme Editor");
    }   

    public function on_start() { 

        $this->registerRoutes();
        $this->registerAssets();
    }

    public function registerAssets() {
        $al = AssetList::getInstance(); 
        $al->register( 'javascript', 'theme_editor-edit', 'blocks/theme_editor/javascript/build/block-edit.js', array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => true, 'combine' => true), $this );       
        $al->register( 'javascript', 'less', 'blocks/theme_editor/javascript/build/less.js', array('version' => '2.5', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), $this );       
        $al->register( 'css', 'theme_editor-view', 'blocks/theme_editor/stylesheet/block-view.css', array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => true, 'combine' => true), $this );       
        $al->register( 'css', 'hint', 'blocks/theme_editor/stylesheet/hint.css', array('version' => '1.3.4', 'position' => Asset::ASSET_POSITION_HEADER, 'minify' => true, 'combine' => true), $this ); 

    }
    public function registerRoutes() {
        Route::register('/theme_editor/tools/getcolors','\Concrete\Package\ThemeEditor\Block\ThemeEditor\Controller::getColorsVariables');
        Route::register('/theme_editor/tools/getnewcss','\Concrete\Package\ThemeEditor\Block\ThemeEditor\Controller::getNewCss');
    }  

    public function install() {
    
    // Get the package object
        $this->pkg = parent::install();

    // Install Block
        BlockType::installBlockType('theme_editor', $this->pkg);        

    // Installing                   
        $this->installOrUpgrade();

    }


    private function installOrUpgrade() {

    }    

    public function upgrade () {
        $this->installOrUpgrade();
        parent::upgrade();        
    }



}