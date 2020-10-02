<?php


namespace Backoffice\Mvc\Model;

use Backoffice\Mvc\Parser\BackofficeBeanParser;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Mezzio\Mvc\Controller\ControllerRequest;
use Mezzio\Mvc\Helper\ValidationHelperAwareInterface;
use Mezzio\Mvc\Model\AbstractModel;
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
     * @param array $ids
     */
    public function find(array $ids)
    {
        if ($this->hasFinder()) {
            $this->getFinder()->getLoader()->initByIdMap($ids);
            $this->getFinder()->find();
        }
    }

    /**
     * @param array $ids
     */
    public function create(array $ids)
    {

    }
    /**
     * @param array $attributes
     */
    public function submit(array $attributes)
    {
        if ($attributes['submit'] == 'save') {
            $this->save($attributes);
        }
        if ($attributes['submit'] == 'delete') {
            $this->delete($attributes);
        }
    }

    protected function delete(array $attributes)
    {
        if ($this->hasFinder() && $this->hasProcessor()) {

            if ($this->getFinder()->count()) {
                $bean = $this->getFinder()->getBean();

                $beanList = $this->getFinder()->getFactory()->createBeanList();
                $beanList->addBean($bean);

                $this->getProcessor()->setBeanList($beanList);
                $this->getProcessor()->delete();

            }
            $this->getValidationHelper()->addErrorFieldMap($this->getProcessor()->getValidationHelper()->getErrorFieldMap());
        }
    }

    protected function save(array $attributes)
    {
        if ($this->hasFinder() && $this->hasProcessor()) {

            if (isset($attributes[ControllerRequest::ATTRIBUTE_CREATE])) {
                $bean = $this->getFinder()->getFactory()->createBean();
            } else {
                $bean = $this->getFinder()->getBean();
            }

            $parser = new BackofficeBeanParser();
            $bean = $parser->parse($attributes, $bean)->toBean();

            $beanList = $this->getFinder()->getFactory()->createBeanList();
            $beanList->addBean($bean);

            $this->getProcessor()->setBeanList($beanList);
            $this->getProcessor()->save();

            if ($this->getProcessor() instanceof ValidationHelperAwareInterface) {
                $this->getValidationHelper()->addErrorFieldMap($this->getProcessor()->getValidationHelper()->getErrorFieldMap());
            }
        }
    }

}
