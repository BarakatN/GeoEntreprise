<?php

class Departement extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Num_dept;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Libelle;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Etablissement_siret;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Contact_cin;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("vokuro");
        $this->setSource("departement");
        $this->belongsTo('etablissement_siret', '\Etablissement', 'siret', ['alias' => 'Etablissement']);
        $this->belongsTo('contact_cin', '\Contact', 'cin', ['alias' => 'Contact']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'departement';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Departement[]|Departement|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Departement|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
