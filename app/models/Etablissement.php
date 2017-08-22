<?php
namespace GeoEntreprise\Models;
class Etablissement extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Id_etab;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Siret;
    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Nom;
    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Longitude;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Altitude;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $Entreprise_id_entreprise;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("vokuro");
        $this->setSource("etablissement");
        $this->hasMany('id_etab', 'Departement', 'etablissement_id_etab', ['alias' => 'Departement']);
        $this->belongsTo('entreprise_id_entreprise', '\Entreprise', 'id_entreprise', ['alias' => 'Entreprise']);
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
