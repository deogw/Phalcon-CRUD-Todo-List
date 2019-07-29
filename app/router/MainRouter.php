<?php
/**
 * Created by PhpStorm.
 * User: gamalan
 * Date: 07/10/16
 * Time: 10:24
 */

namespace Application\Router;

use Phalcon\Mvc\Router\Group;

class MainRouter extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'namespaces' => 'Application\\Controllers',
            'controller' => 'todolist'
        ]);

        $this->add(
            '/',
            [
                'action' => 'index'
            ]
        );

        $this->add(
            "/todo",
            [
                "action" => "showtodo"
            ]
        );

        $this->addPost(
            "/todo/add",
            [
                "action" => "save"
            ]
        );

        $this->addPut(
            "/todo/update",
            [
                "action" => "update"
            ]
        );

        $this->addDelete(
            "/todo/delete",
            [
                "action" => "delete"
            ]
        );
    }
}