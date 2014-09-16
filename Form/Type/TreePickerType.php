<?php
/**
 * Created by PhpStorm.
 * User: plazm
 * Date: 9/10/14
 * Time: 5:13 PM
 */

namespace TJB\FormExtensionsBundle\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\EventListener\MergeDoctrineCollectionListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\RouterInterface;
use TJB\FormExtensionsBundle\Form\DataTransformer\TreePickerMultiTransformer;
use TJB\FormExtensionsBundle\Form\EventListener\TreePickerMultiInputListener;

class TreePickerType extends AbstractType
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var RouterInterface
     */
    private $router;

    function __construct($managerRegistry, $router)
    {
        $this->managerRegistry = $managerRegistry;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['multiple']) {
            $builder->addViewTransformer(new TreePickerMultiTransformer($this->managerRegistry->getManagerForClass($options['class']), $options));
            $builder->addEventSubscriber(new TreePickerMultiInputListener($this->managerRegistry->getManagerForClass($options['class']), $options));
            $builder->setAttribute('prototype', $builder->create("__prototype__",new TreePickerItemType())->getForm());
        } else {
            $builder->addViewTransformer(new TreePickerSingleTransformer($this->managerRegistry->getManagerForClass($options['class']), $options));
        }

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['tree_route'] = $this->router->generate($options['tree_route'],array('class' => $options['class'], 'title' => $options['property']));
        $view->vars['prototype'] = $form->getConfig()->getAttribute('prototype')->createView($view);
        $view->vars['buttontext'] = $options['buttontext'];
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'tree_route' => 'tjb_treepicker_gettree',
            'multiple' => false,
            'property' => 'title',
            'buttontext' => 'treepicker.select'
        ));

        $resolver->setRequired(array(
            'class'
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tjb_treepicker_type';
    }
}