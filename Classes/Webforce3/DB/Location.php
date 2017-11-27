<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 27/11/2017
 * Time: 14:52
 */

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class Location extends DbObject{

    /** @var string */
    protected $location;
    /** @var Country */
    protected $country;

    /**
     * Location constructor.
     * @param string $location
     * @param Country $country
     */
    public function __construct($id=0,$location='', Country $country=null,$inserted='')
    {
        $this->location = $location;
        $this->country = (is_object($country)) ? $country : new Country();
        parent::__construct($id, $inserted);
    }

    public static function get($id)
    {
        // TODO: Implement get() method.
        $sql = '
			SELECT loc_id, loc_name, country_cou_id
			FROM location
			WHERE loc_id = :id
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new Location(
                    $row['loc_id'],
                    $row['loc_country'],
                    new Country($row['country_cou_id'])
                );
                return $currentObject;
            }
        }
        return false;
    }

    public static function getAll()
    {
        // TODO: Implement getAll() method.
        $returnList = array();

        $sql = '
			SELECT loc_id, loc_name, country_cou_id
			FROM location
			WHERE loc_id > 0
			ORDER BY loc_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $currentObject = new Location(
                    $row['loc_id'],
                    $row['loc_name'],
                    new Country($row['country_cou_id'])
                );
                $returnList[] = $currentObject;
            }
        }

        return $returnList;
    }

    public static function getAllForSelect()
    {
        // TODO: Implement getAllForSelect() method.
    }

    public function saveDB()
    {
        // TODO: Implement saveDB() method.
        if ($this->id > 0) {
            $sql = '
				UPDATE location
				SET loc_name = :lname,
				WHERE loc_id = :id
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':lname', $this->location);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            }
            else {
                return true;
            }
        }
        else {
            $sql = '
				INSERT INTO location (loc_name)
				VALUES (:lname)
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':lname', $this->name);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            }
            else {
                $this->id = Config::getInstance()->getPDO()->lastInsertId();
                return true;
            }
        }

        return false;

    }

    public static function deleteById($id)
    {
        // TODO: Implement deleteById() method.
        $sql = '
			DELETE FROM location WHERE loc_id = :id
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        }
        else {
            return true;
        }
        return false;
    }


}