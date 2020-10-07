<?php


namespace Backoffice\Mvc\Update;


use Base\Database\Updater\DataUpdater;
use Base\Database\Updater\SchemaUpdater;
use Backoffice\Mvc\Base\BaseModel;
use Mvc\Controller\ControllerRequest;

class UpdateModel extends BaseModel
{

    public const OPTION_SCHEMA_ALLOWED = 'schema_allowed';
    public const OPTION_DATA_ALLOWED = 'data_allowed';

    public function init()
    {

    }



    public function getSchemaUpdater()
    {
        return new SchemaUpdater($this->getDbAdpater());
    }

    public function getDataUpdater()
    {
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
                if ($this->hasOption(self::OPTION_SCHEMA_ALLOWED)) {
                    $schemaUpdater = new SchemaUpdater($this->getDbAdpater());
                    $schemaUpdater->execute($request->getAttributes());
                    $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                } else {
                    $this->handlePermissionDenied();
                }
                break;
            case 'data':
                if ($this->hasOption(self::OPTION_DATA_ALLOWED)) {
                    $schemaUpdater = new DataUpdater($this->getDbAdpater());
                    $schemaUpdater->execute($request->getAttributes());
                    $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                } else {
                    $this->handlePermissionDenied();
                }
                break;
        }
    }
}
