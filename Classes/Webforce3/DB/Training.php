<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 27/11/2017
 * Time: 15:26
 */

namespace Classes\Webforce3\DB;


use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class Training extends DbObject
{
    /** @var string */
    protected $tname;

    /**
     * Training constructor.
     * @param string $tname
     */
    public function __construct($tname='')
    {
        $this->tname = $tname;
    }

    public static function get($id)
    {
        // TODO: Implement get() method.
        $sql = '
			SELECT tra_id, tra_name
			FROM training
			WHERE tra_id = :id
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new Training(
                    $row['tra_id'],
                    $row['tra_name']
                );
                return $currentObject;
            }
        }
        return false;
    }

    public static function getAll()
    {
        // TODO: Implement getAll() method.
        // TODO: Implement get() method.
        $sql = '
			SELECT tra_id, tra_name
			FROM training
			WHERE tra_id > 0
			ORDER BY tra_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $currentObject = new Training(
                    $row['tra_id'],
                    $row['tra_name']
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
				SET tra_name = :tname,
				WHERE tra_id = :id
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':tname', $this->tname);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            }
            else {
                return true;
            }
        }
        else {
            $sql = '
				INSERT INTO training (tra_name)
				VALUES (:tname)
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':tname', $this->name);

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
			DELETE FROM training WHERE tra_id = :id
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