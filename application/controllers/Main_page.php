<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Main_page extends MY_Controller
{
    /**
     * Main_page constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        App::get_ci()->load->model('User_model');
        App::get_ci()->load->model('Login_model');
        App::get_ci()->load->model('Post_model');
        App::get_ci()->load->model('Transaction_model');

        App::get_ci()->load->library('form_validation');

        if (is_prod())
        {
            die('In production it will be hard to debug! Run as development environment!');
        }
    }

    /**
     * Show main page.
     *
     * @throws Exception
     */
    public function index()
    {
        $user = User_model::get_user();

        App::get_ci()->load->view('main_page', ['user' => User_model::preparation($user, 'default')]);
    }

    public function get_auth_user()
    {
        if ( ! User_model::is_logged())
        {
            return $this->response_info(['auth' => FALSE]);
        }

        $user = User_model::get_user();
        $data = array(
            'auth' => TRUE,
            'user_name' => $user->get_personaname(),
        );

        return $this->response_success($data, 200);
    }

    /**
     * Get all posts from DB.
     *
     * @return object|string|void
     * @throws Exception
     */
    public function get_all_posts()
    {
        $posts =  Post_model::preparation(Post_model::get_all(), 'main_page');
        return $this->response_success(['posts' => $posts]);
    }

    /**
     * Get a post by ID.
     *
     * @param $post_id
     * @return object|string|void
     * @throws Exception
     */
    public function get_post($post_id)
    {

        $post_id = intval($post_id);
        if (empty($post_id))
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try
        {
            $post = new Post_model($post_id);
        }
        catch(EmeraldModelNoDataException $ex)
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        $posts =  Post_model::preparation($post, 'full_info');
        return $this->response_success(['post' => $posts]);
    }

    /**
     * Store a comment to the DB.
     *
     * @return mixed
     * @throws Exception
     */
    public function store_comment()
    {

        $post_id = App::get_ci()->input->post('post_id', TRUE);
        $message = App::get_ci()->input->post('message', TRUE);
        $assign_comment = App::get_ci()->input->post('assign_comment', TRUE);
        $assign_comment = ($assign_comment == "null") ? NULL : $assign_comment;

        if ( ! User_model::is_logged())
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $post_id = intval($post_id);
        if (empty($post_id) OR empty($message))
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try
        {
            $post = new Post_model($post_id);
        }
        catch(EmeraldModelNoDataException $ex)
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        $user = User_model::get_user();
        $data = array(
            'user_id' => $user->get_id(),
            'assign_id' => $post_id,
            'assign_comment_id' => $assign_comment,
            'text' => $message,
            'time_created' => date('Y-m-d h:i:s')
        );

        try
        {
            Comment_model::create($data);
        }
        catch(Exception $exception)
        {
            return $this->response_error($exception->getMessage());
        }

        $posts = Post_model::preparation($post, 'full_info');
        return $this->response_success(['post' => $posts]);
    }

    /**
     * Handle a login request to the application.
     *
     * @return object|string|void
     */
    public function login()
    {
        $email = App::get_ci()->input->post('login', TRUE);
        $password = App::get_ci()->input->post('password', TRUE);

        $rules = array(
            array(
                'field' => 'login',
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|alpha_dash'
            )
        );
        App::get_ci()->form_validation->set_rules($rules);

        if (App::get_ci()->form_validation->run() === TRUE)
        {

            try
            {
                // If the validation was successful, we try to find the user in the database.
                $user = User_model::find_user($email);
            }
            catch(EmeraldModelLoadException $exception)
            {
                return $this->response_error($exception->getMessage());
            }
            // Checking password
            if (isset($user) && ($user->get_password() === $password)) {
                try
                {
                    Login_model::start_session($user->get_id());
                }
                catch(Exception $exception)
                {
                    return $this->response_error($exception->getMessage());
                }
            }
            else
            {
                return $this->response_error('These credentials do not match our records');
            }

        }
        else
        {
            return $this->response_error(validation_errors());
        }
        // If the data entered is correct.
        return $this->response_success(['user' => $user], 200);
    }

    /**
     * Logout the user.
     *
     */
    public function logout()
    {
        Login_model::logout();
        redirect(base_url('/'));
    }

    /**
     * Get user's likes and money balances.
     *
     * @return object|string|void
     */
    public function get_user_balance()
    {
        if ( ! User_model::is_logged())
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }
        else
        {
            $user = User_model::get_user();
            $data = array(
                'user_name' => $user->get_personaname(),
                'wallet_balance' => $user->get_wallet_balance(),
                'likes_balance' => $user->get_likes_balance()
            );

            return $this->response_success($data, 200);
        }
    }

    /**
     * Add money to wallet balance.
     *
     * @return object|string|void
     */
    public function add_money()
    {
        $money = floatval(App::get_ci()->input->post('sum', TRUE));
        $user = User_model::get_user();
        $balance = floatval($user->get_wallet_balance());
        $balance += $money;

        $total_wallet_refilled = floatval($user->get_wallet_total_refilled());
        $total_wallet_refilled += $money;

        $data = array(
            'user_id' => $user->get_id(),
            'cash_flow' => $money,
            'likes_flow' => 0,
            'time_created' => date('Y-m-d h:i:s')
        );

        $this->db->trans_begin();

        $user->set_wallet_balance($balance);
        $user->set_wallet_total_refilled($total_wallet_refilled);
        Transaction_model::create($data);

        if ( $this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return $this->response_error(CI_Core::DB_STATE_ERROR);
        }
        else
        {
            $this->db->trans_commit();
        }

        return $this->response_success(['amount' => $balance]);
    }

    /**
     * Likes purchase.
     *
     * @return mixed
     */
    public function buy_likes()
    {
        $likes_amount = intval(App::get_ci()->input->post('likes_amount', TRUE));
        $user = User_model::get_user();
        $wallet_balance = $user->get_wallet_balance();

        if ($likes_amount > $wallet_balance)
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        $wallet_balance -= $likes_amount;

        $total_walet_withdrawn = floatval($user->get_wallet_total_withdrawn());
        $total_walet_withdrawn += $likes_amount;

        $likes_balance = intval($user->get_likes_balance());
        $likes_balance += $likes_amount;

        $data = array(
            'user_id' => $user->get_id(),
            'cash_flow' => 0 - $likes_amount,
            'likes_flow' => $likes_amount,
            'time_created' => date('Y-m-d h:i:s')
        );

        $this->db->trans_begin();

        $user->set_wallet_balance($wallet_balance);
        $user->set_wallet_total_withdrawn($total_walet_withdrawn);
        $user->set_likes_balance($likes_balance);
        Transaction_model::create($data);

        if ( $this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return $this->response_error(CI_Core::DB_STATE_ERROR);
        }
        else
        {
            $this->db->trans_commit();
        }

        return $this->response_success(['likes_balance' => $likes_balance, 'wallet_balance' => $wallet_balance]);
    }

    public function buy_boosterpack()
    {
        // todo: add money to user logic
        return $this->response_success(['amount' => rand(1,55)]);
    }

    /**
     * Like post or comment.
     *
     * @return mixed
     */
    public function like()
    {
        if ( ! User_model::is_logged())
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }
        $user = User_model::get_user();

        $id = intval(App::get_ci()->input->post('id', TRUE));
        $object_type = App::get_ci()->input->post('obj_type', TRUE);

        switch ($object_type)
        {
            case 'post':
                try
                {
                    $object_model = new Post_model($id);
                }
                catch(EmeraldModelNoDataException $ex)
                {
                    return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
                }
                break;
            case 'comment':
                App::get_ci()->load->model('Comment_model');
                try
                {
                    $object_model = new Comment_model($id);
                }
                catch(EmeraldModelNoDataException $ex)
                {
                    return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
                }
                break;
            default:
                return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        $likes = $object_model->get_likes();
        $likes_balance = intval($user->get_likes_balance());
        // Check if the user has likes on the account
        if ($likes_balance > 0)
        {
            $likes++;
            $likes_balance--;

            $data = array(
                'user_id' => $user->get_id(),
                'cash_flow' => 0,
                'likes_flow' => -1,
                'time_created' => date('Y-m-d h:i:s')
            );

            $this->db->trans_begin();

            $user->set_likes_balance($likes_balance);
            $object_model->set_likes($likes);
            Transaction_model::create($data);

            if ( $this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                return $this->response_error(CI_Core::DB_STATE_ERROR);
            }
            else
            {
                $this->db->trans_commit();
            }
        }
        else
        {
            return $this->response_error('Error! No likes on your account.');
        }

        if ($object_type === 'post')
        {
            return $this->response_success(['post_likes' => $likes]);
        }

        if ($object_type === 'comment')
        {
            return $this->response_success(['comment_likes' => $likes, 'comment_id' => $id]);
        }

    }

}
