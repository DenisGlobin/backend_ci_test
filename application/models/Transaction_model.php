<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 19.04.2020
 * Time: 14:09
 */

class Transaction_model extends CI_Emerald_Model
{
    const CLASS_TABLE = 'transactions';

    /** @var int */
    protected $user_id;
    /** @var string */
    protected $cash_flow;
    /** @var string */
    protected $likes_flow;

    /** @var string */
    protected $time_created;
    /** @var string */
    protected $time_updated;

    /**
     * Transaction_model constructor.
     *
     * @param null $id
     * @throws Exception
     */
    public function __construct($id = NULL)
    {
        parent::__construct();
        $this->set_id($id);
    }

    /**
     * @return string
     */
    public function get_time_created(): string
    {
        return $this->time_created;
    }

    /**
     * @param string $time_created
     * @return bool
     */
    public function set_time_created(string $time_created)
    {
        $this->time_created = $time_created;
        return $this->save('time_created', $time_created);
    }

    /**
     * @return string
     */
    public function get_time_updated(): string
    {
        return $this->time_updated;
    }

    /**
     * @param string $time_updated
     * @return bool
     */
    public function set_time_updated(string $time_updated)
    {
        $this->time_updated = $time_updated;
        return $this->save('time_updated', $time_updated);
    }

    /**
     * Get the added amount of money
     *
     * @return float
     */
    public function get_cash(): float
    {
        return $this->cash_flow;
    }

//    public function set_cash(float $cash_flow)
//    {
//        $this->cash_flow = $cash_flow;
//        return $this->save('cash_flow', $cash_flow);
//    }

    /**
     * Get the added amount of likes
     *
     * @return int
     */
    public function get_likes(): int
    {
        return $this->likes_flow;
    }

//    public function set_likes(int $likes_flow)
//    {
//        $this->likes_flow = $likes_flow;
//        return $this->save('likes_flow', $likes_flow);
//    }

    /**
     * Create a new transaction.
     *
     * @param array $data
     * @return Transaction_model
     * @throws Exception
     */
    public static function create(array $data)
    {
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
        return new static(App::get_ci()->s->get_insert_id());
    }

    /**
     * Delete transaction form DB.
     *
     * @return bool
     * @throws Exception
     */
    public function delete()
    {
        $this->is_loaded(TRUE);
        App::get_ci()->s->from(self::CLASS_TABLE)->where(['id' => $this->get_id()])->delete()->execute();
        return (App::get_ci()->s->get_affected_rows() > 0);
    }

    /**
     * Get all transactions.
     *
     * @return self[]
     * @throws Exception
     */
    public static function get_all()
    {

        $data = App::get_ci()->s->from(self::CLASS_TABLE)->many();
        $ret = [];
        foreach ($data as $i)
        {
            $ret[] = (new self())->set($i);
        }
        return $ret;
    }


    /**
     * @param Transaction_model|Transaction_model[] $data
     * @param string $preparation
     * @return stdClass|stdClass[]
     * @throws Exception
     */
    public static function preparation($data, $preparation = 'default')
    {
        switch ($preparation)
        {
            case 'default':
                return self::_preparation_default($data);
            default:
                throw new Exception('undefined preparation type');
        }
    }


    /**
     * @param Transaction_model $data
     * @return stdClass
     */
    private static function _preparation_default($data)
    {
        $o = new stdClass();

        if (!$data->is_loaded())
        {
            $o->id = NULL;
        } else {
            $o->id = $data->get_id();

            $o->cash_flow = $data->get_cash();
            $o->likes_flow = $data->get_likes();

            $o->time_created = $data->get_time_created();
            $o->time_updated = $data->get_time_updated();
        }

        return $o;
    }
}