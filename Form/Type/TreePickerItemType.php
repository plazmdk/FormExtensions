<?php
/**
 * Created by PhpStorm.
 * User: plazm
 * Date: 9/12/14
 * Time: 10:49 PM
 */

namespace TJB\FormExtensionsBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TreePickerItemType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden', array("attr" => array("class" => "treepicker-item-id")))
            ->add('title', 'text', array('required' => false));
    }

    public function getName() {
        return "tjb_treepicker_item";
    }
} 