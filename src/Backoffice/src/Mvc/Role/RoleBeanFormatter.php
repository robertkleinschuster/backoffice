<?php


namespace Backoffice\Mvc\Role;


use Backoffice\Mvc\Base\BackofficeBeanFormatter;

class RoleBeanFormatter extends BackofficeBeanFormatter
{
    protected function formatValueByName(string $name, $value, $originalValue)
    {
        $value = parent::formatValueByName($name, $value, $originalValue);
        switch ($name) {
            case 'UserRole_Active': $value = $this->formatUserRole_Active($value);
                break;
        }
        return $value;
    }

    protected function formatUserRole_Active($value) {
        return $value ? 'Aktiv' : 'Inaktiv';
    }

}
