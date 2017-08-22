<?php
namespace GeoEntreprise\Models  ;
class DomaineHasEntreprise extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Domaine_id_domaine;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Entreprise_id_entreprise;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("vokuro");
        $this->setSource("domaine_has_entreprise");
        $this->belongsTo('domaine_id_domaine', '\Domaine', 'id_domaine', ['alias' => 'Domaine']);
        $this->belongsTo('entreprise_id_entreprise', '\Entreprise', 'id_entreprise', ['alias' => 'Entreprise']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'domaine_has_entreprise';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DomaineHasEntreprise[]|DomaineHasEntreprise|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DomaineHasEntreprise|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
