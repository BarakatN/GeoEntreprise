<?php
namespace Vokuro\Models;
class Transport extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=45, nullable=false)
     */
    public $Matricule;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Model;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Entreprise_siren;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("vokuro");
        $this->setSource("transport");
        $this->belongsTo('entreprise_siren', '\Entreprise', 'siren', ['alias' => 'Entreprise']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'transport';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Transport[]|Transport|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Transport|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
