<?php
namespace GeoEntreprise\Models;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Contact extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Id_contact;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Cin;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Nom;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Prenom;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $Email;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Date_affectation;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    // public function validation()
    // {
    //     $validator = new Validation();
    //
    //     $validator->add(
    //         'Email',
    //         new EmailValidator(
    //             [
    //                 'model'   => $this,
    //                 'message' => 'Please enter a correct email address',
    //             ]
    //         )
    //     );
    //
    //     return $this->validate($validator);
    // }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("vokuro");
        $this->setSource("contact");
        $this->hasMany('id_contact', 'Departement', 'contact_id', ['alias' => 'Departement']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contact';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contact[]|Contact|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contact|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
