<?php

class Etablissement extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Siret;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Lantitude;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Longitude;

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
        $this->setSource("etablissement");
        $this->hasMany('siret', 'Departement', 'etablissement_siret', ['alias' => 'Departement']);
        $this->belongsTo('entreprise_siren', '\Entreprise', 'siren', ['alias' => 'Entreprise']);
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
