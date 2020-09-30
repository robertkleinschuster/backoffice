<?php
namespace Backoffice\Database\Updater;


use Backoffice\Authentication\Bean\UserBean;
use Laminas\Db\Sql\Sql;

class DataUpdater extends AbstractUpdater
{
    public function updateDataUserState()
    {
        $result = [];

        $sql = new Sql($this->adapter);
        $insert = $sql->insert('UserState');

        $insert->columns(['UserState_Code', 'UserState_Active']);
        $insert->values([UserBean::STATE_ACTIVE, true]);
        $result[] = $this->query($insert);

        return $result;
    }
}
