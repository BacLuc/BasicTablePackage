<?php

namespace Concrete\Package\BaclucC5Crud;

defined('C5_EXECUTE') or exit(_('Access Denied.'));

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Application;
use Concrete\Package\BaclucC5Crud\DiscriminatorEntry\DiscriminatorListener;
use Doctrine\ORM\EntityManager;
use Exception;

class Controller extends Package {
    public const PACKAGE_HANDLE = 'bacluc_c5_crud';
    protected $pkgHandle = self::PACKAGE_HANDLE;
    protected $appVersionRequired = '9.0.1';
    protected $pkgVersion = '0.0.1';

    public static function getEntityManagerStatic() {
        $pkg = Package::getByHandle(self::PACKAGE_HANDLE);

        return $pkg->getEntityManager();
    }

    /**
     * @param EntityManager $em
     *                          Because Doctrine itself requires on the topmost entity a discriminatormap with all subentities,
     *                          we add here a EventListener when Doctrine Parses the Annotations.
     *                          This DiscriminatorListener scans the Annotations of Child Classes of BaseEntity for
     * @DiscriminatorEntry(value="Namespace\Classname") and adds them to the Discriminator Map,
     * So that you don't have to define the Cildren in the topmost parent class.
     */
    public static function addDiscriminatorListenerToEm(EntityManager $em) {
        if (!$em->DiscriminatorListenerAttached) {
            $em->getEventManager()->addEventSubscriber(new DiscriminatorListener($em));
            $em->DiscriminatorListenerAttached = true;
        }
    }

    public function getPackageName() {
        return t('BaclucCrudPackage');
    }

    public function getPackageDescription() {
        return t('Package to provide a basic CRUD from DB to GUI');
    }

    public function getPackageAutoloaderRegistries() {
        return ['src' => 'BaclucC5Crud'];
    }

    public function install() {
        require_once $this->getPackagePath().'/vendor/autoload.php';
        $pkg = parent::install();
        BlockType::installBlockType('bacluc_crud', $pkg);
    }

    public function uninstall() {
        $block = BlockType::getByHandle('bacluc_crud');

        $app = Application::getFacadeApplication();
        /** @var Connection $db */
        $db = $app->make(Connection::class);
        $db->beginTransaction();

        try {
            if (is_object($block)) {
                $blockId = $block->getBlockTypeID();
                //delete of blocktype not in orm way, because there is no entity BlockType
                $db->query('DELETE FROM BlockTypes WHERE btID = ?', [$blockId]);
            }
            parent::uninstall();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();

            throw $e;
        }
    }

    public function on_start() {
        require_once $this->getPackagePath().'/vendor/autoload.php';
    }
}
