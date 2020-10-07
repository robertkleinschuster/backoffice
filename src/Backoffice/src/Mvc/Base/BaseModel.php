<?php


namespace Backoffice\Mvc\Base;

use Base\Authentication\Bean\UserBean;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Mvc\Helper\ValidationHelperAwareInterface;
use Mvc\Model\AbstractModel;
use NiceshopsDev\Bean\BeanFinder\BeanFinderInterface;
use NiceshopsDev\Bean\BeanProcessor\BeanProcessorInterface;

abstract class BaseModel extends AbstractModel implements AdapterAwareInterface
{
    use AdapterAwareTrait;

    /**
     * @var BeanFinderInterface
     */
    private $finder;

    /**
     * @var BeanProcessorInterface
     */
    private $processor;

    /**
     * @var UserBean
     */
    private $user;

    /**
     * @return Adapter
     */
    public function getDbAdpater(): Adapter
    {
        return $this->adapter;
    }

    /**
     * @return BeanFinderInterface
     */
    public function getFinder(): BeanFinderInterface
    {
        return $this->finder;
    }

    /**
     * @param BeanFinderInterface $finder
     *
     * @return $this
     */
    public function setFinder(BeanFinderInterface $finder): self
    {
        $this->finder = $finder;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasFinder(): bool
    {
        return $this->finder !== null;
    }

    /**
     * @return BeanProcessorInterface
     */
    public function getProcessor(): BeanProcessorInterface
    {
        return $this->processor;
    }

    /**
     * @param BeanProcessorInterface $processor
     *
     * @return $this
     */
    public function setProcessor(BeanProcessorInterface $processor): self
    {
        $this->processor = $processor;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasProcessor(): bool
    {
        return $this->processor !== null;
    }

    /**
     * @return UserBean
     */
    public function getUser(): UserBean
    {
        return $this->user;
    }

    /**
     * @param UserBean $user
     *
     * @return $this
     */
    public function setUser(UserBean $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasUser(): bool
    {
        return $this->user !== null;
    }


    /**
     * @param array $viewIdMap
     */
    public function find(array $viewIdMap)
    {
        if ($this->hasFinder()) {
            if (!$this->getFinder()->hasLimit()) {
                $this->getFinder()->limit(50, 0);
            }
            $this->getFinder()->getLoader()->initByIdMap($viewIdMap);
            $this->getFinder()->find();
        }
    }

    /**
     * @param int $limit
     * @param int $page
     */
    public function setLimit(int $limit, int $page)
    {
        if ($this->hasFinder()) {
            if ($limit > 0 && $page > 0) {
                $this->getFinder()->limit($limit, $limit * ($page - 1));
            }
        }
    }


    /**
     * @param array $viewIdMap
     * @param array $attributes
     * @return mixed|void
     */
    protected function create(array $viewIdMap, array $attributes)
    {
        if ($this->hasFinder() && $this->hasProcessor()) {
            $bean = $this->getFinder()->getFactory()->createBean();
            $parser = new BackofficeBeanParser();
            $bean = $parser->parse(array_replace($attributes, $viewIdMap), $bean)->toBean();
            $beanList = $this->getFinder()->getFactory()->createBeanList();
            $beanList->addBean($bean);
            if ($this->hasUser() && $this->getUser()->hasData('Person_ID')) {
                $this->getProcessor()->getSaver()->setPersonId($this->getUser()->getData('Person_ID'));
            }
            $this->getProcessor()->setBeanList($beanList);
            $this->getProcessor()->save();
            if ($this->getProcessor() instanceof ValidationHelperAwareInterface) {
                $this->getValidationHelper()->addErrorFieldMap($this->getProcessor()->getValidationHelper()->getErrorFieldMap());
            }
        }
    }

    /**
     * @param array $attributes
     * @return mixed|void
     */
    protected function save(array $attributes)
    {
        if ($this->hasFinder() && $this->hasProcessor()) {
            $bean = $this->getFinder()->getBean();
            $parser = new BackofficeBeanParser();
            $bean = $parser->parse($attributes, $bean)->toBean();
            $beanList = $this->getFinder()->getFactory()->createBeanList();
            $beanList->addBean($bean);
            if ($this->hasUser() && $this->getUser()->hasData('Person_ID')) {
                $this->getProcessor()->getSaver()->setPersonId($this->getUser()->getData('Person_ID'));
            }
            $this->getProcessor()->setBeanList($beanList);
            $this->getProcessor()->save();
            if ($this->getProcessor() instanceof ValidationHelperAwareInterface) {
                $this->getValidationHelper()->addErrorFieldMap($this->getProcessor()->getValidationHelper()->getErrorFieldMap());
            }
        }
    }

    /**
     * @param array $viewIdMap
     * @return mixed|void
     */
    protected function delete(array $viewIdMap)
    {
        if ($this->hasFinder() && $this->hasProcessor()) {
            if ($this->getFinder()->count()) {
                $bean = $this->getFinder()->getBean();
                $beanList = $this->getFinder()->getFactory()->createBeanList();
                $beanList->addBean($bean);
                $this->getProcessor()->setBeanList($beanList);
                $this->getProcessor()->delete();
            }
            if ($this->getProcessor() instanceof ValidationHelperAwareInterface) {
                $this->getValidationHelper()->addErrorFieldMap($this->getProcessor()->getValidationHelper()->getErrorFieldMap());
            }
        }
    }


    protected function handlePermissionDenied()
    {
        $this->getValidationHelper()->addError('Permission', 'Sie haben nicht die Berechtigung diesen Eintrag zu bearbeiten.');
    }


}
