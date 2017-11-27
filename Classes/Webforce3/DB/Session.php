<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

class Session extends DbObject {
	/**
	 * @param int $id
	 * @return DbObject
	 */

    /** @var Location */
    protected $location;
    /** @var Training */
    protected $training;
    /** @var \DateTime */
    protected $ses_start_date;
    /** @var \DateTime */
    protected $ses_end_date;
    /** @var int */
    protected $ses_number;

    /**
     * Session constructor.
     * @param Location $location
     * @param Training $training
     * @param \DateTime $ses_start_date
     * @param \DateTime $ses_end_date
     * @param int $ses_number
     */
    public function __construct($id=0, Location $location=null, Training $training=null, \DateTime $ses_start_date=null, \DateTime $ses_end_date=null, $ses_number=0, $inserted='')
    {
        if(empty($location)){
            $this->location= new Location();
        } else {
            $this->location = $location;
        }
        if(empty($training)){
            $this->training = new Training();
        } else {
            $this->training = $training;
        }
        $this->ses_start_date = $ses_start_date;
        $this->ses_end_date = $ses_end_date;
        $this->ses_number = $ses_number;
        parent::__construct($id, $inserted);
    }


    public static function get($id) {
		// TODO: Implement get() method.
        $sql = '
			SELECT ses_id, ses_start_date, ses_end_date, ses_number, location_loc_id,training_tra_id
			FROM session
			WHERE ses_id = :id
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new Session(
                    $row['ses_id'],
                    $row['ses_start_date'],
                    $row['ses_end_date'],
                    $row['ses_number'],
                    new Location($row['location_loc_id']),
                    new Training($row['training_tra_id'])
                );
                return $currentObject;
            }
        }
	}

	/**
	 * @return DbObject[]
	 */
	public static function getAll() {
        // TODO: Implement getAll() method.
        $returnList = array();

        $sql = '
			SELECT ses_id, ses_start_date, ses_end_date, ses_number, location_loc_id,training_tra_id
			FROM session
			WHERE ses_id > 0
			ORDER BY ses_number ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $currentObject = new Session(
                    $row['ses_id'],
                    $row['ses_start_date'],
                    $row['ses_end_date'],
                    $row['ses_number'],
                    new Location($row['location_loc_id']),
                    new Training($row['training_tra_id'])
                );
                $returnList[] = $currentObject;
            }
        }

        return $returnList;
	}

	/**
	 * @return array
	 */
	public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT ses_id, tra_name, ses_start_date, ses_end_date, loc_name
			FROM session
			LEFT OUTER JOIN training ON training.tra_id = session.training_tra_id
			LEFT OUTER JOIN location ON location.loc_id = session.location_loc_id
			WHERE ses_id > 0
			ORDER BY ses_start_date ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['ses_id']] = '['.$row['ses_start_date'].' > '.$row['ses_end_date'].'] '.$row['tra_name'].' - '.$row['loc_name'];
			}
		}

		return $returnList;
	}

	/**
	 * @return bool
	 */
	public function saveDB() {
		// TODO: Implement saveDB() method.
        if ($this->id > 0) {
            $sql = '
				UPDATE session
				SET ses_start_date = :sdate,
				ses_end_date = :edate,
				ses_number = :snumber,
				location_loc_id = :locId,
				training_tra_id = :traId,
				WHERE loc_id = :id
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':sdate', $this->ses_start_date);
            $stmt->bindValue(':edate', $this->ses_end_date);
            $stmt->bindValue(':snumber', $this->ses_number);
            $stmt->bindValue(':locId', $this->location->id);
            $stmt->bindValue(':traId', $this->training->id);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            }
            else {
                return true;
            }
        }
        else {
            $sql = '
				INSERT INTO session (ses_start_date,ses_end_date,ses_number,location_loc_id,training_tra_id)
				VALUES (:sdate,:edate,:snumber,:locId,:traId)
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':sdate', $this->name);
            $stmt->bindValue(':edate', $this->name);
            $stmt->bindValue(':snumber', $this->name);
            $stmt->bindValue(':locId', $this->name);
            $stmt->bindValue(':traId', $this->name);

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

	/**
	 * @param int $id
	 * @return bool
	 */
	public static function deleteById($id) {
		// TODO: Implement deleteById() method.
        $sql = '
			DELETE FROM session WHERE ses_id = :id
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
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

}