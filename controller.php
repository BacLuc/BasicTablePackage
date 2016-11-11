<?php
namespace Concrete\Package\BasicTablePackage;

defined('C5_EXECUTE') or die(_("Access Denied."));


use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Concrete\Core\Foundation\ClassLoader;
use Concrete\Package\BasicTablePackage\Src\DiscriminatorEntry\DiscriminatorListener;
use Doctrine\ORM\EntityManager;
use Punic\Exception;
use Loader;
use Core;

class Controller extends Package
{

    protected $pkgHandle = 'basic_table_package';
    protected $appVersionRequired = '5.7.4';
    protected $pkgVersion = '0.0.1';



    public function getPackageName()
    {
        return t("BasicTablePackage");
    }

    public function getPackageDescription()
    {
        return t("Package to provide a basic CRUD from DB to GUI");
    }

    public function install()
    {
        $this->currentlyInstalling = true;
       $em = $this->getEntityManager();
        $this->currentlyInstalling = false;
        //begin transaction, so when block install fails, but parent::install was successfully, you don't have to uninstall the package
        $em->getConnection()->beginTransaction();
        try {

            $pkg = parent::install();
            $em = $this->getEntityManager();
            BlockType::installBlockType("basic_table_block_packaged", $pkg);

            $em->getConnection()->commit();
        }catch(Exception $e){
            $em->getConnection()->rollBack();

            throw $e;
        }


    }

    public function uninstall()
    {
        $block = BlockType::getByHandle("basic_table_block_packaged");
        $em = $this->getEntityManager();

        //begin transaction, so when block install fails, but parent::install was successfully, you don't have to uninstall the package
        $em->getConnection()->beginTransaction();
        try{
                if(is_object($block)) {
                    $blockId = $block->getBlockTypeID();
                    $db = Core::make('database');
                    //delete of blocktype not in orm way, because there is no entity BlockType
                    $db->query("DELETE FROM BlockTypes WHERE btID = ?", array($blockId));
                }
                parent::uninstall();
            $em->getConnection()->commit();
        }catch(Exception $e){
            $em->getConnection()->rollBack();

            throw $e;
        }
    }


    /**
     * @param EntityManager $em
     * Because Doctrine itself requires on the topmost entity a discriminatormap with all subentities,
     *  we add here a EventListener when Doctrine Parses the Annotations.
     *  This DiscriminatorListener scans the Annotations of Child Classes of BaseEntity for
     * @DiscriminatorEntry(value="Namespace\Classname") and adds them to the Discriminator Map,
     * So that you don't have to define the Cildren in the topmost parent class.
     */
    public static function addDiscriminatorListenerToEm(EntityManager $em){
        if(!$em->DiscriminatorListenerAttached) {
            $em->getEventManager()->addEventSubscriber(new DiscriminatorListener($em));
            $em->DiscriminatorListenerAttached = true;
        }
    }

    /**
     * @return EntityManager
     * @overrides Package::getEntityManager
     * if the Package is installed, this function calls static::addDiscriminatorListenerToEm on the EntityManager
     * To add support for @DiscriminatorEntry Annotation
     * Only after Installation, because else the Classes to Support this are not found
     */
    public function getEntityManager()
    {
        $em = parent::getEntityManager(); // TODO: Change the autogenerated stub

        if(parent::isPackageInstalled()) {
            static::addDiscriminatorListenerToEm($em);
        }
        return $em;
    }






}

