<?php

namespace Zikula\EZCommentsModule;

use Zikula\ExtensionsModule\Installer\AbstractExtensionInstaller;
use Zikula\EZCommentsModule\Entity\EZCommentsEntity;

class EZCommentsModuleInstaller extends AbstractExtensionInstaller
{
    private $entities = array(
        EZCommentsEntity::class,
    );

    public function install(): bool {
        //Create the tables of the module.
        try {
            $this->schemaTool->create($this->entities);
        } catch (\Exception $e) {
            return false;
        }
        $this->setVar('allowanon', false);

        return true;
    }

    public function upgrade($oldversion): bool  {
        /*switch ($oldversion)
        {
            case '1.2':
                $this->setVar('enablepager', false);
                $this->setVar('commentsperpage', '25');

            case '1.3':
                $this->setVar('blacklinkcount', 5);
                $this->setVar('akismet', false);

            case '1.4':
                $this->setVar('anonusersrequirename', false);
                $this->setVar('akismetstatus', 1);

            case '1.5':
                if (!DBUtil::changeTable('EZComments')) {
                    return '1.5';
                }
                $this->setVar('template', 'Standard');
                $this->setVar('modifyowntime', 6);
                $this->setVar('useaccountpage', '1');

            case '1.6':
            case '1.61':
            case '1.62':
                $this->setVar('migrated', array('dummy' => true));
                $this->setVar('css', 'style.css');

            case '2.0.0':
            case '2.0.1':
                // register hooks

                HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
                HookUtil::registerProviderBundles($this->version->getHookProviderBundles());

                // register the module delete hook
                EventUtil::registerPersistentModuleHandler('EZComments', 'installer.module.uninstalled', array('EZComments_EventHandlers', 'moduleDelete'));
                EventUtil::registerPersistentModuleHandler('EZComments', 'installer.subscriberarea.uninstalled', array('EZComments_EventHandlers', 'hookAreaDelete'));

                // drop table prefix
                $prefix = $this->serviceManager['prefix'];
                $connection = Doctrine_Manager::getInstance()->getConnection('default');
                $sql = 'RENAME TABLE ' . $prefix . '_ezcomments' . " TO ezcomments";
                $stmt = $connection->prepare($sql);
                try {
                    $stmt->execute();
                } catch (Exception $e) {
                }

                if (!DBUtil::changeTable('EZComments')) {
                    return LogUtil::registerError($this->__('Error updating the table.'));
                }

            case '3.0.0':
            case '3.0.1':
                // future upgrade routines
                break;
        }*/

        return true;
    }

    public function uninstall(): bool  {

        //drop the tables
        try {
            $this->schemaTool->drop($this->entities);
        } catch(\Exception $e){
            $this->addFlash('error', $e->getMessage());
            return false;
        }
        // Deletion successful
        return true;
    }
}

