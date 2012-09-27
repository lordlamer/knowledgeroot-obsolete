<?php
/**
 * password class for knowledgeroot
 *
 * @author fhabermann
 */
class Knowledgeroot_Password {
    /**
     * available hash methods
     */
    const HASH_MD5 = 1;
    const HASH_SHA1 = 2;
    const HASH_SHA256 = 3;
    const HASH_SHA384 = 4;
    const HASH_SHA512 = 5;

    /**
     * generated hash
     * @var string
     */
    protected $hash = null;

    /**
     * used hash method
     * @var int
     */
    protected $method = null;

    /**
     * number of rotation
     * @var integer
     */
    protected $rotation = 100;

    /**
     * generated password salt
     * @var string
     */
    protected $salt = null;

    /**
     * construct object
     *
     * @param string $password optional password
     */
    public function __construct($password = null) {
	// get config
	$config = Zend_Registry::get('config');

	// fill defaults
	switch($config->password->method) {
	    case 'md5':
		$this->method = Knowledgeroot_Password::HASH_MD5;
		break;
	    case 'sha1':
		$this->method = Knowledgeroot_Password::HASH_SHA1;
		break;

	    case 'sha256':
		$this->method = Knowledgeroot_Password::HASH_SHA256;
		break;

	    case 'sha384':
		$this->method = Knowledgeroot_Password::HASH_SHA384;
		break;

	    case 'sha512':
	    default:
		$this->method = Knowledgeroot_Password::HASH_SHA512;
	}

	// get rotation
	$this->rotation = $config->password->rotation;

	// if password set generate hash for it
	if($password != null) {
	    $this->salt = $this->generateRandomKey();
	    $this->hash = $this->generateHash($password, $this->method, $this->rotation, $this->salt);
	}
    }

    /**
     * generate random key
     *
     * @return string
     */
    protected function generateRandomKey() {
	$rand = mt_rand(1000000000,9999999999);
	$rand = hash('crc32', $rand, false);

	return $rand;
    }

    /**
     * generate hash value
     *
     * @param string $password
     * @param int $method
     * @param int $rotation
     * @param string $salt
     * @return string
     */
    public static function generateHash($password, $method, $rotation, $salt) {
	// empty hash
	$hash = "";

	// add method to hash
	$hash .= "$" . $method;

	// add rotation to hash
	$hash .= "$" . $rotation;

	// add salt
	$hash .= "$" . $salt;

	// get method
	switch($method) {
	    case 1:
		$method = 'md5';
		break;

	    case 2:
		$method = 'sha1';
		break;

	    case 3:
		$method = 'sha256';
		break;

	    case 4:
		$method = 'sha384';
		break;

	    case 5:
	    default:
		$method = 'sha512';
		break;
	}

	// now rotate with salt
	for($i=0;$i<$rotation;$i++) {
	    $password = hash($method, $salt . $password, false);
	}

	// add passwordhash
	$hash .= "$" . $password;

	// return hash
	return $hash;
    }

    /**
     * get generated hash
     *
     * @return string
     */
    public function getHash() {
	return $this->hash;
    }

    /**
     * verify password with hash value
     *
     * @param type $password
     * @param type $hash
     * @return bool
     */
    public static function verify($password, $hash) {
	// get  old settings from hash
	$hashArr = explode("$", $hash);
	$method = $hashArr[1];
	$rotation = $hashArr[2];
	$salt = $hashArr[3];
	$pwHash = $hashArr[4];

	// generate hash with old values
	$verifyHash = Knowledgeroot_Password::generateHash($password, $method, $rotation, $salt);

	return ($hash == $verifyHash);
    }
}

?>