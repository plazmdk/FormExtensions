<?php
/**
 * Created by PhpStorm.
 * User: plazm
 * Date: 9/13/14
 * Time: 11:58 AM
 */

namespace TJB\FormExtensionsBundle\Interfaces;


interface SearchableTree {
    public function getId();
    public function getLft();
    public function getRgt();
    /**
     * @return SearchableTree
     */
    public function getParent();

    public function getParentPath();
}