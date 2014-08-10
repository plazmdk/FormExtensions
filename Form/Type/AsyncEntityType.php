<?php
/**
 * Created by PhpStorm.
 * User: plazm
 * Date: 7/6/14
 * Time: 12:17 PM
 */

namespace TJB\FormExtensionsBundle\Form\Type;


use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Bridge\Doctrine\Form\EventListener\MergeDoctrineCollectionListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\RuntimeException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class AsyncEntityType extends AbstractType {

    private $registry;

    public function __construct(ManagerRegistry $registry) {
        $this->registry = $registry;

    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'class'      => null,
            'valueField' => 'id',
            'titleField' => 'title',
            'em'         => null
        ));

        $registry = $this->registry;

        $emNormalizer = function (Options $options, $em) use ($registry) {
            /* @var ManagerRegistry $registry */
            if (null !== $em) {
                return $registry->getManager($em);
            }

            $em = $registry->getManagerForClass($options['class']);

            if (null === $em) {
                throw new RuntimeException(sprintf(
                    'Class "%s" seems not to be a managed Doctrine entity. ' .
                    'Did you forget to map it?',
                    $options['class']
                ));
            }

            return $em;
        };

        $resolver->setNormalizers(array(
            'em' => $emNormalizer,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->addEventSubscriber(new MergeDoctrineCollectionListener())
            ->addViewTransformer(new CollectionToArrayTransformer(), true)
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            "class" => $options['class'],
            "valueField" => $options['valueField'],
            "titleField" => $options['titleField'],
        ));



        $view->vars['full_name'] = $view->vars['full_name'].'[]';
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'async_entity';
    }
}