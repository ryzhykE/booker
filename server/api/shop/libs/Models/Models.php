<?php

namespace Models;

/**
 * Class Models
 * main class for db
 * @package Models
 */
class Models
{
    /**
     * get all info in db
     * @return mixed
     */
    public static function findAll()
    {
        $db = DB::getInstance();
        $data = $db->query(
            'SELECT * FROM ' . static::$table,
            []
        );
        return $data;
    }

    /**
     * find one item by id
     * @param $id
     * @return mixed
     */
    public static function findByid($id)
    {
        $db = DB::getInstance();
        $data = $db->query(
            'SELECT * FROM ' . static::$table . ' WHERE id=:id',
            [':id' => $id]
        );
        return $data[0];
		//?? false;
    }

    /**
     * insert data in DB, return last insert id
     * @return mixed
     */
    public function insert()
    {

        $columns = [];
        $binds = [];
        $data = [];
            foreach ($this as $column => $value) {
                if ('id' == $column) {
                    continue;
                }
                $columns[] = $column;
                $binds[] = ':' . $column;
                $data[':' . $column] = $value;
            }
            $sql = '
                INSERT INTO ' . static::$table . '
                (' . implode(', ', $columns). ')
                VALUES
                (' . implode(', ', $binds). ')
                ';
            $db = DB::getInstance();
            $db->execute($sql, $data);
            $res = $this->id = $db->lastInsertId();
            return $res;
    }


    /**
     * update date in DB
     * @return bool
     */
    public function update()
    {
        $columns = [];
        $data = [];
        foreach ($this as $item => $value) {
            if ('id' == $item) {
                continue;
            }
            $columns[] = $item . ' = ' . ':' . $item;
            $data[':' . $item] = $value;
        }
        $sql = '
               UPDATE ' . static::$table . '
               SET ' . implode(',', $columns) .
            ' WHERE id = :id';
        $data[':id'] = $this->id;
        $db = DB::getInstance();
        $result = $db->execute($sql, $data);
        return true;
    }

    public static function normalTime($id_room, $start, $end,$id = null)
    {
        if(!self::timeFrame($start,$end))
        {
            return false;
       }
        $db = DB::getInstance();
        $data = $db->query(
            "SELECT time_start,time_end FROM events  WHERE id_room = :id
            AND time_start  BETWEEN '$start' AND  '$end' ",
            [':id' => $id_room]
        );
        if (!is_array($data)) {
            return true;
        }
        foreach ($data as $val) {
            $valSt = new \DateTime($val['time_start']);
            $valE = new \DateTime($val['time_end']);
            if ((($valSt < $start && $valE <= $start)
                || ($end <= $valSt && $end < $valE))
            ) {
                return false;
            }
        }
        return true;
    }

    /**
     * check time from 8-00 to 20-00
     * @param $start_ev
     * @param $end_ev
     * @return bool

    public  function timeFrame($start_ev, $end_ev)
    {
        $start_t = date("G", $start_ev/1000);
        $end_t = date("G", $end_ev/1000);
        if ($start_t >= FROM_T && $start_t < TO_T)
        {
            if ($end_t >= FROM_T && $end_t <= TO_T)
            {
                return true;
            }
        }
        return false;
    }
     */
}