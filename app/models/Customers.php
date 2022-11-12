<?php

class Customers extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=10, nullable=false)
     */
    public $cst_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $cst_status_flag;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $cst_name_last;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $cst_name_first;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("store");
        $this->setSource("customers");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'customers';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Customers[]|Customers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Customers|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
