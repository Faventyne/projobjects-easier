<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 27/11/2017
 * Time: 11:40
 */

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Exceptions\InvalidSqlQueryException;
use Classes\Webforce3\Config\Config;
class Country extends DbObject
{
    /** @var string */
    private $name;

    public function __construct($id = 0, $name='', $inserted = '')
    {
        $this->name = $name;
        parent::__construct($id, $inserted);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public static function get($id)
    {
        // TODO: Implement get() method.
        $sql = '
			SELECT cou_id, cou_name
			FROM country
			WHERE cou_id = :id
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new Country(
                    $row['cou_id'],
                    $row['cou_name']
                );
                return $currentObject;
            }
        }
    }

    public static function getAll()
    {
        // TODO: Implement getAll() method.
        $returnList = array();

        $sql = '
			SELECT cou_id, cou_name
			FROM country
			WHERE cou_id > 0
			ORDER BY cou_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $currentObject = new Country(
                    $row['cou_id'],
                    $row['cou_name']
                );
                $returnList[] = $currentObject;
            }
        }

        return $returnList;

    }

    public static function getAllForSelect()
    {
        // TODO: Implement getAllForSelect() method.

        $returnList = array();

        $sql = '
			SELECT cou_id, cou_name
			FROM country
			WHERE cou_id > 0
			ORDER BY cou_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        }
        else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList[$row['cou_id']] = $row['cou_name'];
            }
        }

        return $returnList;
    }

    public function saveDB()
    {
        // TODO: Implement saveDB() method.
        if ($this->id > 0) {
            $sql = '
				UPDATE country
				SET cou_name = :cname,
				WHERE cou_id = :id
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':cname', $this->name);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            }
            else {
                return true;
            }
        }
        else {
            $sql = '
				INSERT INTO country (cou_name)
				VALUES (:cname)
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':cname', $this->name);

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
			DELETE FROM country WHERE cou_id = :id
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



}