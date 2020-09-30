<?php


namespace Backoffice\Mvc\Model;


use Backoffice\Database\Updater\DataUpdater;
use Backoffice\Database\Updater\SchemaUpdater;

class UpdateModel extends BaseModel
{

    public function init()
    {
        // TODO: Implement init() method.
    }

    public function getSchemaUpdaterPreview() {
        $schemaUpdater = new SchemaUpdater($this->getDbAdpater());
        return $schemaUpdater->getPreviewMap();
    }

    public function getDataUpdaterPreview() {
        $schemaUpdater = new DataUpdater($this->getDbAdpater());
        return $schemaUpdater->getPreviewMap();
    }

    public function submit(array $attributes)
    {
        if ($attributes['submit'] == 'schema') {
            $schemaUpdater = new SchemaUpdater($this->getDbAdpater());
            $schemaUpdater->execute($attributes);
            $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
        }

        if ($attributes['submit'] == 'data') {
            $schemaUpdater = new DataUpdater($this->getDbAdpater());
            $schemaUpdater->execute($attributes);
            $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
        }
    }


}
