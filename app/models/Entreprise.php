<?php
<<<<<<< HEAD
namespace Vokuro\Models  ; 
=======
namespace Vokuro\Models;
>>>>>>> 690211f5110b172fcbc69799086b8975a190b614
class Entreprise extends \Phalcon\Mvc\Model
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
     * @var string
     * @Column(type="string", length=45, nullable=true)
     */
    public $Nom;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Siren;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Denomination;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Ville;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Pays;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Code_postal;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Capital_social;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Forme_juridique;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Immatriculation;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Ca;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Date_creation;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Rayonnement;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("vokuro");
        $this->setSource("entreprise");
        $this->hasMany('id', 'Categorieemploye', 'entreprise_id', ['alias' => 'Categorieemploye']);
        $this->hasMany('id', 'DomaineHasEntreprise', 'entreprise_id', ['alias' => 'DomaineHasEntreprise']);
        $this->hasMany('id', 'Etablissement', 'entreprise_id', ['alias' => 'Etablissement']);
        $this->hasMany('id', 'Transport', 'entreprise_id', ['alias' => 'Transport']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'entreprise';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Entreprise[]|Entreprise|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Entreprise|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
