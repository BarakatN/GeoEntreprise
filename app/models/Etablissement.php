<?php
namespace Vokuro\Models;
class Etablissement extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $Siret;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $Longitude;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $Altitude;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $Entreprise_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("vokuro");
        $this->setSource("etablissement");
        $this->hasMany('id', 'Departement', 'etablissement_id', ['alias' => 'Departement']);
        $this->belongsTo('entreprise_id', '\Entreprise', 'id', ['alias' => 'Entreprise']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'etablissement';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Etablissement[]|Etablissement|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Etablissement|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
