<?php
/**
 * Created by PhpStorm.
 * User: plazm
 * Date: 9/11/14
 * Time: 9:50 PM
 */

namespace TJB\FormExtensionsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use TJB\FormExtensionsBundle\Interfaces\SearchableTree;

class TreePickerController extends Controller {
    /**
     * @Route("__treepicker_gettree", name="tjb_treepicker_gettree", options={"expose"=true})
     */
    public function getTreeAction(Request $request) {
        $class = $request->query->get('class');
        $title = $request->query->get('title');
        $repository = $this->getDoctrine()->getRepository($class);

        $parent = null;

        $id = $request->query->get("id");
        if ($id != "#") {
            $parent = $repository->find($id);
        }

        $result = array();
        /** @var SearchableTree[] $objects */
        $objects = $repository->findBy(array("parent" => $parent));

        $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()->getPropertyAccessor();

        foreach($objects as $object) {
            $result[] = array(
                "id" => $object->getId(),
                "text" => $propertyAccessor->getValue($object, $title),
                "children" => ($object->getLft() + 1 != $object->getRgt()),
                "path" => $object->getParentPath()
            );
        }

        return new JsonResponse($result);
    }

} 