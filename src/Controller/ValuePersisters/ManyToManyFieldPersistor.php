<?php


namespace BasicTablePackage\Controller\ValuePersisters;


use BasicTablePackage\Entity\ValueSupplier;
use Doctrine\Common\Collections\ArrayCollection;
use function BasicTablePackage\Lib\collect as collect;

class ManyToManyFieldPersistor implements FieldPersistor
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var ValueSupplier
     */
    private $valueSupplier;

    /**
     * TextFieldPersistor constructor.
     * @param string $name
     * @param ValueSupplier $valueSupplier
     */
    public function __construct(string $name, ValueSupplier $valueSupplier)
    {
        $this->name = $name;
        $this->valueSupplier = $valueSupplier;
    }

    public function persist($valueMap, $toEntity)
    {
        $values = $this->valueSupplier->getValues();
        $postvalues = $valueMap[$this->name];
        if (filter_var($postvalues, FILTER_VALIDATE_INT) !== false || is_string($postvalues)) {
            $postvalues = [$postvalues];
        }

        $newCollection = collect($postvalues)
            ->map(function ($postValue) use ($values) {
                return $values[$postValue];
            })
            ->reduce(function (ArrayCollection $collection, $item) {
                $collection->add($item);
                return $collection;
            },
                new ArrayCollection());

        $toEntity->{$this->name} = $newCollection;
    }

}