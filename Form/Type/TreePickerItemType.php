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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class TreePickerItemType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden', array("attr" => array("class" => "treepicker-item-id")));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $parentPath = array();
        if (isset($view->vars['value']['parentPath'])) {
            $parentPath = $view->vars['value']['parentPath'];
        }
        $view->vars['parentPath'] = $parentPath;
        $view->vars['title'] = $view->vars['value']['title'];
    }

    public function getName() {
        return "tjb_treepicker_item";
    }
} 