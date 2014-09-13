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

class TreePickerController extends Controller {
    /**
     * @Route("__treepicker_gettree", name="tjb_treepicker_gettree", options={"expose"=true})
     * @Secure("ROLE_ADMIN")
     */
    public function getTreeAction(Request $request) {

        return new JsonResponse();
    }
} 