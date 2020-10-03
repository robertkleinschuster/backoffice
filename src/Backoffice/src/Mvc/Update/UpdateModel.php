<?php


namespace Backoffice\Mvc\Update;


use Backoffice\Database\Updater\DataUpdater;
use Backoffice\Database\Updater\SchemaUpdater;
use Backoffice\Mvc\Base\BaseModel;
use Mezzio\Mvc\Controller\ControllerRequest;

class UpdateModel extends BaseModel
{

    public function init()
    {

    }

    public function getSchemaUpdater() {
        return new SchemaUpdater($this->getDbAdpater());
    }

    public function getDataUpdater() {
        return new DataUpdater($this->getDbAdpater());
    }

    /**
     * @param ControllerRequest $request
     * @throws \NiceshopsDev\NiceCore\Exception
     */
    public function submit(ControllerRequest $request)
    {
        switch ($request->getSubmit()) {
            case 'schema':
                $schemaUpdater = new SchemaUpdater($this->getDbAdpater());
                $schemaUpdater->execute($request->getAttributes());
                $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                break;
            case 'data':
                $schemaUpdater = new DataUpdater($this->getDbAdpater());
                $schemaUpdater->execute($request->getAttributes());
                $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                break;
        }
    }
}
