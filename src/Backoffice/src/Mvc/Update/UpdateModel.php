<?php

namespace Pars\Backoffice\Mvc\Update;

use Pars\Base\Database\Updater\DataUpdater;
use Pars\Base\Database\Updater\SchemaUpdater;
use Pars\Backoffice\Mvc\Base\BaseModel;
use Pars\Mvc\Parameter\IdParameter;
use Pars\Mvc\Parameter\SubmitParameter;

class UpdateModel extends BaseModel
{

    public const OPTION_SCHEMA_ALLOWED = 'schema_allowed';
    public const OPTION_DATA_ALLOWED = 'data_allowed';


    public function getSchemaUpdater()
    {
        return new SchemaUpdater($this->getDbAdpater());
    }

    public function getDataUpdater()
    {
        return new DataUpdater($this->getDbAdpater());
    }

    /**
     * @param SubmitParameter $submitParameter
     * @param IdParameter $idParameter
     * @param array $attribute_List
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    public function submit(SubmitParameter $submitParameter, IdParameter $idParameter, array $attribute_List)
    {
        switch ($submitParameter->getMode()) {
            case 'schema':
                if ($this->hasOption(self::OPTION_SCHEMA_ALLOWED)) {
                    $schemaUpdater = new SchemaUpdater($this->getDbAdpater());
                    $schemaUpdater->execute($attribute_List);
                    $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                } else {
                    $this->handlePermissionDenied();
                }
                break;
            case 'data':
                if ($this->hasOption(self::OPTION_DATA_ALLOWED)) {
                    $schemaUpdater = new DataUpdater($this->getDbAdpater());
                    $schemaUpdater->execute($attribute_List);
                    $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                } else {
                    $this->handlePermissionDenied();
                }
                break;
        }
    }
}
