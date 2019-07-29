<?php
namespace Application\Models;

use Phalcon\Mvc\Model;

class Todo extends Model
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $content;


    /**
     * Method to set the value of field id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }


    public function setCretedAt($time)
    {
        $this->created_at = $time;
    }

    /**
     * Method to set the value of field typesId
     *
     * @param integer $typesId
     */
    public function setTypesId($typesId)
    {
        $this->typesId = $typesId;
    }

    // ...

    /**
     * Returns the value of field status
     *
     * @return string
     */
    public function getTodo()
    {
        return $this->content;
    }

    public function getId()
    {
        return $this->id;
    }
}