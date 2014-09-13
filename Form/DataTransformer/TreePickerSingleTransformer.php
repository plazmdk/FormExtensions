<?php

namespace TJB\FormExtensionsBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class TreePickerSingleTransformer implements DataTransformerInterface
{

    private $manager;
    private $options;

    public function __construct(ObjectManager $manager, $options)
    {
        $this->manager = $manager;
        $this->options = $options;
    }

    public function transform($object)
    {
        if ($object === null) {
            return null;
        }

        $idFields = $this->manager->getClassMetadata($this->options['class'])->getIdentifierFieldNames();
        $idField = reset($idFields);

        $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()->getPropertyAccessor();

        return array(
            "id" => $propertyAccessor->getValue($object, $idField),
            "title" => $propertyAccessor->getValue($object, $this->options['property'])
        );
    }

    public function reverseTransform($value)
    {
        return $this->manager->find($this->options['class'], $value['id']);
    }
}
